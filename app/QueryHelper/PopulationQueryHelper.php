<?php

namespace App\QueryHelper;


class PopulationQueryHelper
{
    const PAGE_LIMIT = 50;
    protected $queryInstance;

    public function __construct($queryInstance)
    {
        $this->queryInstance = $queryInstance;
    }

    /**
     * @param string $countryName
     * @return $this
     */
    public function addCountrySearch(string $countryName)
    {
        $this->queryInstance->where('country', 'LIKE', "%{$countryName}%");

        return $this;
    }

    /**
     * @param int $yearFrom
     * @param int $yearTo
     * @return $this
     */
    public function addYearSearch(int $yearFrom, int $yearTo)
    {
        $this->queryInstance->where('year', '>=', $yearFrom);
        $this->queryInstance->where('year', '<=', $yearTo);

        return $this;
    }

    /**
     * @param int $popFrom
     * @param int $popTo
     * @return $this
     */
    public function addPopulationSearch(int $popFrom, int $popTo)
    {
        $this->queryInstance->where('population', '>=', $popFrom);
        $this->queryInstance->where('population', '<=', $popTo);

        return $this;
    }

    /**
     * Insert limit and offset based on actual page
     * @param int $page
     */
    public function paginate(int $page)
    {
        $page--;
        $this->queryInstance->limit(self::PAGE_LIMIT);
        $offset = $page * self::PAGE_LIMIT;
        $this->queryInstance->offset($offset);
    }

    /**
     * @param int $matches
     * @return float
     */
    public function getMaxPages(int $matches)
    {
        return ceil($matches / self::PAGE_LIMIT);
    }

    /**
     * @return mixed
     */
    public function getQuery()
    {
        return $this->queryInstance;
    }

}
