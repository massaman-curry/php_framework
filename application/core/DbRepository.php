<?php

abstract class DbRepository{
    
    protected $con;
    // $conには、PDOクラスの変数が格納される。

    public function __construct($con){

        $this->setConnection($con);

    }

    public function setConnection($con){

        $this->con = $con;

    }

    public function execute($sql, $params = array()){

        $stmt = $this->con->prepare($sql);
        // $con、つまり、PDO::prepare($sql)する、ということであり、PDOStatementを返す。
        $stmt->execute($params);
        // そして、上のPDOStatementをPDOStatement::executeし、次の行でreturnする。
        
        return $stmt;

    }

    public function fetch($sql, $params = array()){
        // fetch_assocによって、データベースから連想配列で取り出し。fetchとは一行ごとに取り出すこと。
        return $this->execute($sql, $params)->fetch(PDO::FETCH_ASSOC);

    }

    public function fetchAll($sql, $params = array()){

        return $this->execute($sql, $params)->fetchAll(PDO::FETCH_ASSOC);

    }


}

