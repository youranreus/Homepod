<?php

namespace App\Model;
use App\Conf\Conf;
use Medoo\Medoo;

class User
{
    protected $id;
    protected $name;
    protected $email;
    protected $avatar;
    protected $role;
    protected $token;
    protected $pwd;
    protected $expired;

    protected $database;

    /**
     * @param int $id
     */
    public function __construct(int $id = 1)
    {
        $this->database = new medoo([
            'database_type' => 'mysql',
            'database_name' => Conf::$dbname,
            'server' => Conf::$servername,
            'username' => Conf::$username,
            'password' => Conf::$password,
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_general_ci',
        ]);
        $this->id = $id;
        $meta = $this->database->select('user','*',['id'=>$id])[0];
        $this->name = $meta['name'];
        $this->avatar = $meta['avatar'];
        $this->email = $meta['email'];
        $this->pwd = $meta['password'];
        $this->token = $meta['token'];
        $this->role = $meta['role'];
        $this->expired = $meta['expired'];
    }

    /**
     * @param $_name
     * @return mixed
     * User: youranreus
     * Date: 2021/8/1 18:34
     */
    public function get($_name)
    {
        return $this->$_name;
    }

    /**
     * @param $_name
     * @param $_val
     * @return bool
     * User: youranreus
     * Date: 2021/8/1 18:43
     */
    public function set($_name, $_val): bool
    {
        if($_name == 'email')
        {
            if(!filter_var($_val, FILTER_VALIDATE_EMAIL))
                return false;
        }

        $this->$_name = $_val;
        if($this->update())
            return true;
        return false;
    }

    /**
     * @return int
     * User: youranreus
     * Date: 2021/8/1 18:48
     */
    public function update(): int
    {
        $result = $this->database->update('user',[
            'name'=>$this->name,
            'password'=>$this->pwd,
            'avatar'=>$this->avatar,
            'email'=>$this->email,
            'token'=>$this->token,
            'expired'=>$this->expired,
            'role'=>$this->role
        ],['id'=>$this->id]);
        return $result->rowCount();
    }


}