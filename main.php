<!DOCTYPE html>
<?php
    require("./Helpers/_auth.php");
    require_once './Helpers/_db.php';
    $postText = "";
    
    if(isset($_POST["btnPost"]))
    {
        require_once './Helpers/ImageManager.php';
        $result = ImageManager::ProcessInputImage("p_image", "images/post/");
        $postText = filter_var($_POST["postText"], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if($result["error"] == 0 || $result["error"] == 1)//succesfully uploaded or not selected an image
        {
            $result["filepath"] = $result["error"] == 0 ? $result["filepath"] : "";
            try {
                require_once './Helpers/_db.php';
                $stmt = $db->prepare("insert into post (user_id, text, photo) values (?,?,?)") ;
                $stmt->execute( [$_SESSION["user"]["id"],$postText, $result["filepath"]]) ;
                header("Location: main.php?newPost");
                exit ;
            } catch (Exception $ex) {
               $error = "An error occurred in the database." ;
            }  
        }
        else
        {   
            $error = ImageManager::GetErrorString($result["error"]);
        }
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
                datap.last_post_id = lastPostId;
                isFetching = true;
                PostHandler(datap);
                $(window).scroll(function() {
                    if(isFetching)
                        return;
                   if($(window).scrollTop() + $(window).height() > $(document).height() - 100) {   
                        var datap = new Object();
                        datap.last_post_id = lastPostId;
                        
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
                            html += '<img class="miniphoto" src="<?= $_SESSION["user"]["profile_photo"]?>"><a href="profile.php?id=<?=$_SESSION["user"]["id"]?>"><?=$_SESSION["user"]["name"]?> <?=$_SESSION["user"]["surname"]?></a> : <span class="commentText">' + x["text"] +'</span>';
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
                            datap.user_id = <?=$_SESSION["user"]["id"]?>;
                            datap.post_id = post_id;
                            datap.type = type;
                            datap.deleteOnly = true;
                            LikeHandler(datap);                            
                            $(this).find("li").removeClass("checked");
                        }
                        else
                        {
                            var datap = new Object();
                            datap.user_id = <?=$_SESSION["user"]["id"]?>;
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
                        datap.user_id = <?=$_SESSION["user"]["id"]?>;
                        datap.text = text;
                        CommentHandler(datap);
                    });
                    
                }
                
                function PostHTML(post)
                {
                    var html = "";
                    html += '<div class="row justify-content-center" id="'+post["id"]+'">';
                    html += '<div class="col-6 mt-lg-5 alert alert-dark border">';
                    html += '<a href="profile.php?id='+ post["user_id"] +'">';
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
            button {
                margin:0 5px;
            }
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
            .checked {
                font-weight:bold;
                color:blue;
            }
            .commentText {
                margin-left:10px;
                font-size:14px;
            }
        </style>
    <title></title>
</head>
<body>
<div class="container-fluid" id="container">
    <?php require("./Helpers/_header.php"); ?>
    <div class="row justify-content-center">
        <div class="col-6 mt-lg-5 bg-light border">
            <form action="" method="post" enctype="multipart/form-data">
                <div class="form-group">
                    <div class="display-4 text-center text-primary m-3">Share something with your friends.</div>
                    <textarea class="form-control" id="exampleFormControlTextarea1" rows="3" name="postText"><?= $postText ?></textarea>
                </div>
                <div class="form-group">
                    Upload a Picture(Optional) : <input type="file" name="p_image" >
                </div>
                <input class="btn btn-primary input-lg btn-block" type="submit" name="btnPost" value="Post">
                <?php
                    if(isset($_GET["newPost"]))
                    {
                        echo '<div class="alert alert-success text-center" role="alert">Post has been shared.</div>';
                    }
                    if(isset($error))
                    {
                        echo '<div class="alert alert-danger text-center" role="alert">'. $error .'.</div>';
                    }
                ?>
            </form>       
        </div>
    </div>
</div>
</body>
</html>
