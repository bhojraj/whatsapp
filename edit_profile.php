<?php

/* 
     * File name: edit_profile.php
     * Desc: Edit user profile.
     * Input: userId, name, lname, status, phoneCode, phone
     * Output: success or failure.
*/

require_once 'db_connect.php';

if(isset($_POST["id"]) && isset($_POST["name"]) && isset($_POST["lname"]) && isset($_POST["status"]))
{	
	include("constant.php");
	
	$uid = (int)$_POST["id"];
	$fname = $_POST["name"];
	$lname = $_POST["lname"];
	$status = $_POST["status"];
	
	$db = new DB_CONNECT();
	
	$response = array();
	$detail = array();
	
	$response[$TAG_DETAILS] = array();
	
	// Fetching sub categories
	$success = false;
	
	$query	= " UPDATE i_user SET fname='$fname', lname='$lname', status ='$status' ";
	if(isset($_POST["phone_code"]) && isset($_POST["phone"]))
	{
		$phoneCode = $_POST["phone_code"];
		$phone = $_POST["phone"];
		$query .= " ,phone ='$phone', phone_code ='$phoneCode' ";
	}
	
	$query .= " WHERE id=$uid ";
	
	$result = mysql_query($query);
	if($result)
	{	
		if(mysql_affected_rows()>0)
		{	/*
			$query = " SELECT * FROM i_user WHERE id='$uid' ";
			$result = mysql_query($query);
			if($result)
			{	
				if (mysql_num_rows($result) > 0) {	
						
					$row = mysql_fetch_array($result);
					$detail["id"] = $row["id"];
					$detail["name"] = $row["fname"];
					$detail["lname"] = $row["lname"];
					$detail["email"] = $row["email"];
					$detail["online"] = $row["online"];
					$detail["status"] = $row["status"];
					$detail["image"] = $row["image"];
					
					$detail["phone"] = $row["phone"];
					$detail["phone_code"] = $row["phone_code"];	
					
				}
			}	
			*/
			
			$success = true;
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
		array_push($response[$TAG_DETAILS], $detail);
		$response[$TAG_SUCCESS] = true;
		$response[$TAG_MESSAGE] = "Success.";
		echo json_encode($response);	
	}
	else
	{
		$response[$TAG_SUCCESS] = false;
		$response[$TAG_MESSAGE] = "Error changing details.";
		echo json_encode($response);
	}
}

?>

<html>
<body>
  <form action="<?php $_PHP_SELF ?>" method="POST">
  
  id: <input type="text" name="id" />
  fname: <input type="text" name="name" />
  lname: <input type="text" name="lname" />
  status: <input type="text" name="status" />
  phoneCode: <input type="text" name="phone_code" />
  phone: <input type="text" name="phone" />
  <input type="submit" />
  </form>
</body>
</html>