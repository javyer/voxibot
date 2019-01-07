<?php

require("../config.php");

require('include/PHPMailer.php');
require('include/SMTP.php');
require('include/Exception.php');

use PHPMailer\PHPMailer\PHPMailer;
//use PHPMailer\PHPMailer\Exception;

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

if (isset($_REQUEST['address']))
$address = $_REQUEST['address'];
else
if (isset($_REQUEST['to']))
$address = $_REQUEST['to'];
else
if (isset($config['mail']['address']))
$address = $config['mail']['address'];
else
$address = "borja.sixto@ulex.fr";

if (isset($_REQUEST['from']))
$from = $_REQUEST['from'];
else
if (isset($config['mail']['from']))
$from = $config['mail']['from'];
else
$from = "noanswer@voxibot.com";

if (isset($_REQUEST['fromname']))
$fromname = $_REQUEST['fromname'];
else
if (isset($config['mail']['fromname']))
$fromname = $config['mail']['fromname'];
else
$fromname = "Voxibot";

if (isset($_REQUEST['hidden']))
$hidden = $_REQUEST['hidden'];
else
if (isset($config['mail']['hidden']))
$hidden = $config['mail']['hidden'];
else
$hidden = "borja.sixto@ulex.fr";

if (isset($_REQUEST['subject']))
$subject = utf8_encode($_REQUEST['subject']);
else
$subject = "No subject";

if (isset($_REQUEST['body']))
$body = utf8_encode($_REQUEST['body']);
else
$body = "No content";

if (isset($_REQUEST['logs']))
{
  $logs = utf8_encode($_REQUEST['logs']);
  $body .= "\n\nLogs:\n";
}

if (isset($_REQUEST['format']))
{
  $format = $_REQUEST['format'];
}

if (isset($_FILES['attachment']))
{
  $message = $_FILES['attachment']['tmp_name'];
  $message_type = $_FILES['attachment']['type'];
}
else
$message = false;

$caller = "file";

if ($message)
{
  if ($format=="mp3")
  {
   $outfile="/tmp/".$caller.".mp3";
   @unlink($outfile);
   system("ffmpeg -i $message -ab 16k $outfile");
   @unlink($message);
   $message = $outfile;
   $message_type = "audio/mpeg";
  }
  else
  if ($format=="ogg")
  {
   $outfile="/tmp/".$caller.".ogg";
   @unlink($outfile);
   system("ffmpeg -i $message $outfile");
   @unlink($message);
   $message = $outfile;
   $message_type = "audio/ogg";
  }
}

$mail = new PHPMailer();

$mail->IsSMTP();
$mail->SMTPAuth   = $config['mail']['smtpauth'];   // enable SMTP authentication
$mail->SMTPSecure = $config['mail']['smtpsecure'];   // sets the prefix to the server
$mail->Host       = $config['mail']['host'];      // sets GMAIL as the SMTP server
$mail->Port       = $config['mail']['port'];      // set the SMTP port for the GMAIL server
$mail->Username   = $config['mail']['user'];      // GMAIL username
$mail->Password   = $config['mail']['password'];  // GMAIL password

//$mail->SMTPDebug = 10;

//$mail->AddReplyTo("bsixto@gmail.com", "First Last");

$mail->FromName = $fromname;
$mail->From = $from;

$arr = explode(';', $address);
foreach ($arr as &$value) {
  $mail->AddAddress($value);
}
//$mail->AddAddress($address);

if (isset($hidden))
{
  $arr = explode(';', $hidden);
  foreach ($arr as &$value) {
    $mail->addBCC($value);
  }
}

// To add emoji marks
//https://www.iemoji.com/view/emoji/1855/smileys-people/robot-face

//$icon =  "\xE2\x98\x85  "; // Star
//$icon =  "\xF0\x9F\xA4\x96  "; // Robot
$icon =  "\xF0\x9F\x93\x9E  "; // Telephone Receiver
//$icon =  "\xE2\x98\x8E  "; // Telephone
//$icon =  "\xF0\x9F\x92\xAC  "; // Speech Balloon
//$icon =  "\xE2\x9A\xA0  "; // Warning

$mail->Subject = $icon.$subject;

//$mail->CharSet = PHPMailer::CHARSET_ISO88591;
//$mail->CharSet = "text/plain; charset='utf-8'; format='fixed'";
$mail->CharSet = PHPMailer::CHARSET_UTF8;

if ($message)
{
  $extension = pathinfo($message, PATHINFO_EXTENSION);

  if ($message_type == "audio/mpeg")
  $mail->AddAttachment($message, "file.mp3");
  else
  if ($message_type == "audio/x-wav")
  $mail->AddAttachment($message, "file.wav");
  else
  $mail->AddAttachment($message, "file.".$extension);

  $mail->Body = $body."\n\nA file is attached ($message_type)";
}
else
{
  $mail->Body = $body;
}

if(!$mail->Send())
{
 $result="ERROR";
}
else
{
 $result="OK";
}

if ($message)
@unlink($message);

$data = $result;

//header('Content-Type: application/json');
//echo json_encode($data);
header('Content-Type: text/plain');
echo $result;

?>