<!DOCTYPE html>
<?php
    require("_auth.php");
    
    $error = "";
    $id = $_SESSION["user"]["id"];
    if(isset($_GET["id"]))
    {
        $id = $_GET["id"];
        if(!filter_var($id, FILTER_VALIDATE_INT))
        {
            $error = "Id must be an integer.";
        }
        if($error == "")
        {
            require_once './db.php';
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
        <?php require("_header.php"); ?>
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
                            if($id == $_SESSION["user"]["id"])
                            {
                                echo '<a href="editProfile.php"><div class="btn btn-success input-lg">Edit</div></a>';
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
