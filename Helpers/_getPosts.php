<?php
    header("Content-Type: application/json") ;
    require("_auth.php");
    
    $data = ["result" => false];
    if(!isset($_POST["user_id"]) || !isset($_POST["last_post_id"]))
    {
        echo json_encode($data) ;
        return;
    }
    $user_id = $_POST["user_id"];
    if(!filter_var($user_id, FILTER_VALIDATE_INT))
    {
        echo json_encode($data) ;
        return;
    }
    $post_id = $_POST["last_post_id"];
    if(!filter_var($post_id, FILTER_VALIDATE_INT))
    {
        echo json_encode($data) ;
        return;
    }   
    require_once 'PostManager.php';
    require_once '_db.php';
    if(!isset($_POST["onlyUser"]))
        $posts = PostManager::Get10PostsOfFriendsOf($db, $user_id, $post_id);
    else
        $posts = PostManager::GetPostsOf($db, $user_id, $post_id);
        
    echo json_encode($posts) ;
    return;