<?php

echo "!!! YOU NEED TO RENAME THIS FILE WITH config.php, AND REMOVE THIS 2 LINES !!!";
exit;

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
