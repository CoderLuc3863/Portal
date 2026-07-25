<?php

namespace App\Http\Controllers;

use App\Models\EvaluationForm;
use App\Models\EvaluationQuestion;
use App\Models\Department;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;
use App\Models\Employee;
use App\Models\EvaluationAssign;
use App\Models\EvaluationSchedule;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\EvaluationReportExport;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;

class EvaluationController extends Controller
{
    public function index()
    {
        return view('pages.evaluation.forms');
    }

    public function list()
    {
        $data = EvaluationForm::with('questions');
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('total_marks', function ($row) {
                return $row->questions->sum('marks');
            })
            ->addColumn('total_questions', function ($row) {
                return $row->questions->count();
            })
            ->addColumn('action', function ($row) {
                return '
                    <button
                        class="btn btn-primary btn-sm viewBtn"
                        data-id="' . $row->id . '">
                        View
                    </button>

                    <button
                        class="btn btn-warning btn-sm editBtn"
                        data-id="' . $row->id . '">
                        Edit
                    </button>

                    <button
                        class="btn btn-danger btn-sm deleteBtn"
                        data-id="' . $row->id . '">
                        Delete
                    </button>
                ';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function store(Request $request)
    {
        DB::beginTransaction();

        try {

            if (!empty($request->id)) {

                $form = EvaluationForm::findOrFail(
                    $request->id
                );

                $form->update([
                    'name' => $request->name
                ]);

                EvaluationQuestion::where(
                    'evaluation_form_id',
                    $form->id
                )->delete();

                $message = 'Evaluation Form Updated Successfully';
            } else {

                $form = EvaluationForm::create([
                    'name' => $request->name
                ]);

                $message = 'Evaluation Form Created Successfully';
            }

            foreach ($request->question as $key => $question) {

                $subPoints = [];

                if (!empty($request->sub_points[$key])) {

                    $lines = preg_split(
                        "/\r\n|\n|\r/",
                        $request->sub_points[$key]
                    );

                    foreach ($lines as $line) {

                        $line = trim(
                            str_replace('•', '', $line)
                        );

                        if ($line != '') {

                            $subPoints[] = $line;
                        }
                    }
                }

                EvaluationQuestion::create([

                    'evaluation_form_id' => $form->id,

                    'question' => $question,

                    'marks' => $request->marks[$key],

                    'subpoints' => json_encode(
                        $subPoints
                    )

                ]);
            }

            DB::commit();

            return response()->json([

                'status' => true,

                'message' => $message

            ]);
        } catch (\Exception $e) {

            DB::rollBack();

            return response()->json([

                'status' => false,

                'message' => $e->getMessage()

            ], 500);
        }
    }
    public function delete(Request $request)
    {
        EvaluationForm::findOrFail($request->id)
            ->delete();
        return response()->json([
            'status' => true,
            'message' => 'Deleted successfully'
        ]);
    }
    public function getForm($id)
    {
        $form = EvaluationForm::with([
            'questions'
        ])->findOrFail($id);

        return response()->json($form);
    }

    public function viewForm($id)
    {
        $form = EvaluationForm::with([
            'questions'
        ])->findOrFail($id);

        return response()->json($form);
    }
    public function scheduling()
    {
        $departments = Department::where('status', 'active')->orderBy("name","asc")->get();
        $evaluation_forms = EvaluationForm::where('status', 'active')->get();

        return view(
            'pages.evaluation.schedule',
            compact(
                'departments',
                'evaluation_forms',
            )
        );
    }



    public function assignEvaluationForm(Request $request)
    {
        $request->validate([
            'evaluation_id' => 'required|exists:evaluation_forms,id',
            'department'    => 'required|exists:departments,id',
            'year'          => 'required',
            'quater'        => 'required',
            'enddate'       => 'required|date',
        ]);

        DB::beginTransaction();

        try {
            $exists = EvaluationSchedule::where('evaluation_form_id', $request->evaluation_id)
                ->where('department_id', $request->department)
                ->where('year', $request->year)
                ->where('quarter', $request->quater)
                ->exists();

            if ($exists) {
                return response()->json([
                    'status' => false,
                    'message' => 'This evaluation form is already assigned for the selected department and quarter.'
                ]);
            }
            // Create Schedule
            $schedule = EvaluationSchedule::create([
                'evaluation_form_id' => $request->evaluation_id,
                'department_id'      => $request->department,
                'year'               => $request->year,
                'quarter'            => $request->quater,
                'end_date'           => $request->enddate,
            ]);

            // Get employees of selected department
            $employees = Employee::where(
                'department_id',
                $request->department
            )->where("status", "1")->get();

            $assignments = [];

            foreach ($employees as $employee) {

                $assignments[] = [
                    'evaluation_form_id' => $request->evaluation_id,
                    'employee_id'        => $employee->id,
                    'year'               => $request->year,
                    'quarter'            => $request->quater,
                    'status'             => 'Pending',
                    'created_at'         => now(),
                    'updated_at'         => now(),
                ];
            }

            if (!empty($assignments)) {
                EvaluationAssign::insert($assignments);
            }

            DB::commit();

            return response()->json([
                'status'  => true,
                'message' => 'Evaluation form assigned successfully.'
            ]);
        } catch (\Exception $e) {

            DB::rollBack();

            return response()->json([
                'status'  => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
    public function scheduleList(Request $request)
    {
        $query = EvaluationSchedule::with([
            'form' => function ($q) {
                $q->withSum('questions', 'marks');
            },
            'department'
        ]);

        if ($request->year) {
            $query->where('year', $request->year);
        }

        if ($request->quater) {
            $query->where('quarter', $request->quater);
        }

        if ($request->department_id) {
            $query->where('department_id', $request->department_id);
        }



        return DataTables::of($query)

            ->addIndexColumn()

            ->addColumn('form_name', function ($row) {
                return $row->form->name ?? '-';
            })

            ->addColumn('department_name', function ($row) {
                return $row->department->name ?? '-';
            })

            ->addColumn('total_score', function ($row) {

                return $row->form->questions_sum_marks ?? 0;
            })

            ->addColumn('end_date', function ($row) {

                return $row->end_date
                    ? date('d-m-Y', strtotime($row->end_date))
                    : '-';
            })

            ->addColumn('action', function ($row) {

                return '
                    <button
                        class="btn btn-sm btn-info viewBtn"
                        data-id="' . $row->id . '">
                        View
                    </button>
                    <button
                        class="btn btn-sm btn-danger deleteBtn"
                        data-id="' . $row->id . '">
                        Delete
                    </button>
                ';
            })
            ->filter(function ($query) use ($request) {

                if ($search = $request->search['value']) {

                    $query->where(function ($q) use ($search) {

                        $q->where('year', 'like', "%{$search}%")
                            ->orWhere('quarter', 'like', "%{$search}%")

                            ->orWhereHas('form', function ($form) use ($search) {
                                $form->where('name', 'like', "%{$search}%");
                            })

                            ->orWhereHas('department', function ($dept) use ($search) {
                                $dept->where('name', 'like', "%{$search}%");
                            })

                            ->orWhereRaw(
                                "DATE_FORMAT(end_date,'%d-%m-%Y') LIKE ?",
                                ["%{$search}%"]
                            );
                    });
                }
            })
            ->rawColumns(['action'])

            ->make(true);
    }
    public function scheduleView($id)
    {
        $schedule = EvaluationSchedule::with([
            'department',
            'form.questions'
        ])->findOrFail($id);

        return response()->json($schedule);
    }

    public function deleteSchedule($id)
    {
        $schedule = EvaluationSchedule::findOrFail($id);

        EvaluationAssign::where(
            'evaluation_form_id',
            $schedule->evaluation_form_id
        )
            ->where('year', $schedule->year)
            ->where('quarter', $schedule->quarter)
            ->whereHas('employee', function ($q) use ($schedule) {
                $q->where('department_id', $schedule->department_id);
            })
            ->delete();

        $schedule->delete();

        return response()->json([
            'status' => true,
            'message' => 'Schedule deleted successfully.'
        ]);
    }
    public function report()
    {
        $departments = Department::where('status', 'active')->orderBy("name","asc")->get();
        $evaluation_forms = EvaluationForm::where('status', 'active')->get();

        return view(
            'pages.evaluation.report',
            compact(
                'departments',
                'evaluation_forms',
            )
        );
    }
    public function evaluationReportList(Request $request)
    {
        $query = EvaluationAssign::with([
            'employee.department',
            'form'
        ])->when(
            !in_array(Auth::user()->department_id, [1, 2]),
            function ($q) {
                $q->where('employee_id', Auth::id());
            }
        );

        if ($request->year) {
            $query->where('year', $request->year);
        }

        if ($request->quater) {
            $query->where('quarter', $request->quater);
        }

        if ($request->department_id) {

            $query->whereHas('employee', function ($q) use ($request) {

                $q->where(
                    'department_id',
                    $request->department_id
                );
            });
        }

        if ($request->performance_status) {

            $query->where(
                'review',
                $request->performance_status
            );
        }

        if ($request->submission_status) {

            $request->submission_status == 'completed'
                ? $query->whereNotNull('submitted_date')
                : $query->whereNull('submitted_date');
        }


        return DataTables::of($query)

            ->addIndexColumn()

            ->addColumn('employee_name', function ($row) {

                return $row->employee->name ?? '-';
            })

            ->addColumn('employee_code', function ($row) {

                return $row->employee->emp_id ?? '-';
            })

            ->addColumn('form_name', function ($row) {

                return $row->form->name ?? '-';
            })

            ->addColumn('department_name', function ($row) {

                return $row->employee->department->name ?? '-';
            })

            ->addColumn('submitted_date', function ($row) {

                return $row->submitted_date
                    ? date('d-m-Y', strtotime($row->submitted_date))
                    : '-';
            })
            ->addColumn('employee_score', function ($row) {

                if (!$row->marks) {
                    return '-';
                }

                $obtainedMarks = array_sum($row->marks);

                $totalMarks = $row->form
                    ? $row->form->questions()->sum('marks')
                    : 0;

                return $obtainedMarks . '/' . $totalMarks;
            })
            ->addColumn('assessment_score', function ($row) {

                if (!$row->assessment_marks) {
                    return '-';
                }

                $assessmentMarks = array_sum($row->assessment_marks);

                $totalMarks = $row->form
                    ? $row->form->questions()->sum('marks')
                    : 0;

                return $assessmentMarks . '/' . $totalMarks;
            })

            ->addColumn('performance_review', function ($row) {

                return $row->review ?? 'Pending';
            })

            ->addColumn('submission_status', function ($row) {

                if (!empty($row->submitted_date)) {

                    return '<span class="badge bg-success">
                                Completed
                            </span>';
                }

                return '<span class="badge bg-warning text-dark">
                            Pending
                        </span>';
            })

            ->addColumn('action', function ($row) {
                if(in_array(Auth::user()->department_id, [1, 2]))
                {
                    if (empty($row->submitted_date) && $row->employee_id!=Auth::user()->id) {
                        return '    
                        <button
                                class="btn btn-sm btn-secondary" disabled
                                data-id="' . $row->id . '">
                                View
                            </button>
                        ';
                    } elseif(empty($row->submitted_date) && $row->employee_id==Auth::user()->id){
                        return '
                        <button
                            class="btn btn-sm btn-primary viewBtnForm"
                            data-id="' . $row->id . '">
                            View
                        </button>
                        ';
                    } else{
                        return '
                        <button
                            class="btn btn-sm btn-primary viewBtn"
                            data-id="' . $row->id . '">
                            View
                        </button>
                        ';
                    }
                }else{
                    if ($row->review=="Pending") {
                     return '
                        <button
                            class="btn btn-sm btn-primary viewBtnForm"
                            data-id="' . $row->id . '">
                            View
                        </button>
                    ';
                    }
                    else{
                         return '    
                        <button
                                class="btn btn-sm btn-secondary" disabled
                                data-id="' . $row->id . '">
                                View
                            </button>
                        ';

                    }
                }
            })
            ->filter(function ($query) use ($request) {

                if ($search = $request->input('search.value')) {

                    $query->where(function ($q) use ($search) {

                        $q->where('year', 'like', "%{$search}%")
                            ->orWhere('quarter', 'like', "%{$search}%")

                            ->orWhereHas('form', function ($f) use ($search) {

                                $f->where(
                                    'name',
                                    'like',
                                    "%{$search}%"
                                );
                            })

                            ->orWhereHas('employee.department', function ($d) use ($search) {

                                $d->where(
                                    'name',
                                    'like',
                                    "%{$search}%"
                                );
                            });
                    });
                }
            })

            ->rawColumns(['submission_status', 'action', "assessment_score"])

            ->make(true);
    }
    public function evaluationReportView($id)
    {
        $report = EvaluationAssign::with([
            'employee.department',
            'employee.designation',
            'form.questions'
        ])->findOrFail($id);

        $report->employee->formatted_joining_date = $report->employee->joining_date
            ? date('d-m-Y', strtotime($report->employee->joining_date))
            : '-';

        $report->formatted_submitted_date = $report->submitted_date
            ? date('d-m-Y', strtotime($report->submitted_date))
            : '-';

        return response()->json($report);
    }
    public function exportReport(Request $request)
    {
        return Excel::download(
            new EvaluationReportExport($request),
            'evaluation-report.xlsx'
        );
    }
    public function saveEvaluationReview(Request $request)
    {
        $request->validate([

            'id' => 'required|exists:evaluation_assign,id',

            'review' => [
                'required',
                'in:Outstanding,Fully Performing,Developing,Under Performing'
            ],

            'assessment_marks' => 'required|array'

        ]);

        $evaluation = EvaluationAssign::findOrFail(
            $request->id
        );

        $evaluation->assessment_marks =
            $request->assessment_marks;

        $evaluation->review =
            $request->review;

        $evaluation->reviewed_at =
            Carbon::now();

        $evaluation->save();

        return response()->json([

            'status' => true,

            'message' =>
            'Evaluation review saved successfully.'

        ]);
    }

    public function saveEmpEvaluationReview(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:evaluation_assign,id',
            'employeeMarks' => 'required|array',
            'justifications' => 'required|array'
        ]);

        $evaluation = EvaluationAssign::findOrFail(
            $request->id
        );

        $evaluation->marks =
            $request->employeeMarks;

        $evaluation->justifications =
            $request->justifications;


        $evaluation->submitted_date =
            Carbon::now();

        $evaluation->save();

        return response()->json([

            'status' => true,

            'message' =>
            'Evaluation Submitted saved successfully.'

        ]);
    }

    public function pip()
    {
        $departments = Department::where('status', 1)->orderBy("name","asc")->get();

        return view('pages.evaluation.pip', compact('departments'));
    }
    public function pipList(Request $request)
    {


        $year = $request->year;
        $department = $request->department;

        $employees = Employee::with('department')
            ->when($department, function ($q) use ($department) {
                $q->where('department_id', $department);
            })
            ->whereHas('evaluationForms', function ($q) use ($year) {
                if ($year) {
                    $q->where('year', $year);
                }

                $q->where('review', 'Under Performing');
            });

        return DataTables::of($employees)

            ->addIndexColumn()

            ->addColumn('employee_id', function ($row) {
                return $row->emp_id;
            })

            ->addColumn('employee_name', function ($row) {
                return $row->name;
            })

            ->addColumn('department', function ($row) {
                return $row->department->name ?? '-';
            })

            ->addColumn('year', function ($row) use ($year) {
                return $year;
            })

            ->addColumn('review_details', function ($row) use ($year) {

                $reviews = EvaluationAssign::where('employee_id', $row->id)
                    ->when($year, function ($q) use ($year) {
                        $q->where('year', $year);
                    })
                    ->get();

                $html = '';

                foreach ($reviews as $review) {

                    $badge = 'secondary';

                    if ($review->review == 'Outstanding') {
                        $badge = 'success';
                    } elseif ($review->review == 'Developing') {
                        $badge = 'warning';
                    } elseif ($review->review == 'Under Performing') {
                        $badge = 'danger';
                    }

                    $html .= '<div class="mb-1">
                                    <strong>' . $review->quarter . '</strong> :
                                    <span class="badge bg-' . $badge . '">
                                        ' . $review->review . '
                                    </span>
                                </div>';
                }

                return $html;
            })

            ->addColumn('action', function ($row) {

                return '
                       -
                    ';
            })
            // ->filterColumn('employee_name', function ($query, $keyword) {
            //         $query->whereHas('employee', function ($q) use ($keyword) {
            //             $q->where('name', 'like', "%{$keyword}%");
            //         });
            //     })

            //     // Employee ID Search
            //     ->filterColumn('employee_id', function ($query, $keyword) {
            //         $query->whereHas('employee', function ($q) use ($keyword) {
            //             $q->where('emp_id', 'like', "%{$keyword}%");
            //         });
            //     })    
            ->rawColumns([
                'review_details',
                'action'
            ])

            ->make(true);
    }
}
