<!DOCTYPE html>
<?php
    require("./Helpers/_auth.php");
    if(!isset($_POST["btnSearch"]))
    {
        header("Location: main.php");
    }
    $input = filter_var($_POST["fullname"], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $names = explode(" ", $input);
    $condition = "";
    $i = 0;
    foreach($names as $name)
    {
        if($i != 0)
            $condition .= " or ";
        $condition .= "name = '" . $name . "' or surname = '" . $name . "'";
        $i++;
    }
    require_once './Helpers/_db.php';
    $sql = "select * from user where $condition";
    $users = $db->query($sql, PDO::FETCH_ASSOC)->fetchAll();   
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
    <?php
        if(count($users) == 0)
            echo '<div class="row justify-content-center display-3 text-primary border border-info mb-5"><div class="col text-center">NO USER FOUND</div></div>';
        foreach($users as $user)
        {
            echo '<div class="row justify-content-center">';
            echo '<div class="col-6 mt-lg-5 bg-light border">';
            echo '<a href="profile.php?id='. $user["id"] .'">';
            echo '<div class="row m-3">';
            echo '<div class="col-3">';
            echo '';
            echo '';
            echo '';
            echo '<img src="'. $user["profile_photo"] .'" class="photo"/>';
            echo '</div><div class="col text-left display-4 text-primary">'. $user["name"] . ' ' . $user["surname"];
            echo '</div></div></a></div></div>';
        }
    ?>
</div>

</body>
</html>
