<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Moder\UserModel;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Redis;

class IndexController extends Controller
{
    public function index()
    {
        $data = request()->input();
        // dd($data);
        if($data)
        {
            $res = UserModel::insert($data);
            if($res)
            {
                $post = [
                    'error'=>'ok',
                    'msg'=>'添加成功',
                ];
            }
        }else{
            $post = [
                'error'=>400,
                'msg'=>'请使用from_data，post方式进行提交',
            ];
        }

        return $post;
    }

    public function login()
    {
        $name = request()->input('name');
        $pwd = request()->input('pwd');

        // echo $name;
        // echo $pwd;

        $res = UserModel::where('name','=',$name)->first();

        if($res)
        {
            $pwds = $res->pwd;
            if($pwds==$pwd)
            {
                $post = [
                    'error'=>'ok',
                    'msg'=>'登陆成功',
                ];
            }else{
                $post = [
                    'error'=>440,
                    'msg'=>'密码错误',
                ];
            }
        }else{
            $res = UserModel::where('tel','=',$name)->first();

            if($res)
            {
                $pwds = $res->pwd;
                if($pwds==$pwd)
                {
                    $post = [
                        'error'=>'ok',
                        'msg'=>'登陆成功',
                    ];
                }else{
                    $post = [
                        'error'=>444,
                        'msg'=>'密码错误',
                    ];
                }
            }else{
                $res = UserModel::where('email','=',$name)->first();
                
                if($res)
                {
                    $pwds = $res->pwd;
                    if($pwds == $pwd)
                    {
                        $post = [
                            'error'=>'ok',
                            'msg'=>'登陆成功',
                        ];
                    }else{
                        $post = [
                            'error'=>454,
                            'msg'=>'密码错误',
                        ];
                    }
                }else{
                    $post = [
                        'error'=>404,
                        'msg'=>'查不到此用户',
                    ];
                }
            }
        }
        if($post['error']=='ok')
        {
            $token = Str::random(12);
            // echo $token;die;
            $pri = 'werty';
            $key = 'str:u:' . md5($name.$pri);
            // dd($key);
            Redis::set($key,$token);
            Redis::expire($key,3600);
        }

        echo Redis::get($key);

        return $post;

    }

    public function add()
    {
        
        // echo '<pre>';print_r($_GET);echo '</pre>';
        $name = $_GET['name'];
        $token = $_SERVER['HTTP_TOKEN'];
        $pri = 'werty';
        $key = 'str:u:' . md5($name.$pri);

        $post = Redis::get($key);
        // echo $pri;
        // echo '<hr>';
        // echo $key;
        // echo '<hr>';
        // echo $token;
        // echo '<hr>';
        // echo $name;
        // dd($post);

        if($post != $token)
        {
            return $post = [
                'error'=>'303',
                'msg'=>'token不正确',
            ];
        }

        $poss = UserModel::where('name','=',$name)->first();
        
        if($poss)
        {
            return $poss;
        }else{
            $poss = UserModel::where('tel','=',$name)->first();
            if($poss)
            {
                return $poss;
            }else{
                $poss = UserModel::where('tel','=',$name)->first();
                if($poss)
                {
                    return $poss;
                }
            }
        }


        


    }
}
