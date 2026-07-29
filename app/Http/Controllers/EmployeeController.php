<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\Facades\DataTables;
use App\Models\JobType;
use App\Models\Leave;
use App\Models\LeaveCount;
use App\Models\Department;
use App\Models\Designation;
use App\Models\Project;
use App\Models\Task;
use App\Models\TaskUpdate;
use App\Models\EmployeeOffboard;
use App\Models\EmployeeEducation;
use App\Models\EmployeeExperience;
use App\Exports\EmployeeExport;
use App\Exports\OnboardEmployeeExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Auth;
use App\Models\EmployeeLeave;
class EmployeeController extends Controller
{

    // Employee List Page
    public function index()
    {
        $jobTypes = JobType::where('status', 'active')->get();
        $departments = Department::where('status', 'active')->orderBy("name","asc")->get();
        $designations = Designation::where('status', 'active')->orderBy("name","asc")->get();
        $managers = Employee::where('status', '1')->orderBy("name","asc")->get();

        return view(
            'pages.employee-list',
            compact(
                'jobTypes',
                'departments',
                'managers',
                'designations'
            )
        );
    }

    public function resetPassword($id)
    {
        $employee = Employee::find($id);

        if (!$employee) {
            return response()->json([
                'status' => false,
                'message' => 'Employee not found.'
            ], 404);
        }

        // First 3 letters of name
        $namePart = ucfirst(strtolower(substr(trim($employee->name), 0, 3)));

        // Last 3 digits of contact number
        $phonePart = substr(preg_replace('/\D/', '', $employee->contact_no), -3);

        // Password Format: San@210
        $defaultPassword = $namePart . '@' . $phonePart;

        $employee->password = Hash::make($defaultPassword);
        $employee->save();

        return response()->json([
            'status' => true,
            'message' => 'Password has been reset successfully.',
            'password' => $defaultPassword // Optional: Remove this in production if you don't want to expose it.
        ]);
    }
    // DataTable Ajax
    public function employeeList(Request $request)
    {

        if ($request->ajax()) {

            $employees = Employee::with([
                'reportingManager',
                'designation',
                'department'
            ])

                ->when($request->status != '', function ($query) use ($request) {

                    $query->where('status', $request->status);
                })

                ->when($request->job_type != '', function ($query) use ($request) {

                    $query->where('job_type', $request->job_type);
                })

                ->when($request->department_id != '', function ($query) use ($request) {

                    $query->where('department_id', $request->department_id);
                })

                ->when($request->designation_id != '', function ($query) use ($request) {
                    $query->where('designation_id', $request->designation_id);
                })
                ->where("onboard_status", "Completed")
                ->latest();

            return DataTables::of($employees)

                ->addIndexColumn()

                ->addColumn('employee_name', function ($row) {

                    return $row->name;
                })

                ->addColumn('status_badge', function ($row) {

                    if ($row->status == 1) {

                        return '<span class="badge text-bg-success">
                                    Active
                                </span>';
                    } elseif($row->status == 2){
                        return '<span class="badge text-bg-warning">
                                    Resigned
                                </span>';
                    }
                    else{

                        return '<span class="badge text-bg-danger">
                        In Active
                        </span>';
                    }
                })

                ->addColumn('reporting_manager', function ($row) {

                    return $row->reportingManager->name ?? '-';
                })

                ->addColumn('designation_name', function ($row) {

                    return $row->designation->name ?? '-';
                })
                ->addColumn('action', function ($row) {

                    $editUrl = route('employees.edit', $row->id);

                    return '

                        <div class="d-flex gap-1">

                            <button type="button"
                                    class="btn btn-sm btn-primary"
                                    onclick="viewEmployee('.$row->id.')"  title="View">

                                <i class="bi bi-eye"></i> 

                            </button>

                            <button type="button"
                                    class="btn btn-sm btn-success text-white"
                                    onclick="editEmployee('.$row->id.')" title="Edit">

                                <i class="bi bi-pencil-square"></i> 

                            </button>
                            <button type="button"
                                    class="btn btn-sm btn-danger"
                                    onclick="resignEmployee('.$row->id.')"  title="Resign">

                                <i class="bi bi-person-x"></i> 

                            </button>
                            <button type="button"
                                    class="btn btn-sm btn-warning"
                                    onclick="resetPassword('.$row->id.')" title="Reset Password">

                                <i class="bi bi-key"></i>

                            </button>

                        </div>

                        ';
                })

                ->rawColumns([
                    'status_badge',
                    'onboard_badge',
                    'action'
                ])

                ->make(true);
        }
    }
    
    public function resignEmployee(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:employees,id'
        ]);

        $employee = Employee::findOrFail($request->id);

        $employee->status = 2;
        $employee->save();
        EmployeeOffboard::create([
            'employee_id'      => $employee->id,
            // 'resignation_date' => now()->toDateString(),
            'status'           => 'pending', // or 0 if integer
            'created_by'       => auth()->id(),
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Employee resigned successfully.'
        ]);
    }
    public function exportEmployees(Request $request)
    {
        return Excel::download(

            new EmployeeExport(

                $request->status,

                $request->job_type,

                $request->department_id,

                $request->designation_id

            ),

            'employees.xlsx'
        );
    }

    public function employeeDirectory()
    {
        $jobTypes = JobType::where('status', 'active')->get();
        $departments = Department::where('status', 'active')->orderBy("name","asc")->get();
        $designations = Designation::where('status', 'active')->orderBy("name","asc")->get();
        $managers = Employee::where('status', '1')->orderBy("name","asc")->get();
        return view(
            'pages.onboard-list',
            compact(
                'jobTypes',
                'departments',
                'designations',
                'managers'

            )
        );
    }

    public function changePassword(Request $request, $id)
    {
        $request->validate([
            'password' => 'required|min:6'
        ]);

        $employee = Employee::findOrFail($id);

        $employee->password = Hash::make($request->password);
        $employee->save();

        return response()->json([
            'status' => true,
            'message' => 'Password changed successfully.'
        ]);
    }
    public function employeeOnboardList(Request $request)
    {
        if ($request->ajax()) {

            $employees = Employee::with([
                'reportingManager',
                'designation',
                'department'
            ])

                ->when($request->status != '', function ($query) use ($request) {

                    $query->where('status', $request->status);
                })

                ->when($request->onboard_status != '', function ($query) use ($request) {

                    $query->where('onboard_status', $request->onboard_status);
                })

                ->when($request->job_type != '', function ($query) use ($request) {

                    $query->where('job_type', $request->job_type);
                })

                ->when($request->department_id != '', function ($query) use ($request) {

                    $query->where('department_id', $request->department_id);
                })

                ->when($request->designation_id != '', function ($query) use ($request) {

                    $query->where('designation_id', $request->designation_id);
                })
                ->where("onboard_status", "!=", "Completed")
                ->latest();

            return DataTables::of($employees)

                ->addIndexColumn()

                ->addColumn('employee_name', function ($row) {

                    return $row->name;
                })

                ->addColumn('designation_name', function ($row) {

                    return $row->designation->name ?? '-';
                })

                ->addColumn('reporting_manager', function ($row) {

                    return $row->reportingManager->name ?? '-';
                })

                ->addColumn('status_badge', function ($row) {

                    if ($row->status == 1) {

                        return '<span class="badge text-bg-success">
                                    Active
                                </span>';
                    } else {

                        return '<span class="badge text-bg-danger">
                                    Inactive
                                </span>';
                    }
                })

                ->addColumn('onboard_badge', function ($row) {

                    if ($row->onboard_status == 'Pending') {

                        return '<span class="badge text-bg-warning">
                                    Pending
                                </span>';
                    } elseif ($row->onboard_status == 'Completed') {

                        return '<span class="badge text-bg-success">
                                    Completed
                                </span>';
                    } else {

                        return '<span class="badge text-bg-primary">
                                    Profile Created
                                </span>';
                    }
                })

                ->addColumn('action', function ($row) {

                    $editUrl = route('employees.edit', $row->id);

                    return '

                        <div class="d-flex gap-1">

                            <button type="button"
                                    class="btn btn-sm btn-primary viewEmployee"
                                    data-id="' . $row->id . '"
                                    data-bs-toggle="modal"
                                    data-bs-target="#viewEmployeeModal">

                                <i class="bi bi-eye"></i> 

                            </button>

                            <button type="button"
                                    class="btn btn-sm btn-warning text-white editEmployee"
                                    data-id="' . $row->id . '"
                                    data-bs-toggle="modal"
                                    data-bs-target="#addEmployeeModal">

                                <i class="bi bi-pencil-square"></i> 

                            </button>
                            <button type="button"
                                    class="btn btn-sm btn-danger text-white "
                                    onClick="removeEmployee(' . $row->id . ')">

                                <i class="bi bi-trash"></i> 

                            </button>
                            <button type="button"
                                    class="btn btn-sm btn-success text-white "
                                    onClick="verifyEmployee(' . $row->id . ')">

                                <i class="bi bi-check"></i> 

                            </button>

                        </div>

                    ';
                })

                ->rawColumns([
                    'status_badge',
                    'onboard_badge',
                    'action'
                ])

                ->make(true);
        }
    }
    public function destroy($id)
    {
        $employee = Employee::find($id);

        if (!$employee) {
            return response()->json([
                'status' => false,
                'message' => 'Employee not found.'
            ], 404);
        }

        $employee->delete();

        return response()->json([
            'status' => true,
            'message' => 'Employee deleted successfully.'
        ]);
    }
    public function employeeDetails($id)
    {
        $employee = Employee::with([
            'designation',
            'department',
            'reportingManager',
            'educations',
            'experiences'
        ])->findOrFail($id);
        $employee->photo_url = $employee->photo
            ? asset('storage/employees/photos/' . $employee->photo)
            : asset('assets/img/user.png');
        $employee->passbook = $employee->passbook
            ? asset('storage/employees/passbook/' . $employee->passbook)
            : "-";
        return response()->json($employee);
    }

    public function verifyEmployee($id)
    {
        $employee = Employee::find($id);

        if (!$employee) {

            return response()->json([
                'status' => false,
                'message' => 'Employee not found.'
            ], 404);

        }

        if ($employee->onboard_status == 'Completed') {

            return response()->json([
                'status' => false,
                'message' => 'Employee is already verified.'
            ], 400);

        }

        $employee->onboard_status = 'Completed';
        $employee->save();

        return response()->json([
            'status' => true,
            'message' => 'Employee verified successfully.'
        ]);
    }

    public function onboardExport(Request $request)
    {
        return Excel::download(

            new OnboardEmployeeExport(
                $request->status,
                $request->job_type,
                $request->department_id,
                $request->designation_id,
                $request->onboard_status
            ),

            'onboard-employees.xlsx'
        );
    }
    // Add Employee
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:employees,email',
            'emp_id' => 'required|unique:employees,emp_id',
            'department_id' => 'required',
            'designation_id' => 'required',
            'job_type' => 'required',
        ]);
        $namePrefix = ucfirst(strtolower(substr(trim($request->name), 0, 3)));
        $password = $namePrefix . '@123';

        Employee::create([
            'name' => $request->name,
            'email' => $request->email,
            'emp_id' => $request->emp_id,
            'department_id' => $request->department_id,
            'designation_id' => $request->designation_id,
            'job_type' => $request->job_type,
            'reporting_manager_id' => $request->reporting_manager_id,
            'joining_date' => $request->joining_date,
            'confirm_date' => $request->confirm_date,
            'status' => 1,
            'onboard_status' => 'Pending',
            'password' => Hash::make($password),
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Employee added successfully',
            'password' => $password,

        ]);
    }
    // Edit Employee Page
    public function edit($id)
    {
        $employee= Employee::with([
            'department',
            'designation',
            'reportingManager',
            'educations',
            'experiences'
        ])->findOrFail($id);
         $employee->photo = $employee->photo
            ? asset('storage/employees/photos/' . $employee->photo)
            : asset('assets/img/user.png');
        return response()->json($employee);
    }

    public function updatePhoto(Request $request)
    {
        $request->validate([
            'id'    => 'required|exists:employees,id',
            'photo' => 'required|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        $employee = Employee::findOrFail($request->id);

        if ($request->hasFile('photo')) {
           
            // Delete old photo
            if (!empty($employee->photo) &&
                Storage::disk('public')->exists('employees/photos/' . $employee->photo)) {

                Storage::disk('public')->delete('employees/photos/' . $employee->photo);
            }

            // Generate filename
            $extension = $request->file('photo')->getClientOriginalExtension();

            $filename = now()->format('YmdHis') . '_' . $employee->id . '.' . $extension;

            // Store image
            $request->file('photo')->storeAs(
                'employees/photos',
                $filename,
                'public'
            );

            // Save filename only
            $employee->photo = $filename;
            $employee->save();
        }

        return response()->json([
            'status'=>true,
            'message'=>'Profile photo updated successfully.'
        ]);
    }
    // Update Employee
    public function update(Request $request, $id)
    {

        $employee = Employee::findOrFail($id);

        $request->validate([

            'name' => 'required',

            'email' => 'required|email|unique:employees,email,' . $id,

        ]);

        $employee->update([

            'name' => $request->name,

            'email' => $request->email,

            'emp_id' => $request->emp_id,

            'dob' => $request->dob,

            'designation' => $request->designation,

            'contact_no' => $request->contact_no,

            'alt_contact_no' => $request->alt_contact_no,

            'type' => $request->type,

            'job_type' => $request->job_type,

            'reporting_manager_id' => $request->reporting_manager_id,

            'joining_date' => $request->joining_date,
            'confirm_date' => $request->confirm_date,

            'pan_no' => $request->pan_no,

            'aadhar_no' => $request->aadhar_no,

            'status' => $request->status,

        ]);

        if ($request->password) {

            $employee->update([

                'password' => Hash::make($request->password)

            ]);
        }

        return redirect()
            ->route('employees.index')
            ->with('success', 'Employee updated successfully');
    }
    // Delete Employee
   

    public function employeesByDepartment(Request $request)
    {
        return Employee::where(
            'department_id',
            $request->department_id
        )
            ->select('id', 'name')
            ->get();
    }

    public function getActiveEmployees()
    {
        $employees = Employee::with([
            'department',
            'designation',
            'reportingManager'
        ])
            ->where('status', 1)
            ->orderBy('name')
            ->get();

        return response()->json($employees);
    }

    public function updateProfile(Request $request)
    {
        $employee = Employee::findOrFail($request->id);

        $validated = $request->validate([

            'name' => 'required|max:150',

            'emp_id' => [
                'required',
                Rule::unique('employees', 'emp_id')->ignore($employee->id)
            ],

            'email' => [
                'nullable',
                'email',
                Rule::unique('employees', 'personal_email')->ignore($employee->id)
            ],

            'phone' => 'required|digits_between:10,15',

            'dob' => 'nullable|date',

            'gender' => 'nullable',

            'blood_group' => 'nullable',

            'marital_status' => 'nullable',

            'guardian_name' => 'nullable|max:150',

            'address' => 'nullable',

            'nationality' => 'nullable',

            'emergency_phone' => 'nullable|digits_between:10,15',

        ]);

        $employee->update([

            'name' => $request->name,
            'emp_id' => $request->emp_id,
            'personal_email' => $request->email,
            'contact_no' => $request->phone,
            'dob' => $request->dob,
            'gender' => $request->gender,
            'blood_group' => $request->blood_group,
            'marital_status' => $request->marital_status,
            'parent_name' => $request->guardian_name,
            'address' => $request->address,
            'nationality' => $request->nationality,
            'alt_contact_no' => $request->emergency_phone,

        ]);

        return response()->json([
            'status' => true,
            'message' => 'Profile updated successfully.'
        ]);
    }


    public function updateOfficial(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:employees,id',
            'emp_id' => 'required|max:50',
            'official_email' => 'nullable|email|max:255',
            'department_id' => 'nullable|exists:departments,id',
            'designation_id' => 'nullable|exists:designations,id',
            'reporting_manager' => 'nullable|exists:employees,id',
            'joining_date' => 'nullable|date',
            'job_type' => 'nullable|in:Permanent,Temporary,Trainee,Contract',
            'work_mode' => 'nullable|in:Office,Remote,Hybrid',
            'employee_status' => 'nullable|in:Active,Inactive,Resigned',
            'work_location' => 'nullable|string|max:255',
        ]);

        try {

            $employee = Employee::findOrFail($request->id);

            $employee->update([
                'emp_id'             => $request->emp_id,
                'official_email'     => $request->official_email,
                'department_id'      => $request->department_id,
                'designation_id'     => $request->designation_id,
                'reporting_manager_id'  => $request->reporting_manager,
                'joining_date'       => $request->joining_date,
                'job_type'           => $request->job_type,
                'work_mode'          => $request->work_mode,
                'employee_status'    => $request->employee_status,
                'work_location'      => $request->work_location,
                'status'      => $request->status,
            ]);
            if ($request->status != 2) {
                EmployeeOffboard::where('employee_id', $employee->id)->delete();
            }else{
                EmployeeOffboard::create([
                    'employee_id'      => $employee->id,
                    // 'resignation_date' => now()->toDateString(),
                    'status'           => 'pending', // or 0 if integer
                    'created_by'       => auth()->id(),
                ]);
            }


            return response()->json([
                'status' => true,
                'message' => 'Official information updated successfully.'
            ]);

        } catch (\Exception $e) {

            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ],500);

        }
    }
    public function updateBank(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:employees,id',
            'account_no' => 'required',
            'bank_name' => 'required',
            'ifsc' => 'required',
            'branch' => 'required',
            'passbook' => 'nullable|mimes:pdf,png,jpg|max:5120'
        ]);

        try{

            $employee = Employee::findOrFail($request->id);

            $employee->account_no = $request->account_no;
            $employee->bank_name = $request->bank_name;
            $employee->ifsc = $request->ifsc;
            $employee->branch = $request->branch;

            if($request->hasFile('passbook')){
            // Delete old file
                if ($employee->passbook && Storage::disk('public')->exists('employees/passbook/' . $employee->passbook)) {
                    Storage::disk('public')->delete('employees/passbook/' . $employee->passbook);
                }

                $file = $request->file('passbook');

                // Example: 25_20260708_180530.pdf
                $fileName = $employee->id . '_' . Carbon::now()->format('Ymd_His') . '.' . $file->getClientOriginalExtension();

                // Store file
                $file->storeAs('employees/passbook', $fileName, 'public');

                // Save only filename in database
                $employee->passbook = $fileName;
            }

            $employee->save();

            return response()->json([
                'status'=>true,
                'message'=>'Bank details updated successfully.'
            ]);

        }catch(\Exception $e){

            return response()->json([
                'status'=>false,
                'message'=>$e->getMessage()
            ],500);

        }
    }

    public function employeeDashboardStats(){
        $employeeId = Auth::user()->id; // Change if your employee id column is different
        $year = date('Y');
        // Total Tasks
        $totalTasks = TaskUpdate::where('employee_id', $employeeId)
            ->where('status', '!=', 'Completed')
            ->distinct('task_id')
            ->count('task_id');

        // Completed Tasks
        $completedTasks = TaskUpdate::where('employee_id', $employeeId)
            ->where('status', 'Completed')
            ->distinct('task_id')
            ->count('task_id');

        // Total Projects
        $totalProjects = Project::where('status', 'Active')
            ->where(function ($query) use ($employeeId) {

                $query->where('project_manager_id', $employeeId)
                    ->orWhere('team_head_id', $employeeId)
                    ->orWhereRaw('FIND_IN_SET(?, team_members)', [$employeeId]);

            })
            ->count();

        $leaveCount = LeaveCount::where('employee_id', $employeeId)
        // ->where('year', $year)
        ->first();

        $leaveBalance = 0;

        if ($leaveCount) {

            $total =
                $leaveCount->sick_leaves +
                $leaveCount->casual_leaves +
                $leaveCount->earned_leaves;

            $used = Leave::where('employee_id', $employeeId)
                ->where('status', 'Approved')
                ->whereYear('from_date', $year)
                ->sum('leavecount');
                

            $leaveBalance = $total - $used;
        }

        // Attendance Days
        $attendanceDays = 0;

        $tableName = 'z_attendance_log_' . now()->format('n_Y');

        if (Schema::hasTable($tableName)) {

            $attendanceDays = DB::table($tableName)
                ->where('employee_id', $employeeId)
                ->whereMonth('log_date', now()->month)
                ->whereYear('log_date', now()->year)
                ->distinct(DB::raw('DATE(log_date)'))
                ->count(DB::raw('DATE(log_date)'));
        }

        return response()->json([
            'total_tasks'      => $totalTasks,
            'completed_tasks'  => $completedTasks,
            'total_projects'   => $totalProjects,
            'leave_balance'    => $leaveBalance,
            'attendance_days'  => $attendanceDays,
        ]);
    }
    public function dashboardStats()
    {
        $tableName = 'z_attendance_log_' . now()->format('n_Y');
        $presentToday = 0;
        if (Schema::hasTable($tableName)) {
            $presentToday = DB::table($tableName)
                ->whereYear('log_date', now()->year)
                ->whereMonth('log_date', now()->month)
                ->whereDay('log_date', now()->day)
                ->distinct('employee_id')
                ->count('employee_id');
        }
        $totalEmployee=Employee::where('status', 1)->count();
        $leaveCount=$totalEmployee-$presentToday;
        $data = [
            'total_employees' => $totalEmployee,
            'present_today' => $presentToday,
            'on_leave' => $leaveCount,
            'total_projects' => Project::where('status', 'Active')->count(),
            'total_tasks' => TaskUpdate::where('status', '!=', 'Completed')
                            ->distinct('task_id')
                            ->count('task_id'),
        ];

        return response()->json($data);
    }

    public function employeeDistribution()
    {
        $colors = [
            '#0d6efd', // Blue
            '#198754', // Green
            '#dc3545', // Red
            '#ffc107', // Yellow
            '#0dcaf0', // Cyan
            '#6f42c1', // Purple
            '#fd7e14', // Orange
            '#20c997', // Teal
            '#e83e8c', // Pink
            '#6610f2', // Indigo
            '#6c757d', // Gray
            '#795548', // Brown
            '#009688', // Dark Teal
            '#3f51b5', // Deep Indigo
            '#8bc34a', // Light Green
            '#ff5722', // Deep Orange
            '#607d8b', // Blue Grey
            '#9c27b0', // Violet
            '#ff9800', // Amber
            '#4caf50', // Lime Green
            '#f44336', // Bright Red
            '#03a9f4', // Light Blue
            '#cddc39', // Lime
            '#673ab7', // Deep Purple
            '#ff4081', // Rose
        ];

        $departments = Department::where('status', 'active')->orderBy("name","asc")
            ->withCount([
                'employees as employee_count' => function ($q) {
                    $q->where('status', 1);
                }
            ])
            ->get();

        $data = [];

        foreach ($departments as $index => $department) {

            $data[] = [
                'name'  => $department->name,
                'count' => $department->employee_count,
                'color' => $colors[$index % count($colors)]
            ];

        }

        return response()->json([
            'total' => Employee::where('status',1)->count(),
            'departments' => $data
        ]);
    }

    public function taskStatus()
    {
        $statuses = [
            'Not Started',
            'Completed',
            'In Progress',
            'Pending',
            'On Hold',
            'Over Due'
        ];

        $colors = [
            '#6c757d', // Not Started - Gray
            '#198754', // Completed - Green
            '#0d6efd', // In Progress - Blue
            '#ffc107', // Pending - Yellow
            '#6f42c1', // On Hold - Purple
            '#dc3545', // Over Due - Red
        ];

        $data = [];

        foreach ($statuses as $index => $status) {

            if ($status == 'Over Due') {

                $count = TaskUpdate::where('status', '!=', 'Completed')
                    ->whereDate('end_date', '<', today())
                    ->distinct('task_id')
                    ->count();

            } else {

                $count = TaskUpdate::where('status', $status)->distinct('task_id')->count();
            }

            $data[] = [
                'name'  => $status,
                'count' => $count,
                'color' => $colors[$index]
            ];
        }

        return response()->json([
            'total' => TaskUpdate::distinct('task_id')->count(),
            'statuses' => $data
        ]);
    }
    public function onboardingChart(Request $request)
    {
        $year = $request->year ?? date('Y');

        $records = Employee::select(
                DB::raw('MONTH(joining_date) as month'),
                DB::raw('COUNT(*) as total')
            )
            ->whereYear('joining_date', $year)
            ->groupBy(DB::raw('MONTH(joining_date)'))
            ->pluck('total', 'month');

        $months = [];
        $counts = [];

        for ($i = 1; $i <= 12; $i++) {

            $months[] = date('M', mktime(0,0,0,$i,1));
            $counts[] = $records[$i] ?? 0;
        }

        return response()->json([
            'labels' => $months,
            'data'   => $counts
        ]);
    }

    public function attendanceOverview(Request $request)
    {
        $year = $request->year ?? date('Y');

        $totalEmployees = Employee::where('status', 1)->count();

        $labels = [];
        $presentData = [];
        $absentData = [];

        for ($month = 1; $month <= 12; $month++) {

            $labels[] = date('M', mktime(0, 0, 0, $month, 1));

            $tableName = "z_attendance_log_{$month}_{$year}";

            if (!Schema::hasTable($tableName)) {
                $presentData[] = 0;
                $absentData[] = 0;
                continue;
            }

            $present = DB::table($tableName)
                ->distinct('employee_id')
                ->count('employee_id');

            $presentData[] = $present;
            $absentData[] = max($totalEmployees - $present, 0);
        }

        return response()->json([
            'labels'  => $labels,
            'present' => $presentData,
            'absent'  => $absentData,
        ]);
    }

    public function employeeProjects()
    {
        $employee = Auth::user();

        $projects = Project::where("status","Active")->where(function ($q) use ($employee) {

            $q->where('project_manager_id', $employee->id)
                ->orWhere('team_head_id', $employee->id)
                ->orWhereRaw(
                            "JSON_CONTAINS_PATH(team_members, 'one', ?)",
                            ['$."' . auth()->id() . '"']
                        );

        })->orderBy('project_name')->get();

        $data = [];

        foreach ($projects as $project) {

            $totalTasks = Task::where('project_id', $project->id)->count();

            if ($totalTasks == 0) {

                $completedTasks = 0;
                $progress = 0;

            } else {

                $completedTasks = Task::where('project_id', $project->id)
                    ->whereHas('latestUpdate', function ($q) {
                        $q->where('status', 'Completed');
                    })
                    ->count();

                $progress = round(($completedTasks / $totalTasks) * 100);
            }

            $data[] = [
                'project_name' => $project->project_name,
                'progress'     => $progress,
                'completed'    => $completedTasks,
                'total'        => $totalTasks,
            ];
        }

        return response()->json($data);
    }


    public function employeeCelebration()
    {
        
        $employee = Auth::user();

        $today = Carbon::today();

        $response = [
            'status' => false,
            'type' => '',
            'title' => '',
            'message' => '',
            'photo' => asset('storage/employees/photos/'.$employee->photo),
            'name' => $employee->emp_name,
            'years' => 0
        ];

        // Birthday
        if (!empty($employee->dob)) {

            if (Carbon::parse($employee->dob)->format('m-d') == $today->format('m-d')) {

                $response['status'] = true;
                $response['type'] = 'birthday';
                $response['title'] = '🎂 Happy Birthday!';
                $response['message'] = 'Wishing you happiness, good health and a wonderful year ahead.';

                return response()->json($response);
            }
        }

        // Work Anniversary
        if (!empty($employee->joining_date)) {

            if (Carbon::parse($employee->joining_date)->format('m-d') == $today->format('m-d')) {

                $years = Carbon::parse($employee->joining_date)->diffInYears($today);

                $response['status'] = true;
                $response['type'] = 'anniversary';
                $response['years'] = $years;
                $response['title'] = '🎉 Happy Work Anniversary!';
                $response['message'] = "Congratulations on completing {$years} years with our organization.";

                return response()->json($response);
            }
        }

        return response()->json($response);
    }
    public function exitForm()
    {
       $employee = Employee::findOrFail(auth()->id());
        return view('pages.exitform',compact("employee"));
    }
    
    public function storeExitForm(Request $request)
    {
        $offboard = EmployeeOffboard::where('employee_id', auth()->id())->firstOrFail();

        // Check if already submitted
        if (
            !empty($offboard->leaving_type) ||
            !empty($offboard->reason) ||
            !empty($offboard->feedback) ||
            !empty($offboard->signature)
        ) {
            return response()->json([
                'status' => false,
                'submitted' => true,
                'message' => 'You have already submitted the Exit Form.'
            ], 422);
        }
        $request->validate([
            'separation_type' => 'required',
            'reason' => 'required',
            'liked' => 'required',
            'improve' => 'required',
            'experience' => 'required',
            'recommend' => 'required',
            'handover' => 'required',
            'signature' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        DB::beginTransaction();

        try{

            // $offboard = EmployeeOffboard::where('employee_id',auth()->id())->firstOrFail();

            $signature = $offboard->signature;

            if ($request->hasFile('signature')) {

                $employee = auth()->user();

                $file = $request->file('signature');

                $filename = $employee->emp_id . '_' . date('Ymd_His') . '.' . $file->getClientOriginalExtension();

                $signature = $file->storeAs(
                    'employees/signature',
                    $filename,
                    'public'
                );
            }

            $offboard->update([

                'leaving_date' => now()->toDateString(),

                'leaving_type' => $request->separation_type,

                'reason' => $request->reason,

                'additional_comments' => $request->comments,

                'feedback' => $request->liked,

                'improvements' => $request->improve,

                'experience' => $request->experience,

                'recommend_company' => $request->recommend,

                'suggestions' => $request->suggestions,

                'knowledge_transfer' => $request->handover,

                'handover_details' => $request->handover_details,

                'signature' => $signature,

                'emp_process' => "completed",

            ]);

            DB::commit();

            return response()->json([
                'status'=>true,
                'message'=>'Exit form submitted successfully.'
            ]);

        }catch(\Exception $e){

            DB::rollBack();

            return response()->json([
                'status'=>false,
                'message'=>$e->getMessage()
            ],500);

        }

    }

    
    public function storeEducation(Request $request)
    {
        $savedIds = [];

        foreach ($request->education as $index => $edu) {

            $filename = null;

            // Upload new attachment
            if ($request->hasFile("education.$index.attachment")) {

                $file = $request->file("education.$index.attachment");

                $filename = $request->employee_id . '_' . time() . '_' . $index . '.' . $file->getClientOriginalExtension();

                $file->storeAs(
                    'employees/education',
                    $filename,
                    'public'
                );
            }

            // Update Existing Record
            if (!empty($edu['id'])) {

                $education = EmployeeEducation::find($edu['id']);

                if ($education) {

                    $education->qualification = $edu['qualification'];
                    $education->university_board = $edu['university_board'];
                    $education->passing_year = $edu['passing_year'];
                    $education->percentage = $edu['percentage'];

                    // Replace attachment if new one uploaded
                    if ($filename) {

                        // Delete old file
                        if ($education->attachment) {

                            $oldFile = 'employees/education/' . $education->attachment;

                            if (Storage::disk('public')->exists($oldFile)) {
                                Storage::disk('public')->delete($oldFile);
                            }
                        }

                        // Save only filename
                        $education->attachment = $filename;
                    }

                    $education->save();

                    $savedIds[] = $education->id;
                }

            } else {

                // Create New Record
                $education = EmployeeEducation::create([
                    'employee_id'      => $request->employee_id,
                    'qualification'    => $edu['qualification'],
                    'university_board' => $edu['university_board'],
                    'passing_year'     => $edu['passing_year'],
                    'percentage'       => $edu['percentage'],
                    'attachment'       => $filename, // Only filename
                ]);

                $savedIds[] = $education->id;
            }
        }

        // Delete removed education records and their files
        $deletedEducations = EmployeeEducation::where('employee_id', $request->employee_id)
            ->whereNotIn('id', $savedIds)
            ->get();

        foreach ($deletedEducations as $education) {

            if ($education->attachment) {

                $filePath = 'employees/education/' . $education->attachment;

                if (Storage::disk('public')->exists($filePath)) {
                    Storage::disk('public')->delete($filePath);
                }
            }

            $education->delete();
        }

        return response()->json([
            'status'  => true,
            'message' => 'Education details saved successfully.'
        ]);
    }

    public function storeExperience(Request $request)
    {
        $savedIds = [];

        foreach ($request->experience as $index => $exp) {

            $filename = null;

            // Upload new attachment
            if ($request->hasFile("experience.$index.attachment")) {

                $file = $request->file("experience.$index.attachment");

                $filename = $request->employee_id . '_' . time() . '_' . $index . '.' . $file->getClientOriginalExtension();

                $file->storeAs(
                    'employees/experience',
                    $filename,
                    'public'
                );
            }

            // Update Existing Record
            if (!empty($exp['id'])) {

                $experience = EmployeeExperience::find($exp['id']);

                if ($experience) {

                    $experience->company_name = $exp['company_name'];
                    $experience->job_role = $exp['job_role'];
                    $experience->year_of_experience = $exp['year_of_experience'];

                    // Replace attachment if new one uploaded
                    if ($filename) {

                        // Delete old file
                        if ($experience->attachment) {

                            $oldFile = 'employees/experience/' . $experience->attachment;

                            if (Storage::disk('public')->exists($oldFile)) {
                                Storage::disk('public')->delete($oldFile);
                            }
                        }

                        // Save only filename
                        $experience->attachment = $filename;
                    }

                    $experience->save();

                    $savedIds[] = $experience->id;
                }

            } else {

                // Create New Record
                $experience = EmployeeExperience::create([
                    'employee_id'      => $request->employee_id,
                    'company_name'    => $exp['company_name'],
                    'job_role' => $exp['job_role'],
                    'year_of_experience'     => $exp['year_of_experience'],
                    'attachment'       => $filename, // Only filename
                ]);

                $savedIds[] = $experience->id;
            }
        }

        // Delete removed experience records and their files
        $deletedExperience = EmployeeExperience::where('employee_id', $request->employee_id)
            ->whereNotIn('id', $savedIds)
            ->get();

        foreach ($deletedExperience as $experience) {

            if ($experience->attachment) {

                $filePath = 'employees/experience/' . $experience->attachment;

                if (Storage::disk('public')->exists($filePath)) {
                    Storage::disk('public')->delete($filePath);
                }
            }

            $experience->delete();
        }

        return response()->json([
            'status'  => true,
            'message' => 'Experience details saved successfully.'
        ]);
    }
}
