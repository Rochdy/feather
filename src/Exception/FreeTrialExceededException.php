<?php
namespace App\Exception;

class FreeTrialExceededException extends \Exception
{
    public $code = 429;
    public $message = 'Are you using the free trial? cause it\'s exceeded!';

}
