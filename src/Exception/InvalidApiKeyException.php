<?php
namespace App\Exception;

class InvalidApiKeyException extends \Exception
{
    public $code = 401;
    public $message = 'You API Key is Invalid!';

}
