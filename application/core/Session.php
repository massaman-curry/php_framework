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


}
