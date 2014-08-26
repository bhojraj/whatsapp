<?php

require_once 'db_connect.php';
require_once 'GCM.php';
require_once 'constant.php';

if (isset($_POST['message_id']))
{		
	$current_date = time();
	$dateNow = date('Y-m-d H:i:s', $current_date);
	
	$mid = (int) $_POST['message_id'];
		
	$db = new DB_CONNECT();
	
	// array for JSON response
	$response  = array();
	$response[$TAG_DETAILS] = array();
	$detail = array();
	
	$who = $MESSAGETYPE_DELIVERY;
	//$success = true;		
	
	$STATUS_DELIVERED = 2;
	
	$fid;
	
	//if($success)
	{
		$success = false;
		$query = "UPDATE i_friend SET status=$STATUS_DELIVERED, delivery_time='$dateNow' WHERE id=$mid ";
		$result = mysql_query($query);
		if($result) 
		{			
			if(mysql_affected_rows()>0)
			{	
				$query = "SELECT * FROM i_friend WHERE id=$mid ";
				$result = mysql_query($query);
				if($result) {
					if (mysql_num_rows($result) > 0) {
						$row = mysql_fetch_array($result);
						$detail["id"] = $row["id"];			
						$detail["time"] = $row["time"];
						$detail["type"] = $row["type"];
						$detail["user_id"] = $row["user_id"];
						$detail["friend_id"] = $row["friend_id"];
						$detail["who"] = $who;			
						$detail["message"] = "Message Delivered";
						$detail["status"] = $row["status"];
						$detail["delivery_time"] = $row["delivery_time"];
						$fid = $row["user_id"]; 			// fid should be user_id becoz it is delivery report back to user.
						
						$success = true;
						array_push($response[$TAG_DETAILS], $detail);
						$response[$TAG_SUCCESS] = true;
						$response[$TAG_MESSAGE] = "Successfully update delivery status.";
						$message = json_encode($response);
					}	
				}
			}
		}
		else
		{
			$response[$TAG_SUCCESS] = false;
			$response[$TAG_MESSAGE] = "Message Sending failed.";
			return;
		}
	}
	
	if($success)
	{
		$query = "SELECT regid FROM i_user WHERE id = '$fid' AND online = 1 "; //LIMIT 1";
		$result = mysql_query($query);
		if($result) {
			
			if (mysql_num_rows($result) > 0) {
				$registration_ids = array();
				$row = mysql_fetch_array($result);
				$registration_ids[0] = $row["regid"];
				$gcm = new GCM();
				$message = array("message" => $message);
				$result = $gcm->send_notification($registration_ids, $message);
				$response2[$TAG_SUCCESS] = true;
				$response2[$TAG_MESSAGE] = "Successfully stored message for sending.";
				//echo json_encode($response2);
			}
			else
			{
				// No such friend
				$success = false;
			}
		}
	}
	else {
		$response2[$TAG_SUCCESS] = false;
		$response2[$TAG_MESSAGE] = "Message Sending failed.";
		//echo json_encode($response2);
	}
}

?>


<html>
<body>
  <form action="<?php $_PHP_SELF ?>" method="POST">
  
  message_id: <input type="text" name="message_id" />
  <input type="submit" />
  </form>
</body>
</html>
