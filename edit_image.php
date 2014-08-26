<?php

if(isset($_POST["id"]) && isset($_POST["image"]) && isset($_POST["type"]))
{	
	include("constant.php");
	
	$uid = (int)$_POST["id"];
	$image = $_POST["image"];
	$type = (int)$_POST["type"];
	
	// type = 1 -> table i_user
	// type = 2 -> table i_group
	
	require_once 'db_connect.php';
	$db = new DB_CONNECT();
	
	$response = array();
	$detail = array();
	
	$response[$TAG_DETAILS] = array();
	
	// Fetching sub categories
	$success = false;
	if($type==1)
	{
		$query	= " UPDATE i_user SET image='$image' WHERE id=$uid ";
	}
	else if($type==2)
	{
		$query	= " UPDATE i_group SET image='$image' WHERE id=$uid ";
	}
	
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
		array_push($response[$TAG_DETAILS], $detail);
		$response[$TAG_SUCCESS] = true;
		$response[$TAG_MESSAGE] = "Updated Image.";
		echo json_encode($response);	
	}
	else
	{
		$response[$TAG_SUCCESS] = false;
		$response[$TAG_MESSAGE] = "Error changing details.";
		echo json_encode($response);
	}
}

?>

<html>
<body>
  <form action="<?php $_PHP_SELF ?>" method="POST">
  
  id: <input type="text" name="id" />
  image: <input type="text" name="image" />
  type: <input type="text" name="type" />
  <input type="submit" />
  </form>
</body>
</html>
