<?php

if(isset($_POST["user_id"])  && isset($_POST["qb_id"]))
{	
	include("constant.php");
	
	$uid = $_POST["user_id"];
	$qb_id = $_POST["qb_id"];
	
	require_once 'db_connect.php';
	$db = new DB_CONNECT();
	
	$response = array();
	
	$response[$TAG_DETAILS] = array();
	
	// Fetching sub categories
	$success = false;
	$query	= " UPDATE i_user SET qb_id = '$qb_id' WHERE id='$uid' ";
	$result = mysql_query($query) or die(mysql_error);
	if($result)
	{
		$response[$TAG_SUCCESS] = true;
		$response[$TAG_MESSAGE] = "Success.";
		echo json_encode($response);
	}
	else
	{
		$response[$TAG_SUCCESS] = false;
		$response[$TAG_MESSAGE] = "Some error occured.";
		echo json_encode($response);
	}
}

?>

<html>
<body>
  <form action="<?php $_PHP_SELF ?>" method="POST">
  
  user_id: <input type="text" name="id" />
  <input type="submit" />
  </form>
</body>
</html>