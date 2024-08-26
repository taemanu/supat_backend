<?php

namespace App\Http\Controllers;
use App\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CustomerController extends Controller
{
    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $data = new Customer;
            $data->address = $request->address ?? '';
            $data->email = $request->email ?? '';
            $data->id_tax = $request->id_card ?? '';
            $data->firstname = $request->firstname ?? '';
            $data->lastname = $request->lastname ?? '';
            $data->remark = $request->note ?? '';
            $data->status = $request->status == 1 ? 'active' : 'inactive';
            $data->tel = $request->tel ?? '';
            $data->img_url = '';
            $data->created_by = 0;
            $data->save();

            DB::commit();

            return $this->ok([], 'success !');
        } catch (\Exception $e) {
            DB::rollback();
            Log::info("error".$e);
            return $this->ERROR("Oops! There was some problem. Please try again.");
        }
    }

    public function ChangeStatus(Request $request)
    {
        $customer = Customer::FindId($request->id_customer)->first();

        if ($customer) {
            $customer->status = $customer->status === "active" ? 'inactive' : 'active';
            $customer->save();

            return $this->ok([], 'success !');
        }

        return response()->json([
            'message' => 'Customer not found.'
        ], 404);

    }

    public function listCustomer()
    {
        $data_list = Customer::orderBy('id','desc')->get();

        return $data_list;
    }

}
