<?php
 // File containing User related DB functions

require_once('db.php');

	function createUser($postData) {

		$hashedPass = hashPassword($postData['password']);

	}

	function hashPassword($password) {

		// Taken from: http://alias.io/2010/01/store-passwords-safely-with-php-and-mysql/
		// How to store passwords safely with PHP and MySQL

		// A higher "cost" is more secure but consumes more processing power
		$cost = 10;

		// Create a random salt
		$salt = strtr(base64_encode(mcrypt_create_iv(16, MCRYPT_DEV_URANDOM)), '+', '.');

		// Prefix information about the hash so PHP knows how to verify it later.
		// "$2a$" Means we're using the Blowfish algorithm. The following two digits are the cost parameter.
		$salt = sprintf("$2a$%02d$", $cost) . $salt;

		// Hash the password with the salt
		$hash = crypt($password, $salt);

		return $hash;

	}

?>