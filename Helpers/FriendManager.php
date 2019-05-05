<?php
class FriendManager
{
    const NOTFRIEND = 0;
    const ISFRIEND = 1;
    const USER = 2;
    const SENTREQUEST = 3;
    const RECEIVEDREQUEST = 4;
    
    const CANCELREQUEST = 5;
    const REJECTREQUEST = 6;
    
    public static function CheckFriendStatus($db,$id1, $id2)
    {
        if($id1 == $id2)
            return FriendManager::USER;
        
        $sql = "select * from friend where (sender_id = $id1 and receiver_id = $id2) or (sender_id = $id2 and receiver_id = $id1)";
        $rowCount = $db->query($sql, PDO::FETCH_ASSOC)->rowCount() ;
        if($rowCount != 0)
            return FriendManager::ISFRIEND;
        
        $sql = "select * from friend_request where (sender_id = $id1 and receiver_id = $id2)";
        $rowCount = $db->query($sql, PDO::FETCH_ASSOC)->rowCount() ;
        if($rowCount != 0)
            return FriendManager::SENTREQUEST;
        
        $sql = "select * from friend_request where (sender_id = $id2 and receiver_id = $id1)";
        $rowCount = $db->query($sql, PDO::FETCH_ASSOC)->rowCount() ;
        if($rowCount != 0)
            return FriendManager::RECEIVEDREQUEST;
        
        return FriendManager::NOTFRIEND;       
    }
    
    public static function GetFriends($db, $id)
    {
        try {
            $sql1 = "select receiver_id id from friend where sender_id = $id";
            $sql2 = "select sender_id id from friend where receiver_id = $id";
            $friends = array_merge($db->query($sql1, PDO::FETCH_ASSOC)->fetchAll() , $db->query($sql2, PDO::FETCH_ASSOC)->fetchAll());
            $friendList = "(";
            foreach($friends as $friend)
            {
                $friendList .= $friend["id"] . ',';
            }
            $friendList .= '0)';
            $sql = "select * from user where id in $friendList";
            return ($db->query($sql, PDO::FETCH_ASSOC)->fetchAll());
            
        } catch (Exception $ex) {
            return array();
        }
    }
}