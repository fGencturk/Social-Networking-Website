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
        <script src="js/jquery-3.4.1.min.js" type="text/javascript"></script>
        <script>
            $(function(){
                var lastPostId = -1;
                var isFetching = false;
                
                var datap = new Object();                    
                datap.user_id = <?=$result["id"]?>;
                datap.last_post_id = lastPostId;
                datap.onlyUser = true;
                isFetching = true;
                PostHandler(datap);
                $(window).scroll(function() {
                    if(isFetching)
                        return;
                   if($(window).scrollTop() + $(window).height() > $(document).height() - 100) {   
                        var datap = new Object();                    
                        datap.user_id = <?=$result["id"]?>;
                        datap.last_post_id = lastPostId;
                        datap.onlyUser = true;
                        
                        console.log("bottom");
                        isFetching = true;
                        PostHandler(datap);
                   }
                });
                
                function CommentHandler(datap)
                {
                    $.ajax({
                        type: "POST",
                        url: "./Helpers/_commentInsert.php",
                        data: datap,
                        success: function(x){
                            console.log(x);
                            var html = "";
                            html += '<div class="row"><div class="col pl-3 pr-3  alert alert-dark mr-5 ml-5 text-justify">';
                            html += '<img class="miniphoto" src="<?= $_SESSION["user"]["profile_photo"]?>"><a href="profile.php?id=<?=$result["id"]?>"><?=$_SESSION["user"]["name"]?> <?=$_SESSION["user"]["surname"]?></a> : <span class="commentText">' + x["text"] +'</span>';
                            html += '</div></div>';
                            $("#comments" + x["post_id"]).find(">div:last-child").after(html);
                            var text = $("#p" + x["post_id"]).find("textarea").val("");
                            
                        }
                    });                    
                }
                
                function PostHandler(datap){
                    $.ajax({
                        type: "POST",
                        url: "./Helpers/_getPosts.php",
                        data: datap,
                        success: function(x){
                            console.log(x);
                            CreatePosts(x);
                            isFetching = false;
                        }
                    });
                }
                
                function LikeHandler(datap) {
                    $.ajax({
                        type: "POST",
                        url: "./Helpers/_likeHandler.php",
                        data: datap,
                        success: function(x){
                            $("#" + datap.post_id + " .likecount").html(x.likecount + " Likes");
                            $("#" + datap.post_id + " .dislikecount").html(x.dislikecount + " Dislikes");
                        }
                    });
                }
                
                function CreatePosts(posts)
                {
                    var html = "";
                    
                    for (var key in posts) {
                        if (!posts.hasOwnProperty(key)) continue;

                        var obj = posts[key];
                        html += PostHTML(obj);
                        lastPostId = obj["id"];
                    }
                    if(html != "")
                        $("#container > div:last-child").after(html);
                    else
                    {   
                        $("#container > div:last-child").after('<div class="row justify-content-center display-3 text-primary border border-info mb-5"><div class="col text-center">NO MORE POSTS</div></div>')
                        $(window).unbind();                        
                    }
                        
                    $(".like").unbind();
                    $(".like").click(function(){
                        var post_id = parseInt($(this).attr("value"));
                        var type = parseInt($(this).attr("type"));
                        console.log(post_id + "  " + type);
                        if($(this).find("li").hasClass("checked"))
                        {
                            var datap = new Object();
                            datap.user_id = <?=$result["id"]?>;
                            datap.post_id = post_id;
                            datap.type = type;
                            datap.deleteOnly = true;
                            LikeHandler(datap);                            
                            $(this).find("li").removeClass("checked");
                        }
                        else
                        {
                            var datap = new Object();
                            datap.user_id = <?=$result["id"]?>;
                            datap.post_id = post_id;
                            datap.type = type;
                            LikeHandler(datap);
                            var thisClass = $(this).attr("class").replace(" ", ".");
                            $("." + thisClass).find("li").removeClass("checked");
                            $(this).find("li").addClass("checked");
                        }
                    });
                    $(".comment").unbind();
                    $(".comment").click(function(){   
                        var datap = new Object();
                        datap.post_id = parseInt($(this).attr("value"));
                        var text = $("#p" + datap.post_id).find("textarea").val();
                        if(text == "")
                            return;
                        datap.user_id = <?=$result["id"]?>;
                        datap.text = text;
                        CommentHandler(datap);
                    });
                    
                }
                
                function PostHTML(post)
                {
                    var html = "";
                    html += '<div class="row justify-content-center" id="'+post["id"]+'">';
                    html += '<div class="col-6 mt-lg-5 alert alert-dark border">';
                    html += '<a href="profile+php?id='+ post["user_id"] +'">';
                    html += '<div class="row m-3"><div class="col-1"><img src="'+ post["user"]["profile_photo"] +'" class="photo"/></div>';
                    html += '<div class="col text-left">'+ post["user"]["name"] + ' ' + post["user"]["surname"] +'</a><br>';
                    html += post["date"] + '</div></div>';
                    if(post["photo"] != "")
                    {
                        html += '<div class="row"><img src="' + post["photo"] + '" class="postphoto"></div>';
                    }
                    html += '<div class="row"><div class="col p-3  alert alert-primary m-5 text-justify">';
                    html += post["text"];
                    html += '</div></div>';
                    html += '<div class="row">';
                    html += '<ul class="list-inline mr-auto ml-auto mb-3">';
                    like = post["isLiked"] == 1 ? " checked" : "";
                    dislike = post["isLiked"] == -1 ? " checked" : "";
                    html += '<button type="1" class="like '+ post["id"] +'" value="'+ post["id"] +'"><li class="list-inline-item '+ like +'">Like</li></button>';
                    html += '<button type="-1" class="like '+ post["id"] +'" value="'+ post["id"] +'"><li class="list-inline-item '+ dislike +'">Dislike</li></button>';
                    html += '<button class="likecount" value="'+ post["id"] +'"><li class="list-inline-item">'+ post["likecount"] +' Likes</li></button>';
                    html += '<button class="dislikecount" value="'+ post["id"] +'"><li class="list-inline-item">'+ post["dislikecount"] +' Dislikes</li></button>';
                    html += '</ul></div>';
                    html += '<div class="row mb-2"><div class="col text-center text-primary border border-info">Comments</div></div><div id="comments' + post["id"] + '"><div></div>';
                    
                                        
                    for (var key in post["comments"]) {
                        if (!post["comments"].hasOwnProperty(key)) continue;

                        var obj = post["comments"][key];
                        html += '<div class="row"><div class="col pl-3 pr-3  alert alert-dark mr-5 ml-5 text-justify">';
                        html += '<img class="miniphoto" src="'+ obj["profile_photo"] + '"><a href="profile.php?id='+ obj["user_id"] +'"> '+ obj["name"] + ' ' + obj["surname"] +'</a> : <span class="commentText">' + obj["text"] +'</span>';
                        html += '</div></div>';
                    }
                    
                    
                    html += '</div><div class="row"><div class="col pl-3 pr-3  alert alert-dark ml-5 mr-5 text-justify" id="p'+ post["id"] +'">';
                    html += '<textarea class="form-control" name="text"></textarea><button class="form-control btn btn-primary ml-0 comment" value="'+ post["id"] +'">Comment</button>';
                    html += '</div></div>';
                    html += '</div></div>';
                    return html;
                }
            });

        </script>
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
<div class="container-fluid" id="container">
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
</div>

</body>
</html>
