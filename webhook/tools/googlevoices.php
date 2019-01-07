<?php

$handle = curl_init();

if (FALSE === $handle)
   throw new Exception('failed to initialize');

curl_setopt($handle, CURLOPT_URL,'https://texttospeech.googleapis.com/v1/voices?key=AIzaSyCbg9yNqJI3uE9L8eBm4T5OwTFpR4nxi9k');
curl_setopt($handle, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, false);
//curl_setopt($handle, CURLOPT_POSTFIELDS, array('key'=> 'AIzaSyCbg9yNqJI3uE9L8eBm4T5OwTFpR4nxi9k'));
curl_setopt($handle,CURLOPT_HTTPHEADER,array('X-HTTP-Method-Override: GET'));
$response = curl_exec($handle);

//echo $response;

$json = json_decode($response, true);

//print_r($json);

echo "<h1>Google TTS voices</h1>";

foreach ($json['voices'] as &$item) {
    echo $item['name'].' ('.$item['ssmlGender'].')<br>';
}

?>