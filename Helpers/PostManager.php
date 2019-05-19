<?php
class PostManager {
    
    public static function GetPostsOf($db, $id, $last_post_id, $active_user)
    {        
        try{
            if($last_post_id != -1)
                $sql = "select * from post where user_id = $id and id < $last_post_id order by date desc limit 0,10";
            else
                $sql = "select * from post where user_id = $id order by date desc limit 0,10";
                
            $posts = $db->query($sql, PDO::FETCH_ASSOC)->fetchAll();
            $sql = "select id, name, surname, profile_photo from user where id = $id";
            $photos = array_map('reset', $db->query($sql, PDO::FETCH_ASSOC)->fetchAll(PDO::FETCH_GROUP|PDO::FETCH_ASSOC));
            $sql = "select post_id, count(*) plike from plike where type = 1 group by post_id";
            $likes = array_map('reset', $db->query($sql, PDO::FETCH_ASSOC)->fetchAll(PDO::FETCH_GROUP|PDO::FETCH_ASSOC));
            $sql = "select post_id, count(*) dislike from plike where type = -1 group by post_id";
            $dislikes = array_map('reset', $db->query($sql, PDO::FETCH_ASSOC)->fetchAll(PDO::FETCH_GROUP|PDO::FETCH_ASSOC));
            $sql = "select post_id, type from plike where user_id = $active_user";
            $isLiked = array_map('reset', $db->query($sql, PDO::FETCH_ASSOC)->fetchAll(PDO::FETCH_GROUP|PDO::FETCH_ASSOC));
            for($i = 0; $i < count($posts); $i++)
            {
                $posts[$i]["user"] = $photos[$posts[$i]["user_id"]];
                $posts[$i]["likecount"] = isset($likes[$posts[$i]["id"]]) ? $likes[$posts[$i]["id"]]["plike"] : 0;
                $posts[$i]["dislikecount"] = isset($dislikes[$posts[$i]["id"]]) ? $dislikes[$posts[$i]["id"]]["dislike"] : 0;
                $posts[$i]["isLiked"] = isset($isLiked[$posts[$i]["id"]]) ? $isLiked[$posts[$i]["id"]]["type"] : 0;
                $sql = "select user_id,profile_photo,name,surname,text from comment,user where user_id = user.id and post_id = " . $posts[$i]["id"];
                $posts[$i]["comments"] = $db->query($sql, PDO::FETCH_ASSOC)->fetchAll();
            }
            return $posts;
        } catch (Exception $ex) {
            return array();
        }        
    } 

    
    public static function Get10PostsOfFriendsOf($db, $id, $last_post_id)
    {
        require_once __DIR__ . '/FriendManager.php';
        $friends = FriendManager::GetFriends($db, $id);
        $userList = "(";
        foreach($friends as $friend)
        {
            $userList .= $friend["id"] . ',';
        }
        $userList .= $id . ')';
        
        try{
            if($last_post_id != -1)
                $sql = "select * from post where user_id in $userList and id < $last_post_id order by date desc limit 0,10";
            else
                $sql = "select * from post where user_id in $userList order by date desc limit 0,10";
                
            $posts = $db->query($sql, PDO::FETCH_ASSOC)->fetchAll();
            $sql = "select id, name, surname, profile_photo from user where id in $userList";
            $photos = array_map('reset', $db->query($sql, PDO::FETCH_ASSOC)->fetchAll(PDO::FETCH_GROUP|PDO::FETCH_ASSOC));
            $sql = "select post_id, count(*) plike from plike where type = 1 group by post_id";
            $likes = array_map('reset', $db->query($sql, PDO::FETCH_ASSOC)->fetchAll(PDO::FETCH_GROUP|PDO::FETCH_ASSOC));
            $sql = "select post_id, count(*) dislike from plike where type = -1 group by post_id";
            $dislikes = array_map('reset', $db->query($sql, PDO::FETCH_ASSOC)->fetchAll(PDO::FETCH_GROUP|PDO::FETCH_ASSOC));
            $sql = "select post_id, type from plike where user_id = $id";
            $isLiked = array_map('reset', $db->query($sql, PDO::FETCH_ASSOC)->fetchAll(PDO::FETCH_GROUP|PDO::FETCH_ASSOC));
            for($i = 0; $i < count($posts); $i++)
            {
                $posts[$i]["user"] = $photos[$posts[$i]["user_id"]];
                $posts[$i]["likecount"] = isset($likes[$posts[$i]["id"]]) ? $likes[$posts[$i]["id"]]["plike"] : 0;
                $posts[$i]["dislikecount"] = isset($dislikes[$posts[$i]["id"]]) ? $dislikes[$posts[$i]["id"]]["dislike"] : 0;
                $posts[$i]["isLiked"] = isset($isLiked[$posts[$i]["id"]]) ? $isLiked[$posts[$i]["id"]]["type"] : 0;
                $sql = "select user_id,profile_photo,name,surname,text from comment,user where user_id = user.id and post_id = " . $posts[$i]["id"];
                $posts[$i]["comments"] = $db->query($sql, PDO::FETCH_ASSOC)->fetchAll();
            }
            return $posts;
        } catch (Exception $ex) {
            return array();
        }        
    }    
    
    public static function GetPost($db, $id, $user_id)
    {
        try {
            $sql = "select post.id,text,photo,date,name,surname,profile_photo,user_id,date from post, user where post.id = $id and user_id = user.id";
            $post = $db->query($sql, PDO::FETCH_ASSOC)->fetchAll();
            if(count($post) == 0)
                return null;
            $post = $post[0];
            $sql = "select user_id,name, surname, profile_photo, comment.text from user,comment where post_id = $id and user.id = user_id";
            $post["comments"] = $db->query($sql, PDO::FETCH_ASSOC)->fetchAll();
            $sql = "select post_id, type from plike where user_id = $user_id and post_id = $id";
            $post["isLiked"] = $db->query($sql, PDO::FETCH_ASSOC)->fetchAll();
            $sql = "select count(*) cnt from plike where post_id = $id and type = 1";
            $post["likecount"] = $db->query($sql, PDO::FETCH_ASSOC)->fetchAll()[0]["cnt"];
            $sql = "select count(*) cnt from plike where post_id = $id and type = -1";
            $post["dislikecount"] = $db->query($sql, PDO::FETCH_ASSOC)->fetchAll()[0]["cnt"];
            if(count($post["isLiked"]) == 0)
                $post["isLiked"] = 0;
            else
                $post["isLiked"] = $post["isLiked"][0]["type"];
            return $post;
            
            
            
        } catch (Exception $ex) {
            return null;
        }
    }
}