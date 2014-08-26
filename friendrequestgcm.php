<?php

if(isset($_POST["id"]) && isset($_POST["type"]) && isset($_POST["friend_id"]) && isset($_POST["user_id"]))
{	
	include("constant.php");
	
	$id 	= (int)$_POST["id"];  // This is user id (Latest Change)
	$type 	= (int)$_POST["type"];
	$fid 	= (int)$_POST["friend_id"];
	$uid 	= (int)$_POST["user_id"];
	
	require_once 'db_connect.php';
	$db = new DB_CONNECT();
	
	$response = array();	
	$response[$TAG_DETAILS] = array();
	$detail = array();
	
	$detail["id"] = $id;
	$detail["who"] = 3;
	$detail["type"] = $type;
	$detail["user_id"] = $uid;
	$detail["friend_id"] = $fid;
	
	$success = false;
	
	$query = "SELECT fname, lname FROM i_user WHERE id = $uid "; //LIMIT 1";
	$result = mysql_query($query);
	if($result) {
			
		if (mysql_num_rows($result) > 0) {
			
			$row = mysql_fetch_array($result);
			
			$detail["name"] = $row["fname"]. " ". $row["lname"];
			array_push($response[$TAG_DETAILS], $detail);
			$response[$TAG_SUCCESS] = true;
			$response[$TAG_MESSAGE] = "Success.";
			$message =  json_encode($response);
			
			$success = true;
		}
	}
	
	if($success)
	{
		$query = "SELECT regid FROM i_user WHERE id = $fid AND online = 1 "; //LIMIT 1";
		$result = mysql_query($query);
		if($result) {
			
			if (mysql_num_rows($result) > 0) {
			
				$row = mysql_fetch_array($result);
				
				$registration_ids = array();
				$registration_ids[0] = $row["regid"];
				require_once 'GCM.php';	
				$gcm = new GCM();
				$message = array("message" => $message);
				$result = $gcm->send_notification($registration_ids, $message);
			}
			else
			{
				// No such friend
				$success = false;
			}
		}
	}
	else {
		$response[$TAG_SUCCESS] = false;
		$response[$TAG_MESSAGE] = "Message Sending failed.";
		//echo json_encode($response2);
	}
	
	
}

?>

<html>
<body>
  <form action="<?php $_PHP_SELF ?>" method="POST">
  id: <input type="text" name="id" />
  user_id: <input type="text" name="user_id" />
  friend_id: <input type="text" name="friend_id" />
  type: <input type="text" name="type" />
  <input type="submit" />
  </form>
</body>
</html>