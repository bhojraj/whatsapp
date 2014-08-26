<?php

if(isset($_POST['login'])){
    if(isset($_POST["email"]) && isset($_POST["password"]))
    {
            include("constant.php");

            $email = $_POST["email"];
            $password = $_POST["password"];
            $regid = "";

            if(isset($_POST["regid"]))
            {
                    $regid = $_POST["regid"];
            }

            require_once 'db_connect.php';
            $db = new DB_CONNECT();

            $response = array();

            $response[$TAG_DETAILS] = array();

            // Fetching sub categories
            $success = false;
            $query	= " SELECT * FROM i_user WHERE email='$email' AND password='$password' ";
            $result = mysql_query($query);
            if($result)
            {
                    if (mysql_num_rows($result) > 0)
                    {
                            $row = mysql_fetch_array($result);
                            $detail = array();
                            $userId = $row["id"];

                            $query	= " SELECT online FROM i_user WHERE id='$userId' AND online=0 ";
                            $result = mysql_query($query);
                            if($result)
                            {
                                    if (mysql_num_rows($result) > 0)
                                    {
                                            $detail["id"] = $row["id"];
                                            $detail["name"] = $row["fname"];
                                            $detail["lname"] = $row["lname"];
                                            $detail["email"] = $row["email"];
                                            $detail["online"] = 1;
                                            $detail["status"] = $row["status"];
                                            $detail["image"] = $row["image"];

                                            $detail["phone"] = $row["phone"];
                                            $detail["phone_code"] = $row["phone_code"];

//                                            array_push($response[$TAG_DETAILS], $detail);
                                            $response['message'] = 'Log-in Successfully';
                                            echo json_encode($response['message']);
                                            $success = true;
                                    }
                                    else
                                    {
                                            $response[$TAG_SUCCESS] = false;
                                            $response[$TAG_MESSAGE] = "You are already logged in on another device.";
                                            echo json_encode($response['message']);
                                            return;
                                    }
                            }
                            else
                            {
                                    $response[$TAG_SUCCESS] = false;
                                    $response[$TAG_MESSAGE] = "Incorrect username or password...";
                                    echo json_encode($response);
                                    return;
                            }
                    }
                    else
                    {
                            $response[$TAG_SUCCESS] = false;
                            $response[$TAG_MESSAGE] = "Incorrect username or password.";
                            echo json_encode($response);
                            return;
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
                    $query	= " UPDATE i_user SET online=1 WHERE id=$userId ";
                    $result = mysql_query($query);
                    if($result) {

                            if(isset($_POST["regid"]))
                            {
                                    $query	= " UPDATE i_user SET regid='$regid' WHERE id=$userId ";
                                    $result = mysql_query($query);
                                    if($result) {
                                            $response[$TAG_SUCCESS] = true;
                                            $response[$TAG_MESSAGE] = "Success.";
                                            echo json_encode($response);
                                    }
                                    else
                                    {
                                            $response[$TAG_SUCCESS] = false;
                                            $response[$TAG_MESSAGE] = "Incorrect username or password.";
                                            echo json_encode($response);
                                    }
                            }
                            else
                            {
                                    $response[$TAG_SUCCESS] = true;
                                    $response[$TAG_MESSAGE] = "Success.";
                                    echo json_encode($response);
                            }
                    }
                    else
                    {
                                    $response[$TAG_SUCCESS] = false;
                                    $response[$TAG_MESSAGE] = "Incorrect username or password.";
                                    echo json_encode($response);
                    }
            }
    }
}

//APA91bEkIkEi72B-lqjdTN7zjnqQ5bSMJmWCncfBANOzII6tlwl7cHpT_FkKVqfAbkn-7LxwXeEKrML3bFAAgfoXrUZ2ADuzoz6u6S7tq5qnKUNlVgQp3WUEi7Lk7dxKVWEKFQVS0HSpaYwDf3wx28nDym2kzHvNRw

?>

<html>
<body>
  <form action="<?php $_PHP_SELF ?>" method="POST">
  
  email: <input type="text" name="email" />
  password: <input type="text" name="password" />
  regid: <input type="text" name="regid" />
  <input type="submit" name="login" value="Log-In"/>
  </form>
</body>
</html>