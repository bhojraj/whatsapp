<?php

if (isset($_POST['image']) && isset($_POST['name']))
{	
    $base=$_POST['image'];
	$name=$_POST['name'];
	
	$response  = array();
	$response[$TAG_DETAILS] = array();
	
    $binary=base64_decode($base);
	
	$filename = "images/".$name;
	
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