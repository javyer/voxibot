<?php

date_default_timezone_set('CET');

if (isset($_REQUEST['key']))
{
  $key = $_REQUEST['key'];
  if ($key != "moreno")
  {
    echo "BAD KEY";
    exit();
  }
}
else
{
  echo "ACCESS DENIED";
  exit();
}

if (isset($_REQUEST['text']))
$text = $_REQUEST['text'];
else
if (isset($_REQUEST['message']))
$text = $_REQUEST['message'];
else
$text = "?";

if (isset($_REQUEST['date']))
$date = $_REQUEST['date'];
else
$date = date("j.n.Y-H.i");

if (isset($_REQUEST['type']))
$type = $_REQUEST['type'];
else
if (isset($_REQUEST['param']))
$type = $_REQUEST['param'];
else
$type = "apache";


if ($type == 'date')
$filename = './logs/log_'.date("j.n.Y").'.txt';
else
if ($type == 'date')
$filename = './logs/log_'.date("j.n.Y").'.txt';
else
if ($type == 'apache')
$filename = false;
else
$filename = './logs/log_'.$type.'.txt';

if ($filename)
{
  file_put_contents($filename, $date.' : '.$text."\n", FILE_APPEND);
}
else
{
  error_log($text, 0);
  //error_log($date.' : '.$text."\n", 0, $_SERVER['DOCUMENT_ROOT']."/logs-voxibot.log");
}

$result="OK";

//header('Content-Type: application/json');
//echo json_encode($data);
header('Content-Type: text/plain');
echo $result;

?>
