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
    // $applicationはクラスを定義した時の引数。クラスからインスタンスを生成するときに、
    // 引数の中にオブジェクトを入れることになるはず

        $this->controller_name = strtolower(substr(get_class($this), 0, -10));
        // おそらく、クラス名をExampleControllerという形で命名し、その'Controller'というところを省いたものを
        // クラス名とするためだと思われる。
        $this->application = $application;
        $this->request = $application->getRequest();
        $this->response = $application->getResponse();
        $this->session = $application->getSession();
        $this->db_manager = $application->getDbManager();

    }

    public function run($action, $params = array()){

        $this->action_name = $action;
        $action_method = $action . 'Action';

        if (!method_exists($this, $action_method)){
            $this->forward404();
        }

        $content = $this->$action_method($params);

        return $content;

    }

}
