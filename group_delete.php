<?php

if(isset($_POST["group_id"]))
{	
	include("constant.php");
	
	$gid = $_POST["group_id"];
	
	require_once 'db_connect.php';
	$db = new DB_CONNECT();
	
	$response = array();	
	$response[$TAG_DETAILS] = array();
	
	// Fetching sub categories
	$success = false;
	$query	= " DELETE FROM i_group WHERE id='$gid' ";
	$result = mysql_query($query);
	if($result)
	{
		$success = true;
	}
	else
	{
		$response[$TAG_SUCCESS] = false;
		$response[$TAG_MESSAGE] = "Some error occured.";
		echo json_encode($response);
		return;
	}
	
	if($success)
	{
		// Also delete group members and group messages later.
		$response[$TAG_SUCCESS] = true;
		$response[$TAG_MESSAGE] = "The group is successfully deleted.";
		echo json_encode($response);
	}
}

?>

<html>
<body>
  <form action="<?php $_PHP_SELF ?>" method="POST">
  
  group_id: <input type="text" name="group_id" />
  <input type="submit" />
  </form>
</body>
</html>