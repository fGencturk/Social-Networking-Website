<!DOCTYPE html>
<?php
    require("./Helpers/_auth.php");
    require("./Helpers/_db.php");
    
    $sql = "select * from notification where receiver_id = " . $_SESSION["user"]["id"] . ' order by date desc';
    $notifications = $db->query($sql, PDO::FETCH_ASSOC)->fetchAll();
    
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
        if(count($notifications) == 0)
            echo '<div class="row justify-content-center display-3 text-primary border border-info mb-5"><div class="col text-center">NO NOTIFICATION FOUND</div></div>';
        foreach($notifications as $not)
        {
            echo '<div class="row justify-content-center">';
            echo '<div class="col-6 mt-lg-5 bg-light border">';
            echo '<div class="row m-3">';
            echo '<div class="col-3">';
            echo '</div><a href="'. $not["link"] .'"><div class="col text-left text-primary">'. $not["text"];
            echo '</div></a><a href="./Helpers/_notificationDelete.php?id=' . $not["id"] . '"><img src="./images/delete.png" style="width:30px"></a>';
            echo '<div class="col text-right">'. $not["date"] .'</div>' .'</div></div></div>';
        }
    ?>
</div>

</body>
</html>
