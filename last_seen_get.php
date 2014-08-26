<?php

require_once 'db_connect.php';
include("constant.php");

if(isset($_POST["id"]))
{	
	$uid = $_POST["id"];
	
	$db = new DB_CONNECT();
	
	$response = array();	
	$response[$TAG_DETAILS] = array();
	$detail = array();
	
	// Fetching sub categories
	$success = false;
	$query	= " SELECT last_seen FROM i_user WHERE id=$uid ";
	$result = mysql_query($query);
	if($result)
	{	
		if (mysql_num_rows($result) > 0)
		{	
			$row = mysql_fetch_array($result);
	
			$detail["last_seen"] = $row["last_seen"];
			array_push($response[$TAG_DETAILS], $detail);
			$response[$TAG_SUCCESS] = true;
			$response[$TAG_MESSAGE] = "Last seen fetched.";
			echo json_encode($response);
		}
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
  <input type="submit" />
  </form>
</body>
</html>