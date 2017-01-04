<?php

namespace DontPanic\Model;

use Doctrine\ORM\NoResultException;
use Kdyby\Doctrine\EntityManager;
use Kdyby\Doctrine\EntityRepository;

class DoctrineModel extends MainModel
{

    /** @var EntityManager */
    protected $em;

    /** @var EntityRepository */
    protected $er;

    protected function isSoftDeletable($entity = null)
    {
        return property_exists($entity ?: $this->er->getClassName(), 'deletedAt');
    }

    /**
     * @param array $criteria
     * @param array $orderBy
     * @param bool  $deleted
     *
     * @return bool|mixed|null|object
     */
    public function findOneBy(array $criteria, array $orderBy = null, $deleted = false)
    {
        if ($this->isSoftDeletable() && !$deleted) {
            $criteria['deletedAt'] = null;
        }

        return $this->er->findOneBy($criteria, $orderBy);
    }

    public function save($entity)
    {
        $this->em->persist($entity);
        $this->em->flush();
    }

    public function deleteById($id)
    {
        $entity = $this->find($id);
        if ($entity) {
            $this->delete($entity);
        }
    }

    /**
     * @param            $id
     * @param bool|false $deleted
     * @param null       $er
     *
     * @return mixed
     */
    public function find($id, $deleted = false, $er = null)
    {
        if ($id !== null) {
            $qb = $this->createQueryBuilder();
            $qb->addSelect('e')
               ->from($er ?: $this->er->getClassName(), 'e')
               ->where('e.id = :id')
               ->setParameter('id', $id);
            try {
                $entity = $qb->getQuery()->getSingleResult();
            } catch (NoResultException $e) {
                $entity = null;
            }
            if ($entity) {
                if ($this->isSoftDeletable() && $entity->isDeleted() && !$deleted) {
                    return null;
                }

                return $entity;
            }
        }

        return null;
    }

    public function delete($entity, $flush = true)
    {
        $this->em->remove($entity);
        if ($flush) {
            $this->em->flush();
        }
    }

    public function persist($entity)
    {
        $this->em->persist($entity);
    }

    public function flush($entity = null)
    {
        return $this->em->flush($entity);
    }

    public function clear($entity = null)
    {
        return $this->em->clear($entity);
    }

    public function refresh($entity)
    {
        $this->em->refresh($entity);
    }

    public function merge($entity)
    {
        $this->em->merge($entity);
    }

    public function fetchPairs($result)
    {
        $returnArray = [];
        foreach ($result as $value) {
            $string                  = $value->getSelectLabel();
            $returnArray[$value->id] = $string;
        }

        return $returnArray;
    }

    public function updateEntity($entity, $values)
    {
        foreach ($values as $key => $value) {
            $entity->{'set' . ucfirst($key)}($value);
        }

        return $entity;
    }

    /**
     * @param null $alias
     * @param null $indexBy
     *
     * @return \Kdyby\Doctrine\QueryBuilder
     */
    public function createQueryBuilder($alias = null, $indexBy = null)
    {
        return $this->er->createQueryBuilder($alias, $indexBy);
    }
}
