<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Moder\UserModel as Wd;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Redis;

class IndexController extends Controller
{
    /**
     * Undocumented function
     * 
     * 注册
     *
     * @return void
     */
    public function index()
    {
        $data=$_POST;
        $pwd = sha1($_POST['pwd']);
        $pwd2 = sha1($_POST['pwd2']);
        $name = $_POST['name'];
        $email = $_POST['email'];
        $tel = $_POST['tel'];
        if(empty($pwd) && empty($name) && empty($tel) && empty($email)){
            $response = [
                'erron' => '40006',
                'msg'   => 'error!'
            ];
            echo '<b style=color:red>提示：格式不能为空，请再次注册！</b>';
            return json_encode($response,true);
        }
        if($pwd2 != $pwd){
            $response = [
                'erron' => '40000',
                'msg'   => 'pwd error!'
            ];
            echo '<b style=color:red>提示：密码错误！</b>';
            return json_encode($response,true);
        }
        print_r($data);echo '</br>';echo '<hr>';
        $h = Wd::where('name',$name)->first();
        if($h){
            $response = [
                'errno'  => '40007',
                'msg'    => 'User name already exists!',
            ];
            echo '<b>是否登录--</b>'.'1.'."<a href='logins'> 使用用户名登录</a>";
            echo '2.'."<a href='login2'>使用手机号登录</a>";
            echo '3.'."<a href='login3'>使用邮箱登录</a>";
            echo '<hr>';
            echo '</br>';
            echo '<b style=color:red>提示：用户名已存在！</b>';
            return $response;


        }
        $t = Wd::where('email',$email)->first();
        if($t){
            $response = [
                'errno'  => '40008',
                'msg'    => 'email already exists!',
            ];
            echo '<b>是否登录--</b>'.'1.'."<a href='logins'> 使用用户名登录</a>";
            echo '2.'."<a href='login2'>使用手机号登录</a>";
            echo '3.'."<a href='login3'>使用邮箱登录</a>";
            echo '<hr>';
            echo '</br>';
            echo '<b style=color:red>提示：邮箱已存在！</b>';
            return $response;
        }
        $n = Wd::where('tel',$tel)->first();
        if($n){
            $response = [
                'errno'  => '40009',
                'msg'    => 'tel already exists!',
            ];
            echo '<b>是否登录--</b>'.'1.'."<a href='logins'> 使用用户名登录</a>";
            echo '2.'."<a href='login2'>使用手机号登录</a>";
            echo '3.'."<a href='login3'>使用邮箱登录</a>";
            echo '<hr>';
            echo '</br>';
            echo '<b style=color:red>提示：电话号已存在！</b>';
            return $response;
        }
        $res = Wd::insert($data);
        if($res){
           $response = [
               'errno'  => '0',
               'msg'    => 'Registration successful',
           ];
           echo '<b>是否登录--</b>'.'1.'."<a href='logins'> 使用用户名登录</a>";
           echo '2.'."<a href='login2'>使用手机号登录</a>";
           echo '3.'."<a href='login3'>使用邮箱登录</a>";


           echo '<hr>';
           echo '</br>';
           echo '<b style=color:red>提示：注册成功！</b>';
          return $response;
        }
        
        
    }

    /**
     * 
     * 密码登录
     *
     * @return void
     */
    public function pass()
    {


        print_r($_POST);echo '</br>';echo '<hr>';
        $name = $_POST['name'];
        $pwd = $_POST['pwd'];
        $token = Str::random(30);
        $r = Wd::where('name',$name)->where('pwd',$pwd)->first();
        if (!empty($r)) {
            // echo $url;echo '</br>';echo '<hr>';
            $id = $r['id'];
            $redis_key = 'str:dss:u:'.$id;
            $f =  Redis::set($redis_key,$token);
            // echo $redis_key;
            $redis_time = Redis::expire($redis_key,86400*7);
          
            $rr = [
            'erron' => '0',
            'msg'   => 'ok',
            'data' => [
                'token' => $token
           ]
        ];
            echo '❤'."<a href='token1'>点击进入验证TOKEN页面</a>".'❤';
            echo '<hr>';
            echo '</br>';
            echo '<b style=color:red>提示：登录成功！：</b>';
            return json_encode($rr, true);
        } else {
            echo '登录失败：';
            $rr = [
                'erron' => '40001',
                'msg'   => 'no',
                
            ];
            echo '<b style=color:red>提示：用户名或密码错误!：</b>';
            echo \json_encode($rr, true);
        }


    }


    /**
     * 电话登录
     *
     * @return void
     */
    public function passd()
    {
        print_r($_POST);echo '</br>';echo '<hr>';
        $name = $_POST['tel'];
        $pwd = $_POST['pwd'];
        $token = Str::random(30);
        $r = Wd::where('tel',$name)->where('pwd',$pwd)->first();
        if (!empty($r)) {
            // echo $url;echo '</br>';echo '<hr>';
            $pri='ssssss';
            $key=md5($name.$pri);
            $redis_key = 'str:ds:u:'.$key;
            $f =  Redis::set($redis_key,$token);
            // echo $redis_key;
            $redis_time = Redis::expire($redis_key,86400*7);
          
            $rr = [
            'erron' => '0',
            'msg'   => 'ok',
            'data' => [
                'token' => $token
           ]
        ];
            echo '❤'."<a href='token1'>点击进入验证TOKEN页面</a>".'❤';
            echo '<hr>';
            echo '</br>';
            echo '<b style=color:red>提示：登录成功！：</b>';
            return json_encode($rr, true);
        } else {
            echo '登录失败：';
            $rr = [
                'erron' => '40001',
                'msg'   => 'no',
                
            ];
            echo '<b style=color:red>提示：用户名或密码错误!：</b>';
            echo \json_encode($rr, true);
        }


    }


    /**
     * 邮箱登录
     *
     * @return void
     */
    public function passt()
    {
        print_r($_POST);echo '</br>';echo '<hr>';
        $name = $_POST['email'];
        $pwd = $_POST['pwd'];
        $token = Str::random(30);
        $r = Wd::where('email',$name)->where('pwd',$pwd)->first();
        if (!empty($r)) {
            // echo $url;echo '</br>';echo '<hr>';
            $pri='ssssss';
            $key=md5($name.$pri);
            $redis_key = 'str:ds:u:'.$key;
            $f =  Redis::set($redis_key,$token);
            // echo $redis_key;
            $redis_time = Redis::expire($redis_key,86400*7);
          
            $rr = [
            'erron' => '0',
            'msg'   => 'ok',
            'data' => [
                'token' => $token
           ]
        ];
            echo '❤'."<a href='token1'>点击进入验证TOKEN页面</a>".'❤';
            echo '<hr>';
            echo '</br>';
            echo '<b style=color:red>提示：登录成功！：</b>';
            return json_encode($rr, true);
        } else {
            echo '登录失败：';
            $rr = [
                'erron' => '40001',
                'msg'   => 'no',
                
            ];
            echo '<b style=color:red>提示：邮箱或密码错误!：</b>';
            echo \json_encode($rr, true);
        }


    }

    /**
     * 验证token
     *
     * @return void
     */
    public function token()
    {
        // print_r($_SERVER);die;
        if(empty($_SERVER['HTTP_TOKEN']) || empty($_SERVER['HTTP_NAME']) ){
            $response = [
                'errno'  => '40005',
                'msg'    => 'MANE OR TOKRN NOT Vailt!'
            ];
            return $response;
        }
        // print_r($_SERVER);die;
        $user_token = $_SERVER['HTTP_TOKEN'];
        $name=$_SERVER['HTTP_NAME'];
        // echo '❤.'.$user_token;
        $pri='ssssss';
        $key=md5($name.$pri);
        $redis_key = 'str:ds:u:'.$key;
        // echo $redis_key;
        $token1 = Redis::get($redis_key);
        // echo '❤.'.$token1;


        if($token1 === $user_token){
            $response = [
                'errno'  => '0',
                'msg'    => 'successful',
            ];
            echo '<b style=color:red>提示：token有效！</b>';
            return $response;
        }else{
            $response = [
                'errno'  => '0',
                'msg'    => 'token error！',
            ];
            echo '<b style=color:red>提示：token失效！</b>';
            return $response;
        }  
    }


    public function gitpull()
    {
        $cmd = 'cd /wwwroot/passport && git pull';
        shell_exec($cmd);
    }

    /**
     * 
     * 接口鉴权
     */

     public function auth()
     {
         $id = $_POST['id'];
         $token = $_POST['token'];

         if(empty($_POST['id']) || empty($_POST['token']))
         {
             $response = [
                 'error'    =>  400003,
                 'msg'      =>  'Need token or id',
             ];
             return $response;
         }
         
        $redis_key = 'str:dss:u:'.$id;
        // echo $redis_key;
        $token1 = Redis::get($redis_key);
        // echo '❤.'.$token1;die;


        if($token === $token1){
            $response = [
                'errno'  => '0',
                'msg'    => 'successful',
            ];
            echo '<b style=color:red>提示：鉴权通过！</b>';
            return $response;
        }else{
            $response = [
                'errno'  => '0',
                'msg'    => 'token error！',
            ];
            echo '<b style=color:red>提示：鉴权未通过！</b>';
            return $response;
        }
     }

     

    
}
