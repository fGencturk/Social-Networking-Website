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
        $stmt = $db->prepare("delete from friend_request where sender_id = ? and receiver_id = ?") ;
        $stmt->execute( [$_SESSION["user"]["id"], $id]) ;
        $status = FriendManager::CANCELREQUEST;
        header("Location: ../profile.php?id=$id&status=$status");
        exit ;
    } catch (Exception $ex) {
       $error = true ;
    }
