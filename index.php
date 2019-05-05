<!DOCTYPE html>
<?php
  require("./Helpers/_login.php");
  #REGISTER
  $registerError = "";
  $name = "";
  $surname = "";
  $gender = "";
  $email="";
  $day = 0;
  $month = 0;
  $year = 0;
  if( isset($_POST["btnRegister"]))
  {        
        $name = filter_var($_POST["name"],FILTER_SANITIZE_SPECIAL_CHARS);
        $surname = filter_var($_POST["surname"],FILTER_SANITIZE_SPECIAL_CHARS);
        if (!preg_match("/^[a-zA-Z]{1,}$/",$name)) {
            $registerError .= "Name is invalid!"; 
        }
        if (!preg_match("/^[a-zA-Z]{1,}$/",$surname)) {
            $registerError .= "<br>Surname is invalid!"; 
        }
        $email = filter_var($_POST["email"],FILTER_SANITIZE_SPECIAL_CHARS);
        if(!filter_var($email, FILTER_VALIDATE_EMAIL))
        {
            $registerError .= "<br>Email is invalid!";
        }
        $day = $_POST["day"];
        $month = $_POST["month"];
        $year = $_POST["year"];
        if($day  == 0 || $month == 0 || $year == 0)
        {
            $registerError .= "<br>Birth day is invalid";
        }
        $bdate = $_POST["year"] . "-" . $_POST["month"] . "-" . $_POST["day"];
        if(!isset($_POST["gender"]))
        {
            $registerError .= "<br>Select a gender";
        }
        else
        {
            $gender = $_POST["gender"];
        }
        $pass = $_POST["pass"];
        
        
        if($registerError == "")
        {
            
            require_once './Helpers/ImageManager.php';
            $result = ImageManager::ProcessInputImage("p_image", "images/profile/");
            if($result["error"] == 0 || $result["error"] == 1)//succesfully uploaded or not selected an image
            {
                $result["filepath"] = $result["error"] == 0 ? $result["filepath"] : ImageManager::GetDefaultProfilePath();
                $pass = password_hash($pass, PASSWORD_BCRYPT) ;
                $date=date("Y-m-d",strtotime($bdate));
                try {
                    require_once './Helpers/_db.php';
                    $stmt = $db->prepare("insert into user (name, surname, email, bdate, gender, pass, profile_photo) values (?,?,?,?,?,?,?)") ;
                    $stmt->execute( [$name, $surname, $email, $date, $gender, $pass, $result["filepath"]]) ;
                    header("Location: login.php?newUser");
                    exit ;
                } catch (Exception $ex) {
                   $error = true ;
                }  
            }
            else
            {                
                $registerError .= ImageManager::GetErrorString($result["error"]);
            }
        }
  }
?>
<html>
    <head>
    <title>TODO supply a title</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="css/bootstrap-grid.css" rel="stylesheet" type="text/css"/>
    <link href="css/bootstrap.css" rel="stylesheet" type="text/css"/>
    <style>
        #background {
            min-height: 100vh;
            background-image:url("./images/wall.jpg");
        }
        .signup-form{
            padding: 10px 20px;
            border-radius: 2px;
            box-shadow: 0px 0px 15px 5px rgba(0,0,0,0.4);
        }
        #error {
            text-align:center;
            color:red;
            font-size:25px;
        }
    </style>
</head>
<body>
<div class="container-fluid bg-info" id="background">
    <div class="row justify-content-between p-2 bg-primary text-white align-items-center">
        <div class="col-4 text-center font-weight-bold display-4">
          Facebook
        </div>
        <div class="col-5 h-100">
            <form action="" method="POST">
                <input type="text" name="email" placeholder="Email Address"/>
                <input type="password" name="pass" placeholder="Password"/>
                <input name ="btnLogin" type="submit" value="Login" class="btn btn-info"/>
            </form>
        </div>
    </div>
    <div class="row  justify-content-center">
        <div class="col-4 text-center mt-lg-5 bg-primary">
          <div class="signup-form  text-white p-5">
                <h1>Create a new account</h1>
                <p class="h3">It's free and always help you waste your time ;-)</p>
                <form action="" method="POST" enctype="multipart/form-data">
                    <div class="form-group">
                        <div class="row">
                            <div class="col">
                                <input type="text" placeholder="First Name" name="name" class="form-control" value="<?=$name?>"/>
                            </div>
                            <div class="col">
                                <input type="text" placeholder="Last Name" name="surname" class="form-control" value="<?=$surname?>"/>
                            </div>                            
                        </div>
                    </div>
                    <div class="form-group">
                        <input type="email" placeholder="Email Address" name="email" class="form-control" value="<?=$email?>"/>
                    </div>
                    <div class="form-group">
                        <input type="password" placeholder="New Password" name="pass" class="form-control"/>
                    </div>
                    <div class="row">
                        <div class="col h3 text-center text-white">
                            Birthday
                            
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col">
                                <select class="form-control" name="day">
                                    <?php
                                        $selected= "";
                                        if($day == 0)
                                        {
                                            $selected = " selected";
                                        }
                                        echo '<option value="0"'.$selected.'>Day</option>';
                                        for($i = 1; $i <= 31;$i++)
                                        {
                                            $selected = "";
                                            if($day == $i)
                                                $selected = ' selected';
                                            echo '<option value="'.$i.'"'.$selected.'>'.$i.'</option>';
                                        }
                                    ?>
                                </select>                                
                            </div>
                            <div class="col">
                                <select class="form-control" name="month">
                                    <?php
                                        $selected= "";
                                        if($month == 0)
                                        {
                                            $selected = " selected";
                                        }
                                        echo '<option value="0"'.$selected.'>Month</option>';
                                        for($i = 1; $i <= 12;$i++)
                                        {
                                            $selected = "";
                                            if($month == $i)
                                                $selected = ' selected';
                                            echo '<option value="'.$i.'"'.$selected.'>'.$i.'</option>';
                                        }
                                    ?>
                                </select>
                            </div>
                            <div class="col">
                                 <select class="form-control" name="year">
                                    <?php
                                        $selected= "";
                                        if($year == 0)
                                        {
                                            $selected = " selected";
                                        }
                                        echo '<option value="0"'.$selected.'>Year</option>';
                                        for($i = 2000; $i >= 1930;$i--)
                                        {
                                            $selected = "";
                                            if($year == $i)
                                                $selected = ' selected';
                                            echo '<option value="'.$i.'"'.$selected.'>'.$i.'</option>';
                                        }
                                    ?>
                                </select>    
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col  text-right mr-5">
                                <label for="sex_male" class="form-check-label">
                                    <input type="radio" name="gender" value="M" id="sex_male" class="form-check-input" <?php if($gender=="M") echo " checked";?>> Male
                                </label>                            
                            </div>
                            <div class="col text-left ml-5">
                                <label for="sex_female" class="form-check-label">
                                    <input type="radio" name="gender" value="F" id="sex_female" class="form-check-input" <?php if($gender=="F") echo " checked";?>> Female
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        Profile Picture(Optional) : <input type="file" name="p_image" >
                    </div>
                    <div class="form-group">
                        <small class="text-mute">By clicking Create Account, you agree to our Terms and confirm that you have read our Data Policy, including our Cookie Use Policy. You may receive SMS message notifications from Facebook and can opt out at any time.</small>
                    </div>
                    <div class="form-group">
                        <input name="btnRegister" type="submit" value="Create Account" class="btn btn-info input-lg"/>
                    </div>
                    <?php
                        if($registerError != "")
                        {
                            echo '<div class="alert alert-danger" role="alert">';
                            echo $registerError;
                            echo '</div>';
                        }
                    ?>
                </form>
            </div>
        </div>
    </div>
</div>
</body>
</html>
