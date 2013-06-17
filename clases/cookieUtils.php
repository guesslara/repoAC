<?php
 class CookieUtils{
    const DefaultLife = 3600;//3600 segundos de duracion = 1 hora
    
    public function get($name){
        if(isset($_COOKIE[$name])){
            return $_COOKIE[$name];
        }else{
            return false;
        }    
    }
    
    public function set($name,$value,$expiry = self::DefaultLife,$path='/',$domain=false){
        $val = false;
        if(!headers_sent()){
            if($domain === 1){
                $domain= $_SERVER["HTTP_HOST"];
            }
            if($expiry === false){
                $expiry = 1893456000;
            }else if(is_numeric($expiry)){
                $expiry += time();
            }else{
                $expiry = strtotime($expiry);
            }
        }
        $val = @setcookie($name,$value,$expiry,$path,$domain);
        return $val;
    }
    
    public function delete($name,$path='/',$domain = false){
        $val = false;
        if(!headers_sent()){
            if($domain === false){
                $domain = $_SERVER["HTTP_HOST"];
            }
            $val = setcookie($name,'',time()-3600,$path,$domain);
            unset($_COOKIE[$name]);
        }
    }
    
 }
/*
    //instancia de la clase cookie
    $cookie = new CookieUtils();
    $cookie->set('pruebaLogIn','Administrador',6000); //solo 10 segundos
    echo "Valor de la cookie<br>";
    echo $cookie->get('pruebaLogIn');
    //se muestra el contenido de la cookie
    echo "<pre>";
    print_r($_COOKIE['pruebaLogIn']);
    echo "</pre>";
    echo "Se elimina la cookie<br>";
    $cookie->delete('pruebaLogIn');
    echo "Valor de la cookie<br>";
    echo $cookie->get('pruebaLogIn');
    //se muestra el contenido de la cookie
    echo "<pre>";
    print_r($_COOKIE['pruebaLogIn']);
    echo "</pre>";
*/
?>