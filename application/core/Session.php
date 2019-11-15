<?php

class Session{

    protected static $sessionStarted = false;
    protected static $sessionIdRegenerated = false;

    public function __construct(){

        if(!self::$sessionStarted){
            // $sessionStartedがfalseの時に、session_start()を実行
            session_start();

            self::$sessionStarted = true;

        }

    }

    public function set($name, $value){

        $_SESSION[$name] = $value;

    }

    public function get($name, $default = null){

        if(isset($_SESSION[$name])){
            return $_SESSION[$name];
        }

        return $default;

    }

    public function remove($name){

        unset($_SESSION[$name]);

    }

    public function clear(){

        $_SESSION = array();

    }

    public function regenerate($destroy = true){

        if(!self::$sessionIdRegenerated){
            // 標準のsession関数、session_idを置き換える
            session_regenerate_id($destroy);

            self::$sessionIdRegenerated = true;

        }

    }


}
