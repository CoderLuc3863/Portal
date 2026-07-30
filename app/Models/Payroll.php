<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payroll extends Model
{
    use HasFactory;

    protected $table = 'payrolls';

    protected $fillable = [

        'employee_id',
        'employee_name',

        'year',
        'month',

        'team',
        'billing_unit',
        'gender',

        'net_payment',
        'basic',
        'other_allowance',

        'house_rent',
        'conveyance',
        'medical',
        'cea',
        'telephone',

        'performance_bonus',
        'project_allowance',
        'special_allowance',
        'total_earnings',

        'professional_tax',
        'pf',
        'income_tax',
        'lwf',
        'salary_deductions',
        'esi',
        'total_deduction',

        'net_salary',

        'days_in_month',
        'present_days',

        'daily_rate',

        'advance',
        'recovery',
        'balance',

        'project_bonus_days',
        'project_days_available',

        'wfh',

        'per_day_deduction',
        'total_deduction_2',

        'ifsc_code',
        'bank_account_number',
        'bank',
    ];

    protected $casts = [
        'year' => 'integer',
        'month' => 'integer',

        'net_payment' => 'decimal:2',
        'basic' => 'decimal:2',
        'other_allowance' => 'decimal:2',
        'performance_bonus' => 'decimal:2',
        'project_allowance' => 'decimal:2',
        'special_allowance' => 'decimal:2',
        'total_earnings' => 'decimal:2',
        'house_rent' => 'decimal:2',
        'conveyance' => 'decimal:2',
        'medical' => 'decimal:2',
        'cea' => 'decimal:2',
        'telephone' => 'decimal:2',

        'professional_tax' => 'decimal:2',
        'pf' => 'decimal:2',
        'income_tax' => 'decimal:2',
        'lwf' => 'decimal:2',
        'salary_deductions' => 'decimal:2',
        'esi' => 'decimal:2',
        'total_deduction' => 'decimal:2',

        'net_salary' => 'decimal:2',

        'daily_rate' => 'decimal:2',

        'advance' => 'decimal:2',
        'recovery' => 'decimal:2',
        'balance' => 'decimal:2',

        'project_bonus_days' => 'decimal:2',
        'project_days_available' => 'decimal:2',

        'per_day_deduction' => 'decimal:2',
        'total_deduction_2' => 'decimal:2',
    ];

    /**
     * Employee Relationship
     */
    // public function employee()
    // {
    //     return $this->belongsTo(Employee::class, 'employee_id', 'employee_id');
    // }

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'id');
    }
    /**
     * Scope Filter By Year
     */
    public function scopeYear($query, $year)
    {
        return $query->when($year, function ($q) use ($year) {
            $q->where('year', $year);
        });
    }

    /**
     * Scope Filter By Month
     */
    public function scopeMonth($query, $month)
    {
        return $query->when($month, function ($q) use ($month) {
            $q->where('month', $month);
        });
    }
}