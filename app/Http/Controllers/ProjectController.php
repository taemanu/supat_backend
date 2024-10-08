<?php

namespace App\Http\Controllers;

use App\Project;
use App\Quotation;
use App\ProjectTask;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Haruncpi\LaravelIdGenerator\IdGenerator;

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
            $data->customer_code = $request->customer_code ?? '';
            $data->remake = $request->note ?? '';
            $data->status = $request->status == 1 ? 'padding' : 'success';
            $data->save();

            if(count($request->task) != 0){
                foreach($request->task as $item){
                    $task = new ProjectTask;
                    $task->p_code = $data->id;
                    $task->task_name =  $item['task'] ??'';
                    $task->percent = $item['percent'] ??'';
                    $task->save();
                }

            }

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

    public function tasktDetail($code)
    {
        $data = ProjectTask::where('p_code',$code)->get();

        return $data;
    }

    public function getProjectFromCustomer($code)
    {

        $pj_code = null;
        $project = Project::where('customer_code',$code)->first();
        $task_list = [];

        if($project){
            $pj_code = $project->p_code;


            $data = Quotation::select('projects.*','quotations.qt_code','quotations.qt_name')
                                ->leftjoin('projects','projects.id_qt','quotations.qt_code')
                                ->where('quotations.customer_code',$project->customer_code)->first();

            $task_list = ProjectTask::where('p_code',$project->id)->get();

        }else{
            $config = [
                'table' => 'projects',
                'field' => 'p_code',
                'length' => 11,
                'prefix' => 'PJ-'.rand(1000, 9999),
                'reset_on_prefix_change' => true
            ];

            $pj_code = IdGenerator::generate($config);

            $data = Quotation::select('projects.*','quotations.qt_code','quotations.qt_name','quotations.customer_code')
                            ->where('quotations.customer_code',$code)
                            ->leftjoin('projects','quotations.id','projects.id_qt')
                            ->first();
        }



        $res = [
            'pj_code' => $pj_code,
            'project_data' => $data,
            'task_list' => $task_list
        ];
        return $this->ok($res, 'OK');

    }

    public function updatePercents(Request $request)
    {
        $validatedData = $request->validate([
            'id_project' => 'required|integer',
            'percent' => 'required|numeric',
        ]);

        $project_task = ProjectTask::where('id', $request->id_project)->first();

        if ($project_task) {
            $project_task->status = 'success';
            $project_task->percent = $request->percent??0;
            $project_task->save();

            $project = Project::FindId($project_task->p_code)->first();

            if ($project) {

                $project->increment('percent', $validatedData['percent']);

                if ($project->percent == 100) {
                    $project->update(['status' => 'success']);
                }


                return $this->ok($project_task, 'success !');
            } else {

                return response()->json(['error' => 'Project not found'], 404);
            }

            return $this->ok($project_task, 'success !');
        }

        return $project_task;
    }


    public function getMaterial($id)
    {
        return $id;
    }

    public function projectPeriodDetail($code)
    {
        $project = Project::where('p_code', $code)->first();
        $quotation = Quotation::where('qt_code', $project->id_qt)->first();

        $data = [
            'project' => $project,
            'quotation' => $quotation,
        ];

        return $this->ok($data, 'success!');
    }

}
