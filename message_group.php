<?php

if (isset($_POST['user_id']) && isset($_POST['friend_id']) && isset($_POST['message']) && isset($_POST['type']))
{	
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
	$response2 = array();
	$response2[$TAG_DETAILS] = array();
	$response[$TAG_DETAILS] = array();
	$detail = array();
	
	//$success = true;
	
	$who = 2; // MESSAGE FROM A GROUP
	
	//if($success)
	{
		$success = false;
		$query = "INSERT INTO i_friend(user_id, friend_id, type, message, who, time) VALUES($uid, $fid, $type, '$msg', $who, '$dateNow') ";
		$result = mysql_query($query);
		$insert_id = 0;
		if($result) {
			$insert_id = mysql_insert_id();
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
			$success = true;
			array_push($response[$TAG_DETAILS], $detail);
			$response[$TAG_SUCCESS] = true;
			$response[$TAG_MESSAGE] = "Successfully stored message for sending.";
			$message = json_encode($response);
		}
	}
	
	if($success)
	{
		//$query = "SELECT regid FROM i_user WHERE id = $fid AND online = 1 "; //LIMIT 1";
		$query = " SELECT u.regid, gm.user_id FROM i_user u , i_group_member gm where gm.user_id=u.id and gm.group_id= $fid AND u.online = 1 ";
		$result = mysql_query($query);
		if($result) {
			
			if (mysql_num_rows($result) > 0) {
				
				$i = 0;
				$registration_ids = array();
				while ($row = mysql_fetch_array($result)) {		
					$myid = (int)$row["user_id"];
					if($myid==$uid) {}
					else
					{
						$registration_ids[$i] = $row["regid"];
						$success = true;
						$i++;
					}
				}
				if($success) 
				{
					$gcm = new GCM();
					$message = array("message" => $message);
					$result = $gcm->send_notification($registration_ids, $message);
					$response2[$TAG_SUCCESS] = true;
					$response2[$TAG_MESSAGE] = "Successfully stored message for sending.";
					//echo json_encode($response2);
				}
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
  
  user_id: <input type="text" name="user_id" />
  friend_id: <input type="text" name="friend_id" />
  message: <input type="text" name="message" />
  type: <input type="text" name="type" />
  <input type="submit" />
  </form>
</body>
</html>
