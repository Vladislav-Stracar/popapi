<?php

namespace App\QueryHelper;


class PopulationQueryHelper extends BaseQueryHelper
{
    use PaginationQueryHelperTrait;

    /**
     * @return int
     */
    protected function getMaxPageSize():int
    {
        return 50;
    }


}
