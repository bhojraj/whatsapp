<?php

/*
     * File name: message_delete.php
     * Desc: Delete the selected messages of a conversation.
     * Input: message_id
     * Output: success or failure
*/

require_once 'db_connect.php';
include("constant.php");

if(isset($_POST["message_id"]))
{			
	$base=$_POST['message_id'];
	$base=stripcslashes($base);
	$messageIds = json_decode($base, TRUE);
	
	$db = new DB_CONNECT();
	
	$response = array();	
	$response[$TAG_DETAILS] = array();	
	$success = false;
	
	$msgIdCount = count($messageIds);
	$i = 1;
	
	$query = " DELETE FROM i_friend WHERE (id) IN (";
	foreach($messageIds as $msgIdData)
	{
		$msgId = $msgIdData['message_id'];
		$query .= " ('$msgId') ";
		if($i<$msgIdCount)
			$query .= " , ";
		$i++;
	}
	$query .= " ) ";
	
	$result = mysql_query($query);
	if($result)
	{	
		if(mysql_affected_rows()>0)
		{
			$success = true;
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
		$response[$TAG_MESSAGE] = "Message/s deleted successfully.";
		echo json_encode($response);
	}	
}

?>

<html>
<body>
  <form action="<?php $_PHP_SELF ?>" method="POST">
	message_id: <input type="text" name="message_id" />
  <input type="submit" />
  </form>
</body>
</html>