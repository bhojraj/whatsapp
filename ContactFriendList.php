<?php

/*
     * File name: driverUpdateLocation.php
     * Desc: Updates the location of a particulat driver based on his 'id'.
     * Input: driver id, latitude, longitude
     * Output: entity details for success or error array
*/

require_once 'db_connect.php';
include("constant.php");

if(isset($_POST["data"]) && isset($_POST["user_id"]))
{	
    if(strlen($_POST["data"]) > 0 && strlen($_POST["user_id"]) > 0){
	$db = new DB_CONNECT();
	
	$response = array();
	$response[$TAG_DETAILS] = array();
		
	$user_id = $_POST["user_id"];
	
	$base=$_POST['data'];
	$base=stripcslashes($base);
	$data = json_decode($base, TRUE);
	
	$response["details"] = array();
	foreach($data as $mydata)
	{ 
		$product = array();				

		$user_phone = $mydata['phone'];
		
		$query 	= "SELECT * FROM `i_user` WHERE `phone` = '$user_phone' AND NOT `id`='$user_id'";
		$result = mysql_query($query) or die(mysql_error);
		
		if(mysql_num_rows($result) > 0)
		{
			//check if in friend list
			$row = mysql_fetch_array($result);
			$id_user=$row['id'];	
			
			$query1 = "SELECT * FROM `i_user_friend` WHERE ((`user_id` = '$user_id' AND `friend_id`='$id_user') OR (`user_id` = '$id_user' AND `friend_id`='$user_id')) AND (NOT type = 0)";
			$result1 = mysql_query($query1) or die(mysql_error);
			$product["query"] = $query1;
			if(mysql_num_rows($result1) > 0)
			{
				
			}
			else
			{
				$product["id"] = $row["id"];
				$product["name"] = $row["fname"];
				$product["lname"] = $row["lname"];
				$product["image"] =  $row["image"];
				$product["status"] =  $row["status"];
				
				$product["type"] = "0";
				$product["admin_id"] = 0;
					
				array_push($response["details"], $product);		
			}			
		}		
	}
	
	$response[$TAG_SUCCESS] = true;
	$response[$TAG_MESSAGE] = "Got Friends";
				
	echo json_encode($response[$TAG_MESSAGE]);
	
    }
}
else
{
	$response[$TAG_SUCCESS] = false;
	$response[$TAG_MESSAGE] = "Data Not Set";
	echo json_encode($response[$TAG_MESSAGE]);		
}	

?>

<html>
<body>
  <form action="<?php $_PHP_SELF ?>" method="POST">
  id: <input type="text" name="id" />
  latitude: <input type="text" name="latitude" />
  longitude: <input type="text" name="longitude" />
  <input type="submit" />
  </form>
</body>
</html>