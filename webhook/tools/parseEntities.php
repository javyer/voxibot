<?php

// Set default language
$GLOBAL['lang'] = 'fr';


ini_set("max_execution_time",3000);
$files = scandir($_REQUEST['agent'].'/'.'entities/');
$lang = $_REQUEST['lang'];
$j = 0;
$json = "";
foreach($files as $file) {
	$newFile = substr($file,0,strlen($file)-8)."_".$lang.".json";
	if (!file_exists(dirname(__FILE__)."/$newFile")) {
	    if ($file !== "." && $file !== ".." && preg_match("/_".$GLOBAL['lang'].".json/",$file)) {
	        echo "<h2>$file:</h2><br><br>";
	        echo "";
	        $json = json_decode(file_get_contents($_REQUEST['agent'].'/'.'entities/'.$file), true);
	        $i = 0;
	        $newJson = [];
	        foreach ($json as &$item) {
	            $newItem = $item;
	            $synonyms = $item['synonyms'];
	            $newSynonyms = [];
	            foreach($synonyms as &$ob) {
	                $text = trim($ob);
	                if ($text) {
	                    echo "Original: <b>".$ob."</b><br>";
	                    $text = file_get_contents(fetchUrl() ."translate.php?text=".urlencode($text)."&lang=$lang");

		                if (preg_match("/Fatal error/",$text)) die("Error talking to google.");
	                    echo "Replaced: <b>$text</b><br>";
	                    $i++;
	                }
	                array_push($newSynonyms,$text);
	            }
	            $newItem['synonyms'] = $newSynonyms;
	            array_push($newJson,$newItem);
	        }

	        echo "<br><br>Input JSON: ".json_encode($json)."<br>";
	        echo "Output JSON: ".json_encode($newJson)."<br>";
	        $dir = dirname(__FILE__)."/entities";
	        $file=substr($file,0,strlen($file)-8)."_".$lang.".json";
	        $file2 = fopen($dir . '/' . $file,"w");

	        // a different way to write content into
	        // fwrite($file,"Hello World.");

	        fwrite($file2, json_encode($newJson,JSON_PRETTY_PRINT));

	        // closes the file
	        fclose($file2);
	    }
	}
    $j++;

}

function fetchUrl() {
	$protocol = (((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')	|| $_SERVER['SERVER_PORT'] == 443) ? 'https://' : 'http://');
	$actual_link = "$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
	$url = explode("/",$actual_link);
	$len = count($url);
	if (preg_match("/.php/",$url[$len-1])) array_pop($url);
	$actual_link = $protocol;
	foreach($url as $part) $actual_link .= $part."/";
	return $actual_link;
}