<?php

namespace App\Services\Yandex;

class YandexService
{

    public function acceptCaptchaResult(string $token): bool
    {
        $ch = curl_init();
        $args = http_build_query([
            "secret" => config('yandex.key'),
            "token" => $token,
            "ip" => $_SERVER['REMOTE_ADDR']
        ]);
        curl_setopt($ch, CURLOPT_URL, "https://smartcaptcha.yandexcloud.net/validate?$args");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 1);

        $serverOutput = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        if ($httpCode !== 200) {
            echo "Allow access due to an error: code=$httpCode; message=$serverOutput\n";
            return true;
        }
        $resp = json_decode($serverOutput);
        return $resp->status === "ok";
    }
}
