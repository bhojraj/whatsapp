<?php
/*

Fetch the members of a group.

req. :-
	group id
*/

if(isset($_POST["group_id"]) && isset($_POST["user_id"]) && isset($_POST["type"]))
{	
	include("constant.php");
	
	$gid = (int)$_POST["group_id"];
	$uid = (int)$_POST["user_id"];
	$type = (int)$_POST["type"];
	
	require_once 'db_connect.php';
	$db = new DB_CONNECT();
	
	$response = array();
	
	// Fetching sub categories
	$success = false;
	
	$current_date = time();
	$dateNow = date('Y-m-d H:i:s', $current_date);
	$query;
	if($type==1)
		$query	= " INSERT INTO i_group_member(group_id, time, user_id) VALUES ('$gid', '$dateNow', $uid) ";
	else if($type==2)
		$query	= " DELETE FROM i_group_member WHERE group_id=$gid AND user_id=$uid ";
		
	$result = mysql_query($query);
	if($result)
	{
		$response[$TAG_SUCCESS] = true;
		$response[$TAG_MESSAGE] = "Success.";
		echo json_encode($response);
	}
	else
	{
		$response[$TAG_SUCCESS] = false;
		$response[$TAG_MESSAGE] = "The group does not exists.";
		echo json_encode($response);
		return;
	}
}

?>

<html>
<body>
  <form action="<?php $_PHP_SELF ?>" method="POST">
  group_id: <input type="text" name="group_id" />
  user_id: <input type="text" name="user_id" />
  type: <input type="text" name="type" />
  <input type="submit" />
  </form>
</body>
</html>