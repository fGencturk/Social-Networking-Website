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
        $stmt = $db->prepare("insert into friend_request values (?,?)") ;
        $stmt->execute( [$_SESSION["user"]["id"], $id]) ;
        $status = FriendManager::SENTREQUEST;
        $sql = "insert into notification(text,link,receiver_id) values('". $_SESSION["user"]["name"] ." ". $_SESSION["user"]["surname"] ." sent friend request.', 'profile.php?id=". $_SESSION["user"]["id"] ."', $id)";
        $stmt = $db->prepare($sql) ;
        $stmt->execute();    
        header("Location: ../profile.php?id=$id&status=$status");
        exit ;
    } catch (Exception $ex) {
       $error = true ;
    }