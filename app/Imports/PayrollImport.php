<?php

namespace App\Imports;

use App\Models\Payroll;
use App\Models\Employee;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;

class PayrollImport implements
    ToCollection,
    WithHeadingRow,
    WithCalculatedFormulas
{
    public $inserted = 0;
    public $updated = 0;

    protected $year;
    protected $month;

    public function __construct($year, $month)
    {
        $this->year = $year;
        $this->month = $month;
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {

            if (empty($row['employee_id'])) {
                continue;
            }
           

            $employee = Employee::where("emp_id",$row['employee_id'])->first();
            if (empty($employee)) {
                continue;
            }
            $employeeId=$employee->id;
            $data = [

                'employee_name'          => $row['employee_name'] ?? null,
                'team'                   => $row['team'] ?? null,
                'billing_unit'           => $row['billing_unit'] ?? null,
                'gender'                 => $row['gender'] ?? null,

                'net_payment'            => $this->decimalValue($row['net_payment'] ?? 0),
                'basic'                  => $this->decimalValue($row['basic'] ?? 0),
                'other_allowance'        => $this->decimalValue($row['other_allowance'] ?? 0),
                'performance_bonus'      => $this->decimalValue($row['performance_bonus'] ?? 0),
                'project_allowance'      => $this->decimalValue($row['project_allowance'] ?? 0),
                'special_allowance'      => $this->decimalValue($row['special_allowance'] ?? 0),
                'total_earnings'         => $this->decimalValue($row['total_earnings'] ?? 0),

                'professional_tax'       => $this->decimalValue($row['professional_tax'] ?? 0),
                'pf'                     => $this->decimalValue($row['pf'] ?? 0),
                'income_tax'             => $this->decimalValue($row['income_tax'] ?? 0),
                'lwf'                    => $this->decimalValue($row['lwf'] ?? 0),
                'salary_deductions'      => $this->decimalValue($row['salary_deductions'] ?? 0),
                'esi'                    => $this->decimalValue($row['esi'] ?? 0),
                'total_deduction'        => $this->decimalValue($row['total_deduction'] ?? 0),

                'net_salary'             => $this->decimalValue($row['net_salary'] ?? 0),

                'days_in_month'          => (int) ($row['days_in_month'] ?? 0),
                'present_days'           => (int) ($row['present_days'] ?? 0),

                'daily_rate'             => $this->decimalValue($row['daily_rate'] ?? 0),

                'advance'                => $this->decimalValue($row['advance'] ?? 0),
                'recovery'               => $this->decimalValue($row['recovery'] ?? 0),
                'balance'                => $this->decimalValue($row['balance'] ?? 0),

                'project_bonus_days'     => $this->decimalValue($row['project_bonus_days'] ?? 0),
                'project_days_available' => $this->decimalValue($row['project_days_available'] ?? 0),

                'wfh'                    => (int) ($row['wfh'] ?? 0),

                'per_day_deduction'      => $this->decimalValue($row['per_day_deduction'] ?? 0),
                'total_deduction_2'      => $this->decimalValue($row['total_deduction_2'] ?? 0),

                'ifsc_code'              => $row['ifsc_code'] ?? null,
                'bank_account_number'    => $row['bank_account_number'] ?? null,
                'bank'                   => $row['bank'] ?? null,
            ];

            $payroll = Payroll::where('employee_id', $employeeId)
                ->where('year', $this->year)
                ->where('month', $this->month)
                ->first();

            if ($payroll) {

                $payroll->update($data);

                $this->updated++;

            } else {

                Payroll::create(array_merge(
                    [
                        'employee_id' => $employeeId,
                        'year'        => $this->year,
                        'month'       => $this->month,
                    ],
                    $data
                ));

                $this->inserted++;
            }
        }
    }

    private function decimalValue($value)
    {
        if ($value === null || $value === '') {
            return 0;
        }

        if (is_numeric($value)) {
            return (float) $value;
        }

        $value = str_replace(',', '', $value);

        return is_numeric($value) ? (float) $value : 0;
    }
}