<?php
class PostManager {
    
    public static function GetPostsOf($db, $id)
    {
        try{
            $sql = "select * from post where user_id = $id order by date desc";
            return $db->query($sql, PDO::FETCH_ASSOC)->fetchAll();
        } catch (Exception $ex) {
            return array();
        }
    }
    
    public static function GetPostsOfFriendsOf($db, $id)
    {
        require_once './Helpers/FriendManager.php';
        $friends = FriendManager::GetFriends($db, $id);
        $userList = "(";
        foreach($friends as $friend)
        {
            $userList .= $friend["id"] . ',';
        }
        $userList .= $id . ')';
        
        try{
            $sql = "select * from post where user_id in $userList order by date desc";
            $posts = $db->query($sql, PDO::FETCH_ASSOC)->fetchAll();
            $sql = "select id, name, surname, profile_photo from user where id in $userList";
            $photos = array_map('reset', $db->query($sql, PDO::FETCH_ASSOC)->fetchAll(PDO::FETCH_GROUP|PDO::FETCH_ASSOC));
            for($i = 0; $i < count($posts); $i++)
            {
                $posts[$i]["user"] = $photos[$posts[$i]["user_id"]];
            }
            return $posts;
        } catch (Exception $ex) {
            return array();
        }       
    }
    
}