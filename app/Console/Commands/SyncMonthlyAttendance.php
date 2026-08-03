<?php

namespace App\Console\Commands;

use App\Models\Employee;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class SyncMonthlyAttendance extends Command
{
    protected $signature = 'attendance:sync-monthly';

    protected $description = 'Sync attendance logs from MSSQL';

    public function handle()
    {
        $month = date('n');
        $year = date('Y');

        $sourceTable = "DeviceLogs_{$month}_{$year}";
        $targetTable = "z_attendance_log_{$month}_{$year}";

        $lastDeviceLogId = DB::table($targetTable)
            ->max('device_log_id');

        $lastDeviceLogId = $lastDeviceLogId ?? 0;
        

        // $logs = DB::connection('essl')
        //     ->table($sourceTable)
        //     ->where('DeviceLogId', '>', $lastDeviceLogId)
        //     ->orderBy('DeviceLogId')
        //     ->get();

        $lastDeviceLogId = DB::table($targetTable)->max('device_log_id');

        $query = DB::connection('essl')
            ->table($sourceTable);

        if ($lastDeviceLogId) {
            $query->where('DeviceLogId', '>', $lastDeviceLogId);
        } else {
            $startDate = now()->startOfMonth()->toDateTimeString();
            $endDate = now()->endOfMonth()->toDateTimeString();

            $query->whereBetween('LogDate', [$startDate, $endDate]);
        }

        $logs = $query->orderBy('DeviceLogId')->get();

        if ($logs->count() == 0) {

            $this->info('No new records found.');

            return;
        }

        foreach ($logs as $log) {

            $employee = Employee::where(
                'emp_id',
                $log->UserId
            )->first();
            if($employee?->id!="")
            {DB::table($targetTable)
                ->insert([
                    'employee_id' => $employee?->id,
                    // 'user_id' => $log->UserId,
                    'device_log_id' => $log->DeviceLogId,
                    'log_date' => $log->LogDate,
                    'direction' => strtolower($log->C1) == 'out'
                        ? 'out'
                        : 'in',
                ]);
            }
        }

        $this->info(
            $logs->count() . ' records synced successfully at '.now()->format('Y-m-d H:i:s')
        );
    }
}