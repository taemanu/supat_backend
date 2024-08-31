<?php

namespace App\Http\Controllers;

use App\Customer;
use App\Quotation;
use App\QuotationList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Haruncpi\LaravelIdGenerator\IdGenerator;

class QuotationController extends Controller
{
    public function index() {
        $data = Quotation::all();
        return $this->ok($data, 'success !');
    }

    public function create($id) {

        $customer = Customer::findId($id)->first();
        $config = [
            'table' => 'quotations',
            'field' => 'qt_code',
            'length' => 11,
            'prefix' => 'QT'.date('Ym'),
            'reset_on_prefix_change' => true
        ];
        // now use it
        $qt_code = IdGenerator::generate($config);
        $res = [
            'qt_code' => $qt_code,
            'customer_data' => $customer
        ];
        return $this->ok($res, 'OK');
    }

    public function store(Request $request) {

        DB::beginTransaction();
        try {
            // Validate the incoming request
            $validatedData = $request->validate([
                'qt_name' => 'required|string|max:255',
                'customer_code' => 'required|string|max:50',
                'total_price' => 'required|numeric',
                'qt_list' => 'required|array|min:1',
                'qt_list.*.q_name' => 'required|string|max:255',
                'qt_list.*.amount' => 'required|numeric',
                'qt_list.*.price' => 'required|numeric',
            ]);

            $data = new Quotation;
            $data->qt_name = $validatedData['qt_name'];
            $data->customer_code = $validatedData['customer_code'];
            $data->total_price = $validatedData['total_price'];
            $data->save();

            foreach ($validatedData['qt_list'] as $item) {
                $dataList = new QuotationList;
                $dataList->quotations_id = $data->id;
                $dataList->q_name = $item['q_name'];
                $dataList->amount = $item['amount'];
                $dataList->price = $item['price'];
                $dataList->save();
            }

            DB::commit();
            return $this->ok($data, 'บันทึกข้อมูลเรียบร้อยแล้ว');

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollback();
            Log::error('Validation failed: ' . json_encode($e->errors()));
            return $this->ERROR('ข้อมูลไม่ถูกต้อง: ' . json_encode($e->errors()));
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error saving quotation: ' . $e->getMessage());
            return $this->ERROR('เกิดข้อผิดพลาด: ' . $e->getMessage());
        }
    }

    public function edit($id) {
        $data = Quotation::with('quotationList')->find($id);
        return $this->ok($data,'ok');
    }

    public function update(Request $request, $id) {
        DB::beginTransaction();
        try {
            // Validate the incoming request
            $validatedData = $request->validate([
                'qt_name' => 'required|string|max:255',
                'customer_code' => 'required|string|max:50',
                'total_price' => 'required|numeric',
                'qt_list' => 'required|array|min:1',
                'qt_list.*.q_name' => 'required|string|max:255',
                'qt_list.*.amount' => 'required|numeric',
                'qt_list.*.price' => 'required|numeric',
            ]);

            $data = Quotation::find($id);
            $data->qt_name = $validatedData['qt_name'];
            $data->customer_code = $validatedData['customer_code'];
            $data->total_price = $validatedData['total_price'];
            $data->save();
            QuotationList::where('quotations_id',$data->id)->delete();
            foreach ($validatedData['qt_list'] as $item) {
                $dataList = new QuotationList;
                $dataList->quotations_id = $data->id;
                $dataList->q_name = $item['q_name'];
                $dataList->amount = $item['amount'];
                $dataList->price = $item['price'];
                $dataList->save();
            }

            DB::commit();
            return $this->ok($data, 'บันทึกข้อมูลเรียบร้อยแล้ว');

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollback();
            Log::error('Validation failed: ' . json_encode($e->errors()));
            return $this->ERROR('ข้อมูลไม่ถูกต้อง: ' . json_encode($e->errors()));
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error saving quotation: ' . $e->getMessage());
            return $this->ERROR('เกิดข้อผิดพลาด: ' . $e->getMessage());
        }
    }

    public function destroy($id) {
        DB::beginTransaction();
        try {
            $data = Quotation::find($id);
            $data->delete();

            DB::commit();

        } catch (\Exception $e) {
            DB::rollback();
            return $this->ERROR('Something went wrong. Please try again.');
        }

        return $this->ok([],'success !');
    }
}
