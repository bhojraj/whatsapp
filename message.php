<?php

if (isset($_POST['user_id']) && isset($_POST['friend_id']) && isset($_POST['message']) && isset($_POST['type']))
{	
    if(strlen($_POST['user_id']) > 0 && strlen($_POST['friend_id']) > 0 && strlen($_POST['message']) > 0 && strlen($_POST['type']) > 0){
	require_once 'constant.php';
	
	$current_date = time();
	$dateNow = date('Y-m-d H:i:s', $current_date);
	
        $uid = (int) $_POST['user_id'];
	$fid = (int) $_POST['friend_id'];
	$msg = $_POST['message'];
	$type = (int)$_POST['type'];
		
	require_once 'db_connect.php';
	$db = new DB_CONNECT();
	
	require_once 'GCM.php';
	
	// array for JSON response
	$response  = array();
	$response[$TAG_DETAILS] = array();
	
		
	$response2 = array();
	$response2[$TAG_DETAILS] = array();
	$detail2 = array();
	
	$who = 1;  // MESSAGE FROM A SINGLE PERSON
	//$success = true;		
	
	$TYPE_TEXT = 1;
	$TYPE_IMAGE = 2;
	$TYPE_FILE = 3;
	$TYPE_LOCATION = 4;
	$TYPE_STICKER = 5;
	
	$delivery_message = "";
	$delivery_time = "0000-00-00 00:00:00";
	
	$success = false;
	
	$query = "INSERT INTO i_friend(user_id, friend_id, type, message, who, time, status, delivery_time) VALUES($uid, $fid, $type, '$msg', $who, '$dateNow', '1', '$delivery_time') ";
	$result = mysql_query($query);
	$insert_id = 0;
	if($result) {
		$insert_id = mysql_insert_id();
		
		// Preparing response to send to friend
			$detail = array();
			$detail["id"] = $insert_id;
			$detail["time"] = $dateNow;
			$detail["type"] = $type;
			$detail["user_id"] = "".$uid;
			$detail["friend_id"] = "".$fid;
			$detail["who"] = $who;
			if($type==2)
			{
				$detail["message"] = $msg;
			}
			else
			if($type==$TYPE_LOCATION)
			{
				$detail["message"] = "Location";
			}
			
			array_push($response[$TAG_DETAILS], $detail);
			$response[$TAG_SUCCESS] = true;
			$response[$TAG_MESSAGE] = "Successfully stored message for sending.";
			$message = json_encode($response[$TAG_MESSAGE]);
			// Message is ready to be sent
			// Now make 
			$success = true;
			
			// Now Prepare message to sent back to the user
			$delivery_message = "Stored message for sending.";
			$detail2["status"] = 1; // Message is stored
			
			$response2[$TAG_MESSAGE] = "Stored message for sending.";
			$response2[$TAG_SUCCESS] = true;
			
			echo json_encode($response2[$TAG_MESSAGE]);
			
	}
	else
	{
		// Failed to store message
			$delivery_message = "Could not store message.";
			$detail2["status"] = 0; // Message is not stored
			
			$response2[$TAG_MESSAGE] = "Could not store message.";
			$response2[$TAG_SUCCESS] = true;
			
			echo json_encode($response2);
	}
	
	if($success)
	{
		$query = "SELECT regid FROM i_user WHERE id = $fid AND online = 1 "; //LIMIT 1";
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
				
				array_push($response2[$TAG_DETAILS], $detail2);
			}
			else
			{
				// No such friend
				$success = false;
			}
		}
	}
	else {
		
		//$delivery_message .= " but Message Sending failed."		
		
	}
	
    }else{
        $success = false;
        echo json_encode("Fields Can't be left blank.");
    }
}

?>


<html>
<body>
  <form action="<?php $_PHP_SELF ?>" method="POST">
  
  user_id: <input type="text" name="user_id" />
  friend_id: <input type="text" name="friend_id" />
  message: <input type="text" name="message" />
  type: <input type="text" name="type" />
  <input type="submit" />
  </form>
</body>
</html>
