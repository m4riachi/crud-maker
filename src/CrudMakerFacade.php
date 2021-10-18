<?php

namespace M4riachi\CrudMaker;

use Illuminate\Support\Facades\Facade;

/**
 * @see \M4riachi\CrudMaker\Skeleton\SkeletonClass
 */
class CrudMakerFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'crud-maker';
    }
}
