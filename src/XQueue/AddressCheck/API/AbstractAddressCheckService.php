<?php

namespace XQueue\AddressCheck\API;

use XQueue\AddressCheck\API\AddressCheckResult;
use XQueue\AddressCheck\API\AddressCheckException;

/**
 * An abstract API service class
 * 
 * Defines all request methods to be used by the services and performs the request
 */
abstract class AbstractAddressCheckService
{
    /**
     * @var string The base URI of the API service
     */
    private $baseUri = 'https://adc.maileon.com/svc/2.0';

    /**
     * @var string The user name for authentification
     */
    private $username;

    /**
     * @var string The password for authentification
     */
    private $password;

    /**
     * @var string The MimeType to define the result format
     */
    private $mimeType = 'application/json';

    /**
     * @var string A language tag according to RFC 5646
     */
    private $acceptLanguage;

    /**
     * @var bool Wether or not to activate debugging
     */
    private $debug = false;

    /**
     * @var resource CURL's STDERR verbose output
     */
    private $verboseOut;

    /**
     * @var string The host name of the used proxy
     */
    private $proxyHost;

    /**
     * @var int The port number of the used proxy
     */
    private $proxyPort = 80;

    /**
     * @var int The connection's timeout limit
     */
    private $timeout = 10;

    /**
     * Creates a new service
     * 
     * @param array $config The configuration list
     */
    public function __construct(array $config = array())
    {
        if( array_key_exists('BASE_URI', $config) ) {
            $this->baseUri = $config['BASE_URI'];
        }

        if( array_key_exists('USERNAME', $config) ) {
            $this->username = $config['USERNAME'];
        }
        if( array_key_exists('PASSWORD', $config) ) {
            $this->password = $config['PASSWORD'];
        }

        if( array_key_exists('DEBUG', $config) ) {
            $this->debug = $config['DEBUG'];
        }

        if( array_key_exists('MIME_TYPE', $config) ) {
            $this->mimeType = $config['MIME_TYPE'];
        }

        // Timeout in seconds
        if( array_key_exists('TIMEOUT', $config) ) {
            $this->timeout = $config['TIMEOUT'];
        }

        // Proxy config
        if( array_key_exists('PROXY_HOST', $config) ) {
            $this->proxyHost = $config['PROXY_HOST'];
        }
        if( array_key_exists('PROXY_PORT', $config) ) {
            $this->proxyPort = $config['PROXY_PORT'];
        }
    }

    /**
     * Set the base URI
     * 
     * @param string $baseUri Base URI to the API service
     */
    public function setBaseUri($baseUri)
    {
        $this->baseUri = $baseUri;
    }

    /**
     * Set the user name
     * 
     * @param string $username The username for authentification
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }

    /**
     * Set the password
     * 
     * @param string $password The password for authentification
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * Set the MimeType
     * 
     * @param string $mimeType The MimeType to send and receive body data in
     */
    public function setMimeType($mimeType)
    {
        $this->mimeType = $mimeType;
    }

    /**
     * Set a language tag
     * 
     * @param string $acceptLanguage A language tag according to RFC 5646
     */
    protected function setAcceptLanguage($acceptLanguage)
    {
        $this->acceptLanguage = $acceptLanguage;
    }

    /**
     * Set the connection's timeout
     * 
     * @param int $timeout The connections's timeout limit
     */
    public function setTimeout($timeout)
    {
        $this->timeout = $timeout;
    }

    /**
     * Set the proxy host
     * 
     * @param string $proxyHost The host name of the used proxy
     */
    public function setProxyHost($proxyHost)
    {
        $this->proxyHost = $proxyHost;
    }

    /**
     * Set the proxy port
     * 
     * @param int $proxyPort The port of the used proxy
     */
    public function setProxyPort($proxyPort)
    {
        $this->proxyPort = $proxyPort;
    }

    /**
     * Activates or deactivates debugging
     * 
     * @param bool $debug Activates or deactivates debugging
     */
    public function setDebug($debug)
    {
        $this->debug = $debug;
    }

    /**
     * Returns wether debugging is active
     * 
     * @return bool True if debug is active or false if debug is inactive
     */
    public function isDebug()
    {
        return $this->debug;
    }

    /**
     * Sends a GET request
     * 
     * @param string $resourcePath The path of the resouce
     * @param array $queryParameters A list of all URL parameters
     * @return AddressCheckResult The result of the API call
     */
    public function get($resourcePath, $queryParameters = array())
    {
        $curlSession = $this->prepareSession($resourcePath, $queryParameters);
        return $this->performRequest($curlSession);
    }

    /**
     * Prepares a CURL request
     * 
     * @param string $resourcePath The path of the resource
     * @param array $queryParameters A list of all URL parameters
     * @return \CurlHandle|false Returns the CURL handle on success or false on error
     */
    private function prepareSession($resourcePath, $queryParameters)
    {
        $requestUrl = $this->constructRequestUrl($resourcePath, $queryParameters);
        $headers = $this->constructHeaders();
        $curlSession = curl_init($requestUrl);

        $options = array(
            CURLOPT_HEADER => false,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_FAILONERROR => false,
            CURLOPT_VERBOSE => $this->debug
        );

        if($this->debug) {
            $this->verboseOut = fopen("php://temp", "rw+");
            $options[CURLOPT_STDERR] = $this->verboseOut;
        }

        if($this->timeout) {
            $options[CURLOPT_CONNECTTIMEOUT] = $this->timeout;
            $options[CURLOPT_TIMEOUT] = $this->timeout;
        }

        if($this->proxyHost) {
            $options[CURLOPT_PROXY] = $this->proxyHost;
            $options[CURLOPT_PROXYPORT] = $this->proxyPort;
        }

        curl_setopt_array($curlSession, $options);
        return $curlSession;
    }

    /**
     * Constructs the complete URL to the API service
     * 
     * @param string $resourcePath The path of the resource
     * @param array $queryParameters A list of all URL parameters
     * @return string The complete URL
     */
    private function constructRequestUrl($resourcePath, $queryParameters)
    {
        $requestUrl = $this->baseUri . "/" . $resourcePath;

        if( isset($queryParameters) && !empty($queryParameters) ) {
            $requestUrl = $requestUrl . '?';

            foreach( $queryParameters as $key => $value ) {
                if( is_array($value) ) {
                    foreach( $value as $innerKey => $innerValue ) {
                        $requestUrl .= $key . '=' . $innerValue . '&';
                    }
                } else {
                    $requestUrl .= $key . '=' . $value . '&';
                }
            }

            $requestUrl = rtrim($requestUrl, '&');
        }

        return $requestUrl;
    }

    /**
     * Creates the headers for the request
     * 
     * @throws AddressCheckException if user name and password aren't set
     * @return array A list of all headers
     */
    private function constructHeaders()
    {
        if( empty($this->username) || empty($this->password) ) {
            throw new AddressCheckException("Authorization not set");
        }

        $headers = [
            "Authorization: Basic " . base64_encode($this->username.":".$this->password)
        ];
        
        if( !empty($this->mimeType) ) {
            $headers[] = "Content-type: " . $this->mimeType;
            $headers[] = "Accept: " . $this->mimeType;
        }

        if( !empty($this->acceptLanguage) ) {
            $headers[] = "Accept-Language: " . $this->acceptLanguage;
        }

        return $headers;
    }

    /**
     * Executes the CURL request
     * 
     * @param \CurlHandle $curlSession The CURL handle to be executed
     * @throws AddressCheckException if an error occured
     * @return AddressCheckResult The request's result as an object
     */
    private function performRequest($curlSession)
    {
        $response = curl_exec($curlSession);
        // coerce all false values to null
        $response = $response ? $response : null;

        try {
            $result = new AddressCheckResult($response, $curlSession);

            if( $this->debug ) {
                $this->printDebugInformation($curlSession, $result);
            }

            curl_close($curlSession);

            return $result;
        } catch( AddressCheckException $e ) {
            if( $this->debug ) {
                $this->printDebugInformation($curlSession);
            }

            curl_close($curlSession);

            throw $e;
        }
    }

    /**
     * Prints debug information for the running CURL request
     * 
     * @param \CurlHandle $curlSession The CURL request
     * @param AddressCheckResult $result The result of the CURL request
     * @param AddressCheckException $exception A thrown exception
     */
    private function printDebugInformation($curlSession, $result = null, $exception = null)
    {
        rewind($this->verboseOut);

        $sessionLog = stream_get_contents($this->verboseOut);
        $sessionLog = preg_replace("/^Authorization: .*$/m", "Authorization: ***redacted***", $sessionLog);

        if( defined('RUNNING_IN_PHPUNIT') && RUNNING_IN_PHPUNIT ) {
            echo "\n";
            echo "cURL session log:\n";
            echo $sessionLog . "\n";
            
            if( $result != null ) {
                echo "Result:\n";
                echo $result . "\n";
            }
            
            if( $exception != null ) {
                echo "Caught exception:\n";
                echo $exception . "\n";
            }
        } else {
            echo "<h3>cURL session log</h3>\n";
            echo "<pre>\n";
            echo htmlentities($sessionLog);
            echo "</pre>\n";
            
            if( $result != null ) {
                echo "<h3>Result</h3>\n";
                echo "<pre>\n";
                echo htmlentities($result);
                echo "</pre>\n";
            }
            
            if( $exception != null ) {
                echo "<h3>Exception</h3>\n";
                echo "<pre>\n";
                echo htmlentities($exception);
                echo "</pre>\n";
            }
        }
        
        $this->verboseOut = null;
    }
}
