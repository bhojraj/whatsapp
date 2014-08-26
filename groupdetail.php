<?php

if(isset($_POST["user_id"]) && isset($_POST["group_id"]))
{	
	include("constant.php");
	
	$uid = $_POST["user_id"];
	$gid = $_POST["group_id"];
	
	require_once 'db_connect.php';
	$db = new DB_CONNECT();
	
	$response = array();
	
	$response[$TAG_DETAILS] = array();
	
	// Fetching sub categories
	$success = false;
	$query	= " SELECT * FROM i_friend WHERE friend_id=$gid AND who=2 ";
	$result = mysql_query($query);
	if($result)
	{	
		if (mysql_num_rows($result) > 0)
		{
			while ($row = mysql_fetch_array($result)) {
				
				$detail = array();
				$detail[$id] = $row[$id];
				$userid = $row[$user_id];
				$detail[$user_id] = $userid;
				$detail["friend_id"] = $row["friend_id"];
				$detail[$type] = $row[$type];
				$detail[$time] = $row[$time];
				$detail[$message] = $row[$message];
				$detail["who"] = $row["who"];
				
				$query1	= " SELECT image, fname FROM i_user WHERE id='$userid' ";
				$result1 = mysql_query($query1);
				if($result1)
				{	
					$row1 = mysql_fetch_array($result1);
					$detail["image"] = $row1["image"];
					$detail["name"] = $row1["fname"];
				}
				
				array_push($response[$TAG_DETAILS], $detail);
				$success = true;
			}
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
user_id: <input type="text" name="user_id" />
group_id: <input type="text" name="group_id" />
  <input type="submit" />
  </form>
</body>
</html>