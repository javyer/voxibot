<?php

include_once '../config.php';
include_once 'include/tools.php';

/*
$params['name'] = 'momo';
$params['genre'] = 'madame';
//$params['test'] = '123';

database_set("0677379042", $params);

exit;
*/

//include 'calculator/index.php';
//exit;

// Get the Json request

debug("\n\n\n");
debug("-----------------------------------------------------------------------------\n");
debug("\n\n\n");

debug("---- REQUEST START");

$fulfillment_request = file_get_contents("php://input");
$fulfillment = json_decode($fulfillment_request, true);

debug(">>>> JSON : ".json_encode($fulfillment, JSON_PRETTY_PRINT));
debug("---- PROCESS START");


// Detect the API version with the session parameter
if (isset($fulfillment["id"]))
{
  $id=$fulfillment['id'];
  $apiversion=1;
}
else
if (isset($fulfillment["responseId"]))
{
  $id=$fulfillment['responseId'];
  $apiversion=2;
}
else
{
  debug(">>>> ERROR : Invalid resquest");
  exit();
}


debug("    API version=".$apiversion);

// Process request from Version 1
if ($apiversion==1)
{
  if (isset($fulfillment["session"]))
  {
    $sessionid= $fulfillment['session'];
    $sessionid = str_replace(".", "-", $sessionid);
    session_id($sessionid);
  }

  if (session_start())
  {
    debug("Server=".$_SERVER['HTTP_USER_AGENT']);
    $_SESSION['id'] = session_id();
    debug("Start session=" . $_SESSION['id']);
  }
  else
  {
    debug("Retreive session=" . json_encode($_SESSION, JSON_PRETTY_PRINT));
  }

  if (isset($fulfillment["lang"])) {
    $_SESSION['lang'] = $fulfillment["lang"];
    debug("    lang=" . $_SESSION['lang']);
  }

  // Store the query
  if (isset($fulfillment["result"]["resolvedQuery"])) {
    $GLOBALS["query"] = $fulfillment["result"]["resolvedQuery"];
    debug("    query=" . $GLOBALS["query"]);
  }

  // Store parameters
  if (isset($fulfillment["result"]["parameters"])) {
    $GLOBALS["parameters"] = $fulfillment["result"]["parameters"];

    foreach ($GLOBALS["parameters"] as $key => $value) {
      //debug("{$key} => {$value} ");

      $GLOBALS["parameters"][$key] = utf8_decode(template($GLOBALS["parameters"][$key]));

      /*
      if ($value[0]=='%')
      {
        $var = explode('.', $value);

        if (substr($var[1], -1)=='%')
        $var[1] = substr($var[1], 0, -1);

        if ($var[0]=="%session")
        $GLOBALS["parameters"][$key]=$_SESSION[$var[1]];
        if ($var[0]=="%user")
        $GLOBALS["parameters"][$key]=$_SESSION["user"][$var[1]];
      }
      */
    }

    debug("    parameters=".json_encode($GLOBALS['parameters']));
  }

  // Store the text
  if (isset($fulfillment["result"]["fulfillment"]["speech"])) {
    $GLOBALS["speech"] = $fulfillment["result"]["fulfillment"]["speech"];
    debug("    speech=".$GLOBALS["speech"]);
  }

  if (isset($fulfillment["result"]["action"]))
  if ($fulfillment["result"]["action"] != '')
  {
    $GLOBALS["action"] = $fulfillment["result"]["action"];
    debug("    action=".$GLOBALS["action"]);
  }

  // Wit.ai
  if (isset($fulfillment["result"]["entities"]["intent"]))
  //if ($fulfillment["entities"]["intent"][0] != '')
  {
    $GLOBALS["action"] = 'execute.'.$fulfillment["result"]["entities"]["intent"][0]['value'];
    debug("    action=".$GLOBALS["action"]);
  }


  if (isset($fulfillment["result"]["metadata"]))
  if (isset($fulfillment["result"]["metadata"]["intentName"]))
  {
    $GLOBALS["intent"] = $fulfillment["result"]["metadata"]["intentName"];
    debug("    intent=".$GLOBALS["intent"]);
  }

  //if (!isset($_SESSION["user"]))
  if (isset($fulfillment["originalRequest"]["source"]))
  {
    // facebook
    if ($fulfillment["originalRequest"]["source"] == "facebook")
    if (isset($fulfillment["originalRequest"]["data"]["user"])) {
      $_SESSION["lastSeen"]= $fulfillment["originalRequest"]["data"]["user"]["lastSeen"];
      $_SESSION["locale"]= $fulfillment["originalRequest"]["data"]["user"]["locale"];
      $_SESSION["userId"] = $fulfillment["originalRequest"]["data"]["user"]["userId"];
      $_SESSION["caller"] = 'facebook';
      $_SESSION["called"] = 'facebook/voxibot';
      $_SESSION["user"] = database_get($_SESSION["userFacebook"], "facebook");
      debug("    user(google)=".json_encode($_SESSION["user"], JSON_PRETTY_PRINT));
    }

    // google
    if ($fulfillment["originalRequest"]["source"] == "google")
    if (isset($fulfillment["originalRequest"]["data"]["user"])) {
      $_SESSION["lastSeen"]= $fulfillment["originalRequest"]["data"]["user"]["lastSeen"];
      $_SESSION["locale"]= $fulfillment["originalRequest"]["data"]["user"]["locale"];
      $_SESSION["userId"] = $fulfillment["originalRequest"]["data"]["user"]["userId"];
      $_SESSION["caller"] = 'google';
      $_SESSION["called"] = 'google/voxibot';
      $_SESSION["user"] = database_get($_SESSION["userId"], "google");
      debug("    user(google)=".json_encode($_SESSION["user"], JSON_PRETTY_PRINT));
    }

    // voxibot
    if ($fulfillment["originalRequest"]["source"] == "voxibot")
    if (isset($fulfillment["originalRequest"]["data"]["user"])) {
      $_SESSION["param"]= $fulfillment["originalRequest"]["data"]["user"]["param"];
      $_SESSION["called"]= $fulfillment["originalRequest"]["data"]["user"]["called"];
      $_SESSION["caller"] = $fulfillment["originalRequest"]["data"]["user"]["caller"];
      $_SESSION["user"] = database_get($_SESSION["caller"], "voxibot");
      debug("    user(voxibot)=".json_encode($_SESSION["user"], JSON_PRETTY_PRINT));
    }
  }

}


if ($apiversion==2)
{
  // Session ID
  if (isset($fulfillment["session"]))
  {
    $sessionid= $fulfillment['session'];
    $sessionid = strstr($sessionid, 'session');
    $sessionid = substr($sessionid, 9);

    debug("    use session_id=".$sessionid);
    $sessionid = str_replace("_", "-", $sessionid);
    $sessionid = str_replace(".", "-", $sessionid);
    $sessionid = str_replace("/", "-", $sessionid);
    //debug("    use session_id=".$sessionid);
    session_id($sessionid);
  }

  if (session_start())
  {
    //debug("    Agent=".$_SERVER['HTTP_USER_AGENT']);
    $_SESSION['id'] = session_id();
    debug("    session(id)=" . $_SESSION['id']);
  }

  // Language
  if (isset($fulfillment["queryResult"]["languageCode"])) {
    $_SESSION['lang'] = $fulfillment["queryResult"]["languageCode"];
    debug("    lang=" . $_SESSION['lang']);
  }

  // Store the query
  if (isset($fulfillment["queryResult"]["queryText"])) {
    $GLOBALS["query"] = $fulfillment["queryResult"]["queryText"];
    debug("    query=" . $GLOBALS["query"]);
  }

  // Store parameters
  if (isset($fulfillment["queryResult"]["parameters"])) {
    $GLOBALS["parameters"] = $fulfillment["queryResult"]["parameters"];

    foreach ($GLOBALS["parameters"] as $key => $value) {
      $GLOBALS["parameters"][$key] = utf8_decode(template($GLOBALS["parameters"][$key]));
    }

    debug("    parameters=".json_encode($GLOBALS['parameters'], JSON_PRETTY_PRINT));
  }

  // Store the text
  if (isset($fulfillment["queryText"])) {
    $GLOBALS["speech"] = $fulfillment["queryText"];
    debug("    speech=".$GLOBALS["speech"]);
  }

  if (isset($fulfillment["queryResult"]["action"]))
  if ($fulfillment["queryResult"]["action"] != '')
  {
    $GLOBALS["action"] = $fulfillment["queryResult"]["action"];
    debug("    action=".$GLOBALS["action"]);
  }

  //if (!isset($_SESSION["user"]))
  if (isset($fulfillment["originalDetectIntentRequest"]["source"]))
  {
    debug("    source=".$fulfillment["originalDetectIntentRequest"]["source"]);

    // facebook
    if ($fulfillment["originalDetectIntentRequest"]["source"] == "facebook")
    {
    if (isset($fulfillment["originalRequest"]["data"]["user"])) {
      $_SESSION["lastSeen"]= $fulfillment["originalRequest"]["data"]["user"]["lastSeen"];
      $_SESSION["locale"]= $fulfillment["originalRequest"]["data"]["user"]["locale"];
      $_SESSION["userId"] = $fulfillment["originalRequest"]["data"]["user"]["userId"];
      $_SESSION["caller"] = 'facebook';
      $_SESSION["called"] = 'facebook/voxibot';
      $_SESSION["user"] = database_get($_SESSION["userFacebook"], "facebook");
      debug("    user(google)=".json_encode($_SESSION["user"], JSON_PRETTY_PRINT));
    }
      debug("    user(facebook)=".json_encode($fulfillment["originalDetectIntentRequest"]["payload"], JSON_PRETTY_PRINT));
      $source= $fulfillment["originalDetectIntentRequest"]["payload"]["message"]["attachments"][0]["payload"]["url"];
      debug("    user(facebook) file=".$source);
    }

    // google
    if ($fulfillment["originalDetectIntentRequest"]["source"] == "google")
    if (isset($fulfillment["originalDetectIntentRequest"]["payload"]["user"])) {
      $_SESSION["lastSeen"]= $fulfillment["originalDetectIntentRequest"]["payload"]["user"]["lastSeen"];
      $_SESSION["locale"]= $fulfillment["originalDetectIntentRequest"]["payload"]["user"]["locale"];
      $_SESSION["userId"] = $fulfillment["originalDetectIntentRequest"]["payload"]["user"]["userId"];
      $_SESSION["caller"] = 'google';
      $_SESSION["called"] = 'google/voxibot';
      $_SESSION["user"] = database_get($_SESSION["userId"], "google");
      debug("    user(google)=".json_encode($_SESSION["user"], JSON_PRETTY_PRINT));
    }

    // google
    if ($fulfillment["originalDetectIntentRequest"]["source"] == "GOOGLE_TELEPHONY")
    if (isset($fulfillment["originalDetectIntentRequest"]["payload"]["telephony"])) {
      $_SESSION["caller"] = $fulfillment["originalDetectIntentRequest"]["payload"]["telephony"]["caller_id"];
      $_SESSION["called"] = 'google/voxibot';
      $_SESSION["user"] = database_get($_SESSION["caller"], "voxibot");
      debug("    user(google)=".json_encode($_SESSION["user"], JSON_PRETTY_PRINT));
    }

    // voxibot
    if ($fulfillment["originalDetectIntentRequest"]["source"] == "voxibot")
    if (isset($fulfillment["originalDetectIntentRequest"]["payload"]["user"])) {
      $_SESSION["param"]= $fulfillment["originalDetectIntentRequest"]["payload"]["user"]["param"];
      $_SESSION["called"]= $fulfillment["originalDetectIntentRequest"]["payload"]["user"]["called"];
      $_SESSION["caller"] = $fulfillment["originalDetectIntentRequest"]["payload"]["user"]["caller"];
      $_SESSION["user"] = database_get($_SESSION["caller"], "voxibot");
      debug("    user(voxibot)=".json_encode($_SESSION["user"], JSON_PRETTY_PRINT));
    }
  }

  // Store messages
  if (isset($fulfillment["queryResult"]["fulfillmentMessages"])) {
    //debug(print_r($fulfillment["queryResult"]["fulfillmentMessages"], true));

    $GLOBALS["messages"] = array();
    $index2 =0;

    for($index = 0;$index < count($fulfillment["queryResult"]["fulfillmentMessages"]); $index++)
    {
      //$GLOBALS["messages"][$index] = utf8_decode(template($fulfillment["queryResult"]["fulfillmentMessages"][$index]["text"]["text"][0]));
      if (isset($fulfillment["queryResult"]["fulfillmentMessages"][$index]["text"]["text"][0]))
      {
        $GLOBALS["messages"][$index2] = $fulfillment["queryResult"]["fulfillmentMessages"][$index]["text"]["text"][0];
        debug("    message[".$index."]=".$GLOBALS["messages"][$index2]);

        $index2++;
      }
      else
      {
        debug("    message[".$index."]=[skiped!]");
      }
    }
  }
}

// For Facebook
if (isset($source))
{
  $key = 'AIzaSyCbg9yNqJI3uE9L8eBm4T5OwTFpR4nxi9k';

  //$source = "https://myapps.gia.edu/ReportCheckPortal/downloadReport.do?reportNo=1152872617&weight=1.35";
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $source);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_SSLVERSION,4);
  $data = curl_exec ($ch);
  $error = curl_error($ch);

  debug("    curl error=".$error);

  curl_close ($ch);

  @unlink($destination);
  $destination = "/tmp/test.mp4";
  $file = fopen($destination, "w+");
  fputs($file, $data);
  fclose($file);

  @unlink($destination.".flac");
  system("ffmpeg -i ".$destination." -ar 24000 ".$destination.".flac");

  $data = file_get_contents($destination.".flac");
  $content = base64_encode($data);

  //$source = "https://myapps.gia.edu/ReportCheckPortal/downloadReport.do?reportNo=1152872617&weight=1.35";
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, "https://speech.googleapis.com/v1p1beta1/speech:recognize?key=".$key);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_SSLVERSION,4);
  $headers = array();
  $headers[] = 'Accept: application/json';
  $headers[] = 'Content-Type: application/json';
  curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
  $data = array(
    "audio" => array (
      "content" => $content,
      ),
    "config" => array (
      "encoding" => "FLAC",
      "sampleRateHertz" => "24000",
      "languageCode" => "fr",
      )
  );
  $data_string = json_encode($data);

  //debug($data_string);

  curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
  curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

  $result = curl_exec ($ch);
  $error = curl_error($ch);
  curl_close ($ch);

  debug("    curl result=".$result);
  debug("    curl error=".$error);

  $json = json_decode($result);

  debug(print_r($json, true));

  $GLOBALS["output"] = $json->results[0]->alternatives[0]->transcript;

  debug("    INPUT=".$GLOBALS["output"]);


  $query = $GLOBALS["output"];

  $postData = array('query' => $query, 'lang' => 'fr', 'sessionId' => $sessionid);
  $jsonData = json_encode($postData);

  $v = date('Ymd');
  $ch = curl_init('https://api.dialogflow.com/v1/query?v='.$v);
  curl_setopt($ch, CURLOPT_POST, 1);
  curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
  curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Authorization: Bearer b091f8442ab64407ad6a9bd6c4d45c2c'));
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  $result = curl_exec($ch);

  $answerData = json_decode($result);

  debug($answerData->result->fulfillment->speech);

  //$GLOBALS["speech"] = $answerData->result->fulfillment->speech;
  $GLOBALS['output'] = $answerData->result->fulfillment->speech;

  debug("    OUTPUT=".$GLOBALS["output"]);

}

//$GLOBALS['nossml'] = true;

// Voxibot Actions
if (isset($_SESSION['echo']))
{
  $GLOBALS["output"] = $GLOBALS["query"];
}
else
if (isset($_SESSION['message']))
{
  debug("    message=".$GLOBALS["query"]);

  if (isset($GLOBALS["parameters"]["phone-number"]))
  $_SESSION["message_destination"] = $GLOBALS["parameters"]["phone-number"];
  $_SESSION["message_text"] = $GLOBALS["query"];
  unset($_SESSION["message"]);
  unset($GLOBALS["action"]);
  //$GLOBALS['result']['context'] = "Message-followup";
  if ($GLOBALS["intent"]!="Message - Record")
  $GLOBALS['result']['event'] = "message";
}
else
if (isset($_SESSION['agent']))
{

}


if (isset($GLOBALS['action']))
{
  $action = strtolower($GLOBALS['action']);
  $GLOBALS['action'] = $action;

  $actions = explode(';', $action);

  foreach($actions as $action)
  {
    debug("    action=".$action);

    switch ($action) {
      case 'input.welcome':
          {
            if (isset($GLOBALS["parameters"]["phone"]))
            $_SESSION["phone"] = $GLOBALS["parameters"]["phone"];
            if (isset($GLOBALS['parameters']['voice']))
            $_SESSION["voice"] = $GLOBALS["parameters"]["voice"];
          }
          break;
      case 'session.set':
          {
            $_SESSION["parameters"] = $GLOBALS['parameters'];
          }
          break;
      case 'database.get':
          {
            $phone = get_parameter('phone');
            database_get($phone);
          }
          break;
      case 'database.set':
          {
            $phone = "0677379042";
            //$phone = get_parameter('phone');
            database_set($phone, $GLOBALS["parameters"]);
          }
          break;
      case 'voice':
          {
            if (isset($parameters["voice"]))
            $_SESSION["voice"] = $parameters["voice"];
            debug("    voice=".$_SESSION["voice"]);
          }
          break;
      case 'hangup':
          {
            $GLOBALS['hangup'] = true;
            debug("    hangup...");
          }
          break;
      case 'echo':
          {
            $_SESSION["echo"] = true;
            debug("    echo mode...");
          }
          break;
      case 'message':
          {
            $_SESSION["message"] = true;
            debug("    wait for a message...");
          }
          break;
      case 'data':
          {
            debug("parameter for action Data ".$GLOBALS["parameters"]["destination"]);
            $_SESSION["data"] = database_get($GLOBALS["parameters"]["destination"], "voxibot");
            debug("    data=".json_encode($_SESSION["data"]));
          }
          break;
      case 'callback':
          {
            if (isset($fulfillment["originalDetectIntentRequest"]["source"]))
            if ($fulfillment["originalDetectIntentRequest"]["source"] == "google")
            {
              include "actions/call/index.php";
            }
          }
          break;
      case 'transfer':
      case 'call':
          {
            debug("parameter for action transfer ".$GLOBALS["parameters"]["destination"]);

            if (!isset($GLOBALS["parameters"]["destination"]))
            {
              debug("    Ask phone number...");
              $GLOBALS['result']['event']='ask_phone';
            }
            else
            if (isset($fulfillment["originalDetectIntentRequest"]["source"]))
            if ($fulfillment["originalDetectIntentRequest"]["source"] == "google")
            {
              include "actions/call/index.php";
            }

          }
          break;
      case 'repeat':
          {
            //$GLOBALS["messages"] = array_merge($GLOBALS["messages"], $_SESSION["messages"]);

            //$GLOBALS["messages"] = $_SESSION["messages"];
            //$GLOBALS["messages"][0] .= $_SESSION["messages"][0];

            $GLOBALS["messages"] = $_SESSION["messages"];
          }
          break;
      default:
          {
            $action_elements = explode('.', $action);

            if (isset( $action_elements[1]))
            $GLOBALS["action_file"] = $action_elements[1];
            if (isset( $action_elements[2]))
            $GLOBALS['action_function'] = $action_elements[2];

            if ($action_elements[0] == "execute")
            {
              if (file_exists('actions/'.$GLOBALS["action_file"]."/index.php"))
              {
                include 'actions/'.$GLOBALS["action_file"]."/index.php";
                debug("Back to main file.");
              }
              else
              {
                debug("Script ".$GLOBALS["action_file"]." not found!");
                $GLOBALS['output'] = "Script ERROR";
              }
            }
          }
    }
  }
}


//$_SESSION["data"]["name"] = "Borja";

if (isset($_SESSION["voice"]))
$GLOBALS["voice"]  = $_SESSION["voice"];


//debug("Main : Context before processMessage GLOBAL:" . json_encode($GLOBALS));

if (isset($GLOBALS["output"]))
$GLOBALS["speech"] = template($GLOBALS["output"]);
else
if (isset($GLOBALS["speech"]))
$GLOBALS["speech"] = template($GLOBALS["speech"]);

if (isset($GLOBALS["speech"]))
debug("    speech(output)=".$GLOBALS["speech"]);

// Process messages
if (isset($GLOBALS["messages"])) {
  for($index = 0;$index < count($GLOBALS["messages"]); $index++)
  {
    debug("    Process=" . $GLOBALS["messages"][$index]);

    $GLOBALS["messages"][$index] = template($GLOBALS["messages"][$index]);

    debug("    Result=" . $GLOBALS["messages"][$index]);

    if (strstr($GLOBALS["messages"][$index], "?"))
    $GLOBALS["question"] = $GLOBALS["messages"][$index];
  }

  if (isset($GLOBALS["question"]))
  debug("    question=".$GLOBALS["question"]);
}

// Get Action
if ($apiversion==1)
$message_answer = processMessage($fulfillment);
else
$message_answer = processMessage2($fulfillment);


debug("---- PROCESS END");
debug("<<<< JSON : ".json_encode($message_answer, JSON_PRETTY_PRINT));

sendMessage($message_answer);

$_SESSION["messages"] = $GLOBALS["messages"];

debug("    session=" . json_encode($_SESSION, JSON_PRETTY_PRINT));

debug("---- REQUEST END");

?>
