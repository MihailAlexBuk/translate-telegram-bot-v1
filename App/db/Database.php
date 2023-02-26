<?php


namespace app\App\db;

class Database
{
    public \PDO $pdo;

    public function __construct()
    {
        $dsn = $_ENV['DB_DSN'] ?? '';
        $user = $_ENV['DB_USER'] ?? '';
        $password = $_ENV['DB_PASSWORD'] ?? '';
        $opt = [
            \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
            \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC
        ];
        $this->pdo = new \PDO($dsn, $user, $password, $opt);
    }

    public function getChatId($chat_id)
    {
        $statement = $this->pdo->prepare("SELECT * FROM chat WHERE chat_id = ?");
        $statement->execute([$chat_id]);
        return $statement->fetch();
    }

    public function addChatId($chat_id, $first_name, $lang)
    {
        $statement = $this->pdo->prepare("INSERT INTO chat (chat_id, first_name, lang) values (?,?,?)");
        return $statement->execute([$chat_id, $first_name, $lang]);
    }

    public function updateChat($chat_id, $lang)
    {
        $statement = $this->pdo->prepare("UPDATE chat SET lang = ? WHERE chat_id = ?");
        return $statement->execute([$lang, $chat_id]);
    }

}