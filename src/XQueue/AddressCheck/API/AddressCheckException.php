<?php

namespace XQueue\AddressCheck\API;

/**
 * An exception that is thrown when a technical error has occurred either in the communication
 * with AddressCheck's REST API or in the API itself.
 */
class AddressCheckException extends \RuntimeException
{
    /**
     * @var string|false The response of a previous request or false if not available
     */
    private $response = false;

    /**
     * Create the exception
     * 
     * @param string $message The message of the exception
     * @param string|false $response The response body of a request
     * @param int $code The exception's code
     * @param \Exception $previous The originally thrown Exception
     */
    public function __construct($message = "", $response = false, $code = 0, \Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->response = $response;
    }

    /**
     * Returns the response of the previous request
     * 
     * @return string|false The response if there was one, false otherwise
     */
    public function getResponse()
    {
        return $this->response;
    }
}
