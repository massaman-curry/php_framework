<?php

class Request{


    public function isPost(){

        if ($_SERVER['REQUEST_METHOD'] === 'POST'){
            return true;
        }

        return false;

    }

    public function getGet($name, $default = null){

        if(isset($_GET[$name])){
            return $_GET[$name];
        }

        return $default;

    }

    public function getPost($name, $default = null){

        if(isset($_POST[$name])){
            return $_POST[$name];
        }

        return $default;

    }

    public function getHost(){

        if(!empty($_SERVER['HTTP_HOST'])){
            return $_SERVER['HTTP_HOST'];
        }

        return $_SERVER['SERVER_NAME'];

    }

    public function isSsl(){

        if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on'){
            return true;
        }

        return false;

    }

    public function getRequestUri(){

        return $_SERVER['REQUEST_URI'];

    }

    public function getBaseUrl(){

        $script_name = $_SERVER['SCRIPT_NAME'];
        $request_uri = $this->getRequestUri();
        // getRequestUriでリクエストURIを取得。

        if(0 === strpos($request_uri, $script_name)){
        // strposで、$request_uriの文字列で、$script_nameが出てくるのが何番目かをreturn
            return $script_name;

        }else if(0 === strpos($request_uri, dirname($script_name))){
        // rtrimで後ろの空白削除
            return rtrim(dirname($script_name), '/');

        }

        return '';

    }

    public function getPathInfo(){

        $base_url = $this->getBaseUrl();
        $request_uri = $this->getRequestUri();
        // おそらくGETリクエストの?以降を抽出
        if(false !== ($pos = strpos($request_uri, '?'))){
            // どこかしらに、GETリクエストの?以降のぶんがある場合
            $request_uri = substr($request_uri, 0, $pos);
            // $request_uriに?がある場合は$request_uriの0番目からGETリクエストの?の前まで（パラメーターの前まで）を取得。
        }
        $path_info = (string)substr($request_uri, strlen($base_url));
            // $request_uriに?がないときは、$request_uriはそのままを扱い、$base_urlから後ろを取得。
        return $path_info;

    }


}