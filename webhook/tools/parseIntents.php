<?php

// Set default language
$GLOBAL['lang'] = 'fr';


ini_set("max_execution_time",3000);
$files = scandir($_REQUEST['agent'].'/'.'intents/');
$lang = $_REQUEST['lang'];
$j = 0;
$json = "";
foreach($files as $file) {
	$newFile = substr($file,0,strlen($file)-8)."_".$lang.".json";
	if (!file_exists(dirname(__FILE__)."/$newFile")) {
	    if ($file !== "." && $file !== ".." && preg_match("/_".$GLOBAL['lang'].".json/",$file)) {


          $messageFile = substr($file,0,strlen($file)-17).".json";
          if (!file_exists(dirname(__FILE__)."/$messageFile")) {
	          echo "<h1>Intent : ".substr($file,0,strlen($file)-17)."</h1>";
          }

	        echo "<h3>file : $file:</h3>";
	        $json = json_decode(file_get_contents($_REQUEST['agent'].'/'.'intents/'.$file), true);

          echo "Input JSON: ".json_encode($json)."<br><br>";

	        $i = 0;
	        $newJson = [];
	        foreach ($json as &$item) {
	            $newItem = $item;
	            $data = $item['data'];
	            $newData = [];
	            foreach($data as &$ob) {
	                $ob2 = $ob;
	                $text = trim($ob['text']);
	                if ($text) {
	                    echo "Original: <b>".$ob['text']."</b><br>";
	                    //$text = "foo";
		                $actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
                    //echo fetchUrl() ."translate.php?text=".urlencode($text)."&from=fr"."&to=$lang".'<br>';
		                $text = file_get_contents(fetchUrl() ."translate.php?text=".urlencode($text)."&from=".$GLOBAL['lang']."&to=$lang");
	                    if (preg_match("/Fatal error/",$text)) die("Error talking to google.");
	                    echo "Replaced: <b>$text</b><br>";

	                    $ob2['text'] = $text;
	                    $i++;
	                }
	                array_push($newData,$ob2);
	            }
	            $newItem['data'] = $newData;
	            array_push($newJson,$newItem);
	        }


	        $dir = dirname(__FILE__)."/".$_REQUEST['agent']."/intents";
	        $filename=substr($file,0,strlen($file)-8)."_".$lang.".json.translated";

	        echo "<h3>file : $filename:</h3>";

	        echo "Output JSON: ".json_encode($newJson)."<br>";

	        $file2 = fopen($dir . '/' . $filename,"w");

	        // a different way to write content into
	        // fwrite($file,"Hello World.");

	        fwrite($file2, json_encode($newJson,JSON_PRETTY_PRINT));

	        // closes the file
	        fclose($file2);

          flush();

          $messageFile = substr($file,0,strlen($file)-17).".json";
          if (!file_exists(dirname(__FILE__)."/$messageFile")) {

	          echo "<h3>file : $messageFile:</h3>";
  	        $json = json_decode(file_get_contents($_REQUEST['agent'].'/'.'intents/'.$messageFile), true);

	          echo "Input JSON: ".json_encode($json)."<br><br>";

  	        $newJson = $json;

            if (!$json['webhookUsed'])
            {
              echo "<b>NEED WEEBHOOK !!!</b>".'<br>';
              $newJson['webhookUsed'] = true;
            }
  	        foreach ($json['responses'] as &$response) {

       	        foreach ($response['messages'] as &$message) {

                  if ($message['lang'] == $GLOBAL['lang'])
                  {
                    $text = trim($message['speech']);
  	                if ($text) {
  	                    echo "Original: <b>".$message['speech']."</b><br>";

    	                    //$text = "foo";
    		                $actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
                        //echo fetchUrl() ."translate.php?text=".urlencode($text)."&from=fr"."&to=$lang".'<br>';
    		                $text = file_get_contents(fetchUrl() ."translate.php?text=".urlencode($text)."&from=".$GLOBAL['lang']."&to=$lang");
    	                    if (preg_match("/Fatal error/",$text)) die("Error talking to google.");
    	                    echo "Replaced: <b>$text</b><br>";
                    }

      	            foreach($message['speech'] as &$text) {
                      //$text = trim($text);
    	                if ($text) {
    	                    echo "Original: <b>".$text."</b><br>";
                      }
                    }
                  }

                }
	        }
          }
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