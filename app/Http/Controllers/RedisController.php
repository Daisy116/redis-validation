<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redis;
use Illuminate\Http\Request;

class RedisController extends Controller
{
    /**
     * 显示给定用户的配置文件。
     *
     * @param  int  $id
     * @return Response
     */
    public function test1()
    {
        Redis::set('key', 'thuesday');
        $redis = Redis::get('key');
        echo $redis . "<hr>";

        Redis::set('product:1:sales', 1000);
        Redis::set('product:1:count', 10);
        $redis2 = Redis::get('product:1:sales');
        echo $redis2 . "<hr>";

        $redis3 = Redis::incr('product:1:count');           // product:1:count 值加1
        $redis4 = Redis::incrby('product:1:sales', 100);    // product:1:sales 值加100
        echo $redis3 . " " . $redis4 . "<hr>";

        Redis::getset('product:1:sales', 1);        // 取得舊值,並設成新值(但此時是舊值!!)
        $value = Redis::get('product:1:sales');     // 取得新值
        echo $value . "<hr>";

        Redis::set('user:1:notified', 1, 'EX', 1);   // key(user:1:notified) 会在 5 秒之后过期
        echo "1秒前<br>";
        if(Redis::get('user:1:notified')){
            echo "key存在";
        }else{
            echo "key不存在";
        }
        sleep(1);
        echo "<br>1秒後<br>";
        $value2 = Redis::exists('ticket:sold');    // exists回傳boolean值(例如false)
        // dd($value2);
        var_dump($value2);

        echo "<hr><hr>";

        $value3 = Redis::mget('product:1:sales', 'product:2:sales', 'non_existing_key');
        // 一次讀取多個值,但,怎麼顯示??
        // echo $value3 . "<hr>";
    }

    public function test2(){
        $redis = Redis::connection();   //  使用多个 Redis 连接??
        // dd($redis);

        // pipeline 方法接收一个参数: Closure ，它会接收 Redis 的实例。
        // 你可以在闭包中发布所有的命令，它们将会在一个操作中进行处理??????
        Redis::pipeline(function ($pipe) {
            for ($i = 0; $i < 10; $i++) {
                $pipe->set("key:$i", $i);
                $echo = $pipe->get("key:$i");
                echo '<pre>';
                // var_dump($echo);
            }
        });


        $redis2 = Redis::connection();
        $handle = $redis2->multi();
        $handle = Redis::incr('a');
        $handle = Redis::incr('b');
        // var_dump($handle);
        $v = Redis::get('a');
        echo $v;


        // $redis = new Redis();
        // $pipe = $redis->multi(Redis::PIPELINE);
        // for ($i = 0; $i < 10000; $i++) {
        //     $pipe->set("key::$i", str_pad($i, 4, '0', 0));
        //     $pipe->get("key::$i");
        // }
        // $replies = $pipe->exec();
        // var_dump($replies);       
    }

    public function test3(){
        Redis::set('myname', 'ikodota');       # Redis::set
        echo Redis::get('myname') . '<br>';    # Redis::get

        echo Redis::del('myname') . '<br>';    # Redis::del   返回 TRUE(1)
        var_dump(Redis::get('myname'));        # 返回 Null

        echo '<br>';
        if(!Redis::exists('fake_key')){        # Redis::exists
            echo "不存在" . '<br>';
        }         
        var_dump(Redis::del('fake_key'));      # 返回 int(0)

        echo '<hr>';
        $array_mset = array('first_key'=>'first_val',
          'second_key'=>'second_val',
          'third_key'=>'third_val');
        Redis::mset($array_mset);              # Redis::mset  ,用MSET一次储存多个值

        $array_mget = array('first_key','second_key','third_key');
        var_dump(Redis::mget($array_mget));    # Redis::mget  ,一次返回多个值(array) 

        echo '<br>';
        Redis::del($array_mget);               #同时删除多个key
        var_dump(Redis::mget($array_mget)); 

        echo '<hr>';
        Redis::FLUSHALL();
        $mset_keys = array('one'=>'1',
          'two'=>'2',
          'three '=>'3',
          'four'=>'4');
        Redis::mset($mset_keys);          #用MSET一次储存多个值
        // var_dump(Redis::keys('o*'));           
        // var_dump(Redis::keys('t??')); 
        // var_dump(Redis::keys('t[w]*')); 
        print_r(Redis::keys('*'));        //結果怪  Array ( [0] => four [1] => three [2] => two [3] => one )
    }

    public function set($id,$content)
    {
        Redis::set($id, $content);
        echo Redis::get($id) . '<br>';
 
        var_dump(Redis::keys($id));      // 會自動加上laravel_database_  ??
    }

    public function get()
    {
        $name = Redis::keys('*');
        var_dump($name);                // 內容不對
    }
}
