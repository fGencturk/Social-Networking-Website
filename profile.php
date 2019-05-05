<!DOCTYPE html>
<?php
    require("./Helpers/_auth.php");
    require_once './Helpers/FriendManager.php';
    
    $error = "";
    $id = $_SESSION["user"]["id"];
    $FRIENDSTATUS = FriendManager::USER;
    if(isset($_GET["id"]))
    {
        $id = $_GET["id"];
        if(!filter_var($id, FILTER_VALIDATE_INT))
        {
            $error = "Id must be an integer.";
        }
        if($error == "")
        {
            require_once './Helpers/_db.php';
            $sql = "select * from user where id = $id" ;
            $stmt = $db->query($sql, PDO::FETCH_ASSOC) ;
            $result = $stmt->fetchAll();
            $rowCount = $stmt->rowCount();
            if($rowCount == 0)
            {
                $error = "No profile is found with the given id.";
            }
            else
            {
                $result = $result[0];
                $FRIENDSTATUS = FriendManager::CheckFriendStatus($db, $_SESSION["user"]["id"], $id);
            }
        }
    }
    else
    {
        $result = $_SESSION["user"];
    }    
?>
<html>
    <head>
        <meta charset="UTF-8">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="css/bootstrap-grid.css" rel="stylesheet" type="text/css"/>
        <link href="css/bootstrap.css" rel="stylesheet" type="text/css"/>
        <style>
            .photo {
                width:60px;
                height:60px;
            }
            .postphoto {
                width:400px;
                height:400px;
                margin:0 auto;
            }
            .miniphoto {
                width:30px;
                height:30px;
            }
        </style>
    <title></title>
</head>
<body>
<div class="container-fluid">
    <?php require("./Helpers/_header.php"); ?>
    <div class="row display-3 text-center justify-content-center text-primary border border-info mt-5">
        <div class="col">
            <?php
                if($error != "")
                {
                    echo '<h1>' . $error . "</h1>";
                    exit;                    
                }
            ?>
            <?= $result["name"] . " " . $result["surname"]?>          
        </div>
    </div>
    <div class="row justify-content-center m-5">
        <div class="row">
            <div class="col">
                <img src="<?= $result["profile_photo"] ?>" class="postphoto">
            </div>
            <div class="col">
                <table class="table table-bordered table-primary text-center align-middle">
                    <tbody>
                      <tr>
                        <th class="align-middle" scope="row">Birth Date</th>
                        <td class="align-middle"><?= $result["bdate"]?></td>
                      </tr>
                      <tr>
                        <th class="align-middle" scope="row">E-mail</th>
                        <td class="align-middle"><?= $result["email"]?></td>
                      </tr>
                      <tr>
                        <th class="align-middle" scope="row">Gender</th>
                        <td class="align-middle"><?php if($result["gender"] == "M") echo "Male"; else echo "Female";?></td>
                      </tr>
                    </tbody>
                  </table>
                        <?php
                        
                            if(isset($_GET["status"]))
                            {
                                if($_GET["status"] == FriendManager::NOTFRIEND)
                                {
                                    echo '<div class="alert alert-light text-center" role="alert">Friend has been removed.</div>';
                                }
                                else if($_GET["status"] == FriendManager::ISFRIEND)
                                {
                                    echo '<div class="alert alert-light text-center" role="alert">You are friends now.</div>';                                  
                                }
                                else if($_GET["status"] == FriendManager::SENTREQUEST)
                                {
                                    echo '<div class="alert alert-light text-center" role="alert">Friend request has been sent.</div>';                                   
                                }
                                else if($_GET["status"] == FriendManager::CANCELREQUEST)
                                {
                                    echo '<div class="alert alert-light text-center" role="alert">Friend request has been cancelled.</div>';                                   
                                }
                                else if($_GET["status"] == FriendManager::REJECTREQUEST)
                                {
                                    echo '<div class="alert alert-light text-center" role="alert">Friend request has been rejected.</div>';                                   
                                }                                    
                            }
                            if($FRIENDSTATUS == FriendManager::USER)
                            {
                                echo '<a href="editProfile.php"><div class="btn btn-success btn-lg btn-block">Edit</div></a>';
                            }
                            else if($FRIENDSTATUS == FriendManager::NOTFRIEND)
                            {
                                echo '<div  class="alert alert-dark text-center" role="alert">You are not friends.</div>';
                                echo '<a href="./Helpers/_requestSend.php?id='. $id .'"><div class="btn btn-success btn-lg btn-block">Add Friend</div></a>';
                            }
                            else if($FRIENDSTATUS == FriendManager::ISFRIEND)
                            {
                                echo '<div  class="alert alert-success text-center">You are friends.</div>';
                                echo '<a href="./Helpers/_removeFriend.php?id='. $id .'"><div class="btn btn-danger input-lg btn-block">Remove Friend</div></a>';
                            }
                            else if($FRIENDSTATUS == FriendManager::RECEIVEDREQUEST)
                            {
                                echo '<div  class="alert alert-success text-center">Received friend request.</div>';
                                echo '<a href="./Helpers/_requestAccept.php?id='. $id .'"><div class="btn btn-success btn-lg btn-block">Accept Request</div></a>';
                                echo '<a href="./Helpers/_requestReject.php?id='. $id .'"><div class="btn btn-danger input-lg btn-block">Reject Request</div></a>';
                            }
                            else
                            {
                                echo '<div  class="alert alert-warning text-center" role="alert">Pending request.</div>';
                                echo '<a href="./Helpers/_requestCancel.php?id='. $id .'"><div class="btn btn-danger input-lg btn-block">Cancel Request</div></a>';
                            }
                        ?>
            </div>
            <div class="col">
                <?php
                    if($FRIENDSTATUS == FriendManager::ISFRIEND || $FRIENDSTATUS == FriendManager::USER)
                    {
                        require_once './Helpers/_db.php';
                        echo '<div class="h3 text-center">Friends</div>';
                        $friends = FriendManager::GetFriends($db, $id);
                        foreach($friends as $friend)
                        {
                            echo '<a href="profile.php?id='. $friend["id"] .'">';
                            echo '<div class="row m-3"><div class="col-3">';
                            echo '<img src="'. $friend["profile_photo"] .'" class="miniphoto"/>';
                            echo '</div><div class="col text-left">' . $friend["name"] . " " . $friend["surname"];
                            echo '</div></div></a>';
                        }                                    
                    }
                    else
                    {
                        echo '<div class="h3 text-center">You are not allowed to see friends.</div>';

                    }

                ?>
            </div>
        </div>
    </div>
    <div class="row display-3 text-center justify-content-center text-primary border border-info">
        <div class="col">POSTS            
        </div>
    </div>
    <?php
        require_once './Helpers/PostManager.php';
        $posts = PostManager::GetPostsOf($db, $id);
        foreach($posts as $post)
        {
            echo '<div class="row justify-content-center ">';
            echo '<div class="col-6 mt-lg-5 alert alert-dark border">';
            echo '<a href="profile.php?id='. $result["id"] .'">';
            echo '<div class="row m-3"><div class="col-1"><img src="'. $result["profile_photo"] .'" class="photo"/></div>';
            echo '<div class="col text-left">'. $result["name"] . ' ' . $result["surname"] .'</a><br>';
            echo $post["date"] . '</div></div>';
            if($post["photo"] != "")
            {
                echo '<div class="row"><img src="' . $post["photo"] . '" class="postphoto"></div>';
            }
            echo '<div class="row"><div class="col p-3  alert alert-primary m-5 text-justify"">';
            echo $post["text"];
            echo '</div></div>';
            echo '<div class="row">';
            echo '<ul class="list-inline mr-auto ml-auto mb-3">';
            echo '<li class="list-inline-item">Like</li>';
            echo '<li class="list-inline-item">Dislike</li>';
            echo '<li class="list-inline-item">Dislike</li>';
            echo '<li class="list-inline-item">Comment</li>';
            echo '<li class="list-inline-item">X Dislikes</li>';
            echo '<li class="list-inline-item">X Comments</li>';
            echo '</ul></div></div></div>';
        }
    ?>
</div>

</body>
</html>
