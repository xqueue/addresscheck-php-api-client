<?php

namespace XQueue\AddressCheck;

use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

use XQueue\AddressCheck\AddressCheckException;

class AddressCheckResult {
	private $curlSession;

	private $statusCode;
	private $contentType;

	private $bodyData = null;
	private $resultXML = null;
	private $result = null;

    private $encoders;
    private $normalizers;

    private $serializer;

	public function __construct($bodyData, $curlSession)
	{
        $this->encoders = array(new XmlEncoder(), new JsonEncoder());
        $this->normalizers = array(new ObjectNormalizer());
        $this->serializer = new Serializer($this->normalizers, $this->encoders);

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
        if( $this->bodyData ) {
            if( !empty($this->contentType) ) {
                $type = substr($this->contentType, strpos($this->contentType, '/')+1);
                $this->result = $this->serializer->deserialize($this->bodyData, AddressCheckResult::class, $type, array('object_to_populate' => $this));
            } else {
                $this->result = $this->bodyData;
            }
        }
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

	public function toString() {
		$result = "";
		$result .= "status code: " . $this->getStatusCode() . " "
				. com_maileon_api_HTTPResponseCodes::getStringFromHTTPStatusCode($this->getStatusCode()) . "\n";
		$result .= "is success: " . ($this->isSuccess() ? "true" : "false") . "\n";
		$result .= "is client error: " . ($this->isClientError() ? "true" : "false") . "\n";
		if ($this->bodyData) {
			$result .= "\nbody data:\n";
			$result .= $this->bodyData;
			$result .= "\n\n";
		} else {
			$result .= "No body data.\n";
		}
		if ($this->resultXML) {
			$result .= "Body contains XML.\n";
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
