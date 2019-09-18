<?php
// +----------------------------------------------------------------------
// | rz
// +----------------------------------------------------------------------
// | Copyright (c) 2018 http://www.fuzuchang.com All rights reserved.
// +----------------------------------------------------------------------
// | 版权所有：昌少 
// +----------------------------------------------------------------------
// | Author: 昌少  Date:2019-09-18 Time:21:46
// +----------------------------------------------------------------------

namespace App\Http\Controllers;


use App\Code;
use App\Staff;
use App\StaffCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CodeController extends Controller
{
    public function show()
    {
        return view('code');
    }

    public function store(Request $request)
    {

        if (!$request->has('number')) {
            return response()->json([
                'data' => [],
                'msg' => '员工编号不能为空',
                'code' => '100001'
            ]);
        }

        $number = $request->input('number');

        $staff = Staff::where("number", $number)->exists();

        if (!$staff) {
            return response()->json([
                'data' => [],
                'msg' => '员工编号不存在',
                'code' => '100002'
            ]);
        }
        $staff = Staff::where('number', $number)->first();

        $isBind = StaffCode::where('staff_id', $staff->id)->exists();

        $data = [];

        if ($isBind) {
            $staffCode = StaffCode::where('staff_id', $staff->id)
                ->first(['staff_id', 'code_id']);
            $code = Code::find($staffCode->code_id, ['code']);

            $data ['number'] = $staff->number;
            $data['code'] = $code->code;
        } else {

            $code = Code::where("status", 0)->exists();

            if (!$code) {
                return response()->json([
                    'data' => [],
                    'msg' => '优惠码没有了',
                    'code' => '100003'
                ]);
            }

            // 开始事务

//            DB::beginTransaction();
            try {

                $code = Code::where('status', 0)->first();

//                dd($staff,$code);
                $code->status = 1;
                $code->save();

                $staffCode = new StaffCode();
                $staffCode->staff_id = $staff->id;
                $staffCode->code_id = $code->id;
                $staffCode->save();

                $data ['number'] = $staff->number;
                $data ['code'] = $code->code;

//                DB::commit();
            } catch (\Exception $e) {
//                DB::rollBack();
                return response()->json([
                    'data' => $data,
                    'msg' => $e->getMessage(),
                    'code' => '100004'
                ]);
            }
        }


        return response()->json([
            'data' => $data,
            'msg' => '员工编号不存在',
            'code' => '100000'
        ]);
    }

    public function showCodes()
    {
        $codes = Code::where('status', '>=', 0)->paginate();

        foreach ($codes->items() as $item) {
            $item->status_text = $item->status == 1 ? '已领取' : '未领取';
        }

        return view('codelist', ['codes' => $codes]);
    }
}