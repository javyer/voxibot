<html>
<head>
<title>Click-to-Call</title>
</head>
<body>
<?php
#Click-To-Call script brought to you by VoipJots.com

if ($_GET['phone'])
$phone = $_GET['phone'];
if ($_POST['phone'])
$phone = $_POST['phone'];

if ($_GET['param'])
$param = $_GET['param'];
if ($_POST['param'])
$param = $_POST['param'];

#------------------------------------------------------------------------------------------
#edit the below variable values to reflect your system/information
#------------------------------------------------------------------------------------------

$strPeer="ovh-demo-out"; //"voztele-out";
$strDefaultPhone="0677379042"; $phone; //"0033476894796";
$strAccount="Adecco";

#specify the name/ip address of your asterisk box
#if your are hosting this page on your asterisk box, then you can use
#127.0.0.1 as the host IP.  Otherwise, you will need to edit the following
#line in manager.conf, under the Admin user section:
#permit=127.0.0.1/255.255.255.0
#change to:
#permit=127.0.0.1/255.255.255.0,xxx.xxx.xxx.xxx ;(the ip address of the server this page is running on)
$strHost = "127.0.0.1";

#specify the username you want to login with (these users are defined in /etc/asterisk/manager.conf)
#this user is the default AAH AMP user; you shouldn't need to change, if you're using AAH.
$strUser = "dialer";

#specify the password for the above user
$strSecret = "1234";

#specify the channel (extension) you want to receive the call requests with
#e.g. SIP/XXX, IAX2/XXXX, ZAP/XXXX, etc
if ($strPeer)
$strChannel = "SIP/".$phone."@".$strPeer;
else
$strChannel = "SIP/".$phone;
//echo "SIP/".$phone;

#specify the context to make the outgoing call from.  By default, AAH uses from-internal
#Using from-internal will make you outgoing dialing rules apply
$strContext = "default";

#specify the amount of time you want to try calling the specified channel before hangin up
$strWaitTime = "30";

#specify the priority you wish to place on making this call
$strPriority = "1";

#specify the maximum amount of retries
$strMaxRetry = "2";

#--------------------------------------------------------------------------------------------
#Shouldn't need to edit anything below this point to make this script work
#--------------------------------------------------------------------------------------------
#get the phone number from the posted form
$strExten = $phone; //"207"; //"600"; //.$_POST['txtphonenumber'];

#specify the caller id for the call
$strCallerId = "Web Call <$strExten>";

$length = strlen($phone);

//if ($length >= 9 && is_numeric($phone))
if ($phone)
{
$oSocket = fsockopen($strHost, 5038, $errnum, $errdesc) or die("Connection to host failed");
fputs($oSocket, "Action: login\r\n");
fputs($oSocket, "Events: off\r\n");
fputs($oSocket, "Username: $strUser\r\n");
fputs($oSocket, "Secret: $strSecret\r\n\r\n");
fputs($oSocket, "Codecs: h263p\r\n\r\n");
fputs($oSocket, "Action: originate\r\n");
fputs($oSocket, "Channel: $strChannel\r\n");
fputs($oSocket, "WaitTime: $strWaitTime\r\n");
fputs($oSocket, "CallerId: $strCallerId\r\n");
fputs($oSocket, "Variable: __VOXIMAL_ID=$id\r\n");
fputs($oSocket, "Variable: __VOXIMAL_PARAM=$param\r\n");
fputs($oSocket, "Variable: __VOXIMAL_NUMBER=$strAccount\r\n");
//fputs($oSocket, "Variable: __VOXIMAL_LOCAL=vxml_$phone\r\n");
fputs($oSocket, "Variable: __VOXIMAL_REMOTE=$strExten\r\n");
//fputs($oSocket, "Exten: $strExten\r\n");
//fputs($oSocket, "Context: $strContext\r\n");
//fputs($oSocket, "Priority: $strPriority\r\n\r\n");
fputs($oSocket, "Application: Voximal\r\n\r\n");
fputs($oSocket, "Action: Logoff\r\n\r\n");
sleep(2);
fclose($oSocket);
?>
<p>
<table width="300" border="1" bordercolor="#630000" cellpadding="3" cellspacing="0">
	<tr><td>
	<font size="2" face="verdana,georgia" color="#630000">We are currently trying to call you.  Please be patient, and wait for
	your phone to <?php echo $strChannel  ?> ring!<br>If your phone does not ring after 2 minutes, we apologize, but must either be out, or
already on the
	phone.<br><a href="<?php echo $_SERVER['PHP_SELF'] ?>">Try Again</a></font>
	</td></tr>
</table>
</p>
<?php
}
else
{
?>
<p>
<table width="300" border="1" bordercolor="#630000" cellpadding="3" cellspacing="0">
	<tr><td>
	<font size="2" face="verdana,arial,georgia" color="#630000">Enter your phone number, and we will call you a
	few seconds later!</font>
	<form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
		Phone <input type="text" size="20" maxlength="50" name="phone" value="<?php echo $strDefaultPhone ?>"><br>
		Param <input type="text" size="20" name="param" value="<?php echo $strExten ?>"><br>
		<input type="submit" value="Call">
	</form>
	</td></tr>
</table>
</p>
<?php
}
?>
</body>
</html>
