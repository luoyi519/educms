<?php
/**
 * Created by PhpStorm.
 * User: tanzhenxing
 * Date: 2017/6/26
 * Time: 16:25
 */
namespace app\admin\controller;

use think\facade\Session;
use think\facade\Cookie;

class Logout extends AdminBase
{
    /**
     * 注销登录
     */
    public function index()
    {
        Session::delete('admin_username');
        Session::delete('admin_password');
        $this->success('正在退出', '/admin/login/index');
    }
}