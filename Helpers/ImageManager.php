<?php
class ImageManager {
    
    
    public static function ProcessInputImage($imageName, $path)
    {
        $result = array();
        $result["error"] = 0;
        $result["fileName"] = "";
    
        if ( !isset($_FILES[$imageName]) || !is_uploaded_file($_FILES[$imageName]["tmp_name"]) ) 
        {
            $result["error"] = 1;
            return $result;
        }

        $p_image = $_FILES["p_image"]["name"] ;

        $extension = strtolower( pathinfo($p_image, PATHINFO_EXTENSION) ) ;
        $whitelist = array( "gif", "jpg", "png") ;
        if ( !in_array($extension, $whitelist)){
            $result["error"] = 2;
            return $result;
        }

        /* if file is greater than 1 MB, it gives an error */
        if ( $_FILES["p_image"]["size"] > 1024*1024) {
            $result["error"] = 3;
            return $result;
        }

        /* create a uniqie file name */
        $filename = uniqid() . "_" . $p_image ;
        if ( move_uploaded_file($_FILES["p_image"]["tmp_name"], $path . $filename) ) {
            $result["filepath"] = $path . $filename;
            return $result;
        } else {
            $result["error"] = 4;
            return $result;
        }
    }
    
    public static function GetErrorString($errorCode)
    {
        $errorStrings = array(
            0 => "",
            1 => "File not exist.",
            2 => "Only gif, jpg and png allowed.",
            3 => "Max image size is 1MB.",
            4 => "File already exists."
        );
        return $errorStrings[$errorCode];
    }
    
    public static function GetDefaultProfilePath()
    {
        return "images/profile/default.jpg";
    }
}



