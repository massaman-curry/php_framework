<?php

abstract class Controller{

    protected $controller_name;
    protected $action_name;
    protected $auth_actions = array();

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

        if ($this->needsAuthentication($action) && !$this->session->isAuthenticated()){
            throw new UnauthorizedActionException();
        }

        $content = $this->$action_method($params);

        return $content;

    }

    protected function needsAuthentication($action){

        if ($this->auth_actions === true || 
            (is_array($this->auth_action) && in_array($action, $this->auth_action))){

                return true;

            }
        
        return false;

    }

    protected function render($variables = array(), $template = null, $layout = 'layout'){
        // receive a response, controller(this method) will deliver to View.php

        $defaults = array(

            'request' => $this->request,
            'base_url' => $this->request->getBaseUrl(),
            'session' => $this->session,

        );

        $view = new View($this->application->getViewDir(), $defaults);
        // construct view class method

        if(is_null($template)){
            $template = $this->action_name;
        }

        $path = $this->controller_name . '/' .$template;

        return $view->render($path, $variables, $layout);

    }

    protected function forward404(){

        throw new HttpNotFoundException('Forwarded 404 page from '
            . $this->controller_name . '/' . $this->action_name
        );

    }

    protected function redirect($url){

        if(!preg_match('#http?://#', $url)){

            $protocol = $this->request->isSsl() ? 'https://' : 'http://';
            $host = $this->request->getHost();
            $base_url = $this->request->getBaseUrl();

            $url = $protocol . $host . $base_url . $url;

        }

        $this->response->setStatusCode(302, 'Found');
        $this->renponse->setHttpHeader('Location', $url);

    }

    protected function generateCsrfToken($form_name){

        $key = 'csrf_tokens/' . $form_name;
        $tokens = $this->session->get($key, array());
        // in one session, 10 tokens can be stocked.
        // it reaches 10, oldest token will be released from the queue.
        
        if(count($tokens) >= 10){
            array_shift($tokens);
        }

        $token = sha1($form_name . session_id() . microtime());
        $tokens[] = $token;

        $this->session->set($key, $tokens);
        // registrate $tokens array as $key name. this means,
        // each foam has got tokens as array

        return $token;

    }

    protected function checkCsrfToken($form_name, $token){

        $key = 'csrf_tokens/' . $form_name;
        $tokens = $this->session->get($key, $array());

        if(false !== ($pos = array_search($token, $tokens, true))){

            unset($tokens[$pos]);
            $this->session->set($key, $tokens);

            return true;

        }
        
    }


}
