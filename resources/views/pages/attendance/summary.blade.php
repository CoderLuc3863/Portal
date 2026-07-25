<div class="content-wrapper p-3">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-1">Attendance Summary</h3>
        </div>
        <div class="d-flex justify-content-end mb-3">
            @if(in_array(Auth::user()->department_id, [1, 2]))
                <button class="btn btn-success"
                        id="exportBtn">
                    <i class="fas fa-file-excel"></i>
                    Export
                </button>
            @endif 
            </div>
    </div>

    <div class="mb-3">
       

            <div class="row">

                {{-- <div class="col-md-2">
                    <label>Year</label>
                    <select class="form-select" id="year">
                        <option value="">Select Year</option>

                        @for($i=date('Y');$i>=2020;$i--)
                            <option value="{{ $i }}">
                                {{ $i }}
                            </option>
                        @endfor

                    </select>
                </div>

                <div class="col-md-2">
                    <label>Month</label>
                    <select class="form-select" id="month">
                        <option value="">Select Month</option>

                        @foreach(range(1,12) as $month)
                            <option value="{{ $month }}">
                                {{ date('F', mktime(0,0,0,$month,1)) }}
                            </option>
                        @endforeach

                    </select>
                </div> --}}

                <div class="col-md-2">
                    <label>Date</label>
                    <input type="date"
                           class="form-control"
                           id="date">
                </div>

                <div class="col-md-2 d-flex align-items-end">
                    <button class="btn btn-primary w-100"
                            id="searchBtn">
                        Search
                    </button>
                </div>

            </div>

        
    </div>

            <div class="table-responsive">

                <table class="table table-striped table-hover align-middle w-100 data-table"
                       id="attendanceSummaryTable">

                    <thead>
                        <tr>
                            <th>Sl No</th>
                            <th>Date</th>
                            <th>Employee Name</th>
                            <th>Employee ID</th>
                            <th>Department</th>
                            <th>First In Time</th>
                            <th>Last Out Time</th>
                            <th>Total In Time</th>
                            <th>Total Out Time</th>
                            <th>Action</th>
                        </tr>
                    </thead>

                </table>

            </div>

        

</div>

<div class="modal fade" id="punchDetailModal">

    <div class="modal-dialog modal-xl">

        <div class="modal-content">

            <div class="modal-header">

                <h4 class="modal-title fw-bold">
                    Punch In/Out Details
                </h4>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal">
                </button>

            </div>

            <div class="modal-body">

                <div class="row mb-4">

                    <div class="col-md-3">
                        <small>Employee Name</small>
                        <h6 id="empName"></h6>
                    </div>

                    <div class="col-md-3">
                        <small>Employee ID</small>
                        <h6 id="empId"></h6>
                    </div>

                    <div class="col-md-3">
                        <small>Department</small>
                        <h6 id="department"></h6>
                    </div>

                </div>

                <div class="row mb-4">

                    <div class="col-md-3">
                        <small>Date</small>
                        <h6 id="attendanceDate"></h6>
                    </div>

                    <div class="col-md-3">
                        <small>Total Working Hour</small>
                        <h6 id="totworkingHours"></h6>
                    </div>

                    <div class="col-md-3">
                        <small>Total Break Hour</small>
                        <h6 id="totbreakHours"></h6>
                    </div>

                </div>

                <h4 class="fw-bold mb-3">
                    Punch Details
                </h4>

                <table class="table table-bordered">

                    <thead>
                        <tr>
                            <th>Sl No.</th>
                            <th>Punch Type</th>
                            <th>Time</th>
                        </tr>
                    </thead>

                    <tbody id="punchDetailBody">

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>
<script>
$(document).on(
    'click',
    '.viewPunchBtn',
    function(){

        let employeeId = $(this).data('id');
        let date = $(this).data('date');

        $.get(
            "{{ url('attendance/punch-details') }}/" +
            employeeId +
            "/" +
            date,
            function(res){

                $('#empName').text(
                    res.employee.name
                );

                $('#empId').text(
                    res.employee.emp_id
                );

                $('#department').text(
                    res.employee.department?.name ?? '-'
                );

                $('#attendanceDate').text(
                    res.date
                );

                $('#totworkingHours').text(
                    res.working_hours + ' hrs'
                );

                $('#totbreakHours').text(
                    res.break_hours + ' hrs'
                );

                $('#punchDetailBody').html('');

                $.each(
                    res.logs,
                    function(index,row){

                        let badge =
                            row.direction == 'in'
                            ? '<span class="badge bg-success">In</span>'
                            : '<span class="badge bg-danger">Out</span>';

                        $('#punchDetailBody').append(`
                            <tr>
                                <td>${index + 1}</td>
                                <td>${badge}</td>
                                <td>
                                    ${new Date(row.log_date).toLocaleTimeString('en-US', {
                                        hour: 'numeric',
                                        minute: '2-digit',
                                        hour12: true
                                    })}
                                </td>
                            </tr>
                        `);

                    }
                );

                $('#punchDetailModal').modal('show');

            }
        );

    }
);
$(function () {

    let table = $('#attendanceSummaryTable').DataTable({

        processing: true,
        serverSide: true,

        ajax: {
            url: "{{ route('attendance.summary.list') }}",

            data: function (d) {

                // d.year  = $('#year').val();
                // d.month = $('#month').val();
                d.date  = $('#date').val();

            }
        },

        columns: [

            {
                data: 'DT_RowIndex',
                name: 'DT_RowIndex',
                orderable: false,
                searchable: false
            },

            {
                data: 'attendance_date',
                name: 'attendance_date'
            },

            {
                data: 'employee_name',
                name: 'employee_name'
            },

            {
                data: 'emp_id',
                name: 'emp_id'
            },

            {
                data: 'department',
                name: 'department'
            },

            {
                data: 'first_in',
                name: 'first_in'
            },

            {
                data: 'last_out',
                name: 'last_out'
            },

            {
                data: 'total_in_time',
                name: 'total_in_time'
            },

            {
                data: 'total_out_time',
                name: 'total_out_time'
            },

            {
                data: 'action',
                name: 'action',
                orderable: false,
                searchable: false
            }

        ]

    });

    $('#searchBtn').click(function () {

        table.ajax.reload();

    });

    $('#exportBtn').click(function () {

            // let year  = $('#year').val();
            // let month = $('#month').val();
            let date  = $('#date').val();

            let url =
                "{{ route('attendance.summaryExport') }}" +
                "?year=" + year +
                "&month=" + month +
                "&date=" + date;

            window.location.href = url;
        });

});
</script>