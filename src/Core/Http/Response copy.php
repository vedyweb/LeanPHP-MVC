<?php

namespace LeanPHP\Core\Http;

class Response {
    private $body;
    private $status = 200;
    private $headers = [];

    public function getBody() {
        return $this->body;
    }

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
        return $this;
    }

    public function withHTML($content, $status = null) {
        $this->body = $content;
        $this->withHeader('Content-Type', 'text/html');
        if ($status !== null) {
            $this->withStatus($status);
        }
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

    // Utility function to directly send a JSON response
    public function json($data, $statusCode = 200) {
        $this->withStatus($statusCode)->withJSON($data)->send();
    }

    // Utility function to directly send an HTML response
    public function html($content, $statusCode = 200) {
        $this->withStatus($statusCode)->withHTML($content)->send();
    }
}

