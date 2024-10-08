<?php

namespace App\Service;

class SlugifyService
{
	public function slugify(string $text): string
	{
		$text = preg_replace('~[^\pL\d]+~u', '-', $text);

		$text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

		$text = preg_replace('~[^-\w]+~', '', $text);

		// Remove dashes from the beginning and end
		$text = trim($text, '-');

		// Convert to lowercase
		$text = strtolower($text);

		// Check if the text is empty
		if (empty($text)) {
			return 'n-a';
		}

		return $text;
	}
}
