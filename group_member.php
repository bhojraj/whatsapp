<?php
/*

Fetch the members of a group.

req. :-
	group id
*/


if(isset($_POST["group_id"]))
{	
	include("constant.php");
	
	$gid = (int)$_POST["group_id"];
	
	require_once 'db_connect.php';
	$db = new DB_CONNECT();
	
	$response = array();
	$response[$TAG_DETAILS] = array();
	
	// Fetching sub categories
	$success = false;
	
	$query	= " select u.id, u.fname, u.lname, u.image, u.status from `i_group_member` gmember, i_user u where gmember.group_id='$gid' and gmember.user_id=u.id ";
	$result = mysql_query($query);
	if($result)
	{
		if (mysql_num_rows($result) > 0)
		{
			while ($row = mysql_fetch_array($result)) 
			{	
				$detail[$id] = $row[$id];
				$detail[$name] = $row["fname"];
				$detail[$lname] = $row["lname"];
				$detail[$image] = $row[$image];
				$detail[$status] = $row[$status];
					
				array_push($response[$TAG_DETAILS], $detail);
				$success = true;
			}
		}
		else
		{
			$response[$TAG_SUCCESS] = false;
			$response[$TAG_MESSAGE] = "Please add members to the group.";
			echo json_encode($response);
			return;
		}
	}
	else
	{
		$response[$TAG_SUCCESS] = false;
		$response[$TAG_MESSAGE] = "The group does not exists.";
		echo json_encode($response);
		return;
	}
	
	if(success)
	{
		$response[$TAG_SUCCESS] = true;
		$response[$TAG_MESSAGE] = "Fetched the groups you have joined.";
		echo json_encode($response);
	}
}

?>

<html>
<body>
  <form action="<?php $_PHP_SELF ?>" method="POST">
  group_id: <input type="text" name="group_id" />
  <input type="submit" />
  </form>
</body>
</html>