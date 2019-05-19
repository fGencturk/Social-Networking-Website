<?php
    header("Content-Type: application/json") ;
    require("_auth.php");
    $data = ["result" => false];
    if(!isset($_POST["post_id"]) || !isset($_POST["type"]))
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
    $type = $_POST["type"];
    if(!filter_var($type, FILTER_VALIDATE_INT))
    {
        echo json_encode($data) ;
        return;
    }
    if($type != 1 && $type != -1)
    {
        echo json_encode($data) ;
        return;
    }
    $isDeleteOnly = isset($_POST["deleteOnly"]) ? true : false;
    
    
    require_once '_db.php';
    $sql = "delete from plike where user_id = $user_id and post_id = $post_id";
    $stmt = $db->prepare($sql);
    $stmt->execute();
    if(!$isDeleteOnly)
    {
        $sql = "insert into plike(user_id,post_id,type) values($user_id,$post_id,$type)";
        $stmt = $db->prepare($sql);
        $stmt->execute();
        $typetext = $type == 1 ? " liked " : " disliked ";
        $sql = "select user_id from post where id = $post_id";
        $receiver_id = $db->query($sql, PDO::FETCH_ASSOC)->fetchAll()[0]["user_id"];
        $sql = "insert into notification(text,link,receiver_id) values('". $_SESSION["user"]["name"] ." ". $_SESSION["user"]["surname"] . $typetext ."your post.', 'post.php?id=". $post_id ."', $receiver_id)";
        $stmt = $db->prepare($sql) ;
        $stmt->execute();        
    }
    $sql = "select count(*) plike from plike where post_id = $post_id and type=1";
    $like = $db->query($sql, PDO::FETCH_ASSOC)->fetchAll();
    $sql = "select count(*) plike from plike where post_id = $post_id and type=-1";
    $dislike = $db->query($sql, PDO::FETCH_ASSOC)->fetchAll();
    $data = ["result" => true,
            "likecount" => $like[0]["plike"],
            "dislikecount" => $dislike[0]["plike"]];
    
    echo json_encode($data) ;
    return;