<?php

class DbManager{
// this file try connecting the database with PDO system

    protected $connections = array();

    public function connect($name, $params){

        // functionの引数で与えたparams(array)を下のparams配列にmergeする

        $params = array_merge(array(
            'dsn' => null,
            'user' => '',
            'password' => '',
            'options' => array()
        ), $params);

        $con = new PDO(
            $params['dsn'],
            $params['user'],
            $params['password'],
            $params['options']
        );

        $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $this->connections[$name] = $con;

    }

}
