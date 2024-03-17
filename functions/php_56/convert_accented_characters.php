<?php

/**
 * Converts accented characters to ASCII
 * @param string $str
 * @param string $delimiter
 * @return string
 *
 *
 * Examples:
 * Input: "José compró una vieja zampoña en Perú. Excusándose, Sofía tiró su whisky al desagüe de la banqueta."
 * Output: "jose-compro-una-vieja-zampona-en-peru-excusandose-sofia-tiro-su-whisky-al-desague-de-la-banqueta"
 * Input: "В чащах юга жил бы цитрус? Да, но фальшивый экземпляр!"
 * Output: "v-casah-uga-zil-by-citrus-da-no-fal-sivyj-ekzemplar"
 * Input: "A quick brown fox jumps over the lazy dog."
 * Output: "a-quick-brown-fox-jumps-over-the-lazy-dog"*
 *
 * Advanced transliterator library:
 * @link https://github.com/martinille/transliteratorPlus
 *
 *
 * @author Martin Ille
 * @email ille.martin@gmail.com
 * @since PHP 5.6+
 * @dependencies:
 *     - transliterator extension (required)
 *     - mbstring extension (required)
 */
function convertAccentedCharacters($str, $delimiter = '-') {
	$ret = transliterator_transliterate('Any-Latin; Latin-ASCII', $str);
	$ret = preg_replace("/[^[:alnum:]]+/", $delimiter, $ret);
	$ret = mb_strtolower(trim($ret));
	return (string)preg_replace(["/" . $delimiter . "+/", "/" . $delimiter . "+$/", "/^" . $delimiter . "+/"], [$delimiter, "", ""], $ret);
}

