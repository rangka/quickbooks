<?php

namespace Rangka\Quickbooks;

class Webhook extends Client {
	/**
	 * Validate an incoming webhook.
	 *
	 * @return boolean
	 */
	public function validate()
	{
	    $inputSignature = $_SERVER['HTTP_INTUIT_SIGNATURE'] ?? null;

	    if (!$inputSignature) {
	    	return false;
	    }

		$body = file_get_contents('php://input');
	    $calculatedSignature = base64_encode(hash_hmac('sha256', $body, self::$webhook_token, true));

	    return $inputSignature == $calculatedSignature;
	}
}