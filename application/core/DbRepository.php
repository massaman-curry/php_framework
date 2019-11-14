<?php

abstract class DbRepository{
    
    protected $con;

    public function __construct($con){

        $this->setConnection($con);

    }

    public function setConnection($con){

        $this->con = $con;

    }


    

}

