<?php

function file_get_contents_ssl(string $url, array $post = []) {
	$userAgent = 'Mozilla/5.0 (Windows NT 6.2; WOW64) AppleWebKit/537.31 (KHTML, like Gecko) Chrome/26.0.1410.64 Safari/537.31';

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
	curl_setopt($ch, CURLOPT_VERBOSE, false); // debug: true
	curl_setopt($ch, CURLOPT_USERAGENT, $userAgent);

	if (isLocalhost()) {
		curl_setopt($ch, CURLOPT_RESOLVE, [
			'-findware.localhost:80:127.0.0.1',
			'findware.localhost:80:127.0.0.1',
		]);
	}

	if (!empty($post)) {
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS,http_build_query($post));
	}

	$result = curl_exec($ch);

	//d(curl_getinfo($ch));
	//d(curl_error($ch));

	curl_close($ch);
	return $result;
}