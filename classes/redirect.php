<?php
class redirect{
    public static function to($path = null){
        if($path){
            if(is_numeric($path)){
                switch($path){
                    case 404:
                        header('HTTP/1.0  404 Not Found');
                        include 'includes/error/404.php';
                        exit();
                    break;
                }
            }
        }
        header( 'Location:' . $path);
        exit();
    }
}


?>