<?php

namespace App\Exports;

use App\Models\Project;
use App\Models\Employee;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ProjectsExport implements FromCollection, WithHeadings
{
    protected $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function headings(): array
    {
        return [
            'Project Name',
            'Start Date',
            'End Date',
            'Status',
            'Project Manager',
            'Team Head',
            'Members Count',
            'Team Members',
            'Description'
        ];
    }

    public function collection()
    {
        $query = Project::with("projectManager","teamHead");

        if ($this->request->year) {

            $query->whereYear(
                'start_date',
                $this->request->year
            );
        }

        if ($this->request->month) {

            $query->whereMonth(
                'start_date',
                $this->request->month
            );
        }

        if ($this->request->status) {

            $query->where(
                'status',
                $this->request->status
            );
        }
        if ($this->request->project_manager_id) {

            $query->where(
                'project_manager_id',
                $this->request->project_manager_id
            );
        }

        if ($this->request->team_head_id) {

            $query->where(
                'team_head_id',
                $this->request->team_head_id
            );
        }

        return $query->get()->map(function ($project) {

            $members = [];

            foreach (($project->team_members ?? []) as $employeeId => $role) {

                $employee = Employee::with('designation')->find($employeeId);

                if ($employee) {

                    // Handle role if it is an array
                    if (is_array($role)) {
                        $role = $role['role'] ?? implode(', ', $role);
                    }

                    $members[] =
                        $employee->name .
                        ' - ' .
                        ($employee->designation->name ?? '-') .
                        ' (' . $role . ')';
                }
            }

            return [

                $project->project_name,

                date(
                    'd-m-Y',
                    strtotime($project->start_date)
                ),

                date(
                    'd-m-Y',
                    strtotime($project->end_date)
                ),

                $project->status,
                $project->projectManager->name,
                $project->teamHead->name,

                count(
                    $project->team_members ?? []
                ),

                implode("\r\n", $members),

                $project->description

            ];
        });
    }
}