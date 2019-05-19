<?php
    require("./_auth.php");
    if(!isset($_GET["id"]))
    {
       header("Location: main.php");
       exit;        
    }
    $id = $_GET["id"];
    if(!filter_var($id, FILTER_VALIDATE_INT))
    {
       header("Location: main.php");
       exit;
    }
    require_once './_db.php';
    require_once './FriendManager.php';
    try {
        $stmt = $db->prepare("delete from friend where sender_id = ? and receiver_id = ?") ;
        $stmt->execute( [$_SESSION["user"]["id"], $id]) ;
        $stmt->execute( [$id, $_SESSION["user"]["id"]]) ;
        $status = FriendManager::NOTFRIEND;
        $sql = "insert into notification(text,link,receiver_id) values('". $_SESSION["user"]["name"] ." ". $_SESSION["user"]["surname"] ." removed you from his/her friends.', 'profile.php?id=". $_SESSION["user"]["id"] ."', $id)";
        $stmt = $db->prepare($sql) ;
        $stmt->execute();  
        header("Location: ../profile.php?id=$id&status=$status");
        exit ;
    } catch (Exception $ex) {
       $error = true ;
    }
