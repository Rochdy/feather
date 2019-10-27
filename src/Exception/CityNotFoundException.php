<?php
namespace App\Exception;

class CityNotFoundException extends \Exception
{
    public $code = 404;
    public $message = 'I Can\'t Find This city, Are you sure that you\'re living on the earth?';

}
