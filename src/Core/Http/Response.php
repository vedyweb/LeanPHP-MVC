<?php

namespace LeanPHP\Core\Http;

class Response {
    private $body;
    private $status = 200;
    private $headers = [];

    public function withStatus($status) {
        $this->status = $status;
        return $this;
    }

    public function withHeader($name, $value) {
        $this->headers[$name] = $value;
        return $this;
    }

    public function withJSON($data, $status = null) {
        $this->body = json_encode($data);
        $this->withHeader('Content-Type', 'application/json');
        if ($status !== null) {
            $this->withStatus($status);
        }
        $this->send();
        return $this;
    }

    public function send() {
        if (!headers_sent()) {
            http_response_code($this->status);
            foreach ($this->headers as $name => $value) {
                header("{$name}: {$value}");
            }
        }
        echo $this->body;
    }

    public function json($data, $statusCode = 200) {
        $this->withStatus($statusCode)->withJSON($data);
    }

    public function html($content, $statusCode = 200) {
        $this->body = $content;
        $this->withHeader('Content-Type', 'text/html');
        $this->withStatus($statusCode);
        $this->send();
    }
}


