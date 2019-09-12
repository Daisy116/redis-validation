<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redis;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TestController extends Controller
{
    public function test(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'id' => 'required|integer|between:1,10',   // id不能为空,必须是数字，且长度是1到10
            'title' => 'required|string',              // title必须是字符串，且不能为空
            'nickname' => 'required|max:8|string',     // nickname必须是字符串，且不能为空，不能超過8字元
            'signature' => 'string|max:30',            // signature可為空
            'height' => ' digits_between:5,10',        // digits_between是長度檢查!!
            'weight' => ' integer|min:15|max:100',        // 這裡的min: max: 是數字大小的比較...
            'birth_date' => 'date',
         ]);

        //  'birth_date' => 'date',
        //  'city' => 'string',
        //  'order_number' => 'exists:orders,order_number',
        //  'openid' => 'exists:orders,openid',
        //  'activity_id' => 'required'

        if($validate->fails())
        {
            return $validate->errors()->all();
        }else{
            return "一切ok!";
        }
    }
}