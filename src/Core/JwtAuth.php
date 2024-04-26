<?php

namespace LeanPHP\Core;

use LeanPHP\Core\Http\Request;
use LeanPHP\Core\Http\Response;
use LeanPHP\Model\AuthModel;

class JwtAuth {

    private $secret = "supersecretkey"; // Bu anahtar güvenlik için çok önemli, güçlü ve gizli tutulmalıdır.
    private $authModel;

    public function __construct() {
        $this->authModel = new AuthModel();
    }

    public function getAuthenticate(Request $request, Response $response): bool
    {
        $headers = getallheaders();
        var_dump($headers);
        if (!isset($headers['authorization'])) {
            throw new \Exception('Authorization header is missing');
        }

        $token = str_replace('Bearer ', '', $headers['authorization']);
        if (!$this->validateJWT($token)) {
            throw new \Exception('Invalid or expired token');
        }

        return true;
    }

    public function createJWT($payload, $expiryDuration) {
        $header = ["alg" => "HS256", "typ" => "JWT"];
        $payload['exp'] = time() + $expiryDuration;

        $headerEncoded = $this->base64UrlEncode(json_encode($header));
        $payloadEncoded = $this->base64UrlEncode(json_encode($payload));
        $signature = hash_hmac('sha256', "$headerEncoded.$payloadEncoded", $this->secret, true);
        $signatureEncoded = $this->base64UrlEncode($signature);

        return "$headerEncoded.$payloadEncoded.$signatureEncoded";
    }

    public function validateJWT($token) {
        $parts = explode('.', $token);
        if (count($parts) !== 3) return false;

        list($headerEncoded, $payloadEncoded, $signatureEncoded) = $parts;

        $signature = $this->base64UrlDecode($signatureEncoded);
        $calculatedSignature = hash_hmac('sha256', "$headerEncoded.$payloadEncoded", $this->secret, true);

        return hash_equals($signature, $calculatedSignature);
    }

    public function decodeJWT($token) {
        $parts = explode('.', $token);
        if (count($parts) !== 3) {
            throw new \Exception("Invalid JWT structure");
        }

        $payloadEncoded = $parts[1];
        $payload = json_decode($this->base64UrlDecode($payloadEncoded), true);
        if (is_null($payload)) {
            throw new \Exception("Invalid payload encoding");
        }

        return $payload;
    }

    private function base64UrlEncode($data) {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    private function base64UrlDecode($data) {
        return base64_decode(strtr($data, '-_', '+/'));
    }
}


