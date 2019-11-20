<?php

abstract class Controller{

    protected $controller_name;
    protected $action_name;
    protected $application;
    protected $request;
    protected $response;
    protected $session;
    protected $db_manager;

    public function __construct($application){

        $this->controller_name = strtolower(substr(get_class($this), 0, -10));
        // おそらく、クラス名をExampleControllerという形で命名し、その'Controller'というところを省いたものを
        // クラス名とするためだと思われる。
        $this->application = $application;
        $this->request = $application->getRequest();
        $this->response = $application->getResponse();
        $this->session = $application->getSession();
        $this->db_manager = $application->getDbManager();

    }

}
