<div class="content-wrapper p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-1"> Project Expense Tracking</h3>
        </div>
        <div>
            <button type="button"
            class="btn btn-success"
            id="exportBtn">
                <i class="bi bi-file-earmark-excel me-1"></i>
                Export
            </button>
        </div>
    </div>
    <div class="mb-4">
        <div class="row g-3">
            <div class="col-md-2">
                <label class="form-label">
                    From Date
                </label>
                <input type="date" name="from_date" id="from_date" class="form-control">
            </div>
            <div class="col-md-2">
                <label class="form-label">
                    To Date
                </label>
                <input type="date" name="to_date" id="to_date" class="form-control">
            </div>

            {{-- Project Manager --}}
            <div class="col-md-2">

                <label class="form-label">
                    Project Manager
                </label>

                <select class="form-select" id="project_manager_id">

                    <option value="">
                        Select Project Manager
                    </option>

                    @foreach($projectmanagers as $employee)

                        <option value="{{ $employee->id }}">
                            {{ $employee->name }}
                        </option>

                    @endforeach

                </select>

            </div>

            {{-- Team Head --}}
            <div class="col-md-2">

                <label class="form-label">
                    Team Head
                </label>

                <select class="form-select" id="team_head_id">

                    <option value="">
                        Select Team Head
                    </option>

                    @foreach($teamheads as $employee)

                        <option value="{{ $employee->id }}">
                            {{ $employee->name }}
                        </option>

                    @endforeach

                </select>

            </div>

            <div class="col-md-2">

                <label class="form-label">
                    Status
                </label>

                <select
                    class="form-select"
                    id="status">

                    <option value="">
                        Select Status
                    </option>

                     <option value="Active">Active</option>

                     <option value="Cancelled">Cancelled</option>
                     
                     <option value="Completed">Completed</option>
                     <option value="Hold">Hold</option>

                </select>

            </div>

            <div class="col-md-2 d-flex align-items-end">
                <button
                    class="btn btn-primary w-100"
                    id="searchBtn">
                    Search
                </button>
            </div>
        </div>
    </div>

    <table id="projectTable" class="table table-striped table-hover align-middle w-100 data-table">
        <thead>
            <tr>
                <th>Sl No.</th>
                <th>Project</th>
                <th>Manager</th>
                <th>Team Head</th>
                <th>Members</th>
                <th>Estimated Hour</th>
                <th>Total Hour</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
    </table>
</div>

<div class="modal fade"
     id="viewProjectModal"
     tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="fw-bold mb-0">
                    Project Name:
                    <span id="projectName"></span>
                </h4>
                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal">
                </button>
            </div>

            <div class="modal-body">
                <div class="row mb-4">
                    <div class="col-md-6 mt-2">
                        <small class="text-muted">
                            From Date
                        </small>
                        <div
                            class="fw-semibold"
                            id="projectFromDate">
                        </div>
                    </div>
                    <div class="col-md-6 mt-2">
                        <small class="text-muted">
                            To Date
                        </small>
                        <div
                            class="fw-semibold"
                            id="projectToDate">
                        </div>
                    </div>
                    <div class="col-md-6 mt-2">
                        <small class="text-muted">
                            Project Manager
                        </small>
                        <div id="projectManager" class="fw-semibold"></div>
                    </div>
                    <div class="col-md-6 mt-2">
                        <small class="text-muted">
                            Team Lead
                        </small>
                        <div id="teamLead" class="fw-semibold"></div>
                    </div>
                    <div class="col-md-6 mt-2">
                        <small class="text-muted">
                            Start Date
                        </small>
                        <div
                            class="fw-semibold"
                            id="projectStartDate">
                        </div>
                    </div>
                    <div class="col-md-6 mt-2">
                        <small class="text-muted">
                            End Date
                        </small>
                        <div
                            class="fw-semibold"
                            id="projectEndDate">
                        </div>
                    </div>
                    <div class="col-md-6 mt-2">
                        <small class="text-muted">
                            Estimated Hours
                        </small>
                        <div id="estimatedHours" class="fw-semibold"></div>
                    </div>       
                    <div class="col-md-6 mt-2">
                        <small class="text-muted">
                            Total Hour Worked
                        </small>
                        <div
                            class="fw-semibold"
                            id="projectTotalHrWorked">
                        </div>
                    </div>
                    <div class="col-md-6 mt-2">
                        <small class="text-muted">
                            Status
                        </small>
                        <div
                            class="fw-semibold"
                            id="projectStatus">
                        </div>
                    </div>
                     <div class="col-md-6 mt-2">
                        <small class="text-muted">
                            Description
                        </small>
                        <div
                            class="fw-semibold"
                            id="projectTechnology">
                        </div>
                    </div>
                    
                    
                </div>

                
                <hr>
                <h5 class="mb-3">
                    Project Members
                </h5>
                <table class="table table-striped table-hover align-middle w-100 data-table">
                    <thead>
                        <tr>
                            <th>Sl No.</th>
                            <th>Employee ID / Name</th>
                            <th>Department</th>
                            <th>Role</th>
                            <th>Hours Worked</th>
                            <th>Cost Per Hour</th>
                            <th>Total Cost</th>
                        </tr>
                    </thead>
                    <tbody id="projectMembersBody">
                    </tbody>
                     <tfoot>
                        <tr>
                            <th colspan="4" class="text-end">
                                Grand Total
                            </th>
                            <th id="projectTotalHours">
                                0
                            </th>
                            <th></th>
                            <th id="projectGrandTotal">
                                0
                            </th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>


<script>

var table = $('#projectTable').DataTable({

    processing: true,
    serverSide: true,

    ajax: {
        url: "{{ route('project-costs.list') }}",

        data: function (d) {

            d.from_date = $('#from_date').val();
            d.to_date = $('#to_date').val();

            d.project_manager_id =
                $('#project_manager_id').val();

            d.team_head_id =
                $('#team_head_id').val();

            d.status =
                $('#status').val();
        }
    },

    columns: [

        {
            data: 'DT_RowIndex',
            name: 'DT_RowIndex',
            searchable: false,
            orderable: false
        },

        {
            data: 'project_name',
            name: 'project_name'
        },

        {
            data: 'project_manager',
            name: 'projectManager.name',
            orderable: false
        },

        {
            data: 'team_head',
            name: 'teamHead.name',
            orderable: false
        },

        {
            data: 'members_count',
            name: 'members_count',
            searchable: false,
            orderable: false
        },

        {
            data: 'estimated_hours',
            name: 'estimated_hours'
        },

        {
            data: 'total_hours',
            name: 'total_hours'
        },

        {
            data: 'status',
            name: 'status'
        },

        {
            data: 'action',
            name: 'action',
            searchable: false,
            orderable: false
        }

    ],

    order: [
        [0, 'desc']
    ],

    pageLength: 10,

    responsive: true
});

$('#searchBtn').click(function(){

    table.ajax.reload();

});

$('#addProjectBtn').click(function(){

    $('#projectForm')[0].reset();
    $('#projectModalTitle').text('Add Project');
    $('#project_id').val('');

    $('#memberTableBody').html('');
    $('#moduleTableBody').html('');

    $('#module_index').val(1);

    $('#projectModal').modal('show');

});

$('#addMemberRow').click(function(){

    let row = `
        <tr>

            <td>

                <select
                    name="employee_id[]"
                    class="form-select">

                    <option value="">
                        Select Employee
                    </option>

                    @foreach($employees as $employee)

                        <option value="{{ $employee->id }}">
                            {{ $employee->name }}
                        </option>

                    @endforeach

                </select>

            </td>

            <td>

                <input
                    type="text"
                    name="role[]"
                    class="form-control">

            </td>
             <td>
                <select name="member_type[]"
                        class="form-select">
                    <option value="billable">
                        Billable
                    </option>
                    <option value="non-billable">
                        Non Billable
                    </option>
                </select>
            </td>
            <td>

                <button
                    type="button"
                    class="btn btn-danger removeRow">

                    X

                </button>

            </td>

        </tr>
    `;

    $('#memberTableBody').append(row);

});

$(document).on(
    'click',
    '.removeRow',
    function(){

        $(this)
            .closest('tr')
            .remove();

    }
);

$('#saveProjectBtn').click(function(){

    $.ajax({

        url:"{{ route('project.store') }}",

        type:"POST",

        data:$('#projectForm').serialize(),

        success:function(res){

            if(res.status){

                $('#projectModal')
                    .modal('hide');

                 table.ajax.reload();

                Swal.fire(
                    'Success',
                    res.message,
                    'success'
                );

            }

        },

        error: function(xhr){

            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: xhr.responseJSON?.message || 'Something went wrong'
            });

        }

    });

});

$(document).on(
    'click',
    '.viewBtn',
    function(){

        let id =
            $(this).data('id');
        // Get filter dates
        let from_date = $('#from_date').val();
        let to_date   = $('#to_date').val();

        $.get(
            "{{ url('project-view') }}/"+id,
            {
                from_date: from_date,
                to_date: to_date
            },
            function(res){
                
               

                // If From Date is empty, use project start date
                if (!from_date) {
                    from_date = res.project.start_date
                        ? res.project.start_date.substring(0, 10)
                        : '';
                }

                // If To Date is empty, use today's date
                if (!to_date) {
                    let today = new Date();

                    to_date =
                        today.getFullYear() + '-' +
                        String(today.getMonth() + 1).padStart(2, '0') + '-' +
                        String(today.getDate()).padStart(2, '0');
                }

                $('#teamLead')
                    .text(res.team_head);
                $('#projectManager')
                    .text(res.project_manager);

                $('#estimatedHours')
                    .text(res.project.estimated_hours);
                $('#projectName')
                    .text(
                        res.project.project_name
                    );
                $('#projectFromDate')
                    .text(
                        formatDate(
                            from_date
                        )
                    );
                $('#projectToDate')
                    .text(
                        formatDate(
                            to_date
                        )
                    );

                $('#projectStartDate')
                    .text(
                        formatDate(
                            res.project.start_date
                        )
                    );
                $('#projectTotalHrWorked')
                    .text(
                       
                            res.worker_hr
                       
                    );

                $('#projectEndDate')
                    .text(
                        formatDate(
                            res.project.end_date
                        )
                    );

                $('#projectStatus')
                    .text(
                        res.project.status
                    );

               
                $('#projectTechnology')
                    .text(
                        res.project.description ?? '-'
                    );

                let html = '';

                let totalHours = 0;

                $.each(
                    res.members,
                    function(index, row) {

                        let hoursWorked = parseFloat(row.hoursworked) || 0;
                        let costPerHour = parseFloat(row.costperhour) || 0;
                        let totalCost   = parseFloat(row.totalcost) || 0;

                        totalHours += hoursWorked;

                        html += `
                            <tr>

                                <td>
                                    ${row.slno}
                                </td>

                                <td>
                                    ${row.employee_id} / ${row.employee_name}
                                </td>

                                <td>
                                    ${row.department}
                                </td>

                                <td>
                                    ${row.role}
                                </td>

                                <td>
                                    ${hoursWorked} Hrs
                                </td>

                                <td>
                                    ${costPerHour.toFixed(2)}
                                </td>

                                <td>
                                    ${totalCost.toFixed(2)}
                                </td>

                            </tr>
                        `;
                    }
                );

                $('#projectMembersBody').html(html);

                // Total hours
                $('#projectTotalHours').text(
                    totalHours + ' Hrs'
                );

                // Grand total
                $('#projectGrandTotal').text(
                    (parseFloat(res.totalcost) || 0).toFixed(2)
                );

                let moduleHtml = '';

                $.each(
                    res.modules,
                    function(index,row){

                        moduleHtml += `
                            <tr>
                                <td>${row.slno}</td>
                                <td>${row.module_name}</td>
                            </tr>
                        `;
                    }
                );

                $('#projectModulesBody').html(moduleHtml);

                new bootstrap.Modal(
                    document.getElementById(
                        'viewProjectModal'
                    )
                ).show();

            }
        );

    }
);
$(document).on(
    'click',
    '.deleteBtn',
    function(){

        let id = $(this).data('id');

        Swal.fire({

            title: 'Delete Project?',

            text: 'This project will be moved to trash.',

            icon: 'warning',

            showCancelButton: true,

            confirmButtonColor: '#d33',

            cancelButtonColor: '#3085d6',

            confirmButtonText: 'Yes, Delete'

        }).then((result) => {

            if(result.isConfirmed){

                $.ajax({

                    url: "{{ url('project/delete') }}/" + id,

                    type: "DELETE",

                    data: {

                        _token: "{{ csrf_token() }}"
                    },

                    success: function(res){

                        if(res.status){

                            table.ajax.reload();

                            Swal.fire(
                                'Deleted!',
                                res.message,
                                'success'
                            );
                        }
                    }
                });
            }
        });
    }
);
function formatDate(dateString)
{
    let date = new Date(dateString);

    return String(
        date.getDate()
    ).padStart(2,'0')
    + '-'
    + String(
        date.getMonth()+1
    ).padStart(2,'0')
    + '-'
    + date.getFullYear();
}

$(document).on(
    'click',
    '.editBtn',
    function(){

        let id = $(this).data('id');

        $.get(
            "{{ url('project-edit') }}/"+id,
            function(res){

                $('#project_id')
                    .val(res.id);
                
                $('input[name="project_name"]')
                    .val(res.project_name);

                $('select[name="project_manager_id"]')
                    .val(res.project_manager_id);

                $('select[name="team_head_id"]')
                    .val(res.team_head_id);

                $('input[name="estimated_hours"]')
                    .val(res.estimated_hours);

                $('input[name="start_date"]').val(
                    res.start_date.split('T')[0]
                );

                $('input[name="end_date"]').val(
                    res.end_date.split('T')[0]
                );
                $('select[name="status"]')
                    .val(res.status);

                $('textarea[name="description"]')
                    .val(res.description);

                $('#memberTableBody').html('');
                $('#projectModalTitle').text('Edit Project');
                $.each(res.team_members, function(employeeId, member){

                    let row = `
                        <tr>

                            <td>

                                <select
                                    name="employee_id[]"
                                    class="form-select">

                                    @foreach($employees as $employee)

                                        <option
                                            value="{{ $employee->id }}"
                                            ${employeeId == "{{ $employee->id }}" ? 'selected' : ''}>

                                            {{ $employee->name }}

                                        </option>

                                    @endforeach

                                </select>

                            </td>

                            <td>

                                <input
                                    type="text"
                                    class="form-control"
                                    name="role[]"
                                    value="${member.role}">

                            </td>

                            <td>

                                <select
                                    name="member_type[]"
                                    class="form-select">

                                    <option value="billable"
                                        ${member.type == 'billable' ? 'selected' : ''}>
                                        Billable
                                    </option>

                                    <option value="non-billable"
                                        ${member.type == 'non-billable' ? 'selected' : ''}>
                                        Non Billable
                                    </option>

                                </select>

                            </td>

                            <td>

                                <button
                                    type="button"
                                    class="btn btn-danger removeRow">
                                    X
                                </button>

                            </td>

                        </tr>
                    `;

                    $('#memberTableBody').append(row);

                });
                $('#moduleTableBody').html('');

                $('#moduleTableBody').html('');

                let highestIndex = 0;

                if (res.project_modules) {

                    $.each(res.project_modules, function(moduleId, moduleName) {

                        moduleId = parseInt(moduleId);

                        if (!isNaN(moduleId)) {
                            highestIndex = Math.max(highestIndex, moduleId);
                        }

                        $('#moduleTableBody').append(`
                            <tr>
                                <td>
                                    <input
                                        type="text"
                                        name="project_modules[${moduleId}]"
                                        class="form-control"
                                        value="${moduleName}">
                                </td>
                                <td>
                                    <button
                                        type="button"
                                        class="btn btn-danger removeRow">
                                        X
                                    </button>
                                </td>
                            </tr>
                        `);
                    });
                }


                $('#module_index').val(
                    parseInt(res.last_module_index) + 1
                );

                $('#projectModal').modal('show');

            }
        );

    }
);

$('#exportBtn').click(function(){

    let from_date = $('#from_date').val();

    let to_date = $('#to_date').val();

    let status = $('#status').val();
    let project_manager_id = $('#project_manager_id').val();
    let team_head_id = $('#team_head_id').val();

    let url =
        "{{ route('project-costs.export') }}" +
        '?from_date=' + from_date +
        '&to_date=' + to_date +
        '&project_manager_id=' + project_manager_id +
        '&team_head_id=' + team_head_id +
        '&status=' + status;

    window.location.href = url;

});
</script>
