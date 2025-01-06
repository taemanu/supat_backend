<?php

namespace App\Http\Controllers;

use App\MasterGarage;
use Illuminate\Http\Request;
use App\ProjectGarageCustomer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Haruncpi\LaravelIdGenerator\IdGenerator;

class MasterGarageController extends Controller
{
    public function list(Request $request)
    {
        $data_list = MasterGarage::get();

        return $data_list;
    }

    public function detail($id)
    {
        $data_detail = MasterGarage::where('id', $id)->first();

        return $data_detail;
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {

            $validatedData = $request->validate([
                'id_garage' => 'required',
                'steel_type' => 'required|string',
                'steel_thickness' => 'required|string',
                'steel_color' => 'required|string',
                'type_sheet' => 'required|string',
                'sheet_color' => 'required|string',
            ]);


            $config = [
                'table' => 'project_garage_customer',
                'field' => 'project_code',
                'length' => 11,
                'prefix' => 'PJ-13'.rand(1000, 9999),
                'reset_on_prefix_change' => true
            ];
            // now use it
            $project_code = IdGenerator::generate($config);

            $garage = MasterGarage::where('id', $validatedData['id_garage'])->first();

            $data = new ProjectGarageCustomer;
            $data->project_id = $validatedData['id_garage'];
            $data->project_name = $garage->project_name;
            $data->project_code = $project_code;
            $data->customer_code = 'CS-82450001';
            $data->garage_steel_type = $validatedData['steel_type'];
            $data->garage_steel_thickness = $validatedData['steel_thickness'];
            $data->garage_steel_color = $validatedData['steel_color'];
            $data->garage_sheet_type = $validatedData['type_sheet'];
            $data->garage_sheet_color = $validatedData['sheet_color'];
            $data->garage_note = $request->note ;
            $data->status = 'qt_pending';
            $data->save();


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
}
