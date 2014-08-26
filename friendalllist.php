<?php

if(isset($_POST["id"]))
{	
	include("constant.php");
	
	$myId = (int)$_POST["id"];
	
	require_once 'db_connect.php';
	$db = new DB_CONNECT();
	
	$response = array();
	
	$response[$TAG_DETAILS] = array();	
	$response[$TAG_DETAIL1] = array();
	
	// Fetching sub categories
	$success = false;	
	$query	= " SELECT * FROM i_user WHERE NOT id=$myId ORDER BY fname, lname ";
	$result = mysql_query($query);
	if($result)
	{	
		if (mysql_num_rows($result) > 0)
		{
			while ($row = mysql_fetch_array($result)) {
					
					$detail = array();
					
					$fid = $row[$id];
					$detail[$id] = $row[$id];
					$detail[$name] = $row["fname"];
					$detail["lname"] = $row["lname"];
					$detail[$image] = $row[$image];
					$detail["status"] = $row["status"];
					
					$query1	= " SELECT user_id, type FROM i_user_friend ";
					$query1	.= " WHERE (user_id=$myId AND friend_id=$fid) OR (user_id=$fid AND friend_id=$myId) ";
					
					$result1 = mysql_query($query1);
					if($result1)
					{	
						if (mysql_num_rows($result1) > 0)
						{
							$row1 = mysql_fetch_array($result1);
							$detail["type"] = $row1["type"];
							$detail["admin_id"] = $row1["user_id"];
						}
						else
						{
							$detail["type"] = "0";
							$detail["admin_id"] = 0;
						}	
					}
					else {
						$detail["type"] = "0";
						$detail["admin_id"] = 0;
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
		echo json_encode($response);
		return;
	}
	
	if($success)
	{
		$response[$TAG_SUCCESS] = true;
		$response[$TAG_MESSAGE] = "Fetched the friends.";
		echo json_encode($response[$TAG_MESSAGE]).'<br>';
                echo json_encode($response[$TAG_DETAILS]);
	}
}

?>

<html>
<body>
  <form action="<?php $_PHP_SELF ?>" method="POST">
  User id: <input type="text" name="id" />
  <input type="submit" />
  </form>
</body>
</html>