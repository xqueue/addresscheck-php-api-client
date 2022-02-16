<?php

namespace XQueue\AddressCheck\API;

use XQueue\AddressCheck\API\AddressCheckException;
use XQueue\AddressCheck\API\Util\SyntaxWarningUtil;

class AddressCheckResult {
	/**
	 * @var \CurlHandle The CURL request
	 */
	private $curlSession;

	/**
	 * @var int The returned HTTP status code
	 */
	private $statusCode;

	/**
	 * @var string The response's content-type
	 */
	private $contentType;

	/**
	 * @var string The response's body data, formatted as the content-type
	 */
	private $bodyData = null;

	/**
	 * @var mixed The response's body data or syntax warnings
	 */
	private $result = null;

	/**
	 * Create a new result
	 * 
	 * @param string $bodyData The response's body data
	 * @param \CurlHandle $curlSession The CURL request
	 */
	public function __construct($bodyData, $curlSession)
	{
		$this->bodyData = $bodyData;
		$this->curlSession = $curlSession;
		$this->checkResult();
	}

	/**
	 * Checks errors and sets the result
	 */
	private function checkResult() {
		$this->statusCode = curl_getinfo($this->curlSession, CURLINFO_HTTP_CODE);
		$this->contentType = curl_getinfo($this->curlSession, CURLINFO_CONTENT_TYPE);

        $this->checkForCURLError();
        $this->checkForServerError();

		$this->setResultFields();
	}

	/**
	 * Checks for CURL errors
	 * 
	 * @throws AddressCheckException if a CURL error occured
	 */
	private function checkForCURLError() {
		if( curl_errno($this->curlSession) ) {
			$curlErrorMessage = curl_error($this->curlSession);
			$curlErrorCode = curl_errno($this->curlSession);

			throw new AddressCheckException("An error occurred in the connection to the REST API. Original cURL error message: $curlErrorMessage", $curlErrorCode);
		}
	}

	/**
	 * Checks the HTTP status code for server errors
	 * 
	 * @throws AddressCheckException if a server error occured
	 */
	private function checkForServerError() {
		if( $this->statusCode >= 500 && $this->statusCode <= 599 ) {
			throw new AddressCheckException("A server error occurred in the REST API (HTTP status code ".$this->statusCode.").", $this->bodyData);
		}
	}

	/**
	 * Sets the result
	 */
	private function setResultFields() {
        if( !empty($this->bodyData) ) {
            if( strpos($this->contentType, 'json') !== false ) {
				$result = json_decode($this->bodyData, true);
				$this->result = $this->addSyntaxWarnings($result);
			} elseif( strpos($this->contentType, 'xml') !== false ) {
				$xml = new \SimpleXMLElement($this->bodyData);
				$json = json_encode($xml);
				$result = json_decode($json, true);
				$this->result = $this->addSyntaxWarnings($result);
            } else {
                $this->result = $this->bodyData;
            }
        }
	}
	
	/**
	 * Creates readable syntax warnings
	 * 
	 * @param array $data The list of the response's body data
	 * @return array The result with readable syntax warnings
	 */
	private function addSyntaxWarnings($data) {
		foreach( $data as $key => $val ) {
			if( $key == "syntaxWarnings" ) {
				$syntaxWarnings = array();
				foreach( $val as $innerKey => $innerVal ) {
					$syntaxWarnings[$innerVal] = SyntaxWarningUtil::getWarningMessageById($innerVal);
				}
				$result[$key] = $syntaxWarnings;
			} else {
				$result[$key] = $val;
			}
		}
		
		return $result;
	}

	/**
	 * Returns the result
	 * 
	 * @return mixed The processed result
	 */
	public function getResult() {
		return $this->result;
	}

	/**
	 * Returns the response's HTTP status code
	 * 
	 * @return int The HTTP status code
	 */
	public function getStatusCode() {
		return $this->statusCode;
	}

	/**
	 * Checks if the request succeeded
	 * 
	 * @return bool true if the HTTP status code is within 200-299 or false otherwise
	 */
	public function isSuccess() {
		return $this->statusCode >= 200 and $this->statusCode <= 299;
	}

	/**
	 * Checks if a client error occured
	 * 
	 * @return bool true if the HTTP status code is within 400-499 or false otherwise
	 */
	public function isClientError() {
		return $this->statusCode >= 400 and $this->statusCode <= 499;
	}

	/**
	 * Returns the response's content-type
	 * 
	 * @return string The response's content-type
	 */
	public function getContentType() {
		return $this->getContentType();
	}

	/**
	 * Returns the response's unprocessed body data
	 * 
	 * @return string The response's unprocessed body data
	 */
	public function getBodyData() {
		return $this->bodyData;
	}

	/**
	 * Returns a human readable representation of this class
	 * 
	 * @return string The human readable representation of this class
	 */
	public function __toString() {
		$result = "";
		$result .= "status code: " . $this->getStatusCode() . "\n";
		$result .= "is success: " . ($this->isSuccess() ? "true" : "false") . "\n";
		$result .= "is client error: " . ($this->isClientError() ? "true" : "false") . "\n";

		if ($this->bodyData) {
			$result .= "\nbody data:\n";
			$result .= $this->bodyData;
			$result .= "\n\n";
		} else {
			$result .= "No body data.\n";
		}

		$resultType = gettype($this->result);
		if ($resultType == "object") {
			$result .= "Result type: " . get_class($this->result) . "\n";
		} else {
			$result .= "result type: " . $resultType . "\n";
		}

		return $result;
	}
}
