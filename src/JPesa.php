<?php
/**
 * Created by PhpStorm.
 * User: andre
 * Date: 2017-07-31
 * Time: 2:42 PM
 */

namespace FannyPack\JPesa;


use FannyPack\JPesa\JPesaProcessor;
use Illuminate\Support\Facades\Facade;

class JPesa extends Facade
{
    public static function getFacadeAccessor()
    {
        return JPesaProcessor::class;
    }
}