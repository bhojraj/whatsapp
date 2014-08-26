<?php

if(isset($_POST["id"]))
{	
	include("constant.php");
	
	$uid = $_POST["id"];
	
	require_once 'db_connect.php';
	$db = new DB_CONNECT();
	
	$response = array();
	
	$response[$TAG_DETAILS] = array();
	
	// Fetching sub categories
	$success = false;	
	$query	= " SELECT * FROM i_friend WHERE id=$uid ";
	$result = mysql_query($query);
	if($result)
	{	
		if (mysql_num_rows($result) > 0)
		{
			$row = mysql_fetch_array($result);
				
				$detail = array();
				$detail[$id] = $row[$id];
				$userid = $row[$user_id];
				$detail[$user_id] = $row[$user_id];
				$detail["friend_id"] = $row["friend_id"];
				$detail[$type] = $row[$type];
				$detail[$time] = $row[$time];
				$detail[$message] = $row[$message];
				$detail["who"] = $row["who"];
				$detail["status"] = $row["status"];
				$detail["delivery_time"] = $row["delivery_time"];
				
				
				$query1	= " SELECT fname, image FROM i_user WHERE id='$userid' ";
				$result1 = mysql_query($query1);
				if($result1)
				{	
					if (mysql_num_rows($result1) > 0)
					{
						$row1 = mysql_fetch_array($result1);
						$detail["image"] = $row1["image"];
						$detail["name"] = $row1["fname"];
					}
				}
				
				array_push($response[$TAG_DETAILS], $detail);
				$success = true;
		}
	}
	else
	{
		$response[$TAG_SUCCESS] = false;
		$response[$TAG_MESSAGE] = "Some error occured.";
		// echo no users JSON
		echo json_encode($response);
		return;
	}
	
	if(success)
	{
		$response[$TAG_SUCCESS] = true;
		$response[$TAG_MESSAGE] = "Fetched the friends.";
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