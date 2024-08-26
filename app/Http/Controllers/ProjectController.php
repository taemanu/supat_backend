<?php

namespace App\Http\Controllers;

use App\Project;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProjectController extends Controller
{
    // public function index($type) {
    //     $data = Project::where('status', $type)->get();
    //     return $this->ok($data, 'ok');
    // }

    // public function create() {
    //     $config = [
    //         'table' => 'projects',
    //         'field' => 'p_code',
    //         'length' => 11,
    //         'prefix' => 'P'.date('Ym'),
    //         'reset_on_prefix_change' => true
    //     ];
    //     // now use it
    //     $p_code = IdGenerator::generate($config);
    //     $res = [
    //         'p_code' => $p_code
    //     ];
    //     return $this->ok($res, 'ok');
    // }

    // public function store(Request $request) {
    //     $validatedData = $request->validate([
    //         'p_name' => 'required',
    //         'quotations_id' => 'required',
    //         'date_start' => 'required',
    //         'date_end' => 'required',
    //         'file_work_form' => 'required'
    //     ]);

    //     DB::beginTransaction();
    //     try {
    //         $data = new Project;
    //         $data->p_name = $validatedData['p_name'];
    //         $data->quotations_id = $validatedData['quotations_id'];
    //         $data->date_start = $validatedData['date_start'];
    //         $data->date_end = $validatedData['date_end'];
    //         $data->file_work_form = $validatedData['file_work_form'];
    //         $data->save();

    //         DB::commit();
    //         return $this->ok($data, 'บันทึกข้อมูลเรียบร้อยแล้ว');

    //     } catch (\Illuminate\Validation\ValidationException $e) {
    //         DB::rollback();
    //         Log::error('Validation failed: ' . json_encode($e->errors()));
    //         return $this->ERROR('ข้อมูลไม่ถูกต้อง: ' . json_encode($e->errors()));
    //     } catch (\Exception $e) {
    //         DB::rollback();
    //         Log::error('Error saving quotation: ' . $e->getMessage());
    //         return $this->ERROR('เกิดข้อผิดพลาด: ' . $e->getMessage());
    //     }
    // }

    // public function edit($id) {

    // }

    // public function update(Request $request, $id) {

    // }

    // public function approval($type, $id) {

    //     $data = Project::find($id);
    //     $data->status = $type=='approve'?'success':'cancel';
    //     $data->save();
    //     return $this->ok([], 'success !');
    // }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $data = new Project;
            $data->p_code = $request->id_project ?? '';
            $data->p_name = $request->name_project ?? '';
            $data->id_qt = $request->code_quo ?? '';
            $data->date_start = $request->date_contract ?? '';
            $data->date_end = $request->date_end ?? '';
            $data->remake = $request->note ?? '';
            $data->status = $request->status == 1 ? 'pedding' : 'success';
            $data->save();

            DB::commit();

            return $this->ok([], 'success !');
        } catch (\Exception $e) {
            DB::rollback();
            Log::info("error".$e);
            return $this->ERROR("Oops! There was some problem. Please try again.");
        }
    }

    public function listProject()
    {
        $data = [
            'data_list' => [],
            'data_list_pedding' => [],
        ];

        $data['data_list'] = Project::where('status', '!=', 'padding')->orderBy('id', 'desc')->get();
        $data['data_list_pedding'] = Project::where('status', 'padding')->orderBy('id', 'desc')->get();


        return $data;
    }

    public function ChangeStatus(Request $request)
    {
        $customer = Project::FindId($request->id_project)->first();

        if ($customer) {
            $customer->status = $request->type === "approve" ? 'success' : 'reject';
            $customer->save();

            return $this->ok([], 'success !');
        }

        return response()->json([
            'message' => 'Project not found.'
        ], 404);

    }

    public function projectDetail($code)
    {
        $data = Project::where('p_code',$code)->first();

        return $data;
    }


}
