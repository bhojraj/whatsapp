<?php

if(isset($_POST["name"]) && isset($_POST["user_id"]) && isset($_POST['users']))
{	
	include("constant.php");
	
	$name = $_POST["name"];
	$uid = (int)$_POST["user_id"];
	$base=$_POST['users'];
	$base=stripcslashes($base);
	$data = json_decode($base, TRUE);
	
	require_once 'db_connect.php';
	$db = new DB_CONNECT();
	
	$response = array();
	$detail = array();
	
	$response[$TAG_DETAILS] = array();
	
	// Fetching sub categories
	$success = false;
	$defaultimg = "group.png";
	
	$current_date = time();
	$dateNow = date('Y-m-d H:i:s', $current_date);
	
	$gid = 0;
	
	$query	= " INSERT INTO i_group(name, admin_id, image, time) VALUES('$name', $uid, '$defaultimg', '$dateNow') ";
	$result = mysql_query($query);
	if($result)
	{
		$gid = mysql_insert_id();
		$success = true;
	}
	else
	{
		$response[$TAG_SUCCESS] = false;
		$response[$TAG_MESSAGE] = "The Group Name is already taken.";
		echo json_encode($response);
		return;
	}
	
	if($success)
	{
		$query	= " INSERT INTO i_group_member(group_id, time, user_id) VALUES ('$gid', '$dateNow', $uid) ";		
		foreach($data as $users)
		{
			$userid = $users['user_id'];
			$query .= " , ('$gid', '$dateNow', '$userid') ";
		}
		
		$result = mysql_query($query);
		if($result)
		{
			$success = true;
			
			$detail["id"] = $gid;
			
			
			array_push($response[$TAG_DETAILS], $detail);
			$response[$TAG_SUCCESS] = true;
			$response[$TAG_MESSAGE] = "Group Created.";
			echo json_encode($response);	
		}
		else
		{
			$response[$TAG_SUCCESS] = false;
			$response[$TAG_MESSAGE] = "Not able to insert members.";
			echo json_encode($response);
			return;
		}
	}
	else
	{
		$response[$TAG_SUCCESS] = false;
		$response[$TAG_MESSAGE] = "Incorrect username or password.";
		echo json_encode($response);
		return;
	}
}

?>

<html>
<body>
  <form action="<?php $_PHP_SELF ?>" method="POST">
  
  name: <input type="text" name="name" />
  user_id: <input type="text" name="user_id" />
  users: <input type="text" name="users" />
  <input type="submit" />
  </form>
</body>
</html>