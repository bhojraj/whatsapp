<?php

/*
     * File name: upload_image.php
     * Desc: Upload an image to the server.
     * Input: image content and image name
     * Output: upload success or failure.
*/

if (isset($_POST['content']) && isset($_POST['name']))
{	
    $base=$_POST['content'];
	$name=$_POST['name'];
	
	$response  = array();
	$response[$TAG_DETAILS] = array();
	
    $binary=base64_decode($base);
	
	$filename = "images/".$name;
	
    header('Content-Type: bitmap; charset=utf-8');
    $file = fopen(''.$filename.'', 'wb');
    $result = fwrite($file, $binary);
    fclose($file);
	
	if($result==false) {
		$response["success"] = false;
		$response["message"] = "Upload failed";
	}
	else {
		$response["success"] = true;
		$response["message"] = "Uploaded successfully";
	}
	
    echo json_encode($response);
}
?>