<?php

namespace App\QueryHelper;


trait PaginationQueryHelperTrait
{
    abstract public function getQueryInstance();

    /**
     * Insert limit and offset based on actual page
     * @param int $page
     */
    public function paginate(int $page)
    {
        $page--;
        $this->getQueryInstance()->limit($this->getMaxPageSize());
        $offset = $page * $this->getMaxPageSize();
        $this->getQueryInstance()->offset($offset);
    }

    /**
     * @param int $matches
     * @return float
     */
    public function getMaxPages(int $matches)
    {
        return ceil($matches / $this->getMaxPageSize());
    }

    /**
     * @return int
     */
    protected function getMaxPageSize(): int
    {
        return 30;
    }

}
