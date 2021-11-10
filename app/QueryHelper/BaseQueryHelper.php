<?php

namespace App\QueryHelper;


class BaseQueryHelper
{
    protected $queryInstance;

    public function __construct($queryInstance)
    {
        $this->queryInstance = $queryInstance;
    }

    /**
     * @return mixed
     */
    public function getQueryInstance()
    {
        return $this->queryInstance;
    }

    /**
     * @param string $field
     * @param string $value
     * @return $this
     */
    public function addTextSearch(string $field, string $value): BaseQueryHelper
    {
        $this->getQueryInstance()->where($field, 'LIKE', "%{$value}%");

        return $this;
    }

    /**
     * @param string $field
     * @param string|null $value
     * @return $this
     */
    public function addTextSearchNE(string $field, ?string $value): BaseQueryHelper
    {
        if (!empty($value)) {
            $this->addTextSearch($field, $value);
        }

        return $this;
    }

    /**
     * @param string $field
     * @param int $valueFrom
     * @param int $valueTo
     * @return $this
     */
    public function addIntervalSearch(string $field, int $valueFrom, int $valueTo): BaseQueryHelper
    {
        $this->getQueryInstance()->where($field, '>=', $valueFrom);
        $this->getQueryInstance()->where($field, '<=', $valueTo);

        return $this;
    }

    /**
     * @param string $field
     * @param int|null $valueFrom
     * @param int|null $valueTo
     * @return $this
     */
    public function addIntervalSearchNE(string $field, ?int $valueFrom, ?int $valueTo): BaseQueryHelper
    {
        if (!empty($valueFrom) && !empty($valueTo)) {
            $this->addIntervalSearch($field, $valueFrom, $valueTo);
        }

        return $this;
    }

    public function selectColumns($columns): BaseQueryHelper
    {
        $this->getQueryInstance()->select($columns);
        return $this;
    }


    /**
     * @return mixed
     */
    public function getResult()
    {
        return $this->getQueryInstance()->get();
    }
    /**
     * @return int
     */
    public function getResultCount():int
    {
        return $this->getQueryInstance()->count();
    }

}
