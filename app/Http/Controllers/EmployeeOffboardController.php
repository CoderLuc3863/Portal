<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EmployeeOffboard;
use App\Models\JobType;
use App\Models\Employee;
use App\Models\Department;
use App\Models\Designation;
use Yajra\DataTables\Facades\DataTables;
use App\Exports\EmployeeOffboardExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Validation\Rule;

class EmployeeOffboardController extends Controller
{
    public function index()
    {
        $jobTypes = JobType::where('status', 'active')->get();
        $departments = Department::where('status', 'active')->orderBy("name","asc")->get();
        $designations = Designation::where('status', 'active')->orderBy("name","asc")->get();

        return view(
            'pages.offboard.index',
            compact(
                'jobTypes',
                'departments',
                'designations'
            )
        );
    }

    public function list(Request $request)
    {
        if ($request->ajax()) {

            $employees = EmployeeOffboard::with([
                'employee'
            ])

                ->when($request->hr_process != '', function ($query) use ($request) {

                    $query->where('hr_process', $request->hr_process);
                })

                ->when($request->emp_process != '', function ($query) use ($request) {

                    $query->where('emp_process', $request->emp_process);
                })

                ->when($request->job_type != '', function ($query) use ($request) {

                    $query->whereHas('employee', function ($q) use ($request) {

                        $q->where('job_type', $request->job_type);
                    });
                })

                ->when($request->department_id != '', function ($query) use ($request) {

                    $query->whereHas('employee', function ($q) use ($request) {

                        $q->where('department_id', $request->department_id);
                    });
                })

                ->when($request->designation_id != '', function ($query) use ($request) {

                    $query->whereHas('employee', function ($q) use ($request) {

                        $q->where('designation_id', $request->designation_id);
                    });
                })

                ->latest();

            return DataTables::of($employees)

                ->addIndexColumn()

                ->addColumn('employee_name', function ($row) {
                    return $row->employee->name ?? '-';
                })

                ->addColumn('employee_id', function ($row) {
                    return $row->employee->emp_id ?? '-';
                })

                ->addColumn('job_type', function ($row) {
                    return $row->employee->job_type ?? '-';
                })

                ->addColumn('designation', function ($row) {
                    return $row->employee->designation->name ?? '-';
                })

                ->addColumn('reporting_manager', function ($row) {
                    return $row->employee->reportingManager->name ?? '-';
                })

                ->addColumn('joining_date', function ($row) {
                    return $row->employee->joining_date
                        ? date('d-m-Y', strtotime($row->employee->joining_date))
                        : '-';
                })

                ->addColumn('leaving_date', function ($row) {
                    return $row->leaving_date
                        ? date('d-m-Y', strtotime($row->leaving_date))
                        : '-';
                })

                ->addColumn('submission_status', function ($row) {

                    if ($row->emp_process == 'completed') {
                        return '<span class="badge bg-success">Completed</span>';
                    }

                    return '<span class="badge bg-warning">Pending</span>';
                })

                ->addColumn('hr_process_status', function ($row) {

                    if ($row->hr_process == 'completed') {
                        return '<span class="badge bg-success">Completed</span>';
                    }

                    return '<span class="badge bg-warning">Pending</span>';
                })

                ->addColumn('action', function ($row) {

                    return '
                        <div class="btn-group">

                             <button class="btn btn-sm btn-info viewBtn"
                                    data-id="' . $row->id . '"
                                    data-emp-process="' . $row->emp_process . '">
                                View
                            </button>

                        </div>
                    ';
                })
                ->filterColumn('employee_name', function ($query, $keyword) {

                    $query->whereHas('employee', function ($q) use ($keyword) {

                        $q->where('name', 'like', "%{$keyword}%");
                    });
                })

                ->filterColumn('employee_id', function ($query, $keyword) {

                    $query->whereHas('employee', function ($q) use ($keyword) {

                        $q->where('emp_id', 'like', "%{$keyword}%");
                    });
                })
                ->rawColumns([
                    'submission_status',
                    'hr_process_status',
                    'action'
                ])

                ->make(true);
        }
    }

    public function add(Request $request)
    {
        $request->validate([
            'employee_id' => [
                'required',
                'exists:employees,id',
                Rule::unique('employee_offboards', 'employee_id')
            ],
            'leaving_date' => 'required|date'
        ], [
            'employee_id.unique' => 'An exit form has already been created for this employee.'
        ]);

        EmployeeOffboard::create([
            'employee_id' => $request->employee_id,
            'leaving_date' => $request->leaving_date,
            'emp_process' => 'pending',
            'hr_process' => 'pending'
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Exit Form Created Successfully'
        ]);
    }

    public function store(Request $request)
    {
        //
    }

    public function view($id)
    {
        $offboard = EmployeeOffboard::with([
            'employee',
            'employee.department',
            'employee.designation',
            'employee.reportingManager'
        ])->findOrFail($id);
        $offboard->employee->photo_url = $offboard->employee->photo
            ? asset('storage/employees/photos/' . $offboard->employee->photo)
            : asset('assets/img/user.png');
        $offboard->signature = $offboard->signature
            ? asset('storage/employees/signature/' . $offboard->signature)
            : asset('assets/img/user.png');
        return response()->json($offboard);
    }

    public function updateHrProcess(Request $request)
    {
        $offboard = EmployeeOffboard::findOrFail($request->id);

        $offboard->update([
            'asset_clearance' => $request->asset_clearance,
            'id_card_returned' => $request->id_card_returned,
            'access_card_returned' => $request->access_card_returned,
            'email_disabled' => $request->email_disabled,
            'system_access_revoked' => $request->system_access_revoked,
            'data_backup_completed' => $request->data_backup_completed,
            'salary_settled' => $request->salary_settled,
            'notice_period_completed' => $request->notice_period_completed,
            'reimbursement_settled' => $request->reimbursement_settled,
            'other_finance_notes' => $request->other_finance_notes,
            'exit_interview_completed' => $request->exit_interview_completed,
            'documents_collected' => $request->documents_collected,
            'hr_process' => $request->hr_process
        ]);

        return response()->json([
            'status' => true,
            'message' => 'HR process updated successfully.'
        ]);
    }
    public function export(Request $request)
    {
        return Excel::download(
            new EmployeeOffboardExport($request),
            'employee_offboard_list.xlsx'
        );
    }
}
