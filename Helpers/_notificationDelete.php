<?php    
    require("_auth.php");
    require_once '_db.php';
    
    $id = $_GET["id"] ?? -1;
    
    if(filter_var($id, FILTER_VALIDATE_INT))
    {
        $sql = "delete from notification where id = $id and receiver_id = " . $_SESSION["user"]["id"];        
        $stmt = $db->prepare($sql) ;
        $stmt->execute() ;
    }
    header("Location: ../notification.php");