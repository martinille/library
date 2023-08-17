<?php

/**
 * Returns true if the PHP script is running on localhost (either through Apache server or CLI), otherwise returns false.
 *
 * @since PHP 7.0+
 * @author Martin Ille
 * @email ille.martin@gmail.com
 *
 * @return bool
 */
function isLocalhost(): bool {

	// ddev project
	if (array_key_exists('IS_DDEV_PROJECT', $_SERVER) && 'true' === strtolower($_SERVER['IS_DDEV_PROJECT'])) {
		return true;
	}

	// probably CLI
	if (!array_key_exists('REMOTE_ADDR', $_SERVER) || null===$_SERVER['REMOTE_ADDR']) {
		return true;
	}

	// probably web-server
	if (preg_match('/^192\.168\.\d+\.\d+$/', $_SERVER['REMOTE_ADDR'])) {
		return true;
	}
	if (in_array($_SERVER['REMOTE_ADDR'],['127.0.0.1','::1'])) {
		return true;
	}

	return false;
}