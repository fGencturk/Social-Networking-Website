<?php
    session_start() ;
    require_once './Helpers/_db.php';
    #LOGIN
    if ( isset($_POST["btnLogin"])) {
       $email = $_POST["email"];
       $pass = $_POST["pass"] ;
           
       $stmt = $db->prepare("SELECT * FROM user WHERE email = ?") ;
       $stmt->execute( [$email]) ;
       $row = $stmt->fetch(PDO::FETCH_ASSOC) ;
      
       if ( $row ) {
           if (password_verify($pass, $row['pass'])) {
           // Success - Login
           $_SESSION['loginAt'] = time() ;
           $_SESSION['user'] = $row ;
           header("Location: main.php");
           exit ; 
          }
       }
       header("Location: index.php?loginError");
  }