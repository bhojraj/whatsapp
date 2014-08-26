<?php
/*

Change name of a group.

req. :-
	group id
	name
*/

if(isset($_POST["group_id"]) && isset($_POST["name"]))
{	
	include("constant.php");
	
	$gid = (int)$_POST["group_id"];
	$name = $_POST["name"];
	
	require_once 'db_connect.php';
	$db = new DB_CONNECT();
	
	$response = array();
	
	// Fetching sub categories
	$success = false;
	
	$query = " UPDATE i_group SET name='$name' WHERE id=$gid ";
		
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
  name: <input type="text" name="name" />
  <input type="submit" />
  </form>
</body>
</html>