<?php
  require("_login.php");
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
            
            require("ImageManager.php");
            $result = ImageManager::ProcessInputImage("p_image", "images/profile/");
            if($result["error"] == 0 || $result["error"] == 1)//succesfully uploaded or not selected an image
            {
                $result["filepath"] = $result["error"] == 0 ? $result["filepath"] : ImageManager::GetDefaultProfilePath();
                $pass = password_hash($pass, PASSWORD_BCRYPT) ;
                $date=date("Y-m-d",strtotime($bdate));
                try {
                    require_once './db.php';
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
        <title>Facebook - The Social Network</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="assets/css/style.css"/>
        <style>
            .signup-form{
                background: rgba(255,255,255,1);
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
        <div class="header">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-4">
                        <div class="logo">
                            <h1>Facebook</h1>
                        </div>
                    </div>
                    <div class="col-sm-8 pull-right">
                        <div class="inline-form pull-right">
                            <form action="" method="POST">
                                <input type="text" name="email" placeholder="Email Address"/>
                                <input type="password" name="pass" placeholder="Password"/>
                                <input name ="btnLogin" type="submit" value="Login" class="btn btn-primary"/>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="main">
            <div class="container-fluid">
                <div class="row">
                    <div class="pull-left text-center col-sm-3">
                    </div>
                    <div class="pull-right col-sm-5">
                        <div class="signup-form">
                            <h1>Create a new account</h1>
                            <p class="h3">It's free and always help you waste your time ;-) .</p>
                            <form action="" method="POST" enctype="multipart/form-data">
                                <div class="form-group">
                                    <input type="text" placeholder="First Name" name="name" class="input-lg col-sm-6" value="<?=$name?>"/>
                                    <input type="text" placeholder="Last Name" name="surname" class="input-lg col-sm-6" value="<?=$surname?>"/>
                                </div>
                                <div class="form-group">
                                    <input type="email" placeholder="Email Address" name="email" class="input-lg col-sm-12" value="<?=$email?>"/>
                                </div>
                                <div class="form-group">
                                    <input type="password" placeholder="New Password" name="pass" class="input-lg col-sm-12"/>
                                </div>
                                <div class="form-group">
                                    <p style="font-size:20px">Birthday</p>
                                    <select class="col-sm-4 input-lg" name="day">
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
                                    <select class="col-sm-4 input-lg" name="month">
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
                                    <select class="col-sm-4 input-lg" name="year">
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
                                <div class="form-group">
                                        <label for="sex_male" class="input-lg">
                                            <input type="radio" name="gender" value="M" id="sex_male" <?php if($gender=="M") echo " checked";?>> Male
                                        </label>
                                        <label for="sex_female" class="input-lg">
                                            <input type="radio" name="gender" value="F" id="sex_female" <?php if($gender=="F") echo " checked";?>> Female
                                        </label>
                                </div>
                                <div class="form-group">
                                    Profile Picture(Optional) : <input type="file" name="p_image" >
                                </div>
                                <div class="form-group">
                                    <small class="text-mute">By clicking Create Account, you agree to our Terms and confirm that you have read our Data Policy, including our Cookie Use Policy. You may receive SMS message notifications from Facebook and can opt out at any time.</small>
                                </div>
                                <div class="form-group">
                                    <input name="btnRegister" type="submit" value="Create Account" class="btn btn-success input-lg"/>
                                </div>
                                <p id="error">
                                    <?= $registerError ?>
                                </p>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="footer">
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