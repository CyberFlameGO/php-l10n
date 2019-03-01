<?php
$translation_folder = "lang/";
$main_language = "en";
$extra_languages = ["de"];

function getLangArr($lang_code)
{
	$langarr = [];
	global $translation_folder, $main_language, $extra_languages;
	if($lang_code != $main_language && in_array($lang_code, $extra_languages))
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
		foreach(file($translation_folder.$main_language.".txt") as $line)
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

$subdomain = strtolower(explode(".", $_SERVER["HTTP_HOST"])[0]);
if(in_array($subdomain, $supported_langs))
{
	$lang = getLangArr($subdomain);
}
else
{
	$lang = getLangArr($main_language);
}

// echo $lang["hello"];
