<?php

class AuthController{
    private static $palavraSecreta = "Palavra/FraseQuaseSecreta";

    private static function base64UrlEncode($data){
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    private static function base64UrlDecode($data){
        return base64_decode(strtr($data, '-_', '+/'));
    }

    static function GerarToken($cpf, $email){
        $header = ['typ' => 'JWT','alg' => 'HS256'];
        $payload = ["cpf" => $cpf, "email" => $email, "exp" => time() + 3600];

        $headerCodificado = self::base64UrlEncode(json_encode($header));
        $payloadCodificado = self::base64UrlEncode(json_encode($payload));

        $assinatura = hash_hmac('sha256', "$headerCodificado.$payloadCodificado", self::$palavraSecreta, true);
        $assinaturaCodificada = self::base64UrlEncode($assinatura);

        $token = "$headerCodificado.$payloadCodificado.$assinaturaCodificada";
        return $token;
    }

    static function ValidarToken(){
        $headers = getallheaders()['Authorization'] ?? '';
        $token = str_ireplace("Bearer ","",$headers);
        if($token == '' || trim($token) == '')return ['erro' => true, 'token' => false, 'mensagem' => 'Token vazio!'];
        $partes = explode('.', $token);
        if(count($partes) !== 3)return ['erro' => true, 'token' => false, 'mensagem' => 'Token invalido!'];
        list($headerCodificado, $payloadCodificado, $assinaturaRecebida) = $partes;

        $assinaturaVerificada = self::base64UrlEncode(
            hash_hmac('sha256', "$headerCodificado.$payloadCodificado", self::$palavraSecreta, true)
        );
        if(!hash_equals($assinaturaVerificada, $assinaturaRecebida))return ['erro' => true, 'token' => false, 'mensagem' => 'Assinatura invalida!'];
        $payload = json_decode(self::base64UrlDecode($payloadCodificado), true);
        if(isset($payload['exp']) && time() > $payload['exp']) return ['erro' => true, 'token' => false, 'mensagem' => 'Tempo expirado!'];
        return ['token' => $payload ,"mensagem" => "Token válido e retornado com sucesso!", 'erro' => false]; 
    }

}


?>