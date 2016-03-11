<?php 

namespace DunamisClasses;

 /**
 * 	Classe que gerencia todo o cache e session de informações do site
 */
 class cookie 
 {
 	
     private static $time = "+1 year";


    public static function save($key, $content) {
        $tempo = strtotime("+1 year", time());
        if(is_array($content)){ $content = serialize($content); }
        setcookie($key,$content,$tempo,'/');
        return true;
    }


    public static function read($key) {
            return unserialize($_COOKIE[$key]);
    }


    public static function delete($key) {
           if (isset($_COOKIE[$key])) {
                setcookie($key, false, (time() - 3600), '/');
                unset($_COOKIE[$key]);            
            }
            return true;
    }



 }