<?php

namespace XQueue\AddressCheck\API;

use XQueue\AddressCheck\API\AddressCheckResult;
use XQueue\AddressCheck\API\AddressCheckException;

abstract class AbstractAddressCheckService
{
    private $baseUri = 'https://adc.maileon.com/svc/2.0';

    private $username;
    private $password;

    private $mimeType = 'application/json';

    private $debug = false;
    private $verboseOut;

    private $proxyHost;
    private $proxyPort = 80;

    private $timeout;

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

    public function setBaseUri($baseUri)
    {
        $this->baseUri = $baseUri;
    }

    public function setUsername($username)
    {
        $this->username = $username;
    }

    public function setPassword($password)
    {
        $this->password = $password;
    }

    public function setMimeType($mimeType)
    {
        $this->mimeType = $mimeType;
    }

    public function setTimeout($timeout)
    {
        $this->timeout = $timeout;
    }

    public function setProxyHost($proxyHost)
    {
        $this->proxyHost = $proxyHost;
    }

    public function setProxyPort($proxyPort)
    {
        $this->proxyPort = $proxyPort;
    }

    public function setDebug($debug)
    {
        $this->debug = $debug;
    }

    public function isDebug()
    {
        return $this->debug;
    }

    public function get($resourcePath, $queryParameters = array())
    {
        $curlSession = $this->prepareSession($resourcePath, $queryParameters);
        return $this->performRequest($curlSession);
    }

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

    private function constructHeaders()
    {
        if( empty($this->username) || empty($this->password) ) {
            throw new AddressCheckException("Authorization not set");
        }

        $headers = array(
            "Content-type: " . $this->mimeType,
            "Accept: " . $this->mimeType,
            "Authorization: Basic " . base64_encode($this->username.":".$this->password),
            "Expect:"
        );

        return $headers;
    }

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
