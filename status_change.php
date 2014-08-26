<?php

if(isset($_POST["id"]) && isset($_POST["status"]))
{	
	include("constant.php");
	
	$uid = $_POST["id"];
	$status = $_POST["status"];
	
	require_once 'db_connect.php';
	$db = new DB_CONNECT();
	
	$response = array();	
	$response[$TAG_DETAILS] = array();
	
	// Fetching sub categories
	$success = false;
	$query	= " UPDATE i_user SET status='$status' WHERE id=$uid ";
	$result = mysql_query($query);
	if($result)
	{
			$success = true;		
			$response[$TAG_SUCCESS] = true;
			$response[$TAG_MESSAGE] = "Status Changed.";
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
  
  id: <input type="text" name="id" />
  status: <input type="text" name="status" />
  <input type="submit" />
  </form>
</body>
</html>