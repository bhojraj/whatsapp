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
	$db = new DB_CONNECT();
	
	$response = array();
	$response[$TAG_DETAILS] = array();
		
	$user_id = $_POST["user_id"];
	
	date_default_timezone_set('Asia/Kolkata');
	$date = date("Y-m-d H:i:s"); 
	
	$base=$_POST['data'];
	$base=stripcslashes($base);
	$data = json_decode($base, TRUE);
	
	$response["friend"] = array();
	$temp_id = 1;
	foreach($data as $mydata)
	{ 
		$product = array();				
		$user_phone		= $mydata['phone'];
		$user_name  	= $mydata['name'];
		$query 	= "SELECT * FROM `i_user` WHERE `phone` = '$user_phone'"; 	//NOT REGISTERED
		$result = mysql_query($query) or die(mysql_error);
		
		if(mysql_num_rows($result) > 0)
		{			
						
		}	
		else		// Check if already sent
		{
			$product["user_id"] = $temp_id;
			$product["fname"] = $user_name;
			$product["lname"] = "";
			$product["pic"] =  "user.png";
			$product["phone"] =  $user_phone;
			array_push($response["friend"], $product);		
			
			$temp_id = $temp_id + 1;
			
		}
			
	}
	//SEND PROMO ID OF VALID PROMO CODE AND STATUS 
	
	$query 	= "SELECT * FROM `i_promo_user` WHERE `user_id` = '$user_id'"; 	//NOT REGISTERED
	$result = mysql_query($query) or die(mysql_error);
	if(mysql_num_rows($result) > 0)
	{			
		$promo_available = false;
		
		while ($row = mysql_fetch_array($result)) 
		{
			$promo_id = $row['promo_id'];
			//CHECK IF PROMO CODE IS VALID
			$query1 	= "SELECT * FROM `i_promo_code` WHERE `id` = '$promo_id' AND '$date'<= validity"; 	
			$result1 = mysql_query($query1) or die(mysql_error);
			if(mysql_num_rows($result1) > 0)
			{
				$row1 = mysql_fetch_array($result1);				
				$promocode = $row1['code'];
				$limit = (int)$row1['limit'];
				
				$query1 	= "SELECT count(*) as used FROM `i_user` WHERE `promo_used` = '$promo_id'"; 	
				$result1 = mysql_query($query1) or die(mysql_error);
				
				$row1 = mysql_fetch_array($result1);			
				$used = (int)$row1['used'];
				
				$available = $limit - $used;
				
				if($available>0)
				{
					$ii_user;
					$query1 	= "SELECT * FROM `i_user` WHERE `id` = '$user_id'"; 	
					$result1 = mysql_query($query1) or die(mysql_error);				
					$row1 = mysql_fetch_array($result1);		
					
					$response["sender_name"] = $row1['fname'];
					$response["promo_id"] = $promo_id;
					$response["promocode"] = $promocode;
					$response["left"] = $available;
					$promo_available = true;
					break;
				}
			}

		}
	}	
	else
	{
		$promo_available = false;
	}	
	$response["promo_available"] = $promo_available;
	$response[$TAG_SUCCESS] = true;
	$response[$TAG_MESSAGE] = "Got Friends";
				
	echo json_encode($response);
	
}
else
{
	$response[$TAG_SUCCESS] = false;
	$response[$TAG_MESSAGE] = "Data Not Set";
	echo json_encode($response);		
}	

?>

<html>
<body>
  <form action="<?php $_PHP_SELF ?>" method="POST">
  data: <input type="text" name="data" />
  userid: <input type="text" name="userid" />
  <input type="submit" />
  </form>
</body>
</html>