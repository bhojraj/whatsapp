<?php

if(isset($_POST["id"]))
{	
	include("constant.php");
	
	$uid = (int)$_POST["id"];
	
	require_once 'db_connect.php';
	$db = new DB_CONNECT();
	
	$response = array();
	
	$response[$TAG_DETAILS] = array();
	
	// Fetching sub categories
	$success = false;
	
	// Choose from all users while creating a group
	
	$query	= " SELECT id, fname, lname, image, status FROM i_user ORDER BY fname, lname "; // for all users
	
	// Choose from only friends while creating a group
	$query = "SELECT * FROM i_user where id IN (SELECT CASE 
         WHEN user_id=$uid THEN friend_id
         ELSE user_id
      END FROM `i_user_friend` where (user_id=$uid or friend_id = $uid) and type=2) ORDER BY online DESC, fname, lname";
	
	$result = mysql_query($query);
	if($result)
	{	
		if (mysql_num_rows($result) > 0)
		{
			while ($row = mysql_fetch_array($result)) {
				
				$detail = array();
				
					$detail[$id] = $row[$id];
					$detail[$name] = $row["fname"];
					$detail[$lname] = $row["lname"];
					$detail[$image] = $row[$image];
					$detail[$status] = $row["status"];
					
					array_push($response[$TAG_DETAILS], $detail);
					$success = true;
			}
		}
		else
		{
			$response[$TAG_SUCCESS] = false;
			$response[$TAG_MESSAGE] = "You have no friends.";
			// echo no users JSON
			echo json_encode($response);
			return;
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
  id(1): <input type="text" name="id" />
  type: <input type="text" name="type" />
  <input type="submit" />
  </form>
</body>
</html>