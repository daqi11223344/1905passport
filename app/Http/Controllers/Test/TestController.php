<?php

namespace App\Http\Controllers\Test;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TestController extends Controller
{
    public function md5post()
    {
        echo "<h1 style='color:red'>接收端》》》》》</h1>"; echo '</br>';
        echo '<pre>';print_r($_GET);echo '</pre>';

        $key = "1905";          // 计算签名的KEY 与发送端保持一致

        //验签
        $data = $_GET['data'];  //接收到的数据
        $signature = $_GET['signature'];    //发送端的签名

        // 计算签名
        echo "<h3 style='color:blue'>接收到的签名：</h3>". $signature;echo '</br>';
        $s = md5($data.$key);
        echo "<h3 style='color:blue'>接收端计算的签名：</h3>". $s;echo '</br>';

        //与接收到的签名 比对
        if($s == $signature)
        {
            echo "<b style='color:green'>验签通过</b>";
        }else{
            echo "<b style='color:red'>验签失败</b>";
        }

        // echo '11111111111';
    }
}
