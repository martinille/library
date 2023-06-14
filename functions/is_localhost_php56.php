<?php

/**
 * Returns true if the PHP script is running on localhost (either through Apache server or CLI), otherwise returns false.
 *
 * @since PHP 5.6+
 * @author Martin Ille
 * @email ille.martin@gmail.com
 *
 * @return bool
 */
function isLocalhost() {
	if (!array_key_exists('REMOTE_ADDR', $_SERVER) || null===$_SERVER['REMOTE_ADDR']) {
		return true;
	}
	return in_array($_SERVER['REMOTE_ADDR'], array('127.0.0.1','::1')) || preg_match('/^192\.168\.\d+\.\d+$/', $_SERVER['REMOTE_ADDR']);
}
