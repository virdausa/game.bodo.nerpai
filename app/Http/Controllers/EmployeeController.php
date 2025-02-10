<?php

namespace App\Http\Controllers;
use App\Models\Employee;
use App\Models\User;
use App\Models\CompanyUser;

use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    public function index()
    {
        $employees = Employee::with('companyuser', 'role')->get();
        return view('employees.index', compact('employees'));
    }
    public function create()
    {
        $users = CompanyUser::whereDoesntHave('employee')->get(); // List all users which not employees
        $roles = \Spatie\Permission\Models\Role::all(); // List all roles
        return view('employees.create', compact('users', 'roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:company_users,id',
            'reg_date' => 'required|date',
            'role_id' => 'required|exists:roles,id',
        ]);

        Employee::create([
            'company_user_id' => $request->user_id,
            'reg_date' => $request->reg_date,
            'status' => true, // Default status is active
            'role_id' => $request->role_id,
        ]);

        // Upgrade guest to employee
        $companyUser = CompanyUser::find($request->user_id);
        if($companyUser->user_type == 'guest'){
            $companyUser->update(['user_type' => 'employee']);
        }

        return redirect()->route('employees.index')->with('success', 'Employee assigned successfully!');
    }
    
    public function edit(Employee $employee)
    {
        $roles = \Spatie\Permission\Models\Role::all(); // List all roles
        return view('employees.edit', compact('employee', 'roles'));
    }
    
    public function update(Request $request, Employee $employee)
    {
        $request->validate([
            'reg_date' => 'required|date',
            'out_date' => 'nullable|date|after_or_equal:reg_date',
            'role_id' => 'required|exists:roles,id',
        ]);

        $employee->update([
            'reg_date' => $request->reg_date,
            'out_date' => $request->out_date,
            'status' => $request->out_date ? false : true, // Automatically update status
            'role_id' => $request->role_id,
        ]);

        return redirect()->route('employees.index')->with('success', 'Employee updated successfully!');
    }
    public function destroy(Employee $employee)
{
    $employee->delete();
    return redirect()->route('employees.index')->with('success', 'Employee deleted successfully!');
}




}
