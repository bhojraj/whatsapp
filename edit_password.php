<?php

if(isset($_POST["id"]) && isset($_POST["password_old"]) && isset($_POST["password_new"]) && isset($_POST["password_confirm"]))
{	
    if(strlen($_POST["id"]) > 0 && strlen($_POST["password_old"]) > 0 && strlen($_POST["password_new"]) > 0 && strlen($_POST["password_confirm"]) > 0){
	include("constant.php");
	
	$uid = (int)$_POST["id"];
	$password_old = $_POST["password_old"];
	$password_new = $_POST["password_new"];
	$password_confirm = $_POST["password_confirm"];
	
	require_once 'db_connect.php';
	$db = new DB_CONNECT();
	
	$response = array();
	$detail = array();
	
	$response[$TAG_DETAILS] = array();
	
	$success = false;
	if(strcmp($password_new, $password_confirm)==0)
	{
		$success = true;
	}
	else
	{
		$response[$TAG_SUCCESS] = false;
		$response[$TAG_MESSAGE] = "Confirmation password is not same.";
		echo json_encode($response[$TAG_MESSAGE]);
		return;
	}
	
	$success = false;
	$query	= " SELECT id FROM i_user WHERE id=$uid AND password='$password_old' LIMIT 1 ";
	$result = mysql_query($query);
	if($result)
	{		
		if (mysql_num_rows($result) > 0)
		{
			$success = true;
		}
		else
		{
			$response[$TAG_SUCCESS] = false;
			$response[$TAG_MESSAGE] = "Incorrect password.";
			echo json_encode($response[$TAG_MESSAGE]);
			return;
		}		
	}
	else
	{
		$response[$TAG_SUCCESS] = false;
		$response[$TAG_MESSAGE] = "Some error occured.";
		echo json_encode($response[$TAG_MESSAGE]);
		return;
	}
	
	$success = false;
	$query	= " UPDATE i_user SET password='$password_new' WHERE id=$uid ";
	$result = mysql_query($query);
	if($result)
	{	
		$success = true;
	}	
	else
	{
		$response[$TAG_SUCCESS] = false;
		$response[$TAG_MESSAGE] = "Some error occured.";
		echo json_encode($response[$TAG_MESSAGE]);
		return;
	}
	
	if($success)
	{
		array_push($response[$TAG_DETAILS], $detail);
		$response[$TAG_SUCCESS] = true;
		$response[$TAG_MESSAGE] = "Successfully Changed password.";
		echo json_encode($response[$TAG_MESSAGE]);	
	}
	else
	{
		$response[$TAG_SUCCESS] = false;
		$response[$TAG_MESSAGE] = "Error changing details.";
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
  id: <input type="text" name="id" />
  password_old: <input type="text" name="password_old" />
  password_new: <input type="text" name="password_new" />
  password_confirm: <input type="text" name="password_confirm" />
  <input type="submit" />
  </form>
</body>
</html>