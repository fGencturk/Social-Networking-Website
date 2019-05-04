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
        <title>Facebook - The Social Network</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="assets/css/style.css"/>
        <link rel="stylesheet" href="assets/css/admin.css"/>
        <link href="assets/css/bootstrap.css" rel="stylesheet" type="text/css"/>
        <style>
            .box{
                background: rgba(255,255,255,1);
                padding: 10px 20px;
                border-radius: 2px;
                box-shadow: 0px 0px 15px 5px rgba(0,0,0,0.4);
            }
            #profilephoto{
                width:fit-content;
                margin:0 auto;
            }
            #profilephoto img {
                width:250px;
            }
        </style>
</script>
    </head>
    <body>
        <?php require("./Helpers/_header.php"); ?>
        <div class="main">
            <?php
                if($error != "")
                {
                    echo '<h1>' . $error . "</h1>";
                    exit;                    
                }
            ?>
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-3 left-sidebar">
                        <ul>
                            <li><a href="./settings.html" class="active">Settings</a></li>
                            <li><a href="./privacy.html">Privacy</a></li>
                            <li><a href="./index.html">Logout</a></li>
                        </ul>
                    </div>
                    <div class="col-sm-6">
                        <div id="profilephoto">
                            <img src="<?= $result["profile_photo"] ?>" >
                        </div>
                        <table style="width:100%" class="table table-striped">
                            <tr>
                                <td><strong>Name</strong></td>
                                <td><?= $result["name"] . " " . $result["surname"]?></td>
                            </tr>
                            <tr>
                                <td><strong>Birth Date</strong></td>
                                <td><?= $result["bdate"]?></td>
                            </tr>
                            <tr>
                                <td><strong>E-Mail</strong></td>
                                <td><?= $result["email"]?></td>
                            </tr>
                            <tr>
                                <td><strong>Gender</strong></td>
                                <td><?php if($result["gender"] == "M") echo "Male"; else echo "Female";?></td>
                            </tr>
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
                    <div class="col-sm-3 chat-users">
                        <div class="row">
                            <h3>Chat</h3>
                        </div>
                        <div class="row">
                            <div class="col-sm-12 chat-user online">
                                <a href="#">
                                    <img src="assets/imgs/1.jpg" class="pull-left"/>
                                    &nbsp;
                                    Shubham Kumar
                                </a>
                            </div>
                            <div class="col-sm-12 chat-user online">
                                <a href="#">
                                    <img src="assets/imgs/2.jpg" class="pull-left"/>
                                    &nbsp;
                                    Maninder Kaur
                                </a>
                            </div>
                            <div class="col-sm-12 chat-user online">
                                <a href="#">
                                    <img src="assets/imgs/3.jpg" class="pull-left"/>
                                    &nbsp;
                                    Divyanshu Gupta
                                </a>
                            </div>
                            <div class="col-sm-12 chat-user">
                                <a href="#">
                                    <img src="assets/imgs/4.jpg" class="pull-left"/>
                                    &nbsp;
                                    Akshima
                                </a>
                            </div>
                            <div class="col-sm-12 chat-user online">
                                <a href="#">
                                    <img src="assets/imgs/5.jpg" class="pull-left"/>
                                    &nbsp;
                                    Sourabh Thakur
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="footer no-shadow">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-12">
                        &copy; Facebook 2017.
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
