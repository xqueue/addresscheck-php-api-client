<?php

namespace XQueue\AddressCheck;

use XQueue\AddressCheck\AddressCheckException;
use XQueue\AddressCheck\Util\SyntaxWarningUtil;

class AddressCheckResult {
	private $curlSession;

	private $statusCode;
	private $contentType;

	private $bodyData = null;
	private $result = null;

	public function __construct($bodyData, $curlSession)
	{
		$this->bodyData = $bodyData;
		$this->curlSession = $curlSession;
		$this->checkResult();
	}

	private function checkResult() {
		$this->statusCode = curl_getinfo($this->curlSession, CURLINFO_HTTP_CODE);
		$this->contentType = curl_getinfo($this->curlSession, CURLINFO_CONTENT_TYPE);

        $this->checkForCURLError();
        $this->checkForServerError();

		$this->setResultFields();
	}

	private function checkForCURLError() {
		if( curl_errno($this->curlSession) ) {
			$curlErrorMessage = curl_error($this->curlSession);
			$curlErrorCode = curl_errno($this->curlSession);

			throw new AddressCheckException("An error occurred in the connection to the REST API. Original cURL error message: $curlErrorMessage", $curlErrorCode);
		}
	}

	private function checkForServerError() {
		if( $this->statusCode >= 500 && $this->statusCode <= 599 ) {
			throw new AddressCheckException("A server error occurred in the REST API (HTTP status code ".$this->statusCode.").", $this->bodyData);
		}
	}

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

	public function getResult() {
		return $this->result;
	}

	public function getStatusCode() {
		return $this->statusCode;
	}

	public function isSuccess() {
		return $this->statusCode >= 200 and $this->statusCode <= 299;
	}

	public function isClientError() {
		return $this->statusCode >= 400 and $this->statusCode <= 499;
	}

	public function getContentType() {
		return $this->getContentType();
	}

	public function getBodyData() {
		return $this->bodyData;
	}

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
