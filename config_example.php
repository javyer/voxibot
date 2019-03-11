<?php

echo "!!! YOU NEED TO RENAME THIS FILE WITH config.php, AND REMOVE THIS 2 LINES !!!";
exit;

$headers = apache_request_headers();
if (isset($_REQUEST['param']))
{
  $param=$_REQUEST['param'];
}
if (isset($headers['Voximal-Parameter']))
{
  $param=$headers['Voximal-Parameter'];
}

if (isset($param))
$filename="configs/".$param.".php";

if (false)
if(isset($_SERVER['DATA'])) {
  $oldtime = strtotime($_SERVER['DATE']);
  $newtime = time() - 6000;

  if (file_exists($config))
  $newtime = filemtime($filename);

  if ($oldtime < $newtime) {
    header('HTTP/1.1 304 Not Modified');
    exit;
  }
  else
  {
    header('Last-Modified: ', gmdate('D, d M Y H:i:s T', $newtime));
  }
}

// Voxibot configuration file

$config['db']['hostname'] = "localhost";
$config['db']['user'] = "root";
$config['db']['password'] = "";
$config['db']['name'] = "voxibot";

$config['mail']['smtpauth'] = true;
$config['mail']['smtpsecure'] = "ssl";
$config['mail']['host'] = "smtp.eu.mailgun.org";
$config['mail']['port'] = 465;
$config['mail']['user'] = "postmaster@voxibot.com";
$config['mail']['password'] = "****";
$config['mail']['address'] = "borja.sixto@ulex.fr";
$config['mail']['hidden'] = "bsixto@gmail.com";
//$config['mail']['fromname'] = "VOXIBOT";
//$config['mail']['from'] = "contact@voxibot.com";

$config['recognize']['api'] = "google_dialogflow";
$config['recognize']['language'] = "fr";

$config['chatbot']['api'] = "dialogflow2";
$config['chatbot']['authorization'] = "";

$config['google']['dialogflow'] = "****"; // Dialogflow V1
$config['google']['speech'] = "****";  // Facebook

$headers = apache_request_headers();
if (isset($_REQUEST['param']))
{
  @include("configs/".$_REQUEST['param'].".php");
}
if (isset($headers['Voximal-Parameter']))
{
  @include("configs/".$headers['Voximal-Parameter'].".php");
}


?>
