<?php
/*

Fetch the groups a user is a member of.

req. :-
	user id
*/

if(isset($_POST["user_id"]))
{	
	include("constant.php");
	
	$uid = (int)$_POST["user_id"];
	
	require_once 'db_connect.php';
	$db = new DB_CONNECT();
	
	$response = array();
	$response[$TAG_DETAILS] = array();
	
	//
	// Fetching sub categories
	$success = false;
	
	//select gname.id, gname.name, gname.image, gname.admin_id, gname.time, f.message from `i_group_member` gmember, i_group gname, i_friend f where gmember.user_id=16 and gmember.group_id=gname.id and f.who=2
	//select gname.id, gname.name, gname.image, gname.admin_id, gname.time, (select f.message from i_friend f where f.who=2 order by f.id desc limit 1) as message from `i_group_member` gmember, i_group gname where gmember.user_id=16 and gmember.group_id=gname.id
	
	//$query	= " select gname.id, gname.name, gname.image, gname.admin_id, gname.time from `i_group_member` gmember, i_group gname where gmember.user_id=$uid and gmember.group_id=gname.id ";
	
	$query	= " select gname.id, gname.name, gname.image, gname.admin_id, gname.time from `i_group_member` gmember, i_group gname where gmember.user_id=$uid and gmember.group_id=gname.id group by gname.id ";
	$result = mysql_query($query);
	if($result)
	{
		if (mysql_num_rows($result) > 0)
		{
			while ($row = mysql_fetch_array($result))
			{	
					$fid = $row[$id];
					$detail[$id] = $row[$id];
					$detail[$name] = $row["name"];
					$detail["admin_id"] = $row["admin_id"];
					$detail[$image] = $row[$image];
					$detail["status"] = "";
					$detail["time"] = $row["time"];
					
					$query1	= " SELECT message, type FROM i_friend ";
					//$query1	.= " WHERE (user_id=$uid AND friend_id=$fid) OR (user_id=$fid AND friend_id=$uid) ";
					$query1	.= " WHERE friend_id=$fid ";
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
			$response[$TAG_SUCCESS] = false;
			$response[$TAG_MESSAGE] = "You are not a member of any group.";
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
  id: <input type="text" name="user_id" />
  <input type="submit" />
  </form>
</body>
</html>