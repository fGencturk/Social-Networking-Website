<!DOCTYPE html>
<?php
    require("./Helpers/_auth.php");
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Facebook - The Social Network</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="assets/css/style.css"/>
        <link rel="stylesheet" href="assets/css/admin.css"/>
        <style>
            .box{
                background: rgba(255,255,255,1);
                padding: 10px 20px;
                border-radius: 2px;
                box-shadow: 0px 0px 15px 5px rgba(0,0,0,0.4);
            }
            #profile_photo {
                width:200px;
            }
        </style>
    </head>
    <body>
        <?php require("./Helpers/_header.php"); ?>
        <div class="main">
            <div class="container-fluid">
                <div class="col-sm-3 left-sidebar">
                    <img src="<?= $_SESSION["user"]["profile_photo"]?>" id="profile_photo">
                     <br><?= $_SESSION["user"]["name"] . " " . $_SESSION["user"]["surname"];?>
                     <?php
                     var_dump($_SESSION);
                     ?>
                    
                </div>
                
                <div class="col-sm-6">
                    <?php
                        #TODO retrieve data from database
                        echo '<div class="post col-sm-12" id="post_1">
                        <div class="row post-heading">
                            <div class="col-sm-12">
                                <a href="profile.html">
                                    <img src="assets/imgs/2.jpg" class="profile-picture pull-left"/>
                                    &nbsp;
                                    <span class="post-user-name">Maninder Kaur</span><br/>
                                    &nbsp;
                                    <small class="post-date text-mute">31th March, 2017 2:49PM</small>
                                </a>
                            </div>
                        </div>
                        <div class="row post-body">
                            <div class="col-sm-12">
                                This is the post body. Lorem Ipsum Doler sit. Lorem Ipsum Doler sit. Lorem Ipsum Doler sit. Lorem Ipsum Doler sit.
                            </div>
                        </div>
                        <div class="row post-action">
                            <ul class="post-action-menu">
                                <li><a href="javascript:void(0);" class="text-mute" onclick="like(1);">Like</a></li>
                                <li><a href="javascript:void(0);" class="text-mute" onclick="share(1);">Share</a></li>
                                <li><a href="javascript:void(0);" class="text-mute" onclick="comment(1);">Comment</a></li>
                                <li class="pull-right"><a href="#" class="text-mute"><span id="post_like_count_1">2142</span> Likes</a></li>
                                <li class="pull-right"><a href="#" class="text-mute"><span id="post_comment_count_1">2172</span> Comments</a></li>
                                <li class="pull-right"><a href="#" class="text-mute"><span id="post_share_count_1">200</span> Shares</a></li>
                            </ul>
                        </div>
                    </div>';
                    ?>
                </div>
                <div class="col-sm-3 chat-users">
                    
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