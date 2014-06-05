<?php


if($_POST['action']['upload'])
{
	$upload_url = $_POST['upload_url'];

/*
	$upload = 'image.gif';
	$postdata = array( 'photo' => "@".$upload );
	
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $upload_url );
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 0);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
	$response = curl_exec($ch); 
	curl_close($ch);

	echo $response;*/
	echo $upload_url;
}

$data = array(
  'key1' => 'My key',
  'key5' => 'I love Drupal!'
);
 
$postdata = http_build_query($data);
  $options = array('http' =>
    array(
      'method' => 'POST',
      'header' => 'Content-type: application/x-www-form-urlencoded',
      'content' => $postdata
    )
  );
  $context = stream_context_create($options);
  $result = file_get_contents('<a href="http://example.com/post_data_url">http://example.com/post_data_url</a>', false, $context)