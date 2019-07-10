<?php
namespace App\Controller;
use Swoole;

class Form extends Swoole\Controller
{
    function index()
    {
        $this->swoole->addCatcher(function ($e) {
            if ($e instanceof Swoole\Exception\InvalidParam)
            {
                die("缺少 {$e->key} 表单.");
            }
            else
            {
                return false;
            }
        });

        $this->validate($this->request->get, array(
            'name' => 'required',
            'date' => 'date',
            'id' => 'required|int',
            'mobile' => 'required|mobile',
            'email' => 'required|email',
        ));

        var_dump($this->request->get);
    }
}