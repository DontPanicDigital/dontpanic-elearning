<?php

namespace DontPanic\Model;

interface IFilterModel
{

    /**
     * @return mixed
     */
    public function callNonsetFunctions();

    /**
     * @param string $string
     *
     * @return mixed
     */
    public function setSearch(string $string);

    /**
     * @param bool $status
     *
     * @return mixed
     */
    public function setDeleted($status = false);
}