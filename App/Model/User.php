<?php

namespace App\Model;
use App\Core\BaseModel;

class User extends BaseModel
{
    /**
     * @var int userid
     */
    protected $id;

    /**
     * @var string 用户名
     */
    protected $name;

    /**
     * @var string 邮箱
     */
    protected $email;

    /**
     * @var string 头像
     */
    protected $avatar;

    /**
     * @var string 角色
     */
    protected $role;

    /**
     * @var mixed 用户token
     */
    protected $token;

    /**
     * @var string 用户密码
     */
    protected $pwd;

    /**
     * @var mixed token过期时间
     */
    protected $expired;

    /**
     * @param int $id
     */
    public function __construct(int $id)
    {
        parent::__construct();
        $this->id = $id;
        $meta = $this->database->get('user','*',['id'=>$id]);
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