<?php


namespace App\Module\Note;
use App\Conf\Conf;
use App\Core\BaseController;

class Note extends BaseController
{

    /**
     * Note constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @return array|false
     * User: youranreus
     * Date: 2021/8/1 11:16
     */
    public function initDB()
    {
        return $this->database->query("
            CREATE TABLE IF NOT EXISTS `note` (
                  `id` int(11) NOT NULL AUTO_INCREMENT,
                  `content` text COLLATE utf8mb4_unicode_ci NOT NULL,
                  `key` text COLLATE utf8mb4_unicode_ci NOT NULL,
                  `sid` text COLLATE utf8mb4_unicode_ci NOT NULL,
                  PRIMARY KEY (`id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
            ")->fetchAll();
    }

    /**
     * @param $sid
     * @return array|false
     * User: youranreus
     * Date: 2021/7/25 19:42
     */
    public function getNote($sid)
    {
        if($this->database->has("note", ["sid"=>$sid])) {
            $result = $this->database->select("note", ["content"], [
                "sid" => $sid
            ]);
            $result[0]["lock"] = $this->haveKey($sid) != '';
            return $result;
        }
        else
            return $this->createNote($sid);
    }

    /**
     * @param $sid
     * @return array
     * User: youranreus
     * Date: 2021/3/23 16:42
     */
    public function createNote($sid): array
    {
        $key = $_GET['key'] ?? '';

        $this->database->insert("note",[
            "sid"=>$sid,
            "content"=>"Begin your story.",
            "key"=> $key
        ]);

        return ["content"=>"Begin your story.","key"=>$key,"lock"=> $key != ''];
    }

    /**
     * @return false|string
     * User: youranreus
     * Date: 2021/3/23 16:40
     */
    private function createKey()
    {
        return substr(md5(time()), 0, 5);
    }

    /**
     * @param $sid
     * @return int
     * User: youranreus
     * Date: 2021/7/25 19:43
     */
    public function deleteNote($sid): int
    {
        $haveKey = $this->haveKey($sid);
        if($haveKey != false)
            $this->checkKey($haveKey);

        $data = $this->database->delete("note", [
            "sid"=>$sid
        ]);

        return $data->rowCount();
    }

    /**
     * @param $sid
     * @return array|int
     * User: youranreus
     * Date: 2021/7/25 19:43
     */
    public function modifyNote($sid)
    {
        $haveKey = $this->haveKey($sid);
        if($haveKey != false)
            $this->checkKey($haveKey);
        else
            $this->database->update("note",["key"=>$_GET["key"]],["sid"=>$sid]);

        if(!isset($_POST["content"]))
            return ["msg"=>Conf::$msgOnParamMissing];

        $result = $this->database->update("note",["content"=>$_POST["content"]],["sid"=>$sid]);
        return $result->rowCount();
    }

    /**
     * User: youranreus
     * Date: 2021/3/23 17:00
     * @param $key
     */
    private function checkKey($key)
    {
        if(!isset($_GET["key"]))
            exit(json_encode(["msg"=>Conf::$msgOnKeyMissing]));
        if ($key != $_GET["key"])
            exit(json_encode(["msg"=>Conf::$msgOnKeyError]));
    }

    /**
     * @param $sid
     * @return bool
     * User: youranreus
     * Date: 2021/3/24 19:22
     * @noinspection PhpMissingReturnTypeInspection
     */
    private function haveKey($sid)
    {
        $key = $this->database->select("note","key",['sid'=>$sid]);
        if($key[0] != "")
            return $key[0];
        return false;
    }


}