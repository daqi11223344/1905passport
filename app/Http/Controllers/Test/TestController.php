<?php

namespace App\Http\Controllers\Test;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TestController extends Controller
{
    public function md5post()
    {
        echo "<h1 style='color:red'>接收端>>>>>> </h1>"; echo '</br>';
        echo '<pre>';print_r($_GET);echo '</pre>';

        $key = "111";          // 计算签名的KEY 与发送端保持一致

        //验签
        $data = $_GET['data'];  //接收到的数据
        $signature = $_GET['signature'];    //发送端的签名

        // 计算签名
        echo "<h3 style='color:blue'>接收到的签名：</h3>". $signature;echo '</br>';
        $reg = md5($data.$key);
        echo "<h3 style='color:blue'>接收端计算的签名：</h3>". $reg;echo '</br>';

        //与接收到的签名 比对
        if($reg == $signature)
        {
            echo "<b style='color:green'>验签通过</b>";
        }else{
            echo "<b style='color:red'>验签失败</b>";
        }
    }

    public function checks()
    {
        $key = "111";
        echo '<pre>';print_r($_POST);

        // 接受
        $json = $_POST['order'];
        // dd($json);
        $reg = $_POST['reg'];

        $signs = md5($json.$key);
        echo "<h1 style='color:red'>接受端计算的签名：</h1>" . $signs;
        echo '<br>';

        if($signs==$reg){
            echo "<b style='color:green'>验签通过</b>";
        }else{
            echo "<b style='color:red'>验签失败</b>"; 
        }
    }

    public function priv(){
        $data = $_POST;
        print_r($data);
        $priv_key = file_get_contents(storage_path('keys/priv'));
        echo '<hr>';
        $data = $_POST['data'];
        $s = $_POST['sign'];
        $ss = md5($priv_key . $data);
        if($s == $ss){
             echo '<b style="color:red">验签成功</b>';
 
        }else{
            echo '验签失败';
        }
    }
    public function pub(){
     $data = $_POST;
     print_r($data);
     $pub_key = file_get_contents(storage_path('keys/pub'));
     echo '<hr>';
     $data = $_POST['data'];
     $s = $_POST['sign'];
     $ss = md5($pub_key . $data);
     if($s == $ss){
          echo '<b style="color:red">验签成功</b>';
 
     }else{
         echo '验签失败';
     }
    }
}
