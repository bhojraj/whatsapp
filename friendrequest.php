<?php

if(isset($_POST["user_id"]) && isset($_POST["friend_id"]) && isset($_POST["type"]))
{	
	include("constant.php");
	
	$uid = (int)$_POST["user_id"];
	$fid = (int)$_POST["friend_id"];
	$type = (int)$_POST["type"];
	
	require_once 'db_connect.php';
	$db = new DB_CONNECT();
	
	$response = array();
	
	$response[$TAG_DETAILS] = array();
	
	$current_date = time();
	$dateNow = date('Y-m-d H:i:s', $current_date);
	
	$success = false;
	$query;
	if($type==0) // NEW FRIEND REQUEST
	{
		$query	= " SELECT COUNT(*) as total FROM i_user_friend ";
		$query	.= " WHERE (user_id=$uid AND friend_id=$fid) OR (user_id=$fid AND friend_id=$uid) ";
		$result = mysql_query($query);
		if($result)
		{	
			if (mysql_num_rows($result) > 0)
			{
				$row = mysql_fetch_array($result);
				if((int)$row['total']==0)			// if these two were never friends before
				{
					$query	= " INSERT INTO i_user_friend(user_id, friend_id, type, time) VALUES($uid, $fid, 1, '$dateNow') ";
				}
				else // if these two were already friends before
				{
					$query	= "UPDATE i_user_friend SET type = 1, user_id=$uid, friend_id=$fid ";
					$query	.= " WHERE (user_id=$uid AND friend_id=$fid) OR (user_id=$fid AND friend_id=$uid) ";
				}
			}
		}
	}
	else if($type==1) // ACCEPT FRIEND REQUEST
	{
		$query	= " UPDATE i_user_friend SET type = 2 ";
		$query	.= " WHERE (user_id=$uid AND friend_id=$fid) OR (user_id=$fid AND friend_id=$uid) ";
	}
	else if($type==2) // CANCEL OR REJECT OR UNFRIEND REQUEST
	{	
		$query	= " UPDATE i_user_friend SET type = 0 ";
		$query	.= " WHERE (user_id=$uid AND friend_id=$fid) OR (user_id=$fid AND friend_id=$uid) ";
	}
	
	$result = mysql_query($query);
	if($result)
	{	
		$query	= " SELECT id, type, user_id FROM i_user_friend ";
		$query .= " WHERE (user_id=$uid AND friend_id=$fid) OR (user_id=$fid AND friend_id=$uid) ";
		$result = mysql_query($query);
		if($result)
		{	
			if (mysql_num_rows($result) > 0)
			{
				$row = mysql_fetch_array($result);
				
				$detail = array();
				$detail["friend_request_id"] = $row["id"];
				$detail["type"] = $row["type"];
				$detail["admin_id"] = $row["user_id"];
				
				$success = true;
				
				array_push($response[$TAG_DETAILS], $detail);
				$response[$TAG_SUCCESS] = true;
				$response[$TAG_MESSAGE] = "Success.";
				echo json_encode($response);
			}
		}
	}
	else
	{
		$response[$TAG_SUCCESS] = false;
		$response[$TAG_MESSAGE] = "Some error occured.";
		echo json_encode($response);
		return;
	}
		
	
}

?>

<html>
<body>
  <form action="<?php $_PHP_SELF ?>" method="POST">
  user_id: <input type="text" name="user_id" />
  friend_id: <input type="text" name="friend_id" />
  type: <input type="text" name="type" />
  <input type="submit" />
  </form>
</body>
</html>