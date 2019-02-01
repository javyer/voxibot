<?php

function is_json()
{
  return strncmp($_SERVER['HTTP_USER_AGENT'], 'Apache-HttpClient', 17);
}

function get_parameter($name, $default='?')
{
  if (isset($_REQUEST[$name]))
  return $_REQUEST[$name];

  if (isset($GLOBALS['parameters'][$name]))
  return $GLOBALS['parameters'][$name];

  if (isset($_SESSION['parameters'][$name]))
  return $_SESSION['parameters'][$name];

  return $default;
}


function debug($Message) {
        $stderr = fopen('php://stderr', 'w');
        fwrite($stderr,$Message."\n");
        fclose($stderr);
}



function template($string) {
  $string = preg_replace_callback('/\%php:(.+);\%/'
    ,function($matches) { @eval('$ret='.$matches[1].';'); return $ret; } ,$string);

  // Ternary operator
  //$x = $valid ? 'yes' : 'no';
  // regexp ($1?$2:$3)

  $string = preg_replace_callback('/\%data.(\\S+)\%/'
    ,create_function('$matches', 'return @$_SESSION["data"][$matches[1]];') ,$string);

  $string = preg_replace_callback('/\%user.(\\S+)\%/'
    ,create_function('$matches', 'return @$_SESSION["user"][$matches[1]];') ,$string);

  $string = preg_replace_callback('/\%parameter.(\\S+)\%/'
    ,create_function('$matches', 'return @$GLOBALS["parameters"][$matches[1]];') ,$string);

  $string = preg_replace_callback('/\%result.(\\S+)\%/'
    ,create_function('$matches', 'return @$GLOBALS["result"][$matches[1]];') ,$string);

  $string = preg_replace_callback('/\%session.(\\S+)\%/'
    ,create_function('$matches', 'return @$_SESSION[$matches[1]];') ,$string);

  $string = preg_replace_callback('/\%(\\S+)\%/'
    ,create_function('$matches', 'return @$GLOBALS[$matches[1]];') ,$string);

  return $string;
}


function database_get($id = "", $key = "voxibot") {
  global $config;

  $link = mysql_connect($config['db']['hostname'], $config['db']['user'] , $config['db']['password']) or die('Impossible de se connecter : ' . mysql_error());
  mysql_select_db($config['db']['name']) or die('Impossible de sélectionner la base de données');
  mysql_set_charset('utf8');

  debug("GET database ". json_encode($_SESSION));

  if (($id != "") && ($key == "voxibot"))
  $query = 'SELECT * FROM users WHERE userPhone = \''.$id.'\'';
  else
  if (($id != "") && ($key == "google"))
  $query = 'SELECT * FROM users WHERE userId = \''.$id.'\'';
  else
  if (isset($_SESSION['caller']))
  $query = 'SELECT * FROM users WHERE userPhone = \''.$_SESSION['userPhone'].'\'';
  else
  if (isset($_SESSION['userId']))
  $query = 'SELECT * FROM users WHERE userId = \''.$_SESSION['userId'].'\'';
  else
  return;

  debug("GET database query ". $query);


  $result = mysql_query($query) or die('Échec de la requête : ' . mysql_error());

  if ($result)
  {
    $data = mysql_fetch_array($result, MYSQL_ASSOC);
    //if ($data)
    //$data = array_map('utf8_encode', $data);
  }

  mysql_free_result($result);
  mysql_close($link);

  return $data;
}

function database_set($phone, $parameters) {
  global $config;

  $link = mysql_connect($config['db']['hostname'], $config['db']['user'] , $config['db']['password']) or die('Impossible de se connecter : ' . mysql_error());
  mysql_select_db($config['db']['name']) or die('Impossible de sélectionner la base de données');
  mysql_set_charset('utf8');

  $more = '';
  $query = "UPDATE users SET ";
  foreach($parameters as $k => $v) {
    $query .= $more." `".$k."`='".$v."'";
    $more = ',';
  }
  $query .= " WHERE userPhone='".$phone."'";

  debug("database set : " . $query);


  $result = @mysql_query($query) or die('Échec de la requête : ' . mysql_error());

  mysql_close($link);

  return;
}

function processMessage($update) {

  $message = array(
    "speech" => $GLOBALS['speech'],
    "displayText" => $GLOBALS['speech'],
    "data" => array(),
  );

  if (isset($GLOBALS["voice"]))
  $message["fulfillment"]["messages"] = array(
            array(
                "type" => 0,
                "speech" => "what else?",
            ),
            array(
                "type" => 1,
                "speech" => "what else 2?",
            ),
      );

  if (isset($GLOBALS["voice"]))
  $message["data"]["google"] = array(
        "expect_user_response" => !isset($GLOBALS['hangup']),
        "is_ssml" => true, //!isset($GLOBALS['nossml']),
        "permissions_request" => array(
          "opt_context" => "opt_context",
        ),
        "richResponse" => array(
          "items" => array(
            array(
              "simpleResponse" => array(
                "textToSpeech" => $GLOBALS['speech'],
                "displayText" => $GLOBALS['speech'],
                "ssml" => "<speak><audio src=\"https://d1y0sibbj09q2p.cloudfront.net/tts.php?voice=".$GLOBALS["voice"]."&amp;text=".$GLOBALS['speech']."\"/></speak>",
                ),
            ),

            array(
              "simpleResponse" => array(
                "textToSpeech" => "what else?",
                "displayText" => "what else?",
                "ssml" => "<speak><audio src=\"https://d1y0sibbj09q2p.cloudfront.net/tts.php?voice=".$GLOBALS["voice"]."&amp;text="."what else?"."\"/></speak>",
                ),
            ),

          ),
        ),
      );

  if (isset($update["result"]["source"]))
  $message["source"] = $update["result"]["source"];

  if (isset($GLOBALS['result']['event']))
  {
    debug("Format MESSAGE Add event ".$GLOBALS['result']['event']);

    $message["followupEvent"] = array(
     "name" => $GLOBALS['result']['event'],
     "data" => array(
       "param1" => "1",
       "param2" => "2",
       "message" => $_SESSION['message_text'],
       ),
    );
  }

  if (isset($GLOBALS['result']['context']))
  {
    debug("Format MESSAGE Add context ".$GLOBALS['result']['context']);

    $message["contextOut"] = array(
     "name" => $GLOBALS['result']['context'],
     "data" => array(
       "param1" => "1",
       "param2" => "2",
       "message" => $_SESSION['message_text'],
       ),
      "lifespan" => 5,
    );
  }

  return $message;
}


function processMessage2($update) {

  debug("Process Message V2");
  if (isset($GLOBALS["voice"]))
  debug(" voice=".$GLOBALS["voice"]);

  $message = array(
    "source" => "Voxibot",
    "fulfillmentText" => "",
    "fulfillmentMessages" => array(),
    "payload" => array(),
  );

  //if (isset($GLOBALS["voice"]))
  $message["payload"]["google"] = array(
        "expect_user_response" => !isset($GLOBALS['hangup']),
        "is_ssml" => true, //!isset($GLOBALS['nossml']),
        "permissions_request" => array(
          "opt_context" => "opt_context",
        ),
        "richResponse" => array(
          "items" => array()
        ),
      );

  $questions = array(
    "Autre chose ?",
    "Avez-vous une autre question ?",
    "Encore une question ?",
  );

  $question = $questions[rand(0,count($questions)-1)];
  //$question = "Autre chose ?";


  /*
  "Besoin d'autre chose ?"
  "Une autre question ?"
  "Avez-vous une autre demande ?"
  "Désirez vous autre chose ?"
  "En avant pour une autre demande?"
  "Si vous avez une autre question ?"
  "Je peux répondre à une autre question ?"
  "Encore besoin de moi ?"
  "Je suis pret pour une autre question?"


  "C'est à vous?"
  "Je vous ecoute?"
  "Allez-y"

  "Je rèpète:"
  "J'insiste:"
  "je réitère:"
  "Encore une fois:"
  "je disais:"

  */

  //$GLOBALS['output'] = "momo";

  //if (isset($GLOBALS["voice"]))
  //if (isset($GLOBALS["output"]))
  if (false)
  {
    if (isset($GLOBALS["voice"]) && ($GLOBALS["voice"]!=''))
    {
      $ssml = "<speak><audio src=\"https://d1y0sibbj09q2p.cloudfront.net/tts.php?voice=".$GLOBALS["voice"]."&amp;text=".$GLOBALS["output"]."\"/></speak>";
      array_push($message["payload"]["google"]["richResponse"]["items"], array("simpleResponse" => array(
        "textToSpeech" => $GLOBALS["output"],
        "displayText" => $GLOBALS["output"],
        "ssml" => $ssml )));
    }

    array_push($message["fulfillmentMessages"], array(
      "text" => array("text" => array($GLOBALS["output"]))));
  }
  else
  {
    if (isset($GLOBALS["messages"])) {
      for($index = 0;$index < count($GLOBALS["messages"]); $index++)
      {
        if (isset($GLOBALS["voice"]) && ($GLOBALS["voice"]!=''))
        {
          $ssml = "<speak><audio src=\"https://d1y0sibbj09q2p.cloudfront.net/tts.php?voice=".$GLOBALS["voice"]."&amp;text=".$GLOBALS["messages"][$index]."\"/></speak>";
          array_push($message["payload"]["google"]["richResponse"]["items"], array("simpleResponse" => array(
            "textToSpeech" => $GLOBALS["messages"][$index],
            "displayText" => $GLOBALS["messages"][$index],
            "ssml" => $ssml )));
        }
        else
        {
          array_push($message["payload"]["google"]["richResponse"]["items"], array("simpleResponse" => array(
            "textToSpeech" => $GLOBALS["messages"][$index],
            "displayText" => $GLOBALS["messages"][$index] )));
        }

        if (strstr($GLOBALS["messages"][$index], '?'))
        {
          $question = "";
          debug("Found a question mark.");
        }

        if ($GLOBALS["messages"][$index])
        array_push($message["fulfillmentMessages"], array(
          "text" => array("text" => array($GLOBALS["messages"][$index]))));

        if ($index)
        $message['fulfillmentText'] .= ' '.$GLOBALS["messages"][$index];
        else
        $message['fulfillmentText'] = $GLOBALS["messages"][$index];
      }
    }
  }

/*
  else
  {
    if (isset($GLOBALS["messages"])) {
      for($index = 0;$index < count($GLOBALS["messages"]); $index++)
      {
        if (isset($GLOBALS["voice"]) && ($GLOBALS["voice"]!=''))
        {
          $ssml = "<speak><audio src=\"https://d1y0sibbj09q2p.cloudfront.net/tts.php?voice=".$GLOBALS["voice"]."&amp;text=".$GLOBALS["messages"][$index]."\"/></speak>";
          array_push($message["payload"]["google"]["richResponse"]["items"], array("simpleResponse" => array(
            "textToSpeech" => $GLOBALS["messages"][$index],
            "displayText" => $GLOBALS["messages"][$index],
            "ssml" => $ssml )));
        }
        else
        {
          array_push($message["payload"]["google"]["richResponse"]["items"], array("simpleResponse" => array(
            "textToSpeech" => $GLOBALS["messages"][$index],
            "displayText" => $GLOBALS["messages"][$index] )));
        }

        if (strstr($GLOBALS["messages"][$index], '?'))
        {
          $question = "";
          debug("Found a question mark.");
        }

        if ($GLOBALS["messages"][$index])
        array_push($message["fulfillmentMessages"], array(
          //"text" => array("text" => array($GLOBALS["output"].' : '.$GLOBALS["messages"][$index]))));
          "text" => array("text" => array($GLOBALS["output"]))));

        if ($index)
        $message['fulfillmentText'] .= $GLOBALS["output"]." ".$GLOBALS["messages"][$index];
        else
        $message['fulfillmentText'] = $GLOBALS["output"].$GLOBALS["messages"][$index];
      }
    }
  }
*/

  if (!isset($GLOBALS['hangup']))
  if ($question)
  {
    if (isset($GLOBALS["voice"]) && ($GLOBALS["voice"]!=''))
    {
      debug("Add the final question.");

      $count = count($message["payload"]["google"]["richResponse"]["items"]);

      debug("Count = ".$count);

      if ($count > 1)
      {
        $ssml = "<speak><audio src=\"https://d1y0sibbj09q2p.cloudfront.net/tts.php?voice=".$GLOBALS["voice"]."&amp;text=".$message["payload"]["google"]["richResponse"]["items"][1]["simpleResponse"]["textToSpeech"]." ".$question."\"/></speak>";
        $message["payload"]["google"]["richResponse"]["items"][1]["simpleResponse"]["textToSpeech"].=" ".$question;
        $message["payload"]["google"]["richResponse"]["items"][1]["simpleResponse"]["displayText"].=" ".$question;
        $message["payload"]["google"]["richResponse"]["items"][1]["simpleResponse"]["ssml"]=$ssml;
      }
      else
      {
        $ssml = "<speak><audio src=\"https://d1y0sibbj09q2p.cloudfront.net/tts.php?voice=".$GLOBALS["voice"]."&amp;text=".$question."\"/></speak>";
        array_push($message["payload"]["google"]["richResponse"]["items"], array("simpleResponse" => array(
          "textToSpeech" => $question,
          "displayText" => $question,
          "ssml" => $ssml )));
      }
    }
    else
    {
      array_push($message["payload"]["google"]["richResponse"]["items"], array("simpleResponse" => array(
        "textToSpeech" => $question,
        "displayText" => $question )));
    }

    $message['fulfillmentText'] .= " ".$question;
  }

  if (isset($update["result"]["source"]))
  $message["source"] = $update["result"]["source"];

  if (isset($GLOBALS['result']['event']))
  {
    debug("Format MESSAGE Add event ".$GLOBALS['result']['event']);

    $message["followup_event_input"] = array(
     "name" => $GLOBALS['result']['event'],
     "parameters" => array(
       "param1" => "1",
       "param2" => "2",
       //"message" => $_SESSION['message_text'],
       ),
    );
  }

  if (isset($GLOBALS['result']['context']))
  {
    debug("Format MESSAGE Add context ".$GLOBALS['result']['context']);

    $message["contextOut"] = array(
     "name" => $GLOBALS['result']['context'],
     "data" => array(
       "param1" => "1",
       "param2" => "2",
       "message" => $_SESSION['message_text'],
       ),
      "lifespan" => 5,
    );
  }

  return $message;
}


function sendMessage($parameters) {
  //header('Content-Type: application/json');
  header('Content-Type: application/json; Charset=UTF-8');

  //$value = mb_check_encoding($value, 'UTF-8') ? $value : utf8_encode($value);

  echo json_encode($parameters);
}

function dialogflow($query)
{
  $postData = array('query' => $query, 'lang' => 'fr', 'sessionId' => $GLOBALS['sessionId']);
  $jsonData = json_encode($postData);

  $v = date('Ymd');
  $ch = curl_init('https://api.dialogflow.com/v1/query?v='.$v);
  curl_setopt($ch, CURLOPT_POST, 1);
  curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
  curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Authorization: Bearer b091f8442ab64407ad6a9bd6c4d45c2c'));
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  $result = curl_exec($ch);

  $answerData = json_decode($result);

  return $answerData->result->fulfillment->speech;
}

?>
