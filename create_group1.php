<?php

if(isset($_POST["name"]) && isset($_POST["user_id"]) && isset($_POST['users']))
{	
	include("constant.php");
	
	$name = $_POST["name"];
	$uid = (int)$_POST["user_id"];
	
	$base=$_POST['users'];
	$base=stripcslashes($base);
	$data = json_decode($base, TRUE);
	
	
	require_once 'db_connect.php';
	$db = new DB_CONNECT();
	
	$response = array();
	$detail = array();
	
	$response[$TAG_DETAILS] = array();
	
	
	foreach($data as $users)
	{
		$product = array();
		$id = $users['user_id'];		
		$detail["name"] = " x - ".$users['user_id'];		
	}
	
	array_push($response[$TAG_DETAILS], $detail);
		$response[$TAG_SUCCESS] = true;
		$response[$TAG_MESSAGE] = "Success.";
		echo json_encode($response);			
		return;
	
	/*
	$users = array();
	$users1 = $_POST['users'];
	
	$x = "";
	$c = 0;
	for($i=0; $i<strlen($users1); $i++)
	{
		if($users1[$i]==',')
		{
			$users["id"] = $x;			
			$c++;		
			$x = "";
			echo "c=".$c;
		}
		else
		{
			$x .= $x."".$users1[$i];
			echo " x = ".$x;
		}
	}
	
	for($i=0; $i<sizeof($users); $i++)
	{	
			echo " x - ".$users[$i]. " , size : ".sizeof($users);
	}
	*/
		/*
		$users = json_decode($users);
		
		foreach ($users['items'] as $address)
		{
			echo "items:". $address['address'] ."\n";
		};
		*/
		
		//for($i=0; $i<sizeof($users); $i++)
		//{	
			//$detail["name"] = " x - ".$users[0]. " , size : ".sizeof($users);
		//}
		
		
	
}

?>

<html>
<body>
  <form action="<?php $_PHP_SELF ?>" method="POST">
  
  name: <input type="text" name="name" />
  user_id: <input type="text" name="user_id" />
  users: <input type="text" name="users" />
  <input type="submit" />
  </form>
</body>
</html>