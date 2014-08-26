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
	
	// Make the user online
	$query	= " UPDATE i_user SET online = 1 WHERE id='$uid' ";
	$result = mysql_query($query);
	if($result)
	{
		//proceed
	}
	else
	{
		$response[$TAG_SUCCESS] = false;
		$response[$TAG_MESSAGE] = "Some error occured.";
		// echo no users JSON
		echo json_encode($response);
		return;
	}
	
	 // query to get friends
	$query = "SELECT * FROM i_user where id IN (SELECT CASE 
         WHEN user_id=$uid THEN friend_id
         ELSE user_id
      END FROM `i_user_friend` where (user_id=$uid or friend_id = $uid) and type=2) ORDER BY online DESC, fname";
	
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
					$detail["online"] = $row["online"];
					$detail[$image] = $row[$image];
					
					$detail["phone"] = $row["phone"];
					$detail["phone_code"] = $row["phone_code"];
					
					$detail["qb_id"] = $row["qb_id"];
					
					$query1	= " SELECT message, type FROM i_friend ";
					$query1	.= " WHERE (user_id=$uid AND friend_id=$fid) OR (user_id=$fid AND friend_id=$uid) ";
					$query1	.= " ORDER BY id DESC LIMIT 1 ";
					
					$result1 = mysql_query($query1);
					if($result1)
					{	
						if (mysql_num_rows($result) > 0)
						{
							$row1 = mysql_fetch_array($result1);
							$type = (int)$row1["type"];
							if($type==1) {
								$detail["status"] = $row1["message"];
							}
							else if($type==2) {
								$detail["status"] = "Image";
							}
							else if($type==3) {
								$detail["status"] = "File";
							}
							else if($type==4) {
								$detail["status"] = "Location";
							}
							else if($type==5) {
								$detail["status"] = "Sticker";
							}
							else {
								$detail["status"] = "";
							}
						}
						else
						{
							$detail["status"] = "";
						}
					}
					else {
						$detail["status"] = $row["status"];
					}
					
					array_push($response[$TAG_DETAILS], $detail);
					$success = true;
			}
		}
		else
		{
			$response[$TAG_SUCCESS] = true;
			$response[$TAG_MESSAGE] = "You do not have any friends. Add some friends to chat with them.";
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
  <input type="submit" />
  </form>
</body>
</html>