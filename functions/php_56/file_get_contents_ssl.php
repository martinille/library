<?php

/**
 * Reads an URL address (both http:// and https://) and returns its content (or false if failed)
 * It supports: HTTP/S requests, GET/POST request types, HTTP authentication, cookies.
 *
 * @author Martin Ille
 * @email ille.martin@gmail.com
 *
 * @since PHP 5.6+
 * @dependencies:
 *   - cURL extension (required),
 *   - intl extension (optional but recommended)
 *   - mbstring/multibyte string extension (optional but recommended)
 *
 * @param string $url The URL to be requested. Its contents (without headers) will be returned. (required)
 * @param array $post POST data in array ['key'=>'value']. If not empty, POST request type will be used. (optional)
 * @param string $authUser User-name for HTTP authentication (optional)
 * @param string $authPass Password for HTTP authentication (optional)
 * @param bool $isLocalhost Set true, if URL is localhost and should be requested from IP adress 127.0.0.1 and it is not working
 * @return bool|string
 */
function file_get_contents_ssl($url, array $post = array(), $authUser = '', $authPass = '', $isLocalhost = false) {

	// user-agent header
	$userAgent = "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/112.0.0.0 Safari/537.36";

	// sets temporary directory for cookies file
	if (defined('DIR_TMP')) { // user-defined DIR_TMP folder
		$tmpDir = DIR_TMP;
	} else {
		$tmpDir = sys_get_temp_dir(); // system temp folder
	}
	$tmpDir = rtrim($tmpDir, '\\/') . DIRECTORY_SEPARATOR; // clean-up path to tmp folder


	// creates temp folder if not exists
	if (!is_dir($tmpDir)) {
		mkdir($tmpDir, 0777, true);
	}

	// gets hostname from requested URL
	$hostname = $hostnameNormalized = parse_url($url, PHP_URL_HOST);

	// normalization of host name
	if (extension_loaded('intl')) {
		$hostnameNormalized = transliterator_transliterate('Any-Latin; Latin-ASCII', $hostnameNormalized);
	}
	$hostnameNormalized = preg_replace("/[^[:alnum:]]+/u", '-', $hostnameNormalized);
	$hostnameNormalized = trim($hostnameNormalized);

	if (extension_loaded('mbstring')) {
		$hostnameNormalized = mb_strtolower($hostnameNormalized);
	} else {
		$hostnameNormalized = strtolower($hostnameNormalized);
	}

	// this makes something like "/var/tmp/cookies_translate-google-com_5e862ba3.txt" from hostname "translate.google.com"
	$cookieFile = $tmpDir . 'cookies_' . $hostnameNormalized . '_' . substr(sha1($hostname), 0, 8) . '.txt';
	touch($cookieFile);

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_HEADER, false);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_REFERER, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3000); // 3 sec.
	curl_setopt($ch, CURLOPT_TIMEOUT, 10000); // 10 sec.
	curl_setopt($ch, CURLOPT_VERBOSE, false); // set true if debugging
	curl_setopt($ch, CURLOPT_USERAGENT, $userAgent);
	curl_setopt($ch, CURLOPT_COOKIEFILE, $cookieFile);
	curl_setopt($ch, CURLOPT_COOKIEJAR, $cookieFile);

	// Added in cURL 7.21.3, just to be sure
	if (!defined('CURLOPT_RESOLVE')) {
		define('CURLOPT_RESOLVE', 10203);
	}

	// If running on localhost, custom DNS has to be set
	if ($isLocalhost) {
		curl_setopt($ch, CURLOPT_RESOLVE, array(
			'-'.$hostname.':80:127.0.0.1',
			$hostname.':80:127.0.0.1',
		));
	}

	// Sets request type as POST and adds POST data
	if (!empty($post)) {
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post));
	}

	// HTTP authentication
	if (!empty($authUser . $authPass)) {
		curl_setopt($ch, CURLOPT_USERPWD, $authUser . ':' . $authPass);
	}

	$result = curl_exec($ch);
	// $info = curl_getinfo($ch); // request information
	// $err = curl_error($ch); // error message if curl_exec() failed

	curl_close($ch);
	return $result;
}