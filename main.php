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
               $error = true ;
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
    <?php
        require_once './Helpers/PostManager.php';
        $posts = PostManager::GetPostsOfFriendsOf($db, $_SESSION["user"]["id"]);
        foreach($posts as $post)
        {
            echo '<div class="row justify-content-center ">';
            echo '<div class="col-6 mt-lg-5 alert alert-dark border">';
            echo '<a href="profile.php?id='. $post["user_id"] .'">';
            echo '<div class="row m-3"><div class="col-1"><img src="'. $post["user"]["profile_photo"] .'" class="photo"/></div>';
            echo '<div class="col text-left">'. $post["user"]["name"] . ' ' . $post["user"]["surname"] .'</a><br>';
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
