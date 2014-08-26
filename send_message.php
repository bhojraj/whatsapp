<?php

if (isset($_GET["regId"]) && isset($_GET["message"])) {
    $regId = $_GET["regId"];
    $message = $_GET["message"];
    
    require_once 'GCM.php';
    
    $gcm = new GCM();
	
    $registration_ids = array($regId);
    $message = array("message" => $message);
	
    $result = $gcm->send_notification($registration_ids, $message);
 
    echo $result;
}

?>

<html>
<body>
  <form action="<?php $_PHP_SELF ?>" method="POST">
  RegId: <input type="text" name="regId" />
  message: <input type="text" name="message" />
  <input type="submit" />
  </form>
</body>
</html>