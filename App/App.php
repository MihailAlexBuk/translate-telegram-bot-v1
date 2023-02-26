<?php

namespace app\App;

use app\App\db\Database;

class App
{
    private Response $response;

    public function __construct()
    {
        $this->response = new Response();
    }

    public function start()
    {
        while (true)
        {
            sleep(2);
            $updates = Bot::getUpdates();

            foreach ($updates as $update)
            {
//                file_put_contents(__DIR__.'/logs.txt', print_r($update, 1), FILE_APPEND);

                if($update['edited_message']){
                    $data = $update['edited_message'];
                }elseif($update['callback_query']){
                    $data = $update['callback_query']['message'];
                    $cb_query_data = $update['callback_query']['data'];
                    $cb_query_id = $update['callback_query']['id'];
                }else{
                    $data = $update['message'];
                    $cb_query_data = '';
                    $cb_query_id = '';
                }
                $message = $data['text'];
                $message_id = $data['message_id'];
                $chat_id = $data['chat']['id'];
                $f_name = $data['chat']['first_name'] ?? '';


                $this->response->getResponse($message, $chat_id, $message_id, $cb_query_data, $cb_query_id, $f_name);
            }
        }
    }

}