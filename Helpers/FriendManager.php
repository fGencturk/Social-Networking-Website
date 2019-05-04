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
        
    }
}