<?php namespace Valllabh\Message\Facade;

use Illuminate\Support\Facades\Facade;

class Message extends Facade {

    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor() { return 'message'; }

}