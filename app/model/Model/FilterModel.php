<?php

namespace DontPanic\Model;

use Kdyby\Doctrine\QueryBuilder;

class FilterModel extends DoctrineModel implements IFilterModel
{

    use LikeQueryHelpers;

    /** @var QueryBuilder */
    protected $qb;

    /** @var array */
    protected $searchColumns = [];

    /** @var string */
    protected $namespace;

    /** @var bool */
    protected $deletedCalled = false;

    /************************************************************************************************************z*v***/
    /********** BASE **********/

    public function callNonsetFunctions()
    {
        if (!$this->deletedCalled) {
            $this->setDeleted();
        }
    }

    /************************************************************************************************************z*v***/
    /********** SEARCH **********/

    /**
     * @param string $string
     *
     * @return null
     */
    public function setSearch(string $string)
    {
        $searchTerms = $this->prepareSearchString($string);

        if (count($string)) {
            foreach ($this->searchColumns as $columnKey => $column) {
                foreach ($searchTerms as $termKey => $term) {
                    $parameterName = sprintf(':searchTerm_%s_%s', $columnKey, $termKey);
                    $this->qb->orWhere(sprintf("%s LIKE %s ESCAPE '!'", $column, $parameterName));
                    $this->qb->setParameter($parameterName, $this->makeLikeParam($term));
                }
            }
        }

        return null;
    }

    /**
     * @param $string
     *
     * @return array
     */
    private function prepareSearchString($string)
    {
        $chartSet    = [
            ',' => ' ', '.' => ' ',
        ];
        $tempString  = [];
        $string      = strtr($string, $chartSet);
        $stringSplit = explode(' ', $string);

        $consistString = [ $stringSplit[0] ];

        for ($i = 1; $i <= count($stringSplit); $i++) {
            if (array_key_exists($i, $stringSplit)) {
                $consistString[] = $stringSplit[$i];
                $tempString[]    = implode(' ', $consistString);
            }
        }
        $tempString = array_reverse($tempString);

        foreach ($stringSplit as $term) {
            if (strlen($term) >= 3) {
                $tempString[] = $term;
            }
        }

        return $tempString;
    }

    /************************************************************************************************************z*v***/
    /********** DELETED **********/

    /**
     * @param bool $status
     *
     * @return null
     */
    public function setDeleted($status = false)
    {
        $this->deletedCalled = true;

        if ($status !== null && $status === false) {
            $this->qb->andWhere($this->namespace . '.deletedAt IS NULL');
        }
        if ($status !== null && $status === true) {
            $this->qb->andWhere($this->namespaceů . '.deletedAt IS NOT NULL');
        }

        return null;
    }
}
