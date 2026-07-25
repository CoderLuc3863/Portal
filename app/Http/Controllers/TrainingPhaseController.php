<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Employee;
use App\Models\TrainingPhase;
use App\Models\TrainingPhaseAssign;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;

class TrainingPhaseController extends Controller
{
    public function index()
    {
        $departments = Department::orderBy("name","asc")->get();

        return view(
            'pages.training-phases.index',
            compact('departments')
        );
    }

    public function store(Request $request)
    {
        $request->validate([
            'department_id' => 'required',
            'phase_name' => 'required|max:256',
            'focus' => 'required|max:256'
        ]);

        TrainingPhase::create([
            'department_id' => $request->department_id,
            'phase_name' => $request->phase_name,
            'focus' => $request->focus,
            'topics' => $request->topics,
            'status' => $request->status ?? 'active'
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Phase created successfully'
        ]);
    }

    public function view($id)
    {
        return TrainingPhase::with('department')
            ->findOrFail($id);
    }

    public function edit($id)
    {
        return TrainingPhase::findOrFail($id);
    }
    public function list(Request $request)
    {
        $query = TrainingPhase::with('department');

        if ($request->department_id) {
            $query->where('department_id', $request->department_id);
        }

        return DataTables::of($query)

            ->addIndexColumn()

            ->addColumn('department', function ($row) {
                return $row->department->name ?? '-';
            })

            ->addColumn('duration', function ($row) {

                $total = 0;

                if (!empty($row->topics)) {

                    foreach ($row->topics as $topic) {
                        $total += (int) ($topic['duration'] ?? 0);
                    }
                }

                return $total . ' Week(s)';
            })

            ->addColumn('action', function ($row) {

                return '
                    <button
                        class="btn btn-info btn-sm viewBtn"
                        data-id="' . $row->id . '">
                        View
                    </button>

                    <button
                        class="btn btn-warning btn-sm editBtn"
                        data-id="' . $row->id . '">
                        Edit
                    </button>
                ';
            })

            ->filter(function ($query) use ($request) {

                if ($request->search['value']) {

                    $search = $request->search['value'];

                    $query->where(function ($q) use ($search) {

                        $q->where('phase_name', 'like', "%{$search}%")
                            ->orWhere('focus', 'like', "%{$search}%");
                    });
                }
            })

            ->rawColumns(['action'])

            ->make(true);
    }

    public function update(
        Request $request,
        $id
    ) {
        $phase = TrainingPhase::findOrFail($id);

        $phase->update([

            'department_id' =>
            $request->department_id,

            'phase_name' =>
            $request->phase_name,

            'focus' =>
            $request->focus,

            'topics' =>
            $request->topics

        ]);

        return response()->json([
            'status' => true,
            'message' => 'Phase updated successfully'
        ]);
    }

    public function traineemangement()
    {
        $departments = Department::where('status', 'active')->orderBy("name","asc")->get();
        $trainers = Employee::where('status', '1')->whereNotNull('confirm_date')->orderBy("name","asc")->get();
        $trainees = Employee::where('status', '1')->whereNull('confirm_date')->orderBy("name","asc")->get();

        return view(
            'pages.training-phases.traineemangement',
            compact('departments', "trainers", "trainees")
        );
    }

    public function assignList(Request $request)
    {
        $data = TrainingPhaseAssign::with(['trainee', 'trainer'])
            ->select(
                'trainee_id',
                'trainer_id',
                DB::raw('MIN(id) as id'),
                DB::raw('MIN(assigned_date) as assigned_date'),
                DB::raw('COUNT(*) as total_phases'),
                DB::raw("
                    CASE
                        WHEN SUM(CASE WHEN status='pending' THEN 1 ELSE 0 END) > 0
                        THEN 'pending'
                        ELSE 'completed'
                    END as status
                "),
                DB::raw("
                    CASE
                        WHEN SUM(CASE WHEN hr_status='pending' THEN 1 ELSE 0 END) > 0
                        THEN 'pending'
                        ELSE 'completed'
                    END as hr_status
                ")
            )
            ->groupBy('trainee_id', 'trainer_id');

        if(!in_array(Auth::user()->department_id, [1, 2])){
            $data->where('trainee_id',Auth::user()->id);
        }

        if ($request->department_id) {

            $data->whereHas('trainee', function ($q) use ($request) {
                $q->where('department_id', $request->department_id);
            });
        }

        return DataTables::of($data)

            ->addIndexColumn()

            ->addColumn('trainee', fn($row) => $row->trainee->name ?? '-')

            ->addColumn('trainer', fn($row) => $row->trainer->name ?? '-')

            ->editColumn('status', function ($row) {

                return $row->status == 'completed'
                    ? '<span class="badge bg-success">Completed</span>'
                    : '<span class="badge bg-warning">Pending</span>';
            })
            ->editColumn('assigned_date', function ($row) {

                return \Carbon\Carbon::parse($row->assigned_date)
                    ->format('d-m-Y');
            })

            ->editColumn('hr_status', function ($row) {

                return $row->hr_status == 'completed'
                    ? '<span class="badge bg-success">Completed</span>'
                    : '<span class="badge bg-warning">Pending</span>';
            })

            ->addColumn('action', function ($row) {
                if(!in_array(Auth::user()->department_id, [1, 2])){
                    return '
                        <button class="btn btn-sm btn-info viewBtn"
                            data-id="' . $row->id . '">
                            View
                        </button>';
                }
                else{
                    return '
                        <button class="btn btn-sm btn-info viewBtn"
                            data-id="' . $row->id . '">
                            View
                        </button>
                        <button class="btn btn-sm btn-danger deleteBtn"
                            data-id="' . $row->id . '">
                            Delete
                        </button>
                    ';
                }
            })
            ->filter(function ($query) {

                $search = request('search')['value'] ?? null;

                if ($search) {

                    $query->where(function ($q) use ($search) {

                        $q->whereHas('trainee', function ($sub) use ($search) {
                            $sub->where('name', 'like', "%{$search}%");
                        })

                            ->orWhereHas('trainer', function ($sub) use ($search) {
                                $sub->where('name', 'like', "%{$search}%");
                            });
                    });
                }
            })
            ->rawColumns([
                'status',
                'hr_status',
                'action'
            ])

            ->make(true);
    }

    public function viewAssignment($id)
    {
        $assignment = TrainingPhaseAssign::findOrFail($id);

        $data = TrainingPhaseAssign::with(['trainingPhase', 'trainee', 'trainer'])
            ->where('trainee_id', $assignment->trainee_id)
            ->where('trainer_id', $assignment->trainer_id)
            ->get()
            ->map(function ($item) {
                return [
                    'id'             => $item->id,
                    'phase_name'     => $item->trainingPhase?->phase_name,
                    'topic_details'  => $item->trainingPhase,
                    'status'         => $item->status,
                    'hr_status'      => $item->hr_status,
                    'hr_remark'      => $item->hr_remark,
                    'assigned_date'  => optional($item->assigned_date)->format('d-m-Y'),
                    'trainee_name'   => $item->trainee?->name,
                    'trainer_name'   => $item->trainer?->name,
                ];
            });

        return response()->json($data);
    }
    // public function viewAssignmentold($id)
    // {
    //     $assignment = TrainingPhaseAssign::with("trainingPhase")->findOrFail($id);

    //     $data = DB::table('training_phase_assigns as tpa')
    //         ->join('training_phases as tp', 'tp.id', '=', 'tpa.training_phase_id')
    //         ->join('employees as trainee', 'trainee.id', '=', 'tpa.trainee_id')
    //         ->join('employees as trainer', 'trainer.id', '=', 'tpa.trainer_id')
    //         ->select(
    //             'tpa.id',
    //             'tp.phase_name as phase_name',
    //             'tpa.status',
    //             'tpa.hr_status',
    //             'tpa.hr_remark',
    //             DB::raw("DATE_FORMAT(tpa.assigned_date, '%d-%m-%Y') as assigned_date"),
    //             'trainee.name as trainee_name',
    //             'trainer.name as trainer_name'
    //         )
    //         ->where('tpa.trainee_id', $assignment->trainee_id)
    //         ->where('tpa.trainer_id', $assignment->trainer_id)
    //         ->get();
    //     return response()->json($data);
    // }
    public function phaseHrReview(Request $request, $id)
    {
        DB::table('training_phase_assigns')
            ->where('id', $id)
            ->update([
                'hr_status' => $request->hr_status,
                'hr_remark' => $request->hr_remark,
                'updated_at' => now()
            ]);

        return response()->json([
            'status' => true,
            'message' => 'Phase HR review updated successfully'
        ]);
    }
    public function phaseEmpReview(Request $request, $id)
    {
        DB::table('training_phase_assigns')
            ->where('id', $id)
            ->update([
                'status' => $request->emp_status,
                'updated_at' => now()
            ]);

        return response()->json([
            'status' => true,
            'message' => 'Phase review updated successfully'
        ]);
    }
    public function deleteAssignment($id)
    {
        $assignment = TrainingPhaseAssign::findOrFail($id);

        TrainingPhaseAssign::where(
            'trainee_id',
            $assignment->trainee_id
        )
            ->where(
                'trainer_id',
                $assignment->trainer_id
            )
            ->delete();

        return response()->json([
            'status' => true,
            'message' => 'Assignment deleted successfully'
        ]);
    }
    public function assign(Request $request)
    {
        $request->validate([
            'trainer_id' => 'required',
            'trainee_id' => 'required',
        ]);

        $trainer = Employee::findOrFail($request->trainer_id);
        $trainee = Employee::findOrFail($request->trainee_id);

        $phases = TrainingPhase::where(
            'department_id',
            $trainer->department_id
        )->get();
        if ($phases->isEmpty()) {

            return response()->json([
                'status' => false,
                'message' => 'No training phases found for the selected employee department.'
            ], 404);
        }
        foreach ($phases as $phase) {

            TrainingPhaseAssign::firstOrCreate(
                [
                    'training_phase_id' => $phase->id,
                    'trainee_id' => $trainee->id,
                ],
                [
                    'trainer_id' => $trainer->id,
                    'assigned_date' => $request->assigned_date ?? now(),
                    'status' => 'pending',
                    'hr_status' => 'pending',
                ]
            );
        }

        return response()->json([
            'status' => true,
            'message' => 'Assigned successfully'
        ]);
    }

    public function report()
    {
        $departments = Department::where('status', 'active')->orderBy("name","asc")->get();
        $employees = Employee::where('status', '1')->orderBy("name","asc")->get();

        return view(
            'pages.training-phases.report',
            compact('departments', "employees")
        );
    }
}
