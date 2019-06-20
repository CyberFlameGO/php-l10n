<?php
// Config:
$translation_folder = "lang/";
$main_language = "en";
// Feel free to hardcode languages here for more performance:
$extra_languages = array_keys(json_decode(file_get_contents($translation_folder."/translations.json", true)));

if(headers_sent($file, $line))
{
	die("php-l10n fatal error: headers have already been sent in {$file}:{$line}\n");
}
header("Vary: Accept-Language");

function getLangArr($lang_code)
{
	$lang = [];
	global $translation_folder, $main_language, $extra_languages;
	if($lang_code != $main_language)
	{
		if(!in_array($lang_code, $extra_languages))
		{
			trigger_error("getLangArr called for unsupported language: ".$lang_code);
			return getLangArr($main_language);
		}
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
				$lang[$arr[0]] = $arr[1];
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
				$lang[$arr[0]] = $arr[1];
			}
		}
	}
	return $lang;
}

$lang = false;
if(!empty($_SERVER["HTTP_ACCEPT_LANGUAGE"]))
{
	foreach(explode(",", $_SERVER["HTTP_ACCEPT_LANGUAGE"]) as $lang_code)
	{
		$lang_code = strtolower(trim(explode(";", $lang_code)[0]));
		if($lang_code == $main_language || in_array($lang_code, $extra_languages))
		{
			$lang = true;
			break;
		}
		$pos = strpos($lang_code, "-");
		if($pos !== false)
		{
			$lang_code = substr($lang_code, 0, $pos);
			if($lang_code == $main_language || in_array($lang_code, $extra_languages))
			{
				$lang = true;
				break;
			}
		}
	}
}
if(!$lang)
{
	$lang_code = $main_language;
}
$lang = getLangArr($lang_code);

// echo $lang["hello"];
