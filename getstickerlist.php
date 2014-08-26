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
	$query	= "SELECT * FROM i_stickers ";
	
	$result = mysql_query($query);
	if($result)
	{	
		if (mysql_num_rows($result) > 0)
		{
			while ($row = mysql_fetch_array($result)) {
				
				$detail = array();
				
					$fid = $row[$id];
					$detail[$id] = $row[$id];
					$detail["image"] = $row["image"];
					$detail["ext"] = $row["ext"];
					$detail["category"] = $row["category"];
					
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
		$response[$TAG_MESSAGE] = "Fetched the sticker list.";
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