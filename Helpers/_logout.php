<?php
  require_once '_auth.php';
  
  setcookie("PHPSESSID" , '', 1, '/') ;  // delete cookie
  session_destroy() ;  // delete session file
    
  header("Location: ../index.php") ;
  