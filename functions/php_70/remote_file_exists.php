<?php

/**
 * Check if a remote file exists.
 * @param string $url
 * @return bool
 *
 * @author Martin Ille
 * @email ille.martin@gmail.com
 * @since PHP 7.0+
 * @dependencies:
 *    - cURL extension (required)
 */
function remoteFileExists(string $url): bool {
	$userAgent = 'Mozilla/5.0 (Windows NT 6.2; WOW64) AppleWebKit/537.31 (KHTML, like Gecko) Chrome/26.0.1410.64 Safari/537.31';

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_REFERER, $url);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_NOBODY, true);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3000); // 3 sec.
	curl_setopt($ch, CURLOPT_TIMEOUT, 10000); // 10 sec.
	curl_setopt($ch, CURLOPT_VERBOSE, false); // debug: true
	curl_setopt($ch, CURLOPT_USERAGENT, $userAgent);
	curl_exec($ch);

	$retcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	curl_close($ch);

	return $retcode >= 200 && $retcode < 400;
}