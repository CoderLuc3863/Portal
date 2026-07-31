@php
    $departmentId = session('department_id');

if($departmentId == 1 || $departmentId == 2){
    
@endphp
<div class="col-lg-6 g-3">
    <div class="card shadow-sm rounded-4 h-100">
        <div class="card-header d-flex justify-content-between align-items-center border-0">
            <h6 class="mb-0">Task Status Overview</h6>

            {{-- <select class="form-select form-select-sm w-auto">
                <option>June 2025</option>
                <option>May 2025</option>
            </select> --}}
        </div>

        <div class="card-body">
            <div class="row align-items-center">

                <div class="col-md-6">
                    <div style="height:250px;">
                        <canvas id="taskStatusChart"></canvas>
                    </div>
                </div>

                <div class="col-md-6">

                    <div id="taskStatusLegend"></div>

                </div>

            </div>
        </div>
    </div>
</div>
@php
}else {
@endphp
<div class="col-lg-6 g-3">
    <div class="card shadow-sm rounded-4 h-100">

        <div class="card-header d-flex justify-content-between align-items-center border-0">

            <h6 class="mb-0">Attendance Overview</h6>

           <!-- <select class="form-select form-select-sm w-auto" id="attendanceMonth">
                
            </select>-->

        </div>

        <div class="card-body">

            <div class="row align-items-center">

                <div class="col-md-6">
                    <canvas id="attendanceDonutChart" height="220"></canvas>
                </div>

                <div class="col-md-6">

                    <div class="mb-3 d-flex justify-content-between">
                        <span>
                            <span class="badge bg-success">&nbsp;</span>
                            Present
                        </span>

                        <strong id="presentCount">0</strong>
                    </div>

                    <div class="mb-3 d-flex justify-content-between">
                        <span>
                            <span class="badge bg-warning">&nbsp;</span>
                            On Leave
                        </span>

                        <strong id="leaveCount">0</strong>
                    </div>

                    <div class="mb-3 d-flex justify-content-between">
                        <span>
                            <span class="badge bg-primary">&nbsp;</span>
                            Work From Home
                        </span>

                        <strong id="wfhCount">0</strong>
                    </div>

                    <hr>

                    <div class="d-flex justify-content-between">
                        <strong>Total Working Days</strong>
                        <strong id="workingDays">0</strong>
                    </div>

                </div>

            </div>

        </div>

    </div>
</div>

@php
}
@endphp
