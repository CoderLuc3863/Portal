<div class="content-wrapper p-4">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h3 class="fw-bold mb-1"> Project Management</h3>
        </div>
        <div>
            <button type="button"
            class="btn btn-success"
            id="exportBtn">
                <i class="bi bi-file-earmark-excel me-1"></i>
                Export
            </button>
            <button type="button"
            class="btn btn-primary"
            id="addProjectBtn">
            + Add Project

            </button> 
        </div>

    </div>
    <div class="mb-4">

        <div class="row g-3">

            <div class="col-md-2">

                <label class="form-label">
                    Year
                </label>

                <select
                    class="form-select"
                    id="year">

                    <option value="">
                        Select Year
                    </option>

                    @for($year = date('Y')+1; $year >= 2020; $year--)

                        <option value="{{ $year }}">
                            {{ $year }}
                        </option>

                    @endfor

                </select>

            </div>

            <div class="col-md-2">

                <label class="form-label">
                    Month
                </label>

                <select
                    class="form-select"
                    id="month">

                    <option value="">
                        Select Month
                    </option>

                    @for($month=1;$month<=12;$month++)

                        <option value="{{ $month }}">
                            {{ date('F', mktime(0,0,0,$month,1)) }}
                        </option>

                    @endfor

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


            <div class="col-md-2 d-flex align-items-end">

                <button
                    class="btn btn-primary w-100"
                    id="searchBtn">

                    Search

                </button>

            </div>

        </div>
  
    </div>


    <table
        id="projectTable" class="table table-striped table-hover align-middle w-100 data-table">

        <thead>

            <tr>

                <th>Sl No.</th>
                <th>Project</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Manager</th>
                <th>Team Head</th>
                <th>Members</th>
                <th>Status</th>
                <th>Progress</th>
                <th>Actions</th>

            </tr>

        </thead>

    </table>


</div>

{{-- Add Project Modal --}}
<div
    class="modal fade"
    id="projectModal"
    tabindex="-1">

    <div class="modal-dialog modal-lg">

        <div class="modal-content">

            <div class="modal-header">

                <h5 class="modal-title"  id="projectModalTitle">
                    Add Project
                </h5>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal">
                </button>

            </div>

            <div class="modal-body">

                <form id="projectForm">

                    @csrf
                    <input
                        type="hidden"
                        name="id"
                        id="project_id">
                    <div class="row g-3">

                        <div class="col-md-6">
                            <label class="form-label">Project Name</label>
                            <input type="text"
                                name="project_name"
                                class="form-control">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Project Manager</label>

                            <select name="project_manager_id"
                                    class="form-select">

                                <option value="">
                                    Select Project Manager
                                </option>

                                @foreach($employees as $employee)
                                    <option value="{{ $employee->id }}">
                                        {{ $employee->name }}
                                    </option>
                                @endforeach

                            </select>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Start Date</label>

                            <input type="date"
                                name="start_date"
                                class="form-control">
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">End Date</label>

                            <input type="date"
                                name="end_date"
                                class="form-control">
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Estimated Hours</label>

                            <input type="number"
                                name="estimated_hours"
                                class="form-control">
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Team Lead</label>

                            <select name="team_head_id"
                                    class="form-select">

                                <option value="">
                                    Select Team Lead
                                </option>

                                @foreach($employees as $employee)
                                    <option value="{{ $employee->id }}">
                                        {{ $employee->name }}
                                    </option>
                                @endforeach

                            </select>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Status</label>

                            <select name="status" class="form-select">
                                <option value="Active">Active</option>
                                <option value="Cancelled">Cancelled</option>
                                <option value="Completed">Completed</option>
                                <option value="Hold">Hold</option>
                            </select>
                        </div>

                        <div class="col-md-9">
                            <label class="form-label">Description</label>

                            <textarea class="form-control"
                                    name="description"
                                    rows="2"></textarea>
                        </div>

                    </div>

                    <hr>

                    <div class="d-flex justify-content-between mb-3">

                        <h6>Project Modules</h6>
                        
                        <button type="button"
                                class="btn btn-sm btn-primary"
                                id="addModuleRow">

                            + Add Module

                        </button>

                    </div>

                    <table class="table table-bordered">

                        <thead>

                            <tr>

                                <th>Module Name</th>

                                <th width="10%">
                                    Action
                                </th>

                            </tr>

                        </thead>
                        <input type="hidden" id="module_index" value="1">
                        <tbody id="moduleTableBody">

                        </tbody>

                    </table>
                    <hr>

                    <div class="d-flex justify-content-between mb-3">

                        <h6>
                            Team Members
                        </h6>

                        <button
                            type="button"
                            class="btn btn-sm btn-primary"
                            id="addMemberRow">

                            + Add Member

                        </button>

                    </div>

                    <table
                        class="table table-bordered">

                        <thead>

                            <tr>

                                <th width="30%">
                                    Employee
                                </th>

                                <th width="30">
                                    Role
                                </th>
                                <th width="30%">
                                    Type
                                </th>

                                <th width="10%">
                                    Action
                                </th>

                            </tr>

                        </thead>

                        <tbody id="memberTableBody">

                        </tbody>

                    </table>

                </form>

            </div>

            <div class="modal-footer">

                <button
                    type="button"
                    class="btn btn-secondary"
                    data-bs-dismiss="modal">

                    Close

                </button>

                <button
                    type="button"
                    class="btn btn-primary"
                    id="saveProjectBtn">

                    Save Project

                </button>

            </div>

        </div>

    </div>

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

                    <div class="col-md-4 mt-2">
                        <small class="text-muted">
                            Start Date
                        </small>

                        <div
                            class="fw-semibold"
                            id="projectStartDate">
                        </div>
                    </div>

                    <div class="col-md-4 mt-2">
                        <small class="text-muted">
                            End Date
                        </small>

                        <div
                            class="fw-semibold"
                            id="projectEndDate">
                        </div>
                    </div>

                    <div class="col-md-4 mt-2">
                        <small class="text-muted">
                            Status
                        </small>

                        <div
                            class="fw-semibold"
                            id="projectStatus">
                        </div>
                    </div>
                    <div class="col-md-4 mt-2">

                        <small class="text-muted">
                            Project Manager
                        </small>

                        <div id="projectManager" class="fw-semibold"></div>

                    </div>
                    <div class="col-md-4 mt-2">

                        <small class="text-muted">
                            Team Lead
                        </small>

                        <div id="teamLead" class="fw-semibold"></div>

                    </div>
                    <div class="col-md-4 mt-2">

                        <small class="text-muted">
                            Estimated Hours
                        </small>

                        <div id="estimatedHours" class="fw-semibold"></div>

                    </div>

                    

                    
                    <div class="col-md-4 mt-2">
                        <small class="text-muted">
                            Members
                        </small>

                        <div
                            class="fw-semibold"
                            id="projectMembersCount">
                        </div>
                    </div>

                </div>

              

                <div class="col-md-6 mt-2">

                    <small class="text-muted">
                        Technology
                    </small>

                    <div
                        class="fw-semibold"
                        id="projectTechnology">
                    </div>

                </div>


                <hr>
                <h5 class="mb-3">
                    Project Modules
                </h5>

                <table class="table table-striped table-hover align-middle w-100 data-table">

                    <thead>

                        <tr>

                            <th>Sl No.</th>
                            <th>Modules</th>

                        </tr>

                    </thead>

                    <tbody id="projectModulesBody">

                    </tbody>

                </table>

                <hr>
                <h5 class="mb-3">
                    Project Members
                </h5>

                <table class="table table-striped table-hover align-middle w-100 data-table">

                    <thead>

                        <tr>

                            <th>Sl No.</th>
                            <th>Employee ID</th>
                            <th>Employee Name</th>
                            <th>Department</th>
                            <th>Designation</th>
                            <th>Role</th>
                            <th>Type</th>

                        </tr>

                    </thead>

                    <tbody id="projectMembersBody">

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>


<script>
$('#addModuleRow').click(function(){

    let nextIndex = parseInt(
        $('#module_index').val()
    ) || 1;

    $('#moduleTableBody').append(`
        <tr>
            <td>
                <input
                    type="text"
                    name="project_modules[${nextIndex}]"
                    class="form-control"
                    placeholder="Module Name">
            </td>
            <td>
                <button
                    type="button"
                    class="btn btn-danger removeModule">
                    X
                </button>
            </td>
        </tr>
    `);

    $('#module_index').val(nextIndex + 1);
});
var table = $('#projectTable').DataTable({

    processing:true,

    serverSide:true,

    ajax:{
        url:"{{ route('project.list') }}",

        data:function(d){

            d.year = $('#year').val();

            d.month = $('#month').val();

            d.project_manager_id = $('#project_manager_id').val();
            d.team_head_id = $('#team_head_id').val();

        }
    },

    columns:[

        {
            data:'DT_RowIndex',
            searchable:false,
            orderable:false
        },

        {
            data:'project_name'
        },

        {
            data:'start_date'
        },

        {
            data:'end_date'
        },

        {
            data:'project_manager'
        },
        {
            data:'team_head'
        },

        {
            data:'members_count'
        },
        {
            data:'status'
        },

        {
            data:'progress'
        },
        {
            data:'action',
            searchable:false,
            orderable:false
        }

    ]

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

        $.get(
            "{{ url('project-view') }}/"+id,

            function(res){

                $('#projectManager')
                    .text(res.project_manager);

                $('#teamLead')
                    .text(res.team_head);

                $('#estimatedHours')
                    .text(res.project.estimated_hours);
                $('#projectName')
                    .text(
                        res.project.project_name
                    );

                $('#projectStartDate')
                    .text(
                        formatDate(
                            res.project.start_date
                        )
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

                $('#projectMembersCount')
                    .text(
                        res.members.length
                    );

                $('#projectTechnology')
                    .text(
                        res.project.description ?? '-'
                    );

                let html = '';

                $.each(
                    res.members,
                    function(index,row){

                        html += `
                            <tr>

                                <td>
                                    ${row.slno}
                                </td>

                                <td>
                                    ${row.employee_id}
                                </td>

                                <td>
                                    ${row.employee_name}
                                </td>

                                <td>
                                    ${row.department}
                                </td>

                                <td>
                                    ${row.designation}
                                </td>

                                <td>
                                    ${row.role}
                                </td>
                                <td>
                                    ${row.type}
                                </td>

                            </tr>
                        `;
                    }
                );

                $('#projectMembersBody')
                    .html(html);

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

    let year = $('#year').val();

    let month = $('#month').val();

    let status = $('#status').val();
    let project_manager_id = $('#project_manager_id').val();
    let team_head_id = $('#team_head_id').val();

    let url =
        "{{ route('project.export') }}" +
        '?year=' + year +
        '&month=' + month +
        '&project_manager_id=' + project_manager_id +
        '&team_head_id=' + team_head_id +
        '&status=' + status;

    window.location.href = url;

});
</script>
