<?php

if(isset($_POST["user_id"]) && isset($_POST["group_id"]) && isset($_POST["type"]))
{	
	include("constant.php");
	
	$uid = (int)$_POST["user_id"];
	$gid = (int)$_POST["group_id"];
	$type = (int)$_POST["type"];
	
	$FRIENDS_IN_GROUP	 		= 1;
	$ALL_FRIENDS_GROUP	 		= 2;
	
	require_once 'db_connect.php';
	$db = new DB_CONNECT();
	
	$response = array();
	
	$response[$TAG_DETAILS] = array();
	
	// Fetching sub categories
	$success = false;
	
	$query = "SELECT * FROM i_user where id IN (SELECT CASE 
         WHEN user_id=$uid THEN friend_id
         ELSE user_id
      END FROM `i_user_friend` where (user_id=$uid or friend_id = $uid) and type=2) ORDER BY online DESC, fname";
		
		// Check the 3rd query
	
	$result = mysql_query($query);
	if($result)
	{	
		if (mysql_num_rows($result) > 0)
		{
			while ($row = mysql_fetch_array($result)) {
				
				$detail = array();
				//Temp commented. Remove comment later
				
					$uid  				= $row[$id];
					$detail[$id] 		= $uid;
					$detail[$name] 		= $row["fname"];
					$detail[$lname] 	= $row["lname"];
					$detail[$image] 	= $row[$image];
					$detail[$status]	= $row["status"];
					$detail["type"] 	= $type;
					
						$query1	= " SELECT id FROM `i_group_member` where group_id='$gid' and user_id='$uid' ";
						$result1 = mysql_query($query1);
						if($result1)
						{	
							if (mysql_num_rows($result1) > 0)
							{
								$detail["check_state"] = true;
							}
							else
							{
								$detail["check_state"] = false;
							}
						}
						else
						{
							$detail["check_state"] = false;
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
		$response[$TAG_MESSAGE] = "Fetched the members.";
		echo json_encode($response);
	}
}

?>

<html>
<body>
  <form action="<?php $_PHP_SELF ?>" method="POST">
  user_id: <input type="text" name="user_id" />
  group_id: <input type="text" name="group_id" />
  type: <input type="text" name="type" />
  <input type="submit" />
  </form>
</body>
</html>