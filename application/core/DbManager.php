<?php

class DbManager{
// this file try connecting the database with PDO system

    protected $connections = array();
    protected $repository_connection_map = array();

    public function connect($name, $params){

        // functionの引数で与えたparams(array)を下のparams配列にmergeする。
        // 与えられた$paramsによって上書きするイメージ。これは、呼び出しの時に「ない」状態を避けるため

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

    public function getConnection($name = null){

        if(is_null($name)){
            return current($this->connections);
        }

        return $this->connections[$name];

    }

    public function setRepositoryConnectionMap($repository_name, $name){

        $this->repository_connection_map[$repository_name] = $name;

    }

    public function getConnectionForRepository($repository_name){

        if(isset($this->repository_connection_map[$repository_name])){

            $name = $this->repository_connection_map[$repository_name];
            $con = $this->getConnection($name);

        }else{

            $con = $this->getConnection();

        }

        return $con;

    }


}
