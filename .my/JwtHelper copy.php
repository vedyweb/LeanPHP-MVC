<?php

namespace LeanPHP\Core;

use LeanPHP\Core\Request;
use LeanPHP\Core\Response;
use LeanPHP\Model\Auth;

class JwtHelper {

    private $secret = "supersecretkey"; // Bu anahtar güvenlik için çok önemli, güçlü ve gizli tutulmalıdır.
    private $authModel;

    private static $currentUser = null;

    public function __construct() {
        $this->authModel = new Auth();
    }

    public static function user() {
        return self::$currentUser;
    }

    public function getAuthenticate(Request $request, Response $response): bool
    {
        $headers = getallheaders();

        if (!isset($headers['authorization'])) {
            throw new \Exception('Authorization header is missing');
        }

        $token = str_replace('Bearer ', '', $headers['authorization']);
        if (!$this->validateJWT($token)) {
            throw new \Exception('Invalid or expired token');
        }

        // Kullanıcıyı ayarla
        $payload = $this->decodeJWT($token);
        self::$currentUser = $payload;

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