<?php

abstract class Application{

    protected $debug = false;
    protected $request;
    protected $response;
    protected $session;
    protected $db_manager;

    public function __construct($debug = false){

        $this->setDebugMode($debug);
        $this->initialize();
        $this->configure();

    }

    protected function setDebugMode($debug){

        if($debug){

            $this->debug = true;
            ini_set('display_errors', 1);
            error_reporting(-1);

        }else{

            $this->debug = false;
            ini_set('display_errors', 0);

        }

    }

    protected function initialize(){

        $this->request = new Request();
        $this->response = new Response();
        $this->session = new Session();
        $this->db_manager = new DbManager();
        $this->router = new Router($this->registerRoutes());

    }

    protected function configure(){

    }
    // 以下はabstractメソッドなので、getRootDir, registerRoutesメソッドはこのクラスを継承した、子クラスで実装される。
    // これは、アプリケーションごとに設定することが目的であるため
    abstract public function getRootDir();

    abstract protected function registerRoutes();

    public function isDebugMode(){

        return $this->debug;

    }

    public function getRequest(){

        return $this->request;

    }

    public function getResponse(){

        return $this->response;

    }

    public function getSession(){

        return $this->session;

    }

    public function getDbManager(){

        return $this->db_manager;

    }

    public function getControllerDir(){

        return $this->getRootDir() . '/controllers';
        // controller directoryのなかに、controllerファイルが入っている。それを利用した形。

    }

    public function getViewDir(){

        return $this->getRootDir() . '/views';

    }

    public function getModelDir(){

        return $this->getRootDir() . '/models';

    }

    public function run(){

        try{

            $params = $this->router->resolve($this->request->getPathInfo());
            // $this->routerで、initialize methodで作った、RouterClassを呼び出し。
            // そして、そのRouterClassのresolve methodを実行。resolveの引数として、
            // このファイルの変数$requestのgetPathInfo methodを実行。なお、$requestは
            // $router同様、initialize methodでRequestのインスタンスとして作成している。
            if ($params === false){

                throw new HttpNotFoundException('No route found for' . $this->request->getPathInfo());

            }

            $controller = $params['controller'];
            $action = $params['action'];

            $this->runAction($controller, $action, $params);

        } catch (HttpNotFoundException $e){

            $this->render404Page($e);

        } catch (UnauthorizedActionException $e){

            list($controller, $action) = $this->login_action;
            $this->runAction($controller, $action);

        }

        $this->response->send();

    }

    public function runAction($controller_name, $action, $params = array()){

        $controller_class = ucfirst($controller_name) . 'Controller';
        // ucfirstで先頭大文字。これによって、◯◯Controllerという文字列が作られる。
        // controllerは複数ある。会員登録用コントローラー、ポスト用コントローラーなど。
        // それらを束ねているのが、Application.php
        $controller = $this->findController($controller_class);

        if($controller === false){

            throw new HttpNotFoundException($controller_class . 'controller is not found.');

        }

        $content = $controller->run($action, $params);
        // controller classのrunメソッドを実行している

        $this->response->setContent($content);

    }

    protected function findController($controller_class){

        if(!class_exists($controller_class)){

            $controller_file = $this->getControlerDir() . '/' . $controller_class . '.php';

        }

        if(!is_readable($controller_file)){

            return false;

        }else{

            require_once $controller_file;

            if(!class_exists($controller_class)){

                return false;

            }

        }

        return new $controller_class($this);

    }

    protected function render404Page($e){

        $this->response->setStatusCode(404, 'Not Found');

        $message = $this->isDebugMode() ? $e->getMessage() : 'Page not found.';
        $message = htmlspecialchars($message, ENT_QUOTES, 'UTF-8');

        $this->response->setContent(<<<EOF

    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="UTF-8">
        <title>404</title>
    </head>
    <body>
        {$message}
    </body>
    </html>
EOF

// 終端記号(EOF)の前にインデントが入ってしまっていたため、エラーが出てしまっていた。git diffでチェック。
        );

    }


}
