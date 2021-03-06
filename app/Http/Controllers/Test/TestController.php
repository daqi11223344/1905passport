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
    // 公钥
    public function pub(){
        $data = $_POST;
        print_r($data);
        echo '<hr>';
        $data = $_POST['data'];
        $soso = $_POST['sign'];
        echo $soso;
        echo "<br>";
        $priv_key = file_get_contents(storage_path('keys/pub'));
        $sufo = openssl_verify($data,base64_decode($soso), $pubkey,OPENSSL_ALGO_SHA256);
        if($sufo){
             echo '<b style="color:red">验签成功</b>';
 
        }else{
            echo '验签失败';
        }
    }
    // public function pub(){
        //  $data = $_POST;
        //  print_r($data);
        //  $pub_key = file_get_contents(storage_path('keys/pub'));
        //  echo '<hr>';
        //  $data = $_POST['data'];
        //  $s = $_POST['sign'];
        //  $ss = md5($pub_key . $data);
        //  if($s == $ss){
        //       echo '<b style="color:red">验签成功</b>';
    
        //  }else{
        //      echo '验签失败';
        //  }
    // }

    // 解密
    public function rsa1()
    {
        $data = base64_decode($_GET['data']);
   
       // echo '1:'.$data;echo '</br>';
       $met = 'AES-256-CBC';
       $key = '1905API';
       $vi = 'WUSD8796IDjhkchd';
       $data1 = openssl_decrypt($data,$met,$key,OPENSSL_RAW_DATA,$vi);
       $arr = json_decode($data1,true);
       echo '</pre>';print_r($arr);echo '</pre>';
    }

    // 非对称解密
    public function rsa2()
    {
        $data2 = base64_decode($_GET['data']);
        echo "$data2";echo '<hr>';
   
       $pub_key = file_get_contents(storage_path('keys/pub'));
       echo 'pub_key:' . $pub_key;echo '<hr>';
       $data1 = openssl_public_decrypt($data2,$base64_str,$pub_key);
       echo '<h1 style="color:green">解密：</h1>' . $base64_str;
    }
}
