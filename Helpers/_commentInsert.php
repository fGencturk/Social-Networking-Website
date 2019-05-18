<?php
    header("Content-Type: application/json") ;
    require("_auth.php");
    
    $data = ["result" => false];
    
    
    if(!isset($_POST["post_id"]) || !isset($_POST["text"]))
    {
        echo json_encode($data) ;
        return;
    }    
    $user_id = $_SESSION["user"]["id"];
    if(!filter_var($user_id, FILTER_VALIDATE_INT))
    {
        echo json_encode($data) ;
        return;
    }    
    $post_id = $_POST["post_id"];
    if(!filter_var($post_id, FILTER_VALIDATE_INT))
    {
        echo json_encode($data) ;
        return;
    }
    $text = filter_var($_POST["text"], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $data["result"] = true;
    $data["post_id"] = $post_id;
    $data["text"] = $text;
    require_once '_db.php';
    $sql = "insert into comment(user_id,post_id,text) values(?, ?, ?)";
    $stmt = $db->prepare($sql) ;
    $stmt->execute( [$user_id, $post_id, $text]) ;    
    echo json_encode($data);    
    return;