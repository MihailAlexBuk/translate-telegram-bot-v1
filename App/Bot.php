<?php

namespace app\App;

class Bot
{
    private static $updateId;

    public function query($method = 'getMe', $params = [])
    {
        $url = "https://api.telegram.org/bot".$_ENV['TELEGRAM_BOT_TOKEN']."/".$method;

        if(!empty($params)){
            $url .= "?" . http_build_query($params);
        }
        return json_decode(file_get_contents($url), 1);
    }

    public static function getUpdates()
    {
        $response = self::query('getUpdates', [
            'offset' => self::$updateId + 1
        ]);

        if(!empty($response['result'])){
            self::$updateId = $response['result'][count($response['result']) - 1]['update_id'];
        }
        return $response['result'];
    }




}