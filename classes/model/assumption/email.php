<?php

namespace Nerd\Model\Assumption;

class Email extends \Nerd\Model\Assumption {

	private $_message;

	public function check($email)
	{
		// Must be more than 9 characters.
		if (strlen($email) < 9)
		{
			$this->_message = '%s must be larger than 9 characters';
			return false;
		}

		// Must be less than 255 characters
		if (strlen($email) > 255)
		{
			$this->_message = '%s may only be 255 characters long';
			return false;
		}

		// Must have an @ symbol
		$pos = strpos($email,'@'); 

		if ($pos === false or $pos === 0)
		{
			$this->_message = '%s must contain an "@" symbol';
			return false;
		}

		// Must be a valid format
		if (filter_var($email, FILTER_VALIDATE_EMAIL) === false)
		{
			$this->_message = '%s is not a valid email address';
			return false;
		}

		list($user, $domain) = explode('@', $email);

		// Perform a DNS check
		if (!checkdnsrr($domain, "MX"))
		{
			$this->_message = '%s failed our DNS lookup, please enter a currently valid email';
			return false;
		}

		return true;
	}

	public function errorText()
	{
		return $this->_message;
	}
}