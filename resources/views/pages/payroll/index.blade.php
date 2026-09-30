<div class="content-wrapper p-3">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h3 class="fw-bold mb-1">Payroll List</h3>
        </div>

        <div>
            <a href="{{ route('payroll.template.download') }}"
            class="btn btn-success">
                <i class="bi bi-file-earmark-excel me-1"></i>
                Payroll Excel
            </a>
            
            <button
                class="btn btn-primary"
                data-bs-toggle="modal"
                data-bs-target="#uploadPayrollModal">

                <i class="bi bi-plus-lg me-1"></i>
                 Upload Payroll
            </button>
        </div>

    </div>

    <div class="pb-3 mb-3 border-bottom">
        <div class="row g-3 align-items-end">

            <div class="col-md-3">
                <select
                    id="filter_year"
                    class="form-select">

                    <option value="">select</option>

                    @for($i=date('Y')-2;$i<=date('Y')+2;$i++)
                        <option
                            value="{{ $i }}"
                            {{ $i == date('Y') ? 'selected' : '' }}>
                            {{ $i }}
                        </option>
                    @endfor

                </select>
            </div>

            <div class="col-md-3">

                <select
                    id="filter_month"
                    class="form-select">

                    <option value="">All Months</option>

                    @for($i=1;$i<=12;$i++)
                        <option
                            value="{{ $i }}"
                            {{ $i == date('n') ? 'selected' : '' }}>
                            {{ date('F', mktime(0,0,0,$i,1)) }}
                        </option>
                    @endfor

                </select>

            </div>
        </div>
    </div>

    <table
        id="payrollTable"
        class="table table-striped table-hover align-middle w-100 data-table">

        <thead>

            <tr>

                <th>Sl No</th>
                <th>Employee ID</th>
                <th>Employee Name</th>
                <th>Year</th>
                <th>Month</th>
                <th>Net Salary</th>
                <th width="120">Action</th>

            </tr>

        </thead>

    </table>

</div>


<div class="modal fade" id="uploadPayrollModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <form id="payrollImportForm"
                  enctype="multipart/form-data">

                @csrf

                <div class="modal-header">
                    <h5 class="modal-title">
                        Import Payroll
                    </h5>

                    <button type="button"
                            class="btn-close"
                            data-bs-dismiss="modal">
                    </button>
                </div>

                <div class="modal-body">

                    <div class="row">

                        <div class="col-md-4">
                            <label>Year</label>

                            <select name="year"
                                    id="import_year"
                                    class="form-select"
                                    required>

                                @for($i=date('Y')-2;$i<=date('Y')+2;$i++)
                                    <option value="{{ $i }}">
                                        {{ $i }}
                                    </option>
                                @endfor

                            </select>
                        </div>

                        <div class="col-md-4">
                            <label>Month</label>

                            <select name="month"
                                    id="import_month"
                                    class="form-select"
                                    required>

                                @for($i=1;$i<=12;$i++)
                                    <option value="{{ $i }}">
                                        {{ date('F',mktime(0,0,0,$i,1)) }}
                                    </option>
                                @endfor

                            </select>
                        </div>

                        <div class="col-md-4">
                            <label>Payroll Excel</label>

                            <input type="file"
                                   name="file"
                                   class="form-control"
                                   accept=".xlsx,.xls"
                                   required>
                        </div>

                    </div>

                    <div id="importErrors"
                         class="mt-3"
                         style="display:none;">
                    </div>

                </div>

                <div class="modal-footer">

                    <button type="button"
                            class="btn btn-secondary"
                            data-bs-dismiss="modal">
                        Close
                    </button>

                    <button type="submit"
                            class="btn btn-primary">
                        Import Payroll
                    </button>

                </div>

            </form>

        </div>
    </div>
</div>

<div class="modal fade" id="viewPayrollModal">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Payroll Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <div class="row" id="viewPayrollBody"></div>
            </div>

        </div>
    </div>
</div>

<div class="modal fade" id="editPayrollModal">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">

            <form id="editPayrollForm">

                @csrf

                <input type="hidden" id="edit_id">

                <div class="modal-header">
                    <h5 class="modal-title">Edit Payroll</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <div class="row" id="editPayrollBody"></div>

                </div>

                <div class="modal-footer">

                    <button type="submit"
                            class="btn btn-primary">
                        Update
                    </button>

                </div>

            </form>

        </div>
    </div>
</div>
<script>
    var payrollFields = [

        'employee_id',
        'employee_name',

        'team',
        // 'department',
        'billing_unit',
        'gender',
        // 'level',

        'net_payment',
        'basic',
        'other_allowance',
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

        // 'nft_wib',

        'ifsc_code',
        'bank_account_number',
        'bank'

    ];
    var payrollFieldsOld = [

    'employee_id',
    'employee_name',
    'team',
    'billing_unit',
    'gender',
    // 'net_payment',
    'basic',
    'house_rent',
    'conveyance',
    'medical',
    'cea',
    'telephone',
    'other_allowance',
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
    'bank'

];
$('#uploadPayrollModal').on('show.bs.modal', function () {

    $('#import_year').val('{{ date("Y") }}');
    $('#import_month').val('{{ date("n") }}');

    $('#importErrors').hide().html('');

    $('#payrollImportForm')[0].reset();

    $('#import_year').val('{{ date("Y") }}');
    $('#import_month').val('{{ date("n") }}');

});
$('#payrollImportForm').submit(function(e){

    e.preventDefault();

    let formData = new FormData(this);

    Swal.fire({
        title: 'Uploading...',
        text: 'Please wait',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    $.ajax({

        url: "{{ route('payroll.import') }}",
        type: "POST",
        data: formData,
        processData: false,
        contentType: false,

        success: function(response){

            if(response.status){

                Swal.fire({
                    icon: 'success',
                    title: 'Payroll Imported',
                    text: response.message
                });

                $('#uploadPayrollModal').modal('hide');

                $('#payrollTable')
                    .DataTable()
                    .ajax
                    .reload();

                if(response.failed_rows.length > 0){

                    let html = `
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Row</th>
                                    <th>Employee ID</th>
                                    <th>Reason</th>
                                </tr>
                            </thead>
                            <tbody>
                    `;

                    response.failed_rows.forEach(function(row){

                        html += `
                            <tr>
                                <td>${row.row}</td>
                                <td>${row.employee_id}</td>
                                <td>${row.reason}</td>
                            </tr>
                        `;
                    });

                    html += '</tbody></table>';

                    $('#importErrors')
                        .html(html)
                        .show();
                }
            }
        },

        error:function(xhr){

            Swal.fire({
                icon:'error',
                title:'Import Failed',
                text:xhr.responseJSON.message
            });
        }

    });

});
$(function(){

    var table = $('#payrollTable').DataTable({

        processing: true,
        serverSide: true,

        ajax: {

            url: "{{ route('payroll.list') }}",

            data: function(d){

                d.year  = $('#filter_year').val();
                d.month = $('#filter_month').val();

            }
        },

        columns: [

            {
                data: 'DT_RowIndex',
                name: 'DT_RowIndex',
                orderable:false,
                searchable:false
            },

            {
                data: 'employee_id',
                name: 'employee_id'
            },

            {
                data: 'employee_name',
                name: 'employee_name'
            },

            {
                data: 'year',
                name: 'year'
            },

            {
                data: 'month_name',
                name: 'month'
            },

            {
                data: 'net_salary',
                name: 'net_salary'
            },

            {
                data: 'action',
                name: 'action',
                orderable:false,
                searchable:false
            }

        ]

    });

    $('#filter_year,#filter_month').change(function(){

        table.draw();

    });

});


$(document).on('click','.viewPayroll',function(){

    let id = $(this).data('id');

    $.get('/payroll/view/'+id,function(res){

        let html = '';

        payrollFields.forEach(function(field){

            let label = field
                .replaceAll('_',' ')
                .replace(/\b\w/g, c => c.toUpperCase());

            html += `
                <div class="col-md-4 mb-3">
                    <label class="fw-bold">${label}</label>
                    <input type="text"
                           class="form-control"
                           value="${res.data[field] ?? ''}"
                           readonly>
                </div>
            `;
        });

        $('#viewPayrollBody').html(html);

        $('#viewPayrollModal').modal('show');

    });

});
$(document).on('click','.editPayroll',function(){

    let id = $(this).data('id');

    $.get('/payroll/edit/'+id,function(res){

        $('#edit_id').val(id);

        let html = '';

        payrollFields.forEach(function(field){

            let label = field
                .replaceAll('_',' ')
                .replace(/\b\w/g, c => c.toUpperCase());

            let readonly = '';

            if(field === 'employee_id' ||
               field === 'employee_name')
            {
                readonly = 'readonly';
            }

            html += `
                <div class="col-md-4 mb-3">

                    <label>${label}</label>

                    <input
                        type="text"
                        name="${field}"
                        value="${res.data[field] ?? ''}"
                        class="form-control"
                        ${readonly}>

                </div>
            `;
        });

        $('#editPayrollBody').html(html);

        $('#editPayrollModal').modal('show');

        setTimeout(function () {

            const modal = bootstrap.Modal.getOrCreateInstance(
                document.getElementById('editPayrollModal')
            );

            modal.handleUpdate();

        }, 100);

    });

});
$('#editPayrollForm').submit(function(e){

    e.preventDefault();

    let id = $('#edit_id').val();

    $.ajax({

        url:'/payroll/update/'+id,

        type:'POST',

        data:$(this).serialize(),

        success:function(response){

            Swal.fire({
                icon:'success',
                title:'Success',
                text:response.message
            });

            $('#editPayrollModal').modal('hide');

            $('#payrollTable')
                .DataTable()
                .ajax
                .reload();

        }

    });

});
$(document).on('click', '.deletePayroll', function () {

    let id = $(this).data('id');

    Swal.fire({

        title: 'Delete Payroll?',
        text: 'You will not be able to recover this record.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, Delete'

    }).then((result) => {

        if (result.isConfirmed) {

            $.ajax({

                url: "{{ url('payroll/delete') }}/" + id,
                type: 'DELETE',

                data: {
                    _token: "{{ csrf_token() }}"
                },

                success: function (response) {

                    Swal.fire({
                        icon: 'success',
                        title: 'Deleted',
                        text: response.message
                    });

                    $('#payrollTable').DataTable().ajax.reload();

                }

            });

        }

    });

});
</script>

