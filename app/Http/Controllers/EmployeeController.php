<?php

namespace App\Http\Controllers;

use App\User;
use App\Slary;
use App\Employee;
use Faker\Factory;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Haruncpi\LaravelIdGenerator\IdGenerator;

class EmployeeController extends Controller
{
    public function index() {
        $data = Employee::with('user')->get();
        return $this->ok($data);
    }

    public function create() {

    }

    public function store(Request $request) {
        $user_login = Auth::user();

        $config = [
            'table' => 'employees',
            'field' => 'emp_code',
            'length' => 11,
            'prefix' => 'EM-'.rand(1000, 9999),
            'reset_on_prefix_change' => true
        ];
        // now use it
        $employee_code = IdGenerator::generate($config);

        DB::beginTransaction();
        try {
            $data = new Employee;
            $data->address = $request->address ?? '';
            $data->emp_code = $employee_code;
            // $data->role = $request->position ?? '';
            $data->position = $request->position ?? '';
            $data->salary = $request->salary ?? '';
            $data->email = $request->email ?? '';
            $data->id_card = $request->id_card ?? '';
            $data->name = $request->firstname . ' ' . $request->lastname;
            $data->firstname = $request->firstname ?? '';
            $data->lastname = $request->lastname ?? '';
            $data->gender = $request->gender ?? '';
            $data->dob = $request->dob ?? '';
            $data->start_date = $request->start_date ?? '';
            $data->note = $request->note ?? '';
            $data->employment_status = $request->employment_status;
            $data->tel = $request->tel ?? '';
            // $data->img_url = '';
            $data->line_id = $request->line_id ?? '';
            // $data->created_by = $user_login->id ;

            Log::info('Created Employee '. json_encode($data) );

            if($data){
                $password = Str::random(10);
                $user_id = 'employees-' . str_pad(rand(1, 999999), 6, '0', STR_PAD_LEFT);

                // Save user data
                $user = new User;
                $user->email = $data->email;
                $user->name = $data->firstname . ' ' . $data->lastname;
                $user->password = Hash::make($password);
                $user->password_user = $password;
                $user->user_id = $user_id;
                $user->status = 'active';
                $user->role = $request->position;
                $user->save();

                Log::info('Created User '. json_encode($user) );
            }

            if($user){
                $data->user_id = $user->id;
                $data->save();
            }

            DB::commit();

            return $this->ok([], 'บันทึกสำเร็จ !');
        } catch (\Exception $e) {
            DB::rollback();
            Log::info("error".$e);
            return $this->ERROR("ขออภัย มีปัญหาเกิดขึ้น กรุณาลองใหม่อีกครั้ง");
        }

    }

    public function edit($id) {
        $emp = Employee::find($id);
        return $this->ok($emp);
    }

    public function update(Request $request, $id) {
        DB::beginTransaction();
        try {
            $emp = new Employee;
            $emp->name = $request->name;
            $emp->role = $request->role;
            $emp->tel = $request->tel;
            $emp->slary = $request->slary;
            $emp->user_id = $request->user_id;
            $emp->save();
            DB::commit();
            return $this->ok(null);
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('error: '.$e->getMessage());
            return $this->ERROR('เกิดข้อผิดพลาดบางอย่าง !');
        }
    }

    public function destroy($id) {

    }

    public function listCompensation($id) {
        $data = Slary::where('employees_id', $id)->get();
        return $this->ok($data);
    }

    public function compensationStore(Request $request, $id) {
        DB::beginTransaction();
        try {
            $data = new Slary;
            $data->month = $request->month;
            $data->ot = $request->ot;
            $data->stop_work = $request->stop_work;
            $data->withdraw_advance = $request->withdraw_advance;
            $data->employees_id = $id;
            $data->save();
            DB::commit();
            return $this->ok(null);
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('error: '.$e->getMessage());
            return $this->ERROR('เกิดข้อผิดพลาดบางอย่าง !');
        }
    }

    public function compensationUpdate(Request $request, $id) {
        DB::beginTransaction();
        try {
            $data = Slary::find($id);
            $data->month = $request->month;
            $data->ot = $request->ot;
            $data->stop_work = $request->stop_work;
            $data->withdraw_advance = $request->withdraw_advance;
            $data->employees_id = $id;
            $data->save();
            DB::commit();
            $this->ok(null);
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('error: '.$e->getMessage());
            return $this->ERROR('เกิดข้อผิดพลาดบางอย่าง !');
        }
    }

    public function compensationDestroy($id) {
        $data = Slary::find($id);
        $data->delete();
        return $this->ok(null);
    }


    public function listEmployee()
    {
        $data_list = Employee::Select('employees.*', 'users.user_id','users.password_user')
                            ->LeftJoin('users', 'users.id', '=', 'employees.user_id')
                            ->orderBy('employees.id','desc')
                            ->get();

        return $data_list;
    }
}
