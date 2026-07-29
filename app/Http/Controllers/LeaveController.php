<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Leave;
use App\Models\LeaveCount;
use App\Models\Designation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Exports\LeaveCountExport;
use App\Exports\LeaveRequestExport;
use App\Exports\WfhRequestExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\ScheduleCalendar;
use App\Models\Project;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
class LeaveController extends Controller
{
    /**
     * Store leave request
     */
    public function index()
    {
        return view('pages.leave.index');
    }

    public function leaveList(Request $request)
    {
        $query = Leave::with('employee')
                    ->when(
                        !in_array(Auth::user()->department_id, [1, 2]),
                        function ($q) {
                            $q->where('employee_id', Auth::id());
                        }
                    )

                    ->when(
                        in_array(Auth::user()->department_id, [1, 2]) && $request->filled('department_id'),
                        function ($q) use ($request) {
                            $q->whereHas('employee', function ($emp) use ($request) {
                                $emp->where('department_id', $request->department_id);
                            });
                        }
                    );

        if ($request->filled('from_date')) {
            $query->whereDate('from_date', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->whereDate('to_date', '<=', $request->to_date);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('leave_type')) {
            $query->where('leave_type', $request->leave_type);
        }
        if ($request->filled('manager_status')) {
            $query->where('manager_status', $request->manager_status);
        }
        $query->where('leave_type', "!=", "WFH")->orderBy('id', 'desc');
        
        return datatables()
            ->of($query)

            ->addIndexColumn()

            ->addColumn('employee_name', function ($row) {
                return $row->employee->name ?? '-';
            })

            ->addColumn('employee_id', function ($row) {
                return $row->employee->emp_id ?? '-';
            })

            ->editColumn('from_date', function ($row) {
                return \Carbon\Carbon::parse($row->from_date)->format('d-m-Y');
            })

            ->editColumn('to_date', function ($row) {
                return \Carbon\Carbon::parse($row->to_date)->format('d-m-Y');
            })

            ->editColumn('created_at', function ($row) {
                return \Carbon\Carbon::parse($row->created_at)->format('d-m-Y');
            })

            ->addColumn('leave_count', function ($row) {
                return $row->leavecount;
            })

            ->addColumn('attachment', function ($row) {

                if (!$row->attachment) {
                    return '-';
                }

                $url = asset('storage/employees/leave_attachments/' . $row->attachment);

                return '
                    <a href="javascript:void(0)" class="btn btn-sm btn-warning me-1 view-image" data-image="' . $url . '" title="View">
                        <i class="bi bi-eye"></i>
                    </a>

                    <a href="' . $url . '" download class="btn btn-sm btn-primary" title="Download">
                        <i class="bi bi-download"></i>
                    </a>
                ';
            })
            ->addColumn('manager_approval', function ($row) {

                if (!$row->manager_approval) {
                    return '-';
                }

                $url = asset('storage/employees/leave_attachments/' . $row->manager_approval);

                return '
                    <a href="javascript:void(0)" class="btn btn-sm btn-warning me-1 view-image" data-image="' . $url . '" title="View">
                        <i class="bi bi-eye"></i>
                    </a>

                    <a href="' . $url . '" download class="btn btn-sm btn-primary" title="Download">
                        <i class="bi bi-download"></i>
                    </a>
                ';
            })

            ->addColumn('action', function ($row) {

            
                if ($row->status == 'Pending') {
                    if(in_array(Auth::user()->department_id, [1, 2]))
                    {
                        return '
                            <button class="btn btn-success btn-sm approveLeave" data-id="' . $row->id . '">
                                Approve
                            </button>

                            <button class="btn btn-danger btn-sm rejectLeave" data-id="' . $row->id . '">
                                Reject
                            </button>
                            <button class="btn btn-danger btn-sm deleteLeave" data-id="' . $row->id . '">
                                Remove
                            </button>
                        ';
                    }
                    else{
                        return '<span class="badge bg-warning">Pending</span> <button class="btn btn-danger btn-sm deleteLeave" data-id="' . $row->id . '">
                                Remove
                            </button>';
                    }
                }

                if ($row->status == 'Approved') {
                    return '<span class="badge bg-success">Approved</span>';
                }

                return '<span class="badge bg-danger">Rejected</span>';
            })

            // Employee Name Search
            ->filterColumn('employee_name', function ($query, $keyword) {
                $query->whereHas('employee', function ($q) use ($keyword) {
                    $q->where('name', 'like', "%{$keyword}%");
                });
            })

            // Employee ID Search
            ->filterColumn('employee_id', function ($query, $keyword) {
                $query->whereHas('employee', function ($q) use ($keyword) {
                    $q->where('emp_id', 'like', "%{$keyword}%");
                });
            })

            // From Date Search
            ->filterColumn('from_date', function ($query, $keyword) {

                $query->where(function ($q) use ($keyword) {

                    $q->whereRaw(
                        "DATE_FORMAT(from_date,'%d-%m-%Y') LIKE ?",
                        ["%{$keyword}%"]
                    )
                        ->orWhereDate('from_date', $keyword);
                });
            })

            // To Date Search
            ->filterColumn('to_date', function ($query, $keyword) {

                $query->where(function ($q) use ($keyword) {

                    $q->whereRaw(
                        "DATE_FORMAT(to_date,'%d-%m-%Y') LIKE ?",
                        ["%{$keyword}%"]
                    )
                        ->orWhereDate('to_date', $keyword);
                });
            })

            // Applied Date Search
            ->filterColumn('created_at', function ($query, $keyword) {

                $query->where(function ($q) use ($keyword) {

                    $q->whereRaw(
                        "DATE_FORMAT(created_at,'%d-%m-%Y') LIKE ?",
                        ["%{$keyword}%"]
                    )
                        ->orWhereDate('created_at', $keyword);
                });
            })

            ->rawColumns(['attachment', 'manager_approval', 'action'])
            
            ->make(true);
    }

    public function delete(Request $request)
    {
        $leave = Leave::where('id', $request->id)
            ->where('employee_id', Auth::id())
            ->where('status', 'Pending')
            ->first();

        if (!$leave) {
            return response()->json([
                'status' => false,
                'message' => 'Leave request not found or cannot be removed.'
            ]);
        }

        $leave->delete();

        return response()->json([
            'status' => true,
            'message' => 'Leave request removed successfully.'
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'from_date' => 'required|date',
            'to_date' => 'required|date|after_or_equal:from_date',
            'leave_type' => 'required|in:Sick,Casual,Earned,WFH,Maternity,LOP',
            'reason' => 'nullable|string',
            'leavecount' => 'required',
            'attachment' => 'nullable|file|max:2048',
            'manager_approval' => 'required|file|max:2048',
        ]);
        $fileName = null;
        $approvalfileName = null;

        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $fileName = Auth::user()->emp_id . '_' . time() . '.' . $file->getClientOriginalExtension();

           $file->storeAs(
                'employees/leave_attachments',
                $fileName,
                'public'
            );
        }
        if ($request->hasFile('manager_approval')) {

            $approvalfile = $request->file('manager_approval');

            $approvalfileName = 'manager_approval_' .
                Auth::user()->emp_id . '_' .
                time() . '.' .
                $approvalfile->getClientOriginalExtension();

            $approvalfile->storeAs(
                'employees/leave_attachments',
                $approvalfileName,
                'public'
            );
        }

        Leave::create([
            'employee_id' => Auth::id(),
            'from_date' => $request->from_date,
            'to_date' => $request->to_date,
            'leavecount' => $request->leavecount,
            'leave_type' => $request->leave_type,
            'reason' => $request->reason,
            'attachment' => $fileName,
            'manager_approval' => $approvalfileName,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Leave request submitted successfully.'
        ]);
    }

    /**
     * Approve Leave
     */
    public function approve($id)
    {
        $leave = Leave::findOrFail($id);

        $leave->update([
            'status' => 'Approved',
            'action_date' => now(),
            'action_by' => Auth::id()
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Leave Approved Successfully'
        ]);
    }

    /**
     * Reject Leave
     */
    public function reject($id)
    {
        $leave = Leave::findOrFail($id);

        $leave->update([
            'status' => 'Rejected',
            'action_date' => now(),
            'action_by' => Auth::id()
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Leave Rejected Successfully'
        ]);
    }

    public function exportLeaveRequests(Request $request)
    {
        return Excel::download(
            new LeaveRequestExport($request->all()),
            'leave_requests_' . now()->format('Ymd_His') . '.xlsx'
        );
    }

    public function wfh()
    {
        $designations = Designation::where('status', 'active')->get();
        return view(
            'pages.leave.wfh',
            compact(
                'designations'
            )
        );
    }

    public function wfhList(Request $request)
    {
        $query = Leave::with('employee.designation')->when(
                        !in_array(Auth::user()->department_id, [1, 2]),
                        function ($q) {
                            $q->where('employee_id', Auth::id());
                        }
                    )

                    ->when(
                        in_array(Auth::user()->department_id, [1, 2]) && $request->filled('department_id'),
                        function ($q) use ($request) {
                            $q->whereHas('employee', function ($emp) use ($request) {
                                $emp->where('department_id', $request->department_id);
                            });
                        }
                    );


        if ($request->filled('from_date')) {
            $query->whereDate('from_date', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->whereDate('to_date', '<=', $request->to_date);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('filter_designation')) {

            $query->whereHas('employee', function ($q) use ($request) {

                $q->where('designation_id', $request->filter_designation);
            });
        }
        
        $query->where('leave_type', "WFH");
        return datatables()
            ->of($query)

            ->addIndexColumn()

            ->addColumn('employee_name', function ($row) {
                return $row->employee->name ?? '-';
            })

            ->addColumn('employee_id', function ($row) {
                return $row->employee->emp_id ?? '-';
            })

            ->editColumn('from_date', function ($row) {
                return \Carbon\Carbon::parse($row->from_date)->format('d-m-Y');
            })

            ->editColumn('to_date', function ($row) {
                return \Carbon\Carbon::parse($row->to_date)->format('d-m-Y');
            })

            ->editColumn('created_at', function ($row) {
                return \Carbon\Carbon::parse($row->created_at)->format('d-m-Y');
            })
            ->addColumn('designation', function ($row) {
                return $row->employee?->designation?->name ?? '-';
            })
            ->addColumn('manager_approval', function ($row) {

                if (!$row->manager_approval) {
                    return '-';
                }

                $url = asset('storage/employees/leave_attachments/' . $row->manager_approval);

                return '
                    <a href="javascript:void(0)" class="btn btn-sm btn-warning me-1 view-image" data-image="' . $url . '" title="View">
                        <i class="bi bi-eye"></i>
                    </a>

                    <a href="' . $url . '" download class="btn btn-sm btn-primary" title="Download">
                        <i class="bi bi-download"></i>
                    </a>
                ';
            })
            ->addColumn('action', function ($row) {

            
                if ($row->status == 'Pending') {
                    if(in_array(Auth::user()->department_id, [1, 2]))
                    {
                        return '
                            <button class="btn btn-success btn-sm approveLeave" data-id="' . $row->id . '">
                                Approve
                            </button>

                            <button class="btn btn-danger btn-sm rejectLeave" data-id="' . $row->id . '">
                                Reject
                            </button>
                            <button class="btn btn-danger btn-sm deleteLeave" data-id="' . $row->id . '">
                                Remove
                            </button>
                        ';
                    }
                    else{
                        return '<span class="badge bg-warning">Pending</span><button class="btn btn-danger btn-sm deleteLeave" data-id="' . $row->id . '">
                                Remove
                            </button>';
                    }
                }

                if ($row->status == 'Approved') {
                    return '<span class="badge bg-success">Approved</span>';
                }

                return '<span class="badge bg-danger">Rejected</span>';
            })

            // Employee Name Search
            ->filterColumn('employee_name', function ($query, $keyword) {
                $query->whereHas('employee', function ($q) use ($keyword) {
                    $q->where('name', 'like', "%{$keyword}%");
                });
            })

            // Employee ID Search
            ->filterColumn('employee_id', function ($query, $keyword) {
                $query->whereHas('employee', function ($q) use ($keyword) {
                    $q->where('emp_id', 'like', "%{$keyword}%");
                });
            })

            // From Date Search
            ->filterColumn('from_date', function ($query, $keyword) {

                $query->where(function ($q) use ($keyword) {

                    $q->whereRaw(
                        "DATE_FORMAT(from_date,'%d-%m-%Y') LIKE ?",
                        ["%{$keyword}%"]
                    )
                        ->orWhereDate('from_date', $keyword);
                });
            })

            // To Date Search
            ->filterColumn('to_date', function ($query, $keyword) {

                $query->where(function ($q) use ($keyword) {

                    $q->whereRaw(
                        "DATE_FORMAT(to_date,'%d-%m-%Y') LIKE ?",
                        ["%{$keyword}%"]
                    )
                        ->orWhereDate('to_date', $keyword);
                });
            })

            // Applied Date Search
            ->filterColumn('created_at', function ($query, $keyword) {

                $query->where(function ($q) use ($keyword) {

                    $q->whereRaw(
                        "DATE_FORMAT(created_at,'%d-%m-%Y') LIKE ?",
                        ["%{$keyword}%"]
                    )
                        ->orWhereDate('created_at', $keyword);
                });
            })
            ->filterColumn('designation', function ($query, $keyword) {
                $query->whereHas('employee.designation', function ($q) use ($keyword) {
                    $q->where('name', 'like', "%{$keyword}%");
                });
            })
            
            ->rawColumns(['manager_approval', 'action'])

            ->make(true);
    }

    public function storeWFH(Request $request)
    {
        $request->validate([
            'from_date'=>'required|date',
            'to_date'=>'required|date|after_or_equal:from_date',
            'reason'=>'required',
            'manager_approval'=>'required|file|max:2048'
        ]);

        $approvalfileName = null;
        if ($request->hasFile('manager_approval')) {

            $approvalfile = $request->file('manager_approval');

            $approvalfileName = 'manager_approval_' .
                Auth::user()->emp_id . '_' .
                time() . '.' .
                $approvalfile->getClientOriginalExtension();

            $approvalfile->storeAs(
                'employees/leave_attachments',
                $approvalfileName,
                'public'
            );
        }
       
        Leave::create([
            'employee_id' => Auth::id(),
            'from_date' => $request->from_date,
            'to_date' => $request->to_date,
            'leave_type' => 'WFH',
            'reason' => $request->reason,
            'manager_approval' => $approvalfileName,
            'status' => 'Pending',
        ]);

        return response()->json([

            'status' => true,

            'message' => 'WFH request submitted successfully.'

        ]);
    }

    /**
     * Approve Leave
     */
    public function wfhApprove($id)
    {
        $leave = Leave::findOrFail($id);

        $leave->update([
            'status' => 'Approved',
            'action_date' => now(),
            'action_by' => Auth::id()
        ]);

        return response()->json([
            'status' => true,
            'message' => 'WFH Request Approved Successfully'
        ]);
    }

    /**
     * Reject Leave
     */
    public function wfhReject($id)
    {
        $leave = Leave::findOrFail($id);

        $leave->update([
            'status' => 'Rejected',
            'action_date' => now(),
            'action_by' => Auth::id()
        ]);

        return response()->json([
            'status' => true,
            'message' => 'WFH Request Rejected Successfully'
        ]);
    }

    public function exportWfhRequests(Request $request)
    {
        return Excel::download(
            new WfhRequestExport($request->all()),
            'wfh_requests_' . now()->format('Ymd_His') . '.xlsx'
        );
    }

    public function leavecount()
    {
        $departments = Department::where('status', 'active')->orderBy("name","asc")->get();
        return view(
            'pages.leave.leavecount',
            compact(
                'departments'
            )
        );
    }

    public function leavecountList(Request $request)
    {
        $year = $request->year ?? date('Y');

        $query = LeaveCount::with(['employee.department'])

            ->where('year', $year);

        if ($request->filled('department_id')) {

            $query->whereHas('employee', function ($q) use ($request) {

                $q->where('department_id', $request->department_id);
            });
        }

        return datatables()
            ->of($query)

            ->addIndexColumn()

            ->addColumn('employee_name', function ($row) {
                return $row->employee?->name ?? '-';
            })

            ->addColumn('employee_id', function ($row) {
                return $row->employee?->emp_id ?? '-';
            })

            ->addColumn('department', function ($row) {
                return $row->employee?->department?->name ?? '-';
            })

            ->addColumn('year', function ($row) {
                return $row->year;
            })

            ->addColumn('total_leave', function ($row) {

                return $row->sick_leaves
                    + $row->casual_leaves
                    + $row->earned_leaves;
            })

            ->addColumn('used_leave', function ($row) {

                return Leave::where('employee_id', $row->employee_id)
                    ->whereYear('from_date', $row->year)
                    ->where('status', 'Approved')
                    ->count();
            })

            ->addColumn('balance_leave', function ($row) {

                $total =
                    $row->sick_leaves +
                    $row->casual_leaves +
                    $row->earned_leaves;

                $used = Leave::where('employee_id', $row->employee_id)
                    ->whereYear('from_date', $row->year)
                    ->where('status', 'Approved')
                    ->count();

                return $total - $used;
            })

            ->addColumn('sick_leave', function ($row) {

                $taken = Leave::where('employee_id', $row->employee_id)
                    ->where('leave_type', 'Sick')
                    ->where('status', 'Approved')
                    ->whereYear('from_date', $row->year)
                    ->count();

                $balance = $row->sick_leaves - $taken;

                return $balance . ' / ' . $row->sick_leaves;
            })

            ->addColumn('casual_leave', function ($row) {

                $taken = Leave::where('employee_id', $row->employee_id)
                    ->where('leave_type', 'Casual')
                    ->where('status', 'Approved')
                    ->whereYear('from_date', $row->year)
                    ->count();

                $balance = $row->casual_leaves - $taken;

                return $balance . ' / ' . $row->casual_leaves;
            })

            ->addColumn('earned_leave', function ($row) {

                $taken = Leave::where('employee_id', $row->employee_id)
                    ->where('leave_type', 'Earned')
                    ->where('status', 'Approved')
                    ->whereYear('from_date', $row->year)
                    ->count();

                $balance = $row->earned_leaves - $taken;

                return $balance . ' / ' . $row->earned_leaves;
            })
            ->addColumn('action', function ($row) {

                return '
                    <button
                        type="button"
                        class="btn btn-sm btn-warning editLeaveCount"
                        data-id="'.$row->id.'"
                        data-year="'.$row->year.'"
                        title="Edit Leave Count">
                        <i class="bi bi-pencil-square"></i>
                    </button>
                ';
            })
            ->filterColumn('employee_name', function ($query, $keyword) {
                $query->whereHas('employee', function ($q) use ($keyword) {
                    $q->where('name', 'like', "%{$keyword}%");
                });
            })

            // Employee ID Search
            ->filterColumn('employee_id', function ($query, $keyword) {
                $query->whereHas('employee', function ($q) use ($keyword) {
                    $q->where('emp_id', 'like', "%{$keyword}%");
                });
            })
            ->rawColumns([
                'action'
            ])
            ->make(true);
    }
    public function viewLeaveCount($id)
    {
        $leave = LeaveCount::with('employee')->findOrFail($id);

        $total = $leave->sick_leaves +
                $leave->casual_leaves +
                $leave->earned_leaves;

        $used = Leave::where('employee_id',$leave->employee_id)
            ->whereYear('from_date',$leave->year)
            ->where('status','Approved')
            ->sum('leavecount');

        return response()->json([

            'id'=>$leave->id,

            'employee_name'=>$leave->employee->name,

            'employee_id'=>$leave->employee->emp_id,


            'year'=>$leave->year,

            'total_leave'=>$total,

            'used_leave'=>$used,

            'balance_leave'=>$total-$used,

            'sick_leaves'=>$leave->sick_leaves,

            'casual_leaves'=>$leave->casual_leaves,

            'earned_leaves'=>$leave->earned_leaves

        ]);
    }
    public function leaveSummary()
    {
        $year = now()->year;
        $employeeId = Auth::id();

        $leaveCount = LeaveCount::where('employee_id', $employeeId)
            ->where('year', $year)
            ->first();

        if (!$leaveCount) {
            return response()->json([
                'status' => false,
                'message' => 'Leave count not found.'
            ]);
        }

        $totalLeave =
            $leaveCount->sick_leaves +
            $leaveCount->casual_leaves +
            $leaveCount->earned_leaves;

        $usedLeave = Leave::where('employee_id', $employeeId)
            ->where('status', 'Approved')
            ->whereYear('from_date', $year)
            ->sum('leavecount');

        $balanceLeave = $totalLeave - $usedLeave;

        $sickTaken = Leave::where('employee_id', $employeeId)
            ->where('leave_type', 'Sick')
            ->where('status', 'Approved')
            ->whereYear('from_date', $year)
           ->sum('leavecount');

        $casualTaken = Leave::where('employee_id', $employeeId)
            ->where('leave_type', 'Casual')
            ->where('status', 'Approved')
            ->whereYear('from_date', $year)
            ->sum('leavecount');

        $earnedTaken = Leave::where('employee_id', $employeeId)
            ->where('leave_type', 'Earned')
            ->where('status', 'Approved')
            ->whereYear('from_date', $year)
            ->sum('leavecount');

        return response()->json([
            'total_leave' => $totalLeave,
            'used_leave' => $usedLeave,
            'balance_leave' => $balanceLeave,

            'sick' => [
                'balance' => $leaveCount->sick_leaves - $sickTaken,
                'total' => $leaveCount->sick_leaves,
            ],

            'casual' => [
                'balance' => $leaveCount->casual_leaves - $casualTaken,
                'total' => $leaveCount->casual_leaves,
            ],

            'earned' => [
                'balance' => $leaveCount->earned_leaves - $earnedTaken,
                'total' => $leaveCount->earned_leaves,
            ],
        ]);
    }

    public function getLeaveCount(Request $request)
    {
        $request->validate([
            'from_date' => 'required|date',
            'to_date'   => 'required|date|after_or_equal:from_date',
        ]);

        $employeeId = Auth::id();

        $from = Carbon::parse($request->from_date);
        $to = Carbon::parse($request->to_date);

        // Inclusive day count
        $totalDays = $from->diffInDays($to) + 1;

        // Get employee projects
        $projectIds = Project::where(function ($q) use ($employeeId) {

            $q->where('project_manager_id', $employeeId)
                ->orWhere('team_head_id', $employeeId)
                ->orWhereRaw(
                            "JSON_CONTAINS_PATH(team_members, 'one', ?)",
                            ['$."' . $employeeId . '"']
                        );


        })->pluck('id');

        $holidayDates = [];

        $calendars = ScheduleCalendar::whereIn('project_id', $projectIds)->get();

        foreach ($calendars as $calendar) {

            $holidays = $calendar->holidays ?? [];

            if (!$holidays) {
                continue;
            }
            // dd($holidays);

            foreach ($holidays as $holiday) {

                $date = Carbon::parse($holiday['date']);

                if ($date->betweenIncluded($from, $to)) {

                    $holidayDates[] = $date->toDateString();

                }
            }
        }

        // Remove duplicate holidays from multiple projects
        $holidayCount = count(array_unique($holidayDates));

        $leaveCount = max($totalDays - $holidayCount, 0);

        return response()->json([
            'leavecount' => $leaveCount,
            'total_days' => $totalDays,
            'holidays'   => $holidayCount,
        ]);
    }
    public function updateLeaveCount(Request $request)
    {
        $request->validate([

            'id'=>'required|exists:leave_counts,id',

            'sick_leaves'=>'required|min:0|max:12',

            'casual_leaves'=>'required|min:0|max:12',

            'earned_leaves'=>'required|min:0|max:12',

        ]);

        LeaveCount::where('id',$request->id)
            ->update([

                'sick_leaves'=>$request->sick_leaves,

                'casual_leaves'=>$request->casual_leaves,

                'earned_leaves'=>$request->earned_leaves,

            ]);

        return response()->json([

            'status'=>true,

            'message'=>'Leave count updated successfully.'

        ]);
    }
    public function calculateWFHCount(Request $request)
    {
        $request->validate([
            'from_date' => 'required|date',
            'to_date'   => 'required|date|after_or_equal:from_date',
        ]);

        $employeeId = Auth::id();

        $from = Carbon::parse($request->from_date);
        $to = Carbon::parse($request->to_date);

        // Inclusive day count
        $totalDays = $from->diffInDays($to) + 1;

        // Get employee projects
        $projectIds = Project::where(function ($q) use ($employeeId) {

            $q->where('project_manager_id', $employeeId)
                ->orWhere('team_head_id', $employeeId)
                ->orWhereRaw(
                            "JSON_CONTAINS_PATH(team_members, 'one', ?)",
                            ['$."' . $employeeId . '"']
                        );

        })->pluck('id');

        $holidayDates = [];

        $calendars = ScheduleCalendar::whereIn('project_id', $projectIds)->get();

        foreach ($calendars as $calendar) {

            $holidays = $calendar->holidays ?? [];

            if (!$holidays) {
                continue;
            }
            // dd($holidays);

            foreach ($holidays as $holiday) {

                $date = Carbon::parse($holiday['date']);

                if ($date->betweenIncluded($from, $to)) {

                    $holidayDates[] = $date->toDateString();

                }
            }
        }

        // Remove duplicate holidays from multiple projects
        $holidayCount = count(array_unique($holidayDates));

        $wfhCount = max($totalDays - $holidayCount, 0);

        return response()->json([
            'wfhcount' => $wfhCount,
            'total_days' => $totalDays,
            'holidays'   => $holidayCount,
        ]);
    }

    public function exportLeaveCounts(Request $request)
    {
        $year = $request->year ?? date('Y');
        $departmentId = $request->department_id;

        return Excel::download(
            new LeaveCountExport($year, $departmentId),
            'leave-count-report.xlsx'
        );
    }

    public function employeeAttendanceSummary(Request $request)
    {
        $employee = Auth::user();

        $month = $request->month
            ? Carbon::parse($request->month . '-01')
            : Carbon::now();

        $start = $month->copy()->startOfMonth();
        $end   = $month->copy()->endOfMonth();

        $tableName = "z_attendance_log_{$month->month}_{$month->year}";

        // Present Days
        $present = 0;

        if (Schema::hasTable($tableName)) {
           $present = DB::table($tableName)
            ->where('employee_id', $employee->id)
            ->distinct(DB::raw('DATE(log_date)'))
            ->count(DB::raw('DATE(log_date)'));
        }

        $leave = 0;
        $wfh   = 0;

        $leaves = Leave::where('employee_id', $employee->id)
            ->where('status', 'Approved')
            ->where(function ($q) use ($start, $end) {
                $q->whereBetween('from_date', [$start, $end])
                    ->orWhereBetween('to_date', [$start, $end])
                    ->orWhere(function ($q2) use ($start, $end) {
                        $q2->where('from_date', '<=', $start)
                            ->where('to_date', '>=', $end);
                    });
            })
            ->get();

        foreach ($leaves as $item) {

            $leaveStart = Carbon::parse($item->from_date)->startOfDay();
            $leaveEnd   = Carbon::parse($item->to_date)->startOfDay();

            $overlapStart = $leaveStart->greaterThan($start)
                ? $leaveStart->copy()
                : $start->copy()->startOfDay();

            $overlapEnd = $leaveEnd->lessThan($end)
                ? $leaveEnd->copy()
                : $end->copy()->startOfDay();

            if ($overlapStart->gt($overlapEnd)) {
                continue;
            }

            // Leave within same month
            if ($leaveStart->format('Y-m') == $leaveEnd->format('Y-m')) {

                $days = $item->leavecount;

            } else {

                $days = $this->getEmployeeLeaveCount(
                    $overlapStart,
                    $overlapEnd
                );
            }

            // Normalize floating point values
            $days = round($days, 2);

            if (fmod($days, 1) != 0.5) {
                $days = round($days);
            }

            if ($item->leave_type == 'WFH') {
                $wfh += $days;
            } else {
                $leave += $days;
            }
        }

        return response()->json([
            'present' => $present,
            'leave'   => $leave,
            'wfh'     => $wfh,
            'total'   => $present,
        ]);
    }
    public function getEmployeeLeaveCount(Carbon $from, Carbon $to)
    {
        $employeeId = Auth::id();

        // Ignore time completely
        $from = $from->copy()->startOfDay();
        $to   = $to->copy()->startOfDay();

        // Inclusive days
        $totalDays = (int) $from->diffInDays($to) + 1;

        // Employee projects
        $projectIds = Project::where(function ($q) use ($employeeId) {

            $q->where('project_manager_id', $employeeId)
                ->orWhere('team_head_id', $employeeId)
                ->orWhereRaw(
                            "JSON_CONTAINS_PATH(team_members, 'one', ?)",
                            ['$."' . $employeeId . '"']
                        );
        })->pluck('id');

        $holidayDates = [];

        $calendars = ScheduleCalendar::whereIn('project_id', $projectIds)->get();

        foreach ($calendars as $calendar) {

            $holidays = $calendar->holidays ?? [];

            if (empty($holidays)) {
                continue;
            }

            foreach ($holidays as $holiday) {

                $holidayDate = Carbon::parse($holiday['date'])->startOfDay();

                if ($holidayDate->betweenIncluded($from, $to)) {
                    $holidayDates[] = $holidayDate->toDateString();
                }
            }
        }

        $holidayCount = count(array_unique($holidayDates));

        return max($totalDays - $holidayCount, 0);
    }
}
