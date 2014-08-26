<?php

if(isset($_POST["submit"])){
//    print_r($_POST);die;
    if(isset($_POST["id"]) && strlen($_POST["id"]) > 0)
    {	
            include("constant.php");

            $uid = $_POST["id"];

            require_once 'db_connect.php';
            $db = new DB_CONNECT();

            $response = array();

            $response[$TAG_DETAILS] = array();
            
            $userId = $_POST['id'];

            // Fetching sub categories
            $success = false;
            $query	= "UPDATE i_user SET online = 0 WHERE id='$uid' ";
            $result = mysql_query($query) or die(mysql_error);
            if($result)
            {
                $success = true;
//                    $response[$TAG_SUCCESS] = $success = true;
//                    $response[$TAG_MESSAGE] = "You are succesfully logged out.";
//                    echo json_encode($response[$TAG_MESSAGE]);
            }
            else
            {
                $success = false;
//                    $response[$TAG_SUCCESS] = $success = false;
//                    $response[$TAG_MESSAGE] = "Some error occured.";
//                    echo json_encode($response[$TAG_MESSAGE]);
            }
            if($success){
                $query	= " UPDATE i_user SET online=0 WHERE id=$userId ";
                $result = mysql_query($query);
                if($result) {
//                        $response[$TAG_SUCCESS] = true;
                        $response[$TAG_MESSAGE] = "Logged-out succesfully.";
                        echo json_encode($response[$TAG_MESSAGE]);
                }
                else
                {
//                        $response[$TAG_SUCCESS] = false;
                        $response[$TAG_MESSAGE] = "Incorrect UserID.";
                        echo json_encode($response[$TAG_MESSAGE]);
                }
            }
    }else{
        $success = false;
//        $response[$TAG_SUCCESS] = $success = false;
//        echo json_encode("Can't be left blank.");
    }
}



?>

<html>
<body>
  <form action="<?php $_PHP_SELF ?>" method="POST">
  
  user_id: <input type="text" name="id" />
  <input type="submit" name="submit" value="Log-out"/>
  </form>
</body>
</html>