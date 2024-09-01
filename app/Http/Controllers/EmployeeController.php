<?php

namespace App\Http\Controllers;

use App\Slary;
use App\Employee;
use App\User;
use Faker\Factory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class EmployeeController extends Controller
{
    public function index() {
        $data = Employee::with('user')->get();
        return $this->ok($data);
    }

    public function create() {

    }

    public function store(Request $request) {
        $faker = Factory::create();
        $email = $faker->email();
        $username = $faker->userName();
        DB::beginTransaction();
        try {
            $user = new User;
            $user->email = $email;
            $user->name = $request->name;
            $user->password = Hash::make('123456');
            $user->password_user = '123456';
            $user->user_id = $username;
            $user->status = 'active';
            $user->role = 'employee';
            $user->save();

            $emp = new Employee;
            $emp->name = $request->name;
            $emp->role = $request->role;
            $emp->tel = $request->tel;
            $emp->slary = $request->slary;
            $emp->user_id = $user->id;
            $emp->save();

            DB::commit();
            return $this->ok(null);
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('error: '.$e->getMessage());
            return $this->ERROR('เกิดข้อผิดพลาดบางอย่าง !');
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
}
