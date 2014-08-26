<?php

//if(isset($_POST["name"]) && isset($_POST["lname"]) && isset($_POST["email"]) && isset($_POST["password"]) && isset($_POST["status"]) && isset($_POST["regid"]))
if(isset($_POST["name"]) && isset($_POST["lname"]) && isset($_POST["email"]) && isset($_POST["password"]) && isset($_POST["phone"]) && isset($_POST["phone_code"]))
{	
    /*if(strlen($_POST["name"]) > 0 && strlen($_POST["lname"]) > 0 && strlen($_POST["email"]) > 0 && strlen($_POST["password"]) > 0 && strlen($_POST["status"]) > 0 && strlen($_POST["regid"]) > 0){*/
    if(strlen($_POST["name"]) > 0 && strlen($_POST["lname"]) > 0 && strlen($_POST["email"]) > 0 && strlen($_POST["password"]) > 0 && strlen($_POST["phone"]) > 0 && strlen($_POST["phone_code"]) > 0){
	include("constant.php");
//	print_r($_POST);die;
	$fname = $_POST["name"];
	$lname = $_POST["lname"];
	$email = $_POST["email"];
	$password = $_POST["password"];
//	$status = $_POST["status"];
//	$regid = $_POST["regid"];
	
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
	
	//CHECK IF EMAIL TAKEN
	
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
			echo json_encode($response[$TAG_MESSAGE]);

                }else{
                        $response[$TAG_SUCCESS] = false;
			$response[$TAG_MESSAGE] = "Registration Successful.";
			echo json_encode($response[$TAG_MESSAGE]);
                }
	}
	
	$promo_id = "0";
	if(isset($_POST["promo_code"]))
	{
		//CHECK PROMOCODE		
		$promo_code = "";
		$promo_code = $_POST["promo_code"];
		
		if(strlen("".$promo_code)>0)
		{		
			$query	= " SELECT * FROM i_promo_code WHERE code = '$promo_code' ";
			$result = mysql_query($query);
			if($result)
			{
				$row = mysql_fetch_array($result);
				$promo_id = $row["id"];
				$limit = (int)$row["limit"];
				//NOW CHECK IF VALID OR USED
				
				//FIRST VALIDITY
				$query1  = "SELECT * FROM `i_promo_code` WHERE `id` = '$promo_id' AND '$dateNow'<= validity"; 	
				$result1 = mysql_query($query1) or die(mysql_error);
				if(mysql_num_rows($result1) > 0)
				{
					//Check how many times used
					$query1 	= "SELECT count(*) as used FROM `i_user` WHERE `promo_used` = '$promo_id'"; 	
					$result1 = mysql_query($query1) or die(mysql_error);
					$used = (int)$row1['used'];
						
					$available = $limit - $used;
					if($available==0)
					{
						$response[$TAG_SUCCESS] = false;
						$response[$TAG_MESSAGE] = "Promo Code Already Used";
						echo json_encode($response[$TAG_MESSAGE]);

					}
				}
				else
				{
					$response[$TAG_SUCCESS] = false;
					$response[$TAG_MESSAGE] = "Promo Code Expired";
					echo json_encode($response[$TAG_MESSAGE]);

				}		
			}
			else
			{
				$response[$TAG_SUCCESS] = false;
				$response[$TAG_MESSAGE] = "Invalid Promo Code.";
				echo json_encode($response[$TAG_MESSAGE]);

			
			}	
		}
	}
	
	
	
	$query;
	if(isset($_POST["phone_code"]) && isset($_POST["phone"]))
	{
		/*$query= " INSERT INTO i_user(fname, lname, phone_code, phone, email, password, status, promo_used,regid, online, image, time) ";
		$query .= " VALUES('$fname', '$lname', '$phoneCode', '$phone', '$email', '$password', '$status', '$promo_id','$regid', 0, '$defaultimg', '$dateNow') ";*/
            $query= " INSERT INTO i_user(fname, lname, phone_code, phone, email, password,  promo_used, online, image, time) ";
		$query .= " VALUES('$fname', '$lname', '$phoneCode', '$phone', '$email', '$password',  '$promo_id', 0, '$defaultimg', '$dateNow') ";
	}
	else
	{
		//$query= " INSERT INTO i_user(fname, lname, email, password, status, regid, online, image, time) VALUES('$fname', '$lname', '$email', '$password', '$status', '$regid', 1, '$defaultimg', '$dateNow') ";
		/*$query= " INSERT INTO i_user(fname, lname, email, password, status, regid, online, image, time) ";
		$query .= " VALUES('$fname', '$lname', '$email', '$password', '$status', '$regid', 1, '$defaultimg', '$dateNow') ";*/
            $query= " INSERT INTO i_user(fname, lname, email, password,   online, image, time) ";
		$query .= " VALUES('$fname', '$lname', '$email', '$password',  1, '$defaultimg', '$dateNow') ";
	}
	
	$result = mysql_query($query);
	if($result)
	{	
		$detail["id"] = mysql_insert_id();
		$detail["name"] = $fname;
		$detail["lname"] = $lname;
		$detail["email"] = $email;
		$detail["online"] = 1;
//		$detail["status"] = $status;		
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
		$response[$TAG_MESSAGE] = "Some Error occuried, Please try after some time.";
		echo json_encode($response[$TAG_MESSAGE]);

	}
	
	if($success)
	{		
		array_push($response[$TAG_DETAILS], $detail);
		$response[$TAG_SUCCESS] = true;
		$response[$TAG_MESSAGE] = "Success.";
		echo json_encode($response[$TAG_MESSAGE]);	
	}
	else
	{
		$response[$TAG_SUCCESS] = false;
		$response[$TAG_MESSAGE] = "This email has already been taken.";
		echo json_encode($response[$TAG_MESSAGE]);

	}
    }else{
        echo json_encode("Can't be left blank.");
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
  password: <input type="password" name="password" />
<!--  status: <input type="text" name="status" />
  regid: <input type="text" name="regid" />-->
  <input type="submit" />
  </form>
</body>
</html>