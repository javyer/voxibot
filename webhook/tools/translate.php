<?php

echo "Bla... Bla";
exit();

$handle = curl_init();

if (FALSE === $handle)
   throw new Exception('failed to initialize');

curl_setopt($handle, CURLOPT_URL,'https://www.googleapis.com/language/translate/v2');
curl_setopt($handle, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($handle, CURLOPT_POSTFIELDS, array('key'=> 'AIzaSyCbg9yNqJI3uE9L8eBm4T5OwTFpR4nxi9k', 'q' => $_REQUEST['text'], 'source' => $_REQUEST['from'], 'target' => $_REQUEST['to']));
curl_setopt($handle,CURLOPT_HTTPHEADER,array('X-HTTP-Method-Override: GET'));
$response = curl_exec($handle);

echo $response;

?>