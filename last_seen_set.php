<?php

if(isset($_POST["id"]) && isset($_POST["time"]))
{	
	include("constant.php");
	
	$uid = $_POST["id"];
	$timeNow = $_POST["time"];
	
	require_once 'db_connect.php';
	$db = new DB_CONNECT();
	
	$response = array();	
	$response[$TAG_DETAILS] = array();
	
	$current_date = time(); // $timeNow;  make this proper;
	$dateNow = date('Y-m-d H:i:s', $current_date);
	
	// Fetching sub categories
	$success = false;
	$query	= " UPDATE i_user SET last_seen='$dateNow' WHERE id=$uid ";
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
  time: <input type="text" name="time" />
  <input type="submit" />
  </form>
</body>
</html>