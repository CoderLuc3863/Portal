<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JobType;
use App\Models\Department;
use App\Models\Designation;
use App\Models\RegularizationRequest;
use App\Models\Employee;
use Carbon\Carbon;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AttendanceCaptureExport;
use App\Exports\AttendanceSummaryExport;
use App\Exports\AttendanceTrackingExport;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{

    public function capture()
    {
        $jobTypes = JobType::where('status', 'active')->get();
        $departments = Department::where('status', 'active')->orderBy("name","asc")->get();
        $designations = Designation::where('status', 'active')->orderBy("name","asc")->get();

        return view('pages.attendance.capture', compact(
            'jobTypes',
            'departments',
            'designations'
        ));
    }
    public function captureList(Request $request)
    {
        $employees = Employee::query()->where('status', 1);

        return DataTables::of($employees)
            ->addIndexColumn()

            ->addColumn('employee_name', function ($row) {
                return $row->name;
            })

            ->addColumn('emp_id', function ($row) {
                return $row->emp_id;
            })
            ->addColumn('in_time', function ($row) {

                $tableName = 'z_attendance_log_' . now()->format('n_Y');

                $inTime = DB::table($tableName)
                    ->where('employee_id', $row->id)
                    ->whereDate('log_date', today())
                    ->where('direction', 'in')
                    ->orderBy('log_date')
                    ->value('log_date');

                return $inTime
                    ? Carbon::parse($inTime)->format('h:i A')
                    : '-';
            })

            ->addColumn('out_time', function ($row) {

                $tableName = 'z_attendance_log_' . now()->format('n_Y');

                $outTime = DB::table($tableName)
                    ->where('employee_id', $row->id)
                    ->whereDate('log_date', today())
                    ->where('direction', 'out')
                    ->orderByDesc('log_date')
                    ->value('log_date');

                return $outTime
                    ? Carbon::parse($outTime)->format('h:i A')
                    : '-';
            })

            ->addColumn('work_time', function ($row) {

                $tableName = 'z_attendance_log_' . now()->format('n_Y');

                $logs = DB::table($tableName)
                    ->where('employee_id', $row->id)
                    ->whereDate('log_date', today())
                    ->orderBy('log_date', 'asc')
                    ->get();

                if ($logs->isEmpty()) {
                    return '00:00:00';
                }

                $totalSeconds = 0;
                $inTime = null;

                foreach ($logs as $log) {

                    if ($log->direction == 'in') {

                        $inTime = Carbon::parse($log->log_date);
                    } elseif ($log->direction == 'out' && $inTime) {

                        $outTime = Carbon::parse($log->log_date);

                        $totalSeconds += $inTime->diffInSeconds($outTime);

                        $inTime = null;
                    }
                }

                if ($inTime) {

                    $totalSeconds += $inTime->diffInSeconds(now());
                }

                $hours = floor($totalSeconds / 3600);
                $minutes = floor(($totalSeconds % 3600) / 60);
                $seconds = $totalSeconds % 60;

                return sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);
            })
            ->addColumn('overtime', function ($row) {

                $tableName = 'z_attendance_log_' . now()->format('n_Y');

                $logs = DB::table($tableName)
                    ->where('employee_id', $row->id)
                    ->whereDate('log_date', today())
                    ->orderBy('log_date', 'asc')
                    ->get();

                $totalSeconds = 0;
                $inTime = null;

                foreach ($logs as $log) {

                    if ($log->direction == 'in') {

                        $inTime = Carbon::parse($log->log_date);
                    } elseif ($log->direction == 'out' && $inTime) {

                        $outTime = Carbon::parse($log->log_date);

                        $totalSeconds += $inTime->diffInSeconds($outTime);

                        $inTime = null;
                    }
                }

                // Open session
                if ($inTime) {

                    $totalSeconds += $inTime->diffInSeconds(now());
                }

                // 9 Hours = 32400 Seconds
                $overtimeSeconds = max(0, $totalSeconds - 28800);

                $hours = floor($overtimeSeconds / 3600);
                $minutes = floor(($overtimeSeconds % 3600) / 60);
                $seconds = $overtimeSeconds % 60;

                return sprintf(
                    '%02d:%02d:%02d',
                    $hours,
                    $minutes,
                    $seconds
                );
            })

            ->addColumn('total_time', function ($row) {

                $tableName = 'z_attendance_log_' . now()->format('n_Y');

                $logs = DB::table($tableName)
                    ->where('employee_id', $row->id)
                    ->whereDate('log_date', today())
                    ->orderBy('log_date')
                    ->get();

                if ($logs->isEmpty()) {
                    return '-';
                }

                $firstIn = $logs->firstWhere('direction', 'in');

                if (!$firstIn) {
                    return '-';
                }

                $lastLog = $logs->last();

                $startTime = Carbon::parse($firstIn->log_date);

                $endTime = $lastLog->direction == 'out'
                    ? Carbon::parse($lastLog->log_date)
                    : now();

                $seconds = abs($endTime->timestamp - $startTime->timestamp);

                $hours = floor($seconds / 3600);
                $minutes = floor(($seconds % 3600) / 60);
                $secs = $seconds % 60;

                return sprintf('%02d:%02d:%02d', $hours, $minutes, $secs);
            })

            ->addColumn('status', function ($row) {

                $tableName = 'z_attendance_log_' . now()->format('n_Y');

                $exists = DB::table($tableName)
                    ->where('employee_id', $row->id)
                    ->whereDate('log_date', today())
                    ->exists();

                if ($exists) {
                    return '<span class="badge bg-success">Present</span>';
                }

                return '<span class="badge bg-danger">Absent</span>';
            })
            ->filter(function ($query) use ($request) {

                // Name & Employee ID Search
                if ($request->has('search') && !empty($request->search['value'])) {

                    $search = $request->search['value'];

                    $query->where(function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%")
                            ->orWhere('emp_id', 'like', "%{$search}%");
                    });
                }

                // Present / Absent Filter
                if (!empty($request->status)) {

                    $tableName = 'z_attendance_log_' . now()->format('n_Y');

                    $presentEmployeeIds = DB::table($tableName)
                        ->whereDate('log_date', today())
                        ->pluck('employee_id')
                        ->unique()
                        ->toArray();

                    if ($request->status == 'Present') {

                        $query->whereIn('id', $presentEmployeeIds);
                    } elseif ($request->status == 'Absent') {

                        $query->whereNotIn('id', $presentEmployeeIds);
                    }
                }
            })

            ->rawColumns(['status'])
            ->make(true);
    }

    public function captureExport(Request $request)
    {
        return Excel::download(
            new AttendanceCaptureExport($request->status),
            'attendance_' . now()->format('Ymd_His') . '.xlsx'
        );
    }

    public function tracking()
    {
        $jobTypes = JobType::where('status', 'active')->get();
        $departments = Department::where('status', 'active')->orderBy("name","asc")->get();
        $designations = Designation::where('status', 'active')->orderBy("name","asc")->get();

        return view('pages.attendance.tracking', compact(
            'jobTypes',
            'departments',
            'designations'
        ));
    }
    public function trackingList(Request $request)
    {
        if (
            empty($request->from_date) ||
            empty($request->to_date)
        ) {

            return DataTables::of(collect([]))->make(true);
        }

        $fromDate = Carbon::parse($request->from_date);
        $toDate   = Carbon::parse($request->to_date);

        
        if(in_array(Auth::user()->department_id, [1, 2]))
        {    $employees = Employee::with('department')
                ->where('status', 1)
                ->when($request->department_id, function ($q) use ($request) {

                    $q->where(
                        'department_id',
                        $request->department_id
                    );
                })
                ->get();
        }else{
            $employees = Employee::with('department')
                ->where('status', 1)
                ->where("id",auth()->id())
                ->when($request->department_id, function ($q) use ($request) {

                    $q->where(
                        'department_id',
                        $request->department_id
                    );
                })
                ->get();
        }

        $data = [];

        while ($fromDate->lte($toDate)) {

            $tableName =
                'z_attendance_log_' .
                $fromDate->month .
                '_' .
                $fromDate->year;

            foreach ($employees as $employee) {

                $firstIn = null;
                $lastOut = null;
                $workSeconds = 0;

                if (Schema::hasTable($tableName)) {

                    $logs = DB::table($tableName)
                        ->where(
                            'employee_id',
                            $employee->id
                        )
                        ->whereDate(
                            'log_date',
                            $fromDate->toDateString()
                        )
                        ->orderBy('log_date')
                        ->get();

                    $inTime = null;

                    foreach ($logs as $log) {

                        if (
                            !$firstIn &&
                            $log->direction == 'in'
                        ) {
                            $firstIn = $log->log_date;
                        }

                        if ($log->direction == 'in') {

                            $inTime = Carbon::parse(
                                $log->log_date
                            );
                        } elseif (
                            $log->direction == 'out' &&
                            $inTime
                        ) {

                            $outTime = Carbon::parse(
                                $log->log_date
                            );

                            $workSeconds +=
                                $inTime->diffInSeconds(
                                    $outTime
                                );

                            $lastOut = $log->log_date;

                            $inTime = null;
                        }
                    }
                }

                $hours =
                    floor($workSeconds / 3600);

                $minutes =
                    floor(
                        ($workSeconds % 3600) / 60
                    );

                $seconds =
                    $workSeconds % 60;

                $workingHours = sprintf(
                    '%02d:%02d:%02d',
                    $hours,
                    $minutes,
                    $seconds
                );

                if ($workSeconds == 0) {

                    $status = 'Absent';
                } elseif ($workSeconds < 14400) {

                    // Less than 4 hrs
                    $status = 'Half Day';
                } else {

                    $status = 'Present';
                }

                if (
                    $request->status &&
                    $status != $request->status
                ) {
                    continue;
                }

                $data[] = [
                    'date' => $fromDate->format('d-m-Y'),
                    'emp_id' => $employee->emp_id,
                    'employee_name' => $employee->name,
                    'department' => optional(
                        $employee->department
                    )->name,
                    'check_in' => $firstIn
                        ? Carbon::parse($firstIn)
                        ->format('h:i A')
                        : '-',
                    'check_out' => $lastOut
                        ? Carbon::parse($lastOut)
                        ->format('h:i A')
                        : '-',
                    'hours' => $workingHours,
                    'status' => $status,
                ];
            }

            $fromDate->addDay();
        }

        return DataTables::of(collect($data))
            ->addIndexColumn()
            ->make(true);
    }

    public function trackingExport(Request $request)
    {
        return Excel::download(
            new AttendanceTrackingExport($request),
            'attendance_tracking.xlsx'
        );
    }
    public function regularization()
    {
        $jobTypes = JobType::where('status', 'active')->get();
        $departments = Department::where('status', 'active')->orderBy("name","asc")->get();
        $designations = Designation::where('status', 'active')->orderBy("name","asc")->get();

        return view('pages.attendance.regularization', compact(
            'jobTypes',
            'departments',
            'designations'
        ));
    }
    public function regularizationList(Request $request)
    {
       
        $requests = RegularizationRequest::with([
            'employee.department'
        ])
            ->when(
                !in_array(Auth::user()->department_id, [1, 2]),
                function ($query) {
                    $query->where('employee_id', Auth::id());
                }
            )
            ->when(
                $request->from_date,
                function ($query) use ($request) {

                    $query->whereDate(
                        'date',
                        '>=',
                        $request->from_date
                    );
                }
            )

            ->when(
                $request->to_date,
                function ($query) use ($request) {
                    $query->whereDate(
                        'date',
                        '<=',
                        $request->to_date
                    );
                }
            )

            ->when(
                $request->department_id,
                function ($query) use ($request) {
                    $query->whereHas(
                        'employee',
                        function ($q) use ($request) {
                            $q->where(
                                'department_id',
                                $request->department_id
                            );
                        }
                    );
                }
            )
            ->when(
                $request->status,
                function ($query) use ($request) {
                    $query->where(
                        'status',
                        $request->status
                    );
                }
            )
            ->orderBy('date', 'desc')        // Attendance date DESC
            ->orderBy('created_at', 'desc');

        return DataTables::of($requests)

            ->addIndexColumn()

            ->addColumn('emp_id', function ($row) {

                return $row->employee->emp_id ?? '-';
            })

            ->addColumn('employee_name', function ($row) {

                return $row->employee->name ?? '-';
            })

            ->addColumn('attendance_date', function ($row) {

                return Carbon::parse(
                    $row->date
                )->format('d-m-Y');
            })

            ->addColumn('request_type', function ($row) {

                if ($row->direction == 'in') {

                    return '
                        <span class="badge bg-success">
                            Punch In
                        </span>';
                }

                return '
                    <span class="badge bg-danger">
                        Punch Out
                    </span>';
            })

            ->addColumn('reason', function ($row) {

                return $row->reason;
            })

            ->addColumn('requested_on', function ($row) {
                return $row->requested_on;
            })
            ->addColumn('created_at', function ($row) {

                return Carbon::parse(
                    $row->created_at
                )->format('d-m-Y');
            })

            ->addColumn('action', function ($row) {

                if ($row->status == 'Approved') {

                    return '<span class="badge bg-primary">'
                        . $row->status .
                        '</span>';
                }
                if ($row->status == 'Rejected') {

                    return '<span class="badge bg-danger">'
                        . $row->status .
                        '</span>';
                }
                if ($row->status == 'Pending') {

                    if (in_array(Auth::user()->department_id, [1, 2])) {

                        return '
                            <button class="btn btn-success btn-sm approveBtn"
                                data-id="'.$row->id.'">
                                Approve
                            </button>

                            <button class="btn btn-danger btn-sm rejectBtn"
                                data-id="'.$row->id.'">
                                Reject
                            </button>

                            <button class="btn btn-danger btn-sm deleteBtn"
                                data-id="'.$row->id.'">
                                <i class="bi bi-trash"></i>
                            </button>';
                    } else {

                        return '
                            <span class="badge bg-warning">Pending</span>

                            <button class="btn btn-danger btn-sm deleteBtn ms-1"
                                data-id="'.$row->id.'">
                                <i class="bi bi-trash"></i>
                            </button>';
                    }
                }
            })

            ->filter(function ($query) use ($request) {

                if (
                    $request->has('search')
                    && !empty($request->search['value'])
                ) {

                    $search =
                        $request->search['value'];

                    $query->whereHas(
                        'employee',
                        function ($q) use ($search) {

                            $q->where(
                                'name',
                                'like',
                                "%{$search}%"
                            )
                                ->orWhere(
                                    'emp_id',
                                    'like',
                                    "%{$search}%"
                                );
                        }
                    );
                }
            })

            ->rawColumns([
                'request_type',
                'action'
            ])

            ->make(true);
    }

    public function deleteRegularization($id)
    {
        $request = RegularizationRequest::findOrFail($id);

        // Allow deletion only while pending
        if ($request->status != 'Pending') {
            return response()->json([
                'status' => false,
                'message' => 'Only pending requests can be deleted.'
            ], 422);
        }

        // Employees can delete only their own request
        if (!in_array(Auth::user()->department_id, [1, 2]) &&
            $request->employee_id != Auth::id()) {

            return response()->json([
                'status' => false,
                'message' => 'Unauthorized action.'
            ], 403);
        }

        $request->delete();

        return response()->json([
            'status' => true,
            'message' => 'Regularization request deleted successfully.'
        ]);
    }
    public function storeRegularization(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'direction' => 'required|in:in,out',
            'reason' => 'required|max:500',
        ]);

        RegularizationRequest::create([
            'employee_id' => Auth::id(),
            'date'        => $request->date,
            'direction'   => $request->direction,
            'reason'      => $request->reason,
            'status'      => 'Pending',
            'requested_on'=> now(),
        ]);

        return response()->json([
            'status'  => true,
            'message' => 'Regularization request submitted successfully.'
        ]);
    }

    public function getRegularization($id)
    {
        $request = RegularizationRequest::with('employee')
            ->findOrFail($id);

        return response()->json([
            'id' => $request->id,

            'employee' => [
                'name' => $request->employee->name ?? '',
                'emp_id' => $request->employee->emp_id ?? '',
            ],

            'date' => Carbon::parse(
                $request->created_at
            )->format('d-m-Y'),

            'attendance_date' => Carbon::parse(
                $request->date
            )->format('d-m-Y'),

            'direction' => $request->direction,

            'reason' => $request->reason,

            'status' => $request->status,
        ]);
    }

    public function rejectRegularization($id)
    {
        $request = RegularizationRequest::findOrFail($id);

        $request->update([
            'status' => 'Rejected',
            // 'action_by' => auth()->id(),
            'action_date' => now()
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Request rejected successfully.'
        ]);
    }

    public function approveRegularization(Request $request)
    {
        $request->validate([
            'request_id' => 'required|exists:regularization_requests,id',
            'direction' => 'required|in:in,out',
            'time' => 'required'
        ]);

        $regularization = RegularizationRequest::findOrFail(
            $request->request_id
        );

        $attendanceDate = Carbon::parse(
            $regularization->date
        );

        $tableName = 'z_attendance_log_' .
            $attendanceDate->month .
            '_' .
            $attendanceDate->year;

        $logDateTime = Carbon::parse(
            $attendanceDate->format('Y-m-d') . ' ' . $request->time
        );

        DB::table($tableName)->insert([

            'employee_id' => $regularization->employee_id,

            'device_log_id' => 0,

            'direction' => $request->direction,

            'log_date' => $logDateTime,


        ]);

        $regularization->update([

            'status' => 'Approved',

            'direction' => $request->direction,

            // 'action_by' => auth()->id(),

            'action_date' => now()

        ]);

        return response()->json([
            'status' => true,
            'message' => 'Regularization request approved successfully.'
        ]);
    }
    public function summary()
    {
        return view('pages.attendance.summary');
    }
    public function summaryList(Request $request)
    {
        $attendanceDate = $request->filled('date')
            ? Carbon::parse($request->date)
            : now();

        $year = $request->year ?: $attendanceDate->year;
        $month = $request->month ?: $attendanceDate->month;

        $tableName = 'z_attendance_log_' . $month . '_' . $year;

        $employees = Employee::query()
            ->when(
                !in_array(Auth::user()->department_id, [1, 2]),
                function ($query) {
                    $query->where('id', Auth::id());
                }
            )
            ->where('status', 1);

        return DataTables::of($employees)

            ->addIndexColumn()

            ->addColumn('attendance_date', function ($row) use ($attendanceDate) {

                return $attendanceDate->format('d-M-Y');
            })

            ->addColumn('employee_name', function ($row) {

                return $row->name;
            })

            ->addColumn('emp_id', function ($row) {

                return $row->emp_id;
            })

            ->addColumn('department', function ($row) {

                return optional($row->department)->name ?? '-';
            })

            ->addColumn('first_in', function ($row) use ($tableName, $attendanceDate) {

                if (!Schema::hasTable($tableName)) {
                    return '-';
                }

                $firstIn = DB::table($tableName)
                    ->where('employee_id', $row->id)
                    ->whereDate('log_date', $attendanceDate->toDateString())
                    ->where('direction', 'in')
                    ->orderBy('log_date')
                    ->value('log_date');

                return $firstIn
                    ? Carbon::parse($firstIn)->format('h:i A')
                    : '-';
            })

            ->addColumn('last_out', function ($row) use ($tableName, $attendanceDate) {

                if (!Schema::hasTable($tableName)) {
                    return '-';
                }

                $lastOut = DB::table($tableName)
                    ->where('employee_id', $row->id)
                    ->whereDate('log_date', $attendanceDate->toDateString())
                    ->where('direction', 'out')
                    ->orderByDesc('log_date')
                    ->value('log_date');

                return $lastOut
                    ? Carbon::parse($lastOut)->format('h:i A')
                    : '-';
            })

            ->addColumn('total_in_time', function ($row) use ($tableName, $attendanceDate) {

                if (!Schema::hasTable($tableName)) {
                    return '00:00:00';
                }

                $logs = DB::table($tableName)
                    ->where('employee_id', $row->id)
                    ->whereDate('log_date', $attendanceDate->toDateString())
                    ->orderBy('log_date')
                    ->get();

                $totalSeconds = 0;
                $inTime = null;

                foreach ($logs as $log) {

                    if ($log->direction == 'in') {

                        $inTime = Carbon::parse($log->log_date);
                    } elseif ($log->direction == 'out' && $inTime) {

                        $outTime = Carbon::parse($log->log_date);

                        $totalSeconds += $inTime->diffInSeconds($outTime);

                        $inTime = null;
                    }
                }

                if (
                    $inTime &&
                    $attendanceDate->toDateString() == now()->toDateString()
                ) {
                    $totalSeconds += $inTime->diffInSeconds(now());
                }

                $hours = floor($totalSeconds / 3600);
                $minutes = floor(($totalSeconds % 3600) / 60);
                $seconds = $totalSeconds % 60;

                return sprintf(
                    '%02d:%02d:%02d',
                    $hours,
                    $minutes,
                    $seconds
                );
            })

            ->addColumn('total_out_time', function ($row) use ($tableName, $attendanceDate) {

                if (!Schema::hasTable($tableName)) {
                    return '00:00:00';
                }

                $logs = DB::table($tableName)
                    ->where('employee_id', $row->id)
                    ->whereDate('log_date', $attendanceDate->toDateString())
                    ->orderBy('log_date')
                    ->get();

                $totalSeconds = 0;
                $outTime = null;

                foreach ($logs as $log) {

                    if ($log->direction == 'out') {

                        $outTime = Carbon::parse($log->log_date);
                    } elseif ($log->direction == 'in' && $outTime) {

                        $inTime = Carbon::parse($log->log_date);

                        $totalSeconds += $outTime->diffInSeconds($inTime);

                        $outTime = null;
                    }
                }

                $hours = floor($totalSeconds / 3600);
                $minutes = floor(($totalSeconds % 3600) / 60);
                $seconds = $totalSeconds % 60;

                return sprintf(
                    '%02d:%02d:%02d',
                    $hours,
                    $minutes,
                    $seconds
                );
            })

            ->addColumn('action', function ($row) use ($attendanceDate) {

                return '
                    <button
                        class="btn btn-sm btn-primary viewPunchBtn"
                        data-id="' . $row->id . '"
                        data-date="' . $attendanceDate->toDateString() . '">
                        View
                    </button>';
            })

            ->filter(function ($query) use ($request) {

                if (
                    $request->has('search')
                    && !empty($request->search['value'])
                ) {

                    $search = $request->search['value'];

                    $query->where(function ($q) use ($search) {

                        $q->where('name', 'like', "%{$search}%")
                            ->orWhere('emp_id', 'like', "%{$search}%");
                    });
                }
            })

            ->rawColumns(['action'])

            ->make(true);
    }

    public function punchDetails($employeeId, $date)
    {
        $employee = Employee::with('department')
            ->findOrFail($employeeId);

        $attendanceDate = Carbon::parse($date);

        $tableName = 'z_attendance_log_' .
            $attendanceDate->month .
            '_' .
            $attendanceDate->year;

        $logs = collect();

        if (Schema::hasTable($tableName)) {

            $logs = DB::table($tableName)
                ->where('employee_id', $employeeId)
                ->whereDate('log_date', $attendanceDate->toDateString())
                ->orderBy('log_date')
                ->get();
        }

        $workingSeconds = 0;
        $breakSeconds = 0;

        $inTime = null;
        $outTime = null;

        foreach ($logs as $log) {

            if ($log->direction == 'in') {

                if ($outTime) {

                    $breakSeconds +=
                        Carbon::parse($outTime)
                        ->diffInSeconds(
                            Carbon::parse($log->log_date)
                        );

                    $outTime = null;
                }

                $inTime = Carbon::parse($log->log_date);
            } elseif ($log->direction == 'out') {

                if ($inTime) {

                    $workingSeconds +=
                        $inTime->diffInSeconds(
                            Carbon::parse($log->log_date)
                        );

                    $inTime = null;
                }

                $outTime = Carbon::parse($log->log_date);
            }
        }

        // Handle open punch for current date
        if ($attendanceDate->isToday()) {

            if ($inTime) {

                $workingSeconds +=
                    $inTime->diffInSeconds(now());
            }

            if ($outTime) {

                $breakSeconds +=
                    $outTime->diffInSeconds(now());
            }
        }

        return response()->json([
            'employee' => $employee,
            'date' => $attendanceDate->format('d-M-Y'),
            'working_hours' => gmdate('H.i', $workingSeconds),
            'break_hours' => gmdate('H.i', $breakSeconds),
            'logs' => $logs
        ]);
    }
    public function summaryExport(Request $request)
    {
        $attendanceDate = $request->filled('date')
            ? Carbon::parse($request->date)
            : now();

        $year = $request->year ?: $attendanceDate->year;
        $month = $request->month ?: $attendanceDate->month;

        $tableName = 'z_attendance_log_' . $month . '_' . $year;

        $rows = [];

        $employees = Employee::with('department')
            ->where('status', 1)
            ->get();

        foreach ($employees as $employee) {

            $firstIn = '-';
            $lastOut = '-';
            $totalInTime = '00:00:00';
            $totalOutTime = '00:00:00';

            if (Schema::hasTable($tableName)) {

                $logs = DB::table($tableName)
                    ->where('employee_id', $employee->id)
                    ->whereDate(
                        'log_date',
                        $attendanceDate->toDateString()
                    )
                    ->orderBy('log_date')
                    ->get();

                $firstInRecord = $logs
                    ->where('direction', 'in')
                    ->first();

                if ($firstInRecord) {
                    $firstIn = Carbon::parse(
                        $firstInRecord->log_date
                    )->format('h:i A');
                }

                $lastOutRecord = $logs
                    ->where('direction', 'out')
                    ->last();

                if ($lastOutRecord) {
                    $lastOut = Carbon::parse(
                        $lastOutRecord->log_date
                    )->format('h:i A');
                }

                // Total In Time
                $inSeconds = 0;
                $inTime = null;

                foreach ($logs as $log) {

                    if ($log->direction == 'in') {

                        $inTime = Carbon::parse($log->log_date);
                    } elseif (
                        $log->direction == 'out' &&
                        $inTime
                    ) {

                        $inSeconds += $inTime->diffInSeconds(
                            Carbon::parse($log->log_date)
                        );

                        $inTime = null;
                    }
                }

                if (
                    $inTime &&
                    $attendanceDate->isToday()
                ) {
                    $inSeconds += $inTime->diffInSeconds(now());
                }

                $totalInTime = gmdate(
                    'H:i:s',
                    $inSeconds
                );

                // Total Out Time
                $outSeconds = 0;
                $outTime = null;

                foreach ($logs as $log) {

                    if ($log->direction == 'out') {

                        $outTime = Carbon::parse($log->log_date);
                    } elseif (
                        $log->direction == 'in' &&
                        $outTime
                    ) {

                        $outSeconds += $outTime->diffInSeconds(
                            Carbon::parse($log->log_date)
                        );

                        $outTime = null;
                    }
                }

                $totalOutTime = gmdate(
                    'H:i:s',
                    $outSeconds
                );
            }

            $rows[] = [
                $attendanceDate->format('d-M-Y'),
                $employee->name,
                $employee->emp_id,
                optional($employee->department)->name,
                $firstIn,
                $lastOut,
                $totalInTime,
                $totalOutTime,
            ];
        }

        return Excel::download(
            new AttendanceSummaryExport($rows),
            'attendance_summary.xlsx'
        );
    }

    public function reports()
    {
        return view('pages.attendance.reports');
    }
}
