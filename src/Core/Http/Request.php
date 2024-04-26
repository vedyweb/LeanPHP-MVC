<?php

namespace LeanPHP\Core\Http;

class Request
{
    private $data = [];
    private $parsedBody = null;

    public function __construct()
    {
        $this->setHeadersForCORS();
        $this->parseIncomingParams();
    }

    public function getParsedBody()
    {
        if ($this->parsedBody === null) {
            $this->parseBody();
        }
        return $this->parsedBody;
    }

    private function parseBody()
    {
        $contentType = $_SERVER['CONTENT_TYPE'] ?? '';

        if (strpos($contentType, 'application/json') !== false) {
            $input = file_get_contents('php://input');
            $this->parsedBody = json_decode($input, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                // Log the error or handle it as appropriate
                $this->parsedBody = []; // Defaults to an empty array on error
            }
        } elseif (strpos($contentType, 'application/x-www-form-urlencoded') !== false) {
            $this->parsedBody = $_POST;
        } else {
            $this->parsedBody = []; // Supports only JSON and form-urlencoded
        }
    }

    /**
     * Belirli bir HTTP başlığını döndürür.
     * 
     * @param string $header Başlık ismi.
     * @return string|null Başlığın değeri veya başlık mevcut değilse null.
     */
    public function getHeader($header)
    {
        $header = strtolower($header); // HTTP başlıkları büyük-küçük harf duyarlı değildir.
        $headers = $this->getAllHeaders(); // PHP'nin yerleşik fonksiyonunu veya alternatif bir yöntem kullanarak tüm başlıkları al.

        if (array_key_exists($header, $headers)) {
            return $headers[$header];
        }

        return null; // Başlık bulunamadıysa null dön.
    }

    /**
     * Tüm HTTP başlıklarını döndürür.
     * 
     * @return array Tüm başlıklar.
     */
    private function getAllHeaders()
    {
        if (function_exists('getallheaders')) {
            return array_change_key_case(getallheaders(), CASE_LOWER);
        }
        // getallheaders() fonksiyonu mevcut değilse, başlıkları manuel olarak işle:
        $headers = [];
        foreach ($_SERVER as $key => $value) {
            if (substr($key, 0, 5) == 'HTTP_') {
                $header = strtolower(str_replace('_', '-', substr($key, 5)));
                $headers[$header] = $value;
            }
        }
        return $headers;
    }

    public function getMethod()
    {
        return $_SERVER['REQUEST_METHOD'];
    }

    public function getPath()
    {
        return $_SERVER['REQUEST_URI'];
    }

    public function getParam($key, $default = null)
    {
        return $_GET[$key] ?? $default;
    }

    public function getBody()
    {
        return file_get_contents('php://input');
    }

    public function getHeaders()
    {
        return getallheaders();
    }

    private function parseIncomingParams()
    {
        $input = file_get_contents('php://input');

        if (!empty($input)) {
            $this->data = json_decode($input, true) ?? $_POST; // Fallback to $_POST if JSON decoding fails
        } else {
            $this->data = $_POST;
        }
    }

    public function get($key, $default = null)
    {
        return $this->data[$key] ?? $default;
    }

    private function setHeadersForCORS()
    {
        // CORS headers
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
        header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Authorization");

        if ($this->getMethod() == 'OPTIONS') {
            // If it's an OPTIONS request, exit early
            http_response_code(200); // OK status
            exit;
        }
    }
}
