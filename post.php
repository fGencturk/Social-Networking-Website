<!DOCTYPE html>
<?php
    require("./Helpers/_auth.php");
    require_once './Helpers/_db.php';
    require_once './Helpers/PostManager.php';
    
    if(!isset($_GET["id"]))
    {
        header("Location: main.php");
        exit;        
    }
    $id = $_GET["id"];
    if(!filter_var($id, FILTER_VALIDATE_INT))
    {
        header("Location: main.php");
        exit;         
    }
    
    $post = PostManager::GetPost($db, $id, $_SESSION["user"]["id"]);
    
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
                $(".like").click(function(){
                    var post_id = parseInt($(this).attr("value"));
                    var type = parseInt($(this).attr("type"));
                    console.log(post_id + "  " + type);
                    if($(this).find("li").hasClass("checked"))
                    {
                        var datap = new Object();
                        datap.post_id = post_id;
                        datap.type = type;
                        datap.deleteOnly = true;
                        LikeHandler(datap);                            
                        $(this).find("li").removeClass("checked");
                    }
                    else
                    {
                        var datap = new Object();
                        datap.post_id = post_id;
                        datap.type = type;
                        LikeHandler(datap);
                        var thisClass = $(this).attr("class").replace(" ", ".");
                        $("." + thisClass).find("li").removeClass("checked");
                        $(this).find("li").addClass("checked");
                    }
                });
                $(".comment").click(function(){   
                    var datap = new Object();
                    datap.post_id = parseInt($(this).attr("value"));
                    var text = $("#p" + datap.post_id).find("textarea").val();
                    if(text == "")
                        return;
                    datap.text = text;
                    CommentHandler(datap);
                });
                
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
    <?php
        if(!isset($post["id"]))
        {
            echo 'NO POST FOUND!';
            exit;
        }
            echo  '<div class="row justify-content-center" id="'. $post["id"]. '">';
            echo  '<div class="col-6 mt-lg-5 alert alert-dark border">';
            echo '<a href="profile.php?id='. $post["user_id"] .'">';
            echo '<div class="row m-3"><div class="col-1"><img src="'. $post["profile_photo"] .'" class="photo"/></div>';
            echo '<div class="col text-left">'. $post["name"] . ' ' . $post["surname"] .'</a><br>';
            echo $post["date"] . '</div></div>';
            if($post["photo"] != "")
            {
                echo '<div class="row"><img src="' . $post["photo"] . '" class="postphoto"></div>';
            }
            echo '<div class="row"><div class="col p-3  alert alert-primary m-5 text-justify">';
            echo  $post["text"];
            echo '</div></div>';
            echo '<div class="row">';
            echo '<ul class="list-inline mr-auto ml-auto mb-3">';
            $like = $post["isLiked"] == 1 ? " checked" : "";
            $dislike = $post["isLiked"] == -1 ? " checked" : "";
            echo '<button type="1" class="like '. $post["id"] .'" value="'. $post["id"] .'"><li class="list-inline-item '. $like .'">Like</li></button>';
            echo '<button type="-1" class="like '. $post["id"] .'" value="'. $post["id"] .'"><li class="list-inline-item '. $dislike .'">Dislike</li></button>';
            echo '<button class="likecount" value="'. $post["id"] .'"><li class="list-inline-item">'. $post["likecount"] .' Likes</li></button>';
            echo '<button class="dislikecount" value="'. $post["id"] .'"><li class="list-inline-item">'. $post["dislikecount"] .' Dislikes</li></button>';
            echo '</ul></div>';
            echo '<div class="row mb-2"><div class="col text-center text-primary border border-info">Comments</div></div><div id="comments' . $post["id"] . '"><div></div>';


            foreach ($post["comments"] as $obj) {
                echo '<div class="row"><div class="col pl-3 pr-3  alert alert-dark mr-5 ml-5 text-justify">';
                echo '<img class="miniphoto" src="'. $obj["profile_photo"] . '"><a href="profile.php?id='. $obj["user_id"] .'"> '. $obj["name"] . ' ' . $obj["surname"] .'</a> : <span class="commentText">' . $obj["text"] .'</span>';
                echo '</div></div>';
            }


            echo '</div><div class="row"><div class="col pl-3 pr-3  alert alert-dark ml-5 mr-5 text-justify" id="p'. $post["id"] .'">';
            echo '<textarea class="form-control" name="text"></textarea><button class="form-control btn btn-primary ml-0 comment" value="'. $post["id"] .'">Comment</button>';
            echo '</div></div>';
            echo '</div></div>';
    ?>
    
</div>
</body>
</html>
