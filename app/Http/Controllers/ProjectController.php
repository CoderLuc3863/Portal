<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\Project;
use App\Models\Payroll;
use Yajra\DataTables\Facades\DataTables;
use App\Exports\ProjectsExport;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

use Maatwebsite\Excel\Facades\Excel;

class ProjectController extends Controller
{
    public function index()
    {
        $employees = Employee::orderBy('name')
            ->get();
       // Only employees who are assigned as Project Managers
        $projectManagerIds = Project::whereNotNull('project_manager_id')
            ->distinct()
            ->pluck('project_manager_id');

        $projectmanagers = Employee::whereIn('id', $projectManagerIds)
            ->orderBy('name')
            ->get();

        // Only employees who are assigned as Team Heads
        $teamHeadIds = Project::whereNotNull('team_head_id')
            ->distinct()
            ->pluck('team_head_id');

        $teamheads = Employee::whereIn('id', $teamHeadIds)
            ->orderBy('name')
            ->get();
            

        return view(
            'pages.project.index',
            compact('employees',"teamheads","projectmanagers")
        );
    }

    public function costs()
    {
        $employees = Employee::orderBy('name')
            ->get();
        // Only employees who are assigned as Project Managers
        $projectManagerIds = Project::whereNotNull('project_manager_id')
            ->distinct()
            ->pluck('project_manager_id');

        $projectmanagers = Employee::whereIn('id', $projectManagerIds)
            ->orderBy('name')
            ->get();

        // Only employees who are assigned as Team Heads
        $teamHeadIds = Project::whereNotNull('team_head_id')
            ->distinct()
            ->pluck('team_head_id');

        $teamheads = Employee::whereIn('id', $teamHeadIds)
            ->orderBy('name')
            ->get();
            

        return view(
            'pages.project.costs',
            compact('employees',"teamheads","projectmanagers")
        );
    }

    public function list(Request $request)
    {
        $query = Project::with("projectManager","teamHead");

        if ($request->year) {

            $query->whereYear(
                'start_date',
                $request->year
            );
        }

        if ($request->month) {

            $query->whereMonth(
                'start_date',
                $request->month
            );
        }

        if ($request->status) {

            $query->where(
                'status',
                $request->status
            );
        }
        if ($request->project_manager_id) {

            $query->where(
                'project_manager_id',
                $request->project_manager_id
            );
        }

        if ($request->team_head_id) {

            $query->where(
                'team_head_id',
                $request->team_head_id
            );
        }

        return DataTables::of($query)

            ->addIndexColumn()

            ->addColumn(
                'members_count',
                fn($row) =>
                count(
                    $row->team_members ?? []
                )
            )

            ->addColumn(
                'action',
                function ($row) {

                    return '
                        <button
                            class="btn btn-sm btn-primary viewBtn"
                            data-id="' . $row->id . '">
                            View
                        </button>

                        <button
                            class="btn btn-sm btn-warning editBtn"
                            data-id="' . $row->id . '">
                            Edit
                        </button>

                        <button
                            class="btn btn-danger btn-sm deleteBtn"
                            data-id="'.$row->id.'">
                            Delete
                        </button>
                    ';
                }
            )
            ->addColumn('start_date', function ($row) {

                return $row->start_date
                    ? date('d-m-Y', strtotime($row->start_date))
                    : '-';
            })

            ->addColumn('end_date', function ($row) {

                return $row->end_date
                    ? date('d-m-Y', strtotime($row->end_date))
                    : '-';
            })
            ->addColumn('project_manager', function ($row) {

                return $row->projectManager->name
                    ? $row->projectManager->name
                    : '-';
            })
            ->addColumn('team_head', function ($row) {

                return $row->teamHead->name
                    ? $row->teamHead->name
                    : '-';
            })
            ->addColumn('progress', function ($row) {

                $totalTasks = \App\Models\Task::where('project_id', $row->id)->count();

                if ($totalTasks == 0) {

                    $progress = 0;
                    $completedTasks = 0;

                } else {

                    $completedTasks = \App\Models\Task::where('project_id', $row->id)
                        ->whereHas('latestUpdate', function ($q) {
                            $q->where('status', 'Completed');
                        })
                        ->count();

                    $progress = round(($completedTasks / $totalTasks) * 100);
                }

                return '
                    <div>
                        <div class="mb-1 fw-bold">'.$progress.'%</div>

                        <div class="progress" style="height:6px;">
                            <div class="progress-bar"
                                role="progressbar"
                                style="width: '.$progress.'%;"
                                aria-valuenow="'.$progress.'"
                                aria-valuemin="0"
                                aria-valuemax="100">
                            </div>
                        </div>

                        <small class="text-muted">
                            '.$completedTasks.' / '.$totalTasks.' Tasks
                        </small>
                    </div>
                ';
            })
            ->filter(function ($query) use ($request) {

                if ($search = $request->search['value']) {

                    $query->where(function ($q) use ($search) {

                        $q->where('project_name', 'like', "%{$search}%");
                    });
                }
            })


            ->rawColumns(['action','progress'])

            ->make(true);
    }

    public function delete($id)
    {
        $project = Project::findOrFail($id);

        $project->delete();

        return response()->json([

            'status' => true,

            'message' => 'Project deleted successfully.'

        ]);
    }

    public function store(Request $request)
    {
        $request->validate([

            'project_name'       => 'required',
            'project_manager_id' => 'required',
            'team_head_id'       => 'required',
            'estimated_hours'    => 'required',
            'start_date'         => 'required',
            'end_date'           => 'required',

        ]);

        $teamMembers = [];

        if ($request->employee_id) {

            foreach ($request->employee_id as $index => $employeeId) {

                $teamMembers[$employeeId] = [

                    'role' => $request->role[$index] ?? '',

                    'type' => $request->member_type[$index] ?? 'billable'

                ];
            }
        }

        $modules = [];
        $lastModuleIndex = $project?->last_module_index ?? 0;
        if ($request->project_modules) {

            foreach ($request->project_modules as $moduleId => $moduleName) {
                 $lastModuleIndex = max(
                    $lastModuleIndex,
                    (int) $moduleId
                );
                if (!empty($moduleName)) {

                    $modules[$moduleId] = $moduleName;
                }
            }
        }

        Project::updateOrCreate(

            [
                'id' => $request->id
            ],

            [
                'project_name'       => $request->project_name,

                'project_manager_id' => $request->project_manager_id,

                'team_head_id'       => $request->team_head_id,

                'estimated_hours'    => $request->estimated_hours,

                'project_modules'    => $modules,
                'last_module_index' => $lastModuleIndex,

                'start_date'         => $request->start_date,

                'end_date'           => $request->end_date,

                'status'             => $request->status,

                'description'        => $request->description,

                'team_members'       => $teamMembers
            ]
        );

        return response()->json([
            'status'  => true,
            'message' => 'Project saved successfully'
        ]);
    }

    

    public function edit($id)
    {
        $project = Project::findOrFail($id);
        
        $project->start_date = date(
            'Y-m-d',
            strtotime($project->start_date)
        );

        $project->end_date = date(
            'Y-m-d',
            strtotime($project->end_date)
        );

        return response()->json($project);
    }

    public function view(Request $request, $id)
{
    $project = Project::with("projectManager", "teamHead")
        ->findOrFail($id);

    // Use project dates if filter dates are empty
    $fromDate = $request->filled('from_date')
        ? $request->from_date
        : $project->start_date;

    $toDate = $request->filled('to_date')
        ? $request->to_date
        : $project->end_date;

    $members = [];

    $slno = 1;
    $totalcost = 0;

    foreach (($project->team_members ?? []) as $employeeId => $member) {

        $employee = Employee::with([
            'department',
            'designation'
        ])->find($employeeId);

        if ($employee) {

            $hoursworked = $this->getProjectMembersWorkedHours(
                $id,
                $employee->id,
                $fromDate,
                $toDate
            );

            $costperhour = $this->costperhour($employee->id);

            // Calculate employee total cost
            $employeeTotalCost = $hoursworked * $costperhour;

            // Add to project total
            $totalcost += $employeeTotalCost;

            $members[] = [

                'slno' => $slno++,

                'employee_id' => $employee->emp_id,

                'employee_name' => $employee->name,

                'department' => $employee->department->name ?? '-',

                'role' => $member['role'] ?? '-',

                'hoursworked' => $hoursworked,

                'costperhour' => round($costperhour, 2),

                'totalcost' => round($employeeTotalCost, 2),
            ];
        }
    }

    // Modules
    $modules = [];
    $moduleSlNo = 1;

    foreach (($project->project_modules ?? []) as $moduleId => $moduleName) {

        $modules[] = [

            'slno' => $moduleSlNo++,

            'module_id' => $moduleId,

            'module_name' => $moduleName
        ];
    }

    return response()->json([

        'project' => $project,

        'members' => $members,

        'modules' => $modules,

        'project_manager' =>
            optional($project->projectManager)->name,

        'team_head' =>
            optional($project->teamHead)->name,

        'worker_hr' => $this->getProjectWorkedHours(
            $id,
            $fromDate,
            $toDate
        ),

        'totalcost' => round($totalcost, 2),

        'from_date' => $fromDate,

        'to_date' => $toDate
    ]);
}
    public function viewold(Request $request, $id)
    {
        $project = Project::with("projectManager", "teamHead")->findOrFail($id);
        $fromDate = $request->from_date ?? $project->start_date;
        $toDate   = $request->to_date ?? $project->end_date;

        $members = [];

        $slno = 1;
        $totalcost=0;
        foreach (($project->team_members ?? []) as $employeeId => $member) {

            $employee = Employee::with([
                'department',
                'designation'
            ])->find($employeeId);

            if ($employee) {
                $hoursworked=$this->getProjectMembersWorkedHours(
                    $id,
                    $employee->id,
                    $fromDate,
                    $toDate
                );
                $costperhour=$this->costperhour($employee->id);
                $totalcost=$totalcost+($hoursworked*$costperhour);
                $members[] = [

                    'slno' => $slno++,

                    'employee_id' => $employee->emp_id,

                    'employee_name' => $employee->name,

                    'department' => $employee->department->name ?? '-',

                    'role' => $member['role'] ?? '-',

                    'hoursworked' => $hoursworked,
                    'costperhour' => $costperhour,


                    // 'type' => $member['type'] ?? 'billable',

                    // 'employee_db_id' => $employee->id

                ];
            }
        }

        // Modules
        $modules = [];
        $moduleSlNo = 1;

        foreach (($project->project_modules ?? []) as $moduleId => $moduleName) {

            $modules[] = [

                'slno' => $moduleSlNo++,

                'module_id' => $moduleId,

                'module_name' => $moduleName

            ];
        }
        return response()->json([

            'project' => $project,

            'members' => $members,

            'modules' => $modules,

            'project_manager' => optional($project->projectManager)->name,

            'team_head' => optional($project->teamHead)->name,

            "worker_hr"=> $this->getProjectWorkedHours(
                    $id,
                    $fromDate,
                    $toDate
                ),
            "totalcost"=>$totalcost

        ]);
    }

    public function export(Request $request)
    {
        return Excel::download(
            new ProjectsExport($request),
            'Projects_Report.xlsx'
        );
    }
    public function costExport(Request $request)
    {
        return Excel::download(
            new ProjectsExport($request),
            'Projects_Report.xlsx'
        );
    }


    public function costList(Request $request)
    {
        $fromDate = $request->input('from_date');
        $toDate   = $request->input('to_date');

        $query = Project::with([
            'projectManager',
            'teamHead'
        ]);

        // From Date
        if ($fromDate) {
            $query->whereDate('start_date', '>=', $fromDate);
        }

        // To Date
        if ($toDate) {
            $query->whereDate('start_date', '<=', $toDate);
        }

        // Status
        if ($request->status) {
            $query->where(
                'status',
                $request->status
            );
        }

        // Project Manager
        if ($request->project_manager_id) {
            $query->where(
                'project_manager_id',
                $request->project_manager_id
            );
        }

        // Team Head
        if ($request->team_head_id) {
            $query->where(
                'team_head_id',
                $request->team_head_id
            );
        }

        return DataTables::of($query)

            ->addIndexColumn()

            ->addColumn('members_count', function ($row) {

                return count(
                    $row->team_members ?? []
                );
            })

            ->addColumn('project_manager', function ($row) {

                return optional($row->projectManager)->name ?? '-';
            })

            ->addColumn('team_head', function ($row) {

                return optional($row->teamHead)->name ?? '-';
            })

            // Total Hours Worked
            ->addColumn('total_hours', function ($row) use ($fromDate, $toDate) {

                $hours = $this->getProjectWorkedHours(
                    $row->id,
                    $fromDate,
                    $toDate
                );

                return round((float) $hours, 2) . ' Hrs';
            })

            ->addColumn('status', function ($row) {

                $class = match ($row->status) {

                    'Completed' => 'bg-success',
                    'Pending'   => 'bg-warning text-dark',
                    'Cancelled' => 'bg-danger',

                    default => 'bg-secondary',
                };

                return '<span class="badge ' . $class . '">'
                    . e($row->status)
                    . '</span>';
            })

            ->addColumn('action', function ($row) {

                return '
                    <button
                        type="button"
                        class="btn btn-sm btn-primary viewBtn"
                        data-id="' . $row->id . '">
                        View
                    </button>

                    <button
                        type="button"
                        class="btn btn-sm btn-warning editBtn"
                        data-id="' . $row->id . '">
                        Edit
                    </button>
                ';
            })

            ->filter(function ($query) use ($request) {

                $search = $request->input('search.value');

                if ($search) {

                    $query->where(function ($q) use ($search) {

                        $q->where(
                            'project_name',
                            'like',
                            "%{$search}%"
                        )
                        ->orWhereHas('projectManager', function ($q) use ($search) {

                            $q->where(
                                'name',
                                'like',
                                "%{$search}%"
                            );
                        })
                        ->orWhereHas('teamHead', function ($q) use ($search) {

                            $q->where(
                                'name',
                                'like',
                                "%{$search}%"
                            );
                        });
                    });
                }
            })

            ->rawColumns([
                'status',
                'action'
            ])

            ->make(true);
    }
    private function getProjectWorkedHours($projectId, $fromDate = null, $toDate = null)
    {
        $query = DB::table('task_updates')
            ->join('tasks', 'tasks.id', '=', 'task_updates.task_id')
            ->where('tasks.project_id', $projectId)
            ->where('task_updates.hours_worked', '!=', "0.00");

        if ($fromDate) {
            $query->whereDate('task_updates.created_at', '>=', $fromDate);
        }

        if ($toDate) {
            $query->whereDate('task_updates.created_at', '<=', $toDate);
        }

        return $query->sum('task_updates.hours_worked');
    }

    private function getProjectMembersWorkedHours($projectId,$employeeId, $fromDate = null, $toDate = null)
    {
        $query = DB::table('task_updates')
            ->join('tasks', 'tasks.id', '=', 'task_updates.task_id')
            ->where('tasks.project_id', $projectId)
            ->where('task_updates.employee_id', $employeeId)
            ->where('task_updates.hours_worked', '!=', "0.00");

        if ($fromDate) {
            $query->whereDate('task_updates.created_at', '>=', $fromDate);
        }

        if ($toDate) {
            $query->whereDate('task_updates.created_at', '<=', $toDate);
        }

        return $query->sum('task_updates.hours_worked');
    }

    public function costperhour($id){
        $dailyRate = Payroll::where('employee_id', $id)
        ->orderByDesc('id')
        ->value('daily_rate');

        return $dailyRate ? ($dailyRate / 8) : 0;
    }
}
