<?php
// read_languages.php 1.0 - Copyright (c) 2018, Hellsh Ltd. - https://github.com/hellshltd/L10nUtils

$translation_folder = "lang/";
$primary_language = "en";
$extra_languages = ["de"];

function getLangArr($lang_code)
{
	$langarr = [];
	global $translation_folder, $primary_language, $extra_languages;
	if($lang_code != $primary_language && in_array($lang_code, $extra_languages))
	{
		foreach(file($translation_folder.$lang_code.".txt") as $line)
		{
			$arr = explode("=", str_replace("\n", "", str_replace("\r", "", $line)));
			if(count($arr) == 2 && !empty($arr[1]))
			{
				if(substr($arr[0], 0, 2) == "> ")
				{
					continue;
				}
				else if(substr($arr[0], 0, 2) == "# ")
				{
					$arr[0] = substr($arr[0], 2);
				}
				$langarr[$arr[0]] = $arr[1];
			}
		}
	}
	else
	{
		foreach(file($translation_folder.$primary_language.".txt") as $line)
		{
			$arr = explode("=", str_replace("\n", "", str_replace("\r", "", $line)));
			if(count($arr) == 2 && !empty($arr[1]))
			{
				if(substr($arr[0], 0, 2) == "> ")
				{
					continue;
				}
				$langarr[$arr[0]] = $arr[1];
			}
		}
	}
	return $langarr;
}
