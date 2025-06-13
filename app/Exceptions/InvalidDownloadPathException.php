<?php
namespace App\Exceptions;

use Exception;

class InvalidDownloadPathException extends Exception
{
    protected $data;

    public function __construct(string $message = "Something went wrong", int $code = 0, $data = null)
    {
        parent::__construct($message, $code);
        $this->data = $data;
    }

    public function getData()
    {
        return $this->data;
    }
}
