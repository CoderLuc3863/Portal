<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Employee;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class LoginController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        return view('auth.login');
    }

    // Handle login
    public function login(Request $request)
    { 
        // dd("heer");
        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials)) {
            // session is automatically created by Laravel
            $request->session()->regenerate();
            // ✅ You can store extra session data if needed
            session([
                'id' => Auth::user()->id,
                'user_name' => Auth::user()->name,
                'designation' => Auth::user()->designation->name,
                'user_email' => Auth::user()->email,
                'user_photo' => Auth::user()->photo,
                'onboard_status' => Auth::user()->onboard_status,
                'department' => Auth::user()->department,
                'department_id' => Auth::user()->department_id,
                'emp_status' => Auth::user()->status,
                ]);    
                return redirect()->route('dashboard');
            
            // $employees = Employee::where('status', 1)->get();
            // return view('dashboard', compact("employees"));
        }

        return back()->with('error', 'Invalid credentials');
    }

    public function dashboardContent()
    {
        $currentMonth = Carbon::now()->month;
        $currentYear = now()->year;
        $currentDay   = now()->day;
        $employees = Employee::where('status', 1)->get();
        $birthdays = Employee::where('status', 1)
                    ->whereMonth('dob',$currentMonth)
                ->orderByRaw('DAY(dob)')
                ->get();
        $anniversaries = Employee::where('status', 1)
                ->whereMonth('joining_date',$currentMonth)
                ->whereYear('joining_date', '!=', $currentYear)
                 ->whereDay('joining_date', $currentDay)
                ->orderByRaw('DAY(joining_date)')
                ->get();
        
        return view('pages.dashboard-content', compact(
            'employees',
            'birthdays',
            'anniversaries'
            ));
    }

    public function attendanceData()
    {
        $month = date('n');
        $year = date('Y');

        $table = "z_attendance_log_{$month}_{$year}";

        $checkInTime = null;
        $workingSeconds = 0;
        $breakSeconds = 0;
        $lastDirection = null;

        if (DB::getSchemaBuilder()->hasTable($table)) {

            $logs = DB::table($table)
                ->where('employee_id', Auth::user()->id)
                ->whereDate('log_date', today())
                ->orderBy('log_date')
                ->get();

            if ($logs->count()) {

                $firstIn = $logs->firstWhere('direction', 'in');

                if ($firstIn) {
                    $checkInTime = Carbon::parse($firstIn->log_date);
                }

                $lastIn = null;
                $lastOut = null;

                foreach ($logs as $log) {

                    if ($log->direction == 'in') {

                        if ($lastOut) {

                            $breakSeconds += Carbon::parse($lastOut)
                                ->diffInSeconds(Carbon::parse($log->log_date));

                            $lastOut = null;
                        }

                        $lastIn = $log->log_date;
                    }

                    if ($log->direction == 'out') {

                        if ($lastIn) {

                            $workingSeconds += Carbon::parse($lastIn)
                                ->diffInSeconds(Carbon::parse($log->log_date));

                            $lastIn = null;
                        }

                        $lastOut = $log->log_date;
                    }
                }

                if ($lastIn) {

                    $workingSeconds += Carbon::parse($lastIn)
                        ->diffInSeconds(now());

                    $lastDirection = 'in';
                }

                if ($lastOut) {

                    $breakSeconds += Carbon::parse($lastOut)
                        ->diffInSeconds(now());

                    $lastDirection = 'out';
                }
            }
        }

        return response()->json([
            'checkIn'      => $checkInTime ? $checkInTime->format('h:i A') : 'Not Checked In',
            'workingHours' => gmdate('H:i:s', $workingSeconds),
            'breakHours'   => gmdate('H:i:s', $breakSeconds),
            'lastDirection'=> $lastDirection
        ]);
    }
    // Dashboard
    public function dashboard()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        $currentMonth = Carbon::now()->month;
        $currentYear = now()->year;
        $currentDay   = now()->day;
        $employees = Employee::where('status', 1)->get(); 
        $birthdays = Employee::where('status', 1)
                ->whereMonth('dob',$currentMonth)
                ->orderByRaw('DAY(dob)')
                ->get();
        $anniversaries = Employee::where('status', 1)
                ->whereMonth('joining_date',$currentMonth)
                ->whereYear('joining_date', '!=', $currentYear)
                 ->whereDay('joining_date', $currentDay)
                ->orderByRaw('DAY(joining_date)')
                ->get();
        
        return view('dashboard', compact(
            'employees',
            'birthdays',
            'anniversaries'
            ));
    }

    // Logout
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}