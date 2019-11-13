<?php

class Response{
// this file will be required to return http response.

    protected $content;
    protected $status_code = 200;
    protected $status_text = 'OK';
    protected $http_headers = array();

    public function send(){

        header('HTTP/1.1' . $this->status_code . ' ' . $this->status_text);

        foreach($this->http_headers as $name => $value){

            header($name . ':' . $value);

        }

        echo $this->content;

    }
}
