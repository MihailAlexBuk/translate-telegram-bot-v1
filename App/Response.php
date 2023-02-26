<?php


namespace app\App;

use app\App\db\Database;
use Dejurin\GoogleTranslateForFree;

class Response
{
    public Database $db;
    public Methods $methods;
    public string $lang = 'en';

    public function __construct()
    {
        $this->db = new Database();
        $this->methods = new Methods();
    }

    public function getResponse($message, $chatId, $message_id, $cb_query_data, $cb_query_id, $fname)
    {
        if($message === '/start'){
            $data = $this->db->getChatId($chatId);
            if(empty($data)){
                $this->db->addChatId($chatId, $fname, 'en');
                $this->lang = 'en';
            }else{
                $this->lang = $data['lang'];
            }
            $this->methods->sendInlineKeyboards($chatId,
                "Выберите язык который необходимо перевести.",
                Keyboards::lang_keyboard($this->lang));
        }

        elseif(!empty($cb_query_data)){
            if($this->lang !== $cb_query_data){
                $this->db->updateChat($chatId, $cb_query_data);
                $this->methods->editMessage($chatId,
                    "Введите слово для перевода с $cb_query_data языка.",
                    $message_id,
                    Keyboards::lang_keyboard($cb_query_data));
                $this->lang = $cb_query_data;
            }else{
                $this->methods->answerCallbackQuery('Данный язык уже выбран!', $cb_query_id);
            }
        }

        elseif(!empty($message)){
            $data = $this->db->getChatId($chatId);
            $source = ($data['lang'] == 'en') ? 'en' : 'ru';
            $target = ($data['lang'] == 'ru') ? 'en' : 'ru';
            $attempts = 5;

            $tr = new GoogleTranslateForFree();
            $result = $tr->translate($source, $target, $message, $attempts);
            if($result){
                $this->methods->sendMessage($chatId, $result);
            }else{
                $this->methods->sendMessage($chatId, 'Не удалось перевести... Попробуйте еще раз.');
            }
        }

        else{
            $this->methods->sendMessage($chatId, "Это бот-переводчик, он ожидает от вас текст для перевода");
        }
    }

}
