<?php

if(isset($_POST["name"]) && isset($_POST["lname"]) && isset($_POST["email"]) && isset($_POST["password"]) && isset($_POST["status"]) && isset($_POST["regid"]))
{	
	include("constant.php");
	
	$fname = $_POST["name"];
	$lname = $_POST["lname"];
	$email = $_POST["email"];
	$password = $_POST["password"];
	$status = $_POST["status"];
	$regid = $_POST["regid"];
	
	$phoneCode = "";
	$phone = "";
	
	if(isset($_POST["phone_code"]) && isset($_POST["phone"]))
	{
		$phoneCode = $_POST["phone_code"];
		$phone = $_POST["phone"];
	}
	
	require_once 'db_connect.php';
	$db = new DB_CONNECT();
	
	$response = array();
	$detail = array();
	
	$response[$TAG_DETAILS] = array();
	
	// Fetching sub categories
	$success = false;
	$defaultimg = "user.png";
	
	$current_date = time();
	$dateNow = date('Y-m-d H:i:s', $current_date);
	
	$query	= " SELECT count(*) as total FROM i_user WHERE email='$email' ";
	$result = mysql_query($query);
	if($result)
	{
		$row = mysql_fetch_array($result);
		$total = (int)$row["total"];
		if($total>0)
		{
			$response[$TAG_SUCCESS] = false;
			$response[$TAG_MESSAGE] = "This email has already been taken.";
			echo json_encode($response);
			return;
		}
	}
	
	$query;
	if(isset($_POST["phone_code"]) && isset($_POST["phone"]))
	{
		$query= " INSERT INTO i_user(fname, lname, phone_code, phone, email, password, status, regid, online, image, time) ";
		$query .= " VALUES('$fname', '$lname', '$phoneCode', '$phone', '$email', '$password', '$status', '$regid', 1, '$defaultimg', '$dateNow') ";
	}
	else
	{
		//$query= " INSERT INTO i_user(fname, lname, email, password, status, regid, online, image, time) VALUES('$fname', '$lname', '$email', '$password', '$status', '$regid', 1, '$defaultimg', '$dateNow') ";
		$query= " INSERT INTO i_user(fname, lname, email, password, status, regid, online, image, time) ";
		$query .= " VALUES('$fname', '$lname', '$email', '$password', '$status', '$regid', 1, '$defaultimg', '$dateNow') ";
	}
	
	$result = mysql_query($query);
	if($result)
	{	
		$detail["id"] = mysql_insert_id();
		$detail["name"] = $fname;
		$detail["lname"] = $lname;
		$detail["email"] = $email;
		$detail["online"] = 1;
		$detail["status"] = $status;		
		$detail["image"] = $defaultimg;		
		if(isset($_POST["phone_code"]) && isset($_POST["phone"]))
		{
			$detail["phone_code"] = $phoneCode;
			$detail["phone"] = $phone;
		}
	
		$success = true;
	}
	else
	{
		$response[$TAG_SUCCESS] = false;
		$response[$TAG_MESSAGE] = "This email has already been taken.";
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
		$response[$TAG_MESSAGE] = "This email has already been taken.";
		echo json_encode($response);
		return;
	}
}

?>

<html>
<body>
  <form action="<?php $_PHP_SELF ?>" method="POST">
  
  name: <input type="text" name="name" />
  lname: <input type="text" name="lname" />
  phoneCode: <input type="text" name="phone_code" />
  phone: <input type="text" name="phone" />
  email: <input type="text" name="email" />
  password: <input type="text" name="password" />
  status: <input type="text" name="status" />
  regid: <input type="text" name="regid" />
  <input type="submit" />
  </form>
</body>
</html>