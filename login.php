<!DOCTYPE html>
<?php
    require("./Helpers/_login.php");
?>
<html>
    <head>
        <title>Facebook - The Social Network</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="assets/css/style.css"/>
        <style>
            .login-form{
                background: rgba(255,255,255,1);
                padding: 10% 20px;
                border-radius: 2px;
                box-shadow: 0px 0px 15px 5px rgba(0,0,0,0.4);
            }
            .main{
                min-height:100vh;
                padding:10% 0px;
            }
        </style>
</script>
    </head>
    <body>
        <div class="header">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-9">
                        <div class="logo">
                            <h1>Facebook</h1>
                        </div>
                    </div>
                    <div class="col-sm-3 text-center">
                        <a href="index.php" class="btn btn-primary" style="border-radius:20px;line-height:40px;border-bottom-left-radius: 0px;border-top-right-radius: 0px;">Signup</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="main">
            <div class="container-fluid">
                <div class="row">
                    <div class="pull-left text-center col-sm-3">
                    </div>
                    <div class="pull-right col-sm-6 text-center">
                        <div class="login-form">
                            <p class="h3">

                                <?php
                                    if ( isset($_GET['loginError'])) {
                                        echo '<p>Login Error</p>' ;
                                    } else               
                                    if ( isset($_GET['authError'])) {
                                        echo '<p>Authentication Required</p>';
                                    }
                                    if ( isset($_GET['newUser'])) {
                                        echo '<p>Registration completed!</p>';
                                    }
                                ?>
                                Log in to Facebook</p>
                            <form action="" method="post" style="max-width:400px;margin:0px auto;">
                                <div class="form-group">
                                    <input type="text" name="email" placeholder="Email Address" class="input-lg col-sm-12"/>
                                </div>
                                <div class="form-group">
                                    <input type="password" placeholder="Password" name="pass" class="input-lg col-sm-12"/>
                                </div>
                                <div class="form-group">
                                    <input name="btnLogin" type="submit" value="Login" class="btn btn-success input-lg col-sm-12"/>
                                    <br/><br/><br/><br/>
                                    <a href="index.html">Signup for Facebook</a>
                                </div>
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
