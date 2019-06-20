<?php
$main_language = "en";

if(empty($argv))
{
	die("For security purposes this script may only be executed using PHP-CLI.\n");
}

echo "[".date("r")."] Reading {$main_language}.txt...\n";
$format = file($main_language.".txt");
$translations = false;
if(file_exists("translations.json"))
{
	$translations = json_decode(file_get_contents("translations.json"), true);
}
foreach(scandir(".") as $file)
{
	if(!is_dir($file)&&substr($file,-4)==".txt"&&$file!=$main_language.".txt")
	{
		$strings = [];
		echo "[".date("r")."] Processing ".$file."...\n";
		foreach(file($file) as $line)
		{
			$arr = explode("=", str_replace("\n", "", str_replace("\r", "", $line)));
			if(count($arr) == 2)
			{
				$strings[$arr[0]] = $arr[1];
			}
		}
		$cont = $format;
		$total_strings = 0;
		$translated = 0;
		foreach($cont as $i => $line)
		{
			$arr = explode("=", str_replace("\n", "", str_replace("\r", "", $line)));
			if(count($arr) == 2)
			{
				$total_strings++;
				if(!empty($strings[$arr[0]]))
				{
					$cont[$i] = $arr[0]."=".$strings[$arr[0]]."\n";
					$translated++;
				}
				else
				{
					$cont[$i] = "# ".$arr[0]."=".$arr[1]."\n";
				}
			}
		}
		if($file != "new.txt")
		{
			$translations[substr($file,0,-4)]["translated"] = (round(100/$total_strings*$translated*10)/10);
		}
		file_put_contents($file, join("", $cont));
	}
}
if($translations !== false)
{
	file_put_contents("translations.json", json_encode($translations, JSON_PRETTY_PRINT));
}
