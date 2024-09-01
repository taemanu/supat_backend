<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\PurchaseOrder;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class PurchaseOrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function listPO($type) {

        $data = PurchaseOrder::where(function($q) use ($type) {
            if ($type == 'all') {
                $q->whereIn('status', ['pending', 'success', 'cancel']);
            } else {
                $q->whereIn('status', [$type]);
            }
        })
        ->orderBy('id','desc')
        ->get();
        return $this->ok($data, 'success !');
    }

    public function listPoProject($p_code) {

        $data = PurchaseOrder::where('code_project',$p_code)
        ->orderBy('id','desc')
        ->get();
        return $this->ok($data, 'success !');
    }

    public function createPO(Request $request) {

        $validated = $request->validate([
            'name' => 'required',
            'code_number' => 'required',
            'code_project' => 'required',
            'price' => 'required',
        ]);

        DB::beginTransaction();
        try {
            $data = new PurchaseOrder;
            $data->name = $request->name;
            $data->code_number = $request->code_number;
            $data->code_project = $request->code_project;
            $data->price = $request->price;
            $data->status = 'pending';
            $data->save();

            DB::commit();

            return $this->ok([], 'success !');
        } catch (\Exception $e) {
            DB::rollback();

            return $this->ERROR("Oops! There was some problem. Please try again.");
        }
    }

    public function approval($type, $id) {

        $data = PurchaseOrder::find($id);
        $data->status = $type=='approve'?'success':'cancel';
        $data->save();
        return $this->ok([], 'success !');
    }
}
