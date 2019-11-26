<?php

class View{

    protected $base_dir;
    protected $defaults;
    protected $layout_variables = array();

    public function __construct($base_dir, $defaults = array()){

        $this->base_dir = $base_dir;
        $this->defaults = $defaults;

    }

    
}

