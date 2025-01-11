<?php

namespace App\Http\Controllers;
use App\Customer;
use Illuminate\Http\Request;
use App\ProjectGarageCustomer;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Auth;
use Haruncpi\LaravelIdGenerator\IdGenerator;


class CustomerController extends Controller
{
    public function store(Request $request)
    {

        $user_login = Auth::user();

        $config = [
            'table' => 'customers',
            'field' => 'customer_code',
            'length' => 11,
            'prefix' => 'CS-'.rand(1000, 9999),
            'reset_on_prefix_change' => true
        ];
        // now use it
        $customer_code = IdGenerator::generate($config);

        DB::beginTransaction();
        try {
            $data = new Customer;
            $data->address = $request->address ?? '';
            $data->customer_code = $customer_code;
            $data->email = $request->email ?? '';
            $data->id_tax = $request->id_card ?? '';
            $data->firstname = $request->firstname ?? '';
            $data->lastname = $request->lastname ?? '';
            $data->remark = $request->note ?? '';
            $data->status = $request->status == 1 ? 'active' : 'inactive';
            $data->tel = $request->tel ?? '';
            $data->img_url = '';
            $data->line_id = $request->line_id ?? '';
            $data->created_by = $user_login->id ;
            $data->save();

            DB::commit();

            return $this->ok([], 'บันทึกสำเร็จ !');
        } catch (\Exception $e) {
            DB::rollback();
            Log::info("error".$e);
            return $this->ERROR("ขออภัย มีปัญหาเกิดขึ้น กรุณาลองใหม่อีกครั้ง");
        }
    }

    public function ChangeStatus(Request $request)
    {
        $customer = Customer::FindId($request->id_customer)->first();

        if ($customer) {
            $customer->status = $customer->status === "active" ? 'inactive' : 'active';
            $customer->save();

            return $this->ok([], 'บันทึกสำเร็จ !');
        }

        return response()->json([
            'message' => 'ขออภัย มีปัญหาเกิดขึ้น กรุณาลองใหม่อีกครั้ง'
        ], 404);

    }

    public function listCustomer()
    {
        $data_list = Customer::Select('customers.*','quotations.id as id_qt')->LeftJoin('quotations', 'quotations.customer_code', '=', 'customers.customer_code')->orderBy('customers.id','desc')->get();

        return $data_list;
    }

    public function listProjectCustomer()
    {
        $data_list = ProjectGarageCustomer::Select('customers.*','project_garage_customer.*','quotations.id as qt_id')
        ->LeftJoin('customers','customers.customer_code', '=', 'project_garage_customer.customer_code')
        ->LeftJoin('quotations','quotations.project_id', '=', 'project_garage_customer.id')->get();

        return $data_list;
    }

}
