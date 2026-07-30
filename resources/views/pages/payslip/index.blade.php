

<div class="container-fluid mt-3">

    {{-- Greeting Section --}}
    <div class="row mb-4">

        <div class="col-lg-8">

            <div class="d-flex align-items-center">

                <div class="me-3">

                    <div
                        class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center"
                        style="width:60px;height:60px;">

                        <i class="bi bi-person-fill fs-3"></i>

                    </div>

                </div>

                <div>

                    <h3 class="fw-bold mb-1">
                        Hi, {{ auth()->user()->name }}
                    </h3>

                    <p class="text-muted mb-0">
                        Welcome back. Here's your payroll overview.
                    </p>

                </div>

            </div>

        </div>

        <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">

            <a  href="javascript:void(0)"
                class="btn btn-primary downloadLatestPDF"
                id="downloadLatestPayslip">

                <i class="bi bi-download me-1"></i>
                Download Latest Payslip

            </a>

        </div>

    </div>

    {{-- Summary Cards --}}
    <div class="row">

        {{-- Latest Net Salary --}}
        <div class="col-xl-3 col-md-6 mb-4">

            <div class="card border-1 shadow-sm rounded-4 h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <div>

                            <small class="text-muted">
                                Latest Net Salary
                            </small>

                            <h4 class="fw-bold mt-2 mb-0"
                                id="latestNetSalary">

                                ₹0.00

                            </h4>

                        </div>

                        <div>

                            <span
                                class="badge bg-success rounded-circle p-3">

                                <i class="bi bi-currency-rupee"></i>

                            </span>

                        </div>

                    </div>

                </div>

            </div>

        </div>

        {{-- Total Payslips --}}
        <div class="col-xl-3 col-md-6 mb-4">

            <div class="card border-1 shadow-sm rounded-4 h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <div>

                            <small class="text-muted">
                                Total Payslips
                            </small>

                            <h4 class="fw-bold mt-2 mb-0"
                                id="totalPayslips">

                                0

                            </h4>

                        </div>

                        <div>

                            <span
                                class="badge bg-primary rounded-circle p-3">

                                <i class="bi bi-file-earmark-text"></i>

                            </span>

                        </div>

                    </div>

                </div>

            </div>

        </div>

        {{-- Employee ID --}}
        <div class="col-xl-3 col-md-6 mb-4">

            <div class="card border-1 shadow-sm rounded-4 h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <div>

                            <small class="text-muted">
                                Employee ID
                            </small>

                            <h4 class="fw-bold mt-2 mb-0">

                                {{ auth()->user()->emp_id }}

                            </h4>

                        </div>

                        <div>

                            <span
                                class="badge bg-warning rounded-circle p-3">

                                <i class="bi bi-person-badge"></i>

                            </span>

                        </div>

                    </div>

                </div>

            </div>

        </div>

        {{-- Latest Payroll Period --}}
        <div class="col-xl-3 col-md-6 mb-4">

            <div class="card border-1 shadow-sm rounded-4 h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <div>

                            <small class="text-muted">
                                Payroll Period
                            </small>

                            <h4 class="fw-bold mt-2 mb-0"
                                id="latestPayrollMonth">

                                -

                            </h4>

                        </div>

                        <div>

                            <span
                                class="badge bg-danger rounded-circle p-3">

                                <i class="bi bi-calendar-event"></i>

                            </span>

                        </div>

                    </div>

                </div>

            </div>

        </div>
    </div>
            {{-- Latest Payslip & Quick Actions --}}
    <div class="row">

        {{-- Latest Payslip --}}
        <div class="col-lg-8 mb-4">

            <div class="card border-1 shadow-sm h-100">

                <div class="card-header bg-white border-1 pt-4 px-4">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <h5 class="fw-bold mb-1">
                                Latest Payslip
                            </h5>

                            <small class="text-muted">
                                Your most recent salary statement
                            </small>

                        </div>

                        <span class="badge bg-success px-3 py-2"
                            id="latestPeriod">

                            April 2026

                        </span>

                    </div>

                </div>

                <div class="card-body px-4">

                    <div class="row">

                        <div class="col-md-8">

                            <div class="row g-3">

                                <div class="col-md-6">

                                    <div class="border rounded-3 p-3">

                                        <small class="text-muted">
                                            Basic Salary
                                        </small>

                                        <h5 class="fw-bold mt-2"
                                            id="basicSalary">

                                            ₹0.00

                                        </h5>

                                    </div>

                                </div>

                                <div class="col-md-6">

                                    <div class="border rounded-3 p-3">

                                        <small class="text-muted">
                                            Total Earnings
                                        </small>

                                        <h5 class="fw-bold text-success mt-2"
                                            id="totalEarnings">

                                            ₹0.00

                                        </h5>

                                    </div>

                                </div>

                                <div class="col-md-6">

                                    <div class="border rounded-3 p-3">

                                        <small class="text-muted">
                                            Total Deductions
                                        </small>

                                        <h5 class="fw-bold text-danger mt-2"
                                            id="totalDeduction">

                                            ₹0.00

                                        </h5>

                                    </div>

                                </div>

                                <div class="col-md-6">

                                    <div class="border rounded-3 p-3">

                                        <small class="text-muted">
                                            Working Days
                                        </small>

                                        <h5 class="fw-bold mt-2"
                                            id="workingDays">

                                            0

                                        </h5>

                                    </div>

                                </div>

                            </div>

                        </div>

                        <div class="col-md-4">

                            <div class="rounded-4 text-white p-2 h-100"
                                style="background:#ff6b35;">

                                <small class="text-white-50">

                                    Net Salary

                                </small>

                                <h4 class="fw-bold mt-3"
                                    id="netSalary">

                                    ₹0.00

                                </h4>

                                <hr class="border-light">

                                <div class="d-grid gap-2 mt-4">

                                    <button
                                        class="btn btn-light small p-1 viewPayslip"
                                        id="viewLatestPayslip" data-id="0">

                                        <i class="bi bi-eye"></i>

                                        View Payslip

                                    </button>

                                     <a  href="javascript:void(0)"
                                        class="btn btn-dark small p-1 downloadLatestPDF"
                                        id="downloadLatestPDF" data-id="0">

                                        <i class="bi bi-download"></i>

                                        Download PDF

                                    </a>

                                </div>

                            </div>

                        </div>

                    </div>

                    <hr>

                    <div class="row text-center">

                        <div class="col-md-4">

                            <small class="text-muted">

                                Bank

                            </small>

                            <h6 id="bankName">

                                SBI

                            </h6>

                        </div>

                        <div class="col-md-4">

                            <small class="text-muted">

                                Account No

                            </small>

                            <h6 id="accountNumber">

                                XXXXXXXX1234

                            </h6>

                        </div>

                        <div class="col-md-4">

                            <small class="text-muted">

                                IFSC

                            </small>

                            <h6 id="ifscCode">

                                SBIN000000

                            </h6>

                        </div>

                    </div>

                </div>

            </div>

        </div>

        {{-- Quick Actions --}}
        <div class="col-lg-4 mb-4">

            <div class="card border-1 shadow-sm h-100">

                <div class="card-header bg-white border-1">

                    <h5 class="fw-bold mb-0">

                        Quick Actions

                    </h5>

                </div>

                <div class="card-body">

                    <div class="d-grid gap-3">

                        <button
                            class="btn btn-outline-primary text-start py-3 viewPayslip"
                            id="btnLatestSlip" data-id="0" >

                            <i class="bi bi-file-earmark-text me-2"></i>

                            Latest Payslip

                        </button>

                        <a  href="javascript:void(0)"
                            class="btn btn-outline-success text-start py-3 downloadLatestPDF"
                            id="btnDownloadLatest" data-id="0" >

                            <i class="bi bi-download me-2"></i>

                            Download Latest PDF

                        </a>

                        <button
                            class="btn btn-outline-warning text-start py-3"
                            id="btnViewHistory">

                            <i class="bi bi-clock-history me-2"></i>

                            Payslip History

                        </button>

                        <button
                            class="btn btn-outline-info text-start py-3"
                            id="btnYearFilter">

                            <i class="bi bi-funnel me-2"></i>

                            Filter by Year

                        </button>

                    </div>

                </div>

            </div>

        </div>

    </div>

    {{-- Previous Payslips --}}
    <div class="row" id="paysliprow">

        <div class="col-12">

            <div class="card border-1 shadow-sm">

                <div class="card-header bg-white border-1 py-3">

                    <div class="d-flex justify-content-between align-items-center flex-wrap" id="history">

                        <div>

                            <h5 class="fw-bold mb-1">
                                Payslip History
                            </h5>

                            <small class="text-muted">
                                View and download your previous salary slips
                            </small>

                        </div>

                        <div class="d-flex gap-2 mt-2 mt-lg-0">

                            <select
                                class="form-select"
                                id="filterYear"
                                style="width:120px;">

                                @for($year=date('Y');$year>=date('Y')-5;$year--)

                                    <option value="{{ $year }}">
                                        {{ $year }}
                                    </option>

                                @endfor

                            </select>

                            <select
                                class="form-select"
                                id="filterMonth"
                                style="width:150px;">

                                <option value="">All Months</option>

                                @for($m=1;$m<=12;$m++)

                                    <option value="{{ $m }}">
                                        {{ date('F',mktime(0,0,0,$m,1)) }}
                                    </option>

                                @endfor

                            </select>

                            <button
                                class="btn btn-primary"
                                id="btnSearchPayroll">

                                <i class="bi bi-search"></i>

                            </button>

                        </div>

                    </div>

                </div>

                <div class="card-body">

                    <div class="table-responsive">

                        <table
                            class="table align-middle table-hover"
                            id="payrollTable"
                            width="100%">

                            <thead class="table-light">

                            <tr>

                                <th width="60">
                                    #
                                </th>

                                <th>
                                    Period
                                </th>

                                <th>
                                    Working Days
                                </th>

                                <th>
                                    Gross Earnings
                                </th>

                                <th>
                                    Deductions
                                </th>

                                <th>
                                    Net Salary
                                </th>

                                <th>
                                    Status
                                </th>

                                <th width="140">
                                    Action
                                </th>

                            </tr>

                            </thead>

                            <tbody>

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>

        </div>

    </div>

    

<div class="modal fade" id="payrollModal" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">

            <div class="modal-header bg-dark text-white py-2">
                <h5 class="modal-title">
                    <i class="bi bi-receipt"></i>
                    Employee Payslip
                </h5>

                <button class="btn-close btn-close-white"
                        data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body p-4" id="printArea">

                <!-- Company -->
                <div class="text-center mb-2">

                    <h4 class="fw-bold mb-0">
                        EXACORE IT SOLUTIONS PVT LTD
                    </h4>

                    <small class="text-muted">
                        1st Floor, Indeevaram Bldg, Infopark Smart Space,
                        Koratty, Thrissur - 680308
                    </small>

                </div>

                <div class="text-center mb-4">

                    <span class="border px-4 py-2 small fw-semibold">

                        Payslip for the Month of

                        <span id="mMonth"></span>

                    </span>

                </div>

                <hr>

                <!-- Employee Details -->

                <div class="row small">

                    <div class="col-md-6">

                        <table class="table table-sm table-borderless">

                            <tr>
                                <td width="35%">EMPLOYEE ID</td>
                                <td id="">{{ auth()->user()->emp_id }}</td>
                            </tr>

                            <tr>
                                <td>EMPLOYEE NAME</td>
                                <td id="mEmployee">Employee ID</td>
                            </tr>

                            <tr>
                                <td>DEPARTMENT</td>
                                <td id="">{{ auth()->user()->department->name }}</td>
                            </tr>

                            <tr>
                                <td>DIVISION</td>
                                <td id="mDivision">0</td>
                            </tr>

                            <tr>
                                <td>LOCATION</td>
                                <td id="">{{ auth()->user()->work_location }}</td>
                            </tr>

                            <tr>
                                <td>DESIGNATION</td>
                                <td id="">{{ auth()->user()->designation->name }}</td>
                            </tr>

                            <tr>
                                <td>DATE OF BIRTH</td>
                                <td id="">{{ auth()->user()->dob}}</td>
                            </tr>

                            <tr>
                                <td>DATE OF JOINING</td>
                                <td id="">{{ auth()->user()->joining_date}}</td>
                            </tr>

                        </table>

                    </div>

                    <div class="col-md-6">

                        <table class="table table-sm table-borderless">

                            <tr>
                                <td width="35%">BANK NAME</td>
                                <td id="mBank"></td>
                            </tr>

                            <tr>
                                <td>BANK A/C NO</td>
                                <td id="mAccount"></td>
                            </tr>

                            <tr>
                                <td>PAN NUMBER</td>
                                <td id="">{{ auth()->user()->pan_no }}</td>
                            </tr>

                            <tr>
                                <td>PF UAN NO</td>
                                <td id="">{{ auth()->user()->uan }}</td>
                            </tr>

                            <tr>
                                <td>EMAIL ID</td>
                                <td id="">{{ auth()->user()->email }}</td>
                            </tr>

                            <tr>
                                <td>DAYS IN MONTH</td>
                                <td id="mWorkingDays"></td>
                            </tr>

                            <tr>
                                <td>DAYS PRESENT</td>
                                <td id="mPresentDays"></td>
                            </tr>

                            <tr>
                                <td>WFH DAYS</td>
                                <td id="mWFH"></td>
                            </tr>

                        </table>

                    </div>

                </div>

                <!-- Earnings -->

                <div class="row mt-3">

                    <div class="col-md-6 p-0">

                        <table class="table table-bordered table-sm small mb-0">

                            <thead class="table-dark">

                            <tr>

                                <th>Earnings</th>

                                <th class="text-end">
                                    Amount (INR)
                                </th>

                            </tr>

                            </thead>

                            <tbody>

                            <tr>

                                <td>Basic</td>

                                <td class="text-end" id="mBasic"></td>

                            </tr>

                            <tr>

                                <td>House Rent Allowance</td>

                                <td class="text-end" id="houseAllowance">0.00</td>

                            </tr>

                            <tr>

                                <td>Conveyance Allowance</td>

                                <td class="text-end" id="conveyanceAllowance">0.00</td>

                            </tr>

                            <tr>

                                <td>Other Allowance</td>

                                <td class="text-end" id="mOtherAllowance"></td>

                            </tr>

                            <tr>

                                <td>Medical Allowance</td>

                                <td class="text-end"  id="medicalAllowance">0.00</td>

                            </tr>

                            <tr>

                                <td>Telephone Allowance</td>

                                <td class="text-end"  id="telephoneAllowance">0.00</td>

                            </tr>

                            <tr>

                                <td>CEA</td>

                                <td class="text-end"  id="cea">0.00</td>

                            </tr>

                            <tr>

                                <td>Performance Bonus</td>

                                <td class="text-end"
                                    id="mPerformanceBonus"></td>

                            </tr>

                            <tr>

                                <td>Project Allowance</td>

                                <td class="text-end"
                                    id="mProjectAllowance"></td>

                            </tr>

                            <tr>

                                <td>Special Allowance</td>

                                <td class="text-end"
                                    id="mSpecialAllowance"></td>

                            </tr>

                            <tr class="fw-bold">

                                <td>Total Earnings</td>

                                <td class="text-end"
                                    id="mTotalEarnings"></td>

                            </tr>

                            </tbody>

                        </table>

                    </div>

                    <div class="col-md-6 p-0">

                        <table class="table table-bordered table-sm small mb-0">

                            <thead class="table-dark">

                            <tr>

                                <th>Deductions</th>

                                <th class="text-end">
                                    Amount (INR)
                                </th>

                            </tr>

                            </thead>

                            <tbody>

                            <tr>

                                <td>Provident Fund</td>

                                <td class="text-end" id="mPF"></td>

                            </tr>

                            <tr>

                                <td>Professional Tax</td>

                                <td class="text-end"
                                    id="mProfessionalTax"></td>

                            </tr>

                            <tr>

                                <td>Income Tax</td>

                                <td class="text-end"
                                    id="mIncomeTax"></td>

                            </tr>

                            <tr>

                                <td>LWF</td>

                                <td class="text-end"
                                    id="mLWF"></td>

                            </tr>

                            <tr>

                                <td>ESI</td>

                                <td class="text-end"
                                    id="mESI"></td>

                            </tr>

                            <tr>

                                <td>Salary Advance Recovery</td>

                                <td class="text-end"
                                    id="mSalaryDeduction"></td>

                            </tr>
                            <tr><td>&nbsp;</td><td>&nbsp;</td></tr>
                            <tr><td>&nbsp;</td><td>&nbsp;</td></tr>
                            <tr><td>&nbsp;</td><td>&nbsp;</td></tr>
                            <tr><td>&nbsp;</td><td>&nbsp;</td></tr>

                            

                            <tr class="fw-bold">

                                <td>Total Deductions</td>

                                <td class="text-end"
                                    id="mTotalDeduction"></td>

                            </tr>

                            </tbody>

                        </table>

                    </div>

                </div>

                <!-- Net Salary -->

                <div class="mt-4 p-4 text-white rounded"
                     style="background:#f4630a;">

                    <div class="row align-items-center">

                        <div class="col-md-4">

                            <small>NET PAY</small>

                            <h2 class="fw-bold mb-0">

                                ₹ <span id="mNet"></span>

                            </h2>

                        </div>

                        <div class="col-md-8 text-end">

                            <div id="mSalaryMonth"></div>

                            <small>

                                Credited to
                                <span id="mBankFooter"></span>

                                A/c

                                <span id="mAccountFooter"></span>

                            </small>

                        </div>

                    </div>

                </div>

                <div class="text-center mt-4">

                    <small class="text-muted">

                        This is a computer generated payslip.

                    </small>

                </div>

            </div>

            <div class="modal-footer">

                <a  href="javascript:void(0)" class="btn btn-success downloadLatestPDF"
                        id="btnDownloadSlip">

                    <i class="bi bi-download"></i>

                    Download PDF

                </a>

                <button class="btn btn-secondary"
                        data-bs-dismiss="modal">

                    Close

                </button>

            </div>

        </div>
    </div>
</div>
<script>
var payrollTable = $('#payrollTable').DataTable({

    processing: true,
    serverSide: true,

    ajax: {
        url: "{{ route('payslip.list') }}",
        data: function (d) {
            d.year = $('#filterYear').val();
            d.month = $('#filterMonth').val();
        }
    },

    order: [[1, 'desc']],

    columns: [
        {
            data: 'DT_RowIndex',
            orderable: false,
            searchable: false
        },
        {
            data: 'period'
        },
        {
            data: 'days_in_month'
        },
        {
            data: 'gross'
        },
        {
            data: 'deduction'
        },
        {
            data: 'net'
        },
        {
            data: 'status',
            orderable: false,
            searchable: false
        },
        {
            data: 'action',
            orderable: false,
            searchable: false
        }
    ]
});

loadSummary();
$('#filterYear,#filterMonth').change(function(){

    payrollTable.ajax.reload();

});
var latestPayrollId = null;
function loadSummary(){

    $.get(

        "{{ route('payslip.summary') }}",

        function(response){
           // console.log(res);
            var res = response.latest;
            if(!res){

                return;

            }
            latestPayrollId = res.id;

            $('.downloadLatestPDF').attr(
                'href',
                '/payslip/template/download/' + latestPayrollId
            );
            $("#viewLatestPayslip").attr("data-id",res.id);
            $("#btnLatestSlip").attr("data-id",res.id);
            $("#downloadLatestPDF").attr("data-id",res.id);
            $("#btnDownloadLatest").attr("data-id",res.id);
            $('#latestNetSalary')

                .html('₹ '+numberFormat(res.net_salary));

            $('#totalPayslips')

                .html(response.total_count ?? '');

            $('#latestPayrollMonth')

                .html(monthName(res.month)+' '+res.year);

            $('#basicSalary')

                .html('₹ '+numberFormat(res.basic));

            $('#totalEarnings')

                .html('₹ '+numberFormat(res.total_earnings));

            $('#totalDeduction')

                .html('₹ '+numberFormat(res.total_deduction));

            $('#workingDays')

                .html(res.present_days+'/'+res.days_in_month);

            $('#netSalary')

                .html('₹ '+numberFormat(res.net_salary));

            $('#bankName')

                .html(res.bank);

            $('#accountNumber')

                .html(res.bank_account_number);

            $('#ifscCode')

                .html(res.ifsc_code);

            $('#latestPeriod')

                .html(monthName(res.month)+' '+res.year);

        }

    );

}
$(document).on('click','.viewPayslip',function(){

    let id=$(this).data('id');

    $.ajax({

        url:'/payslip/view/'+id,
        type:'GET',

        success:function(res){

            let p=res.data;
            let e=p.employee ?? {};

            //=========================
            // Header
            //=========================

            $('#mEmployee').text(e.name ?? p.employee_name);

            $('#mEmpCode').text(e.emp_id ?? '');

            $('#mMonth').text(
                monthName(p.month)+' '+p.year
            );

            $('#mSalaryMonth').text(
                'For the month of '
                +monthName(p.month)
                +' '
                +p.year
            );

            //=========================
            // Employee Details
            //=========================

            $('#mDepartment').text(
                e.department?.name ?? '-'
            );

            $('#mDivision').text(
                p.team ?? '-'
            );

            $('#mLocation').text(
                e.branch?.branch_name ?? '-'
            );

            $('#mDesignation').text(
                e.designation?.name ?? '-'
            );

            $('#mDOB').text(
                e.dob ?? '-'
            );

            $('#mJoining').text(
                e.joining_date ?? '-'
            );

            //=========================
            // Right Side
            //=========================

            $('#mBank').text(
                p.bank ?? '-'
            );

            $('#mAccount').text(
                p.bank_account_number ?? '-'
            );

            $('#mIFSC').text(
                p.ifsc_code ?? '-'
            );

            $('#mPan').text(
                e.pan_number ?? '-'
            );

            $('#mPFNumber').text(
                e.pf_number ?? '-'
            );

            $('#mEmail').text(
                e.email ?? '-'
            );

            $('#mWorkingDays').text(
                p.days_in_month
            );

            $('#mPresentDays').text(
                p.present_days
            );

            $('#mWFH').text(
                p.wfh ?? '-'
            );

            //=========================
            // Earnings
            //=========================

            $('#mBasic').text(
                numberFormat(p.basic)
            );

            
            $('#houseAllowance').text(numberFormat(p.house_rent ?? 0));
            $('#conveyanceAllowance').text(numberFormat(p.conveyance ?? 0));
            $('#medicalAllowance').text(numberFormat(p.medical ?? 0));
            $('#cea').text(numberFormat(p.cea ?? 0));
            $('#telephoneAllowance').text(numberFormat(p.telephone ?? 0));

            $('#mOtherAllowance').text(
                numberFormat(p.other_allowance)
            );

            $('#mPerformanceBonus').text(
                numberFormat(p.performance_bonus)
            );

            $('#mProjectAllowance').text(
                numberFormat(p.project_allowance)
            );

            $('#mSpecialAllowance').text(
                numberFormat(p.special_allowance)
            );

            $('#mTotalEarnings').text(
                numberFormat(p.total_earnings)
            );

            //=========================
            // Deductions
            //=========================

            $('#mPF').text(
                numberFormat(p.pf)
            );

            $('#mProfessionalTax').text(
                numberFormat(p.professional_tax)
            );

            $('#mIncomeTax').text(
                numberFormat(p.income_tax)
            );

            $('#mLWF').text(
                numberFormat(p.lwf)
            );

            $('#mESI').text(
                numberFormat(p.esi)
            );

            $('#mSalaryDeduction').text(
                numberFormat(p.salary_deductions)
            );

            $('#mTotalDeduction').text(
                numberFormat(p.total_deduction)
            );

            //=========================
            // Footer
            //=========================

            $('#mNet').text(
                numberFormat(p.net_salary)
            );

            $('#mBankFooter').text(
                p.bank
            );

            $('#mAccountFooter').text(
                p.bank_account_number
            );

            //=========================
            // Download
            //=========================

            $('#btnDownloadSlip')
                .data('id',p.id);

            $('#payrollModal').modal('show');

        }

    });

});
function numberFormat(x){

    return parseFloat(x).toLocaleString(

        'en-IN',

        {

            minimumFractionDigits:2

        }

    );

}
function monthName(month){

    let months=[

        '',

        'January',

        'February',

        'March',

        'April',

        'May',

        'June',

        'July',

        'August',

        'September',

        'October',

        'November',

        'December'

    ];

    return months[month];

}
$('#btnDownloadSlip').click(function(){

    let id=$(this).data('id');

    window.open(
        '/payslip/template/download/'+id,
        '_blank'
    );

});
$('#btnViewHistory').on('click', function () {

    $('html, body').animate({
        scrollTop: $('#paysliprow').offset().top - 80
    }, 600);

});
$('#btnYearFilter').on('click', function () {

    $('html, body').animate({
        scrollTop: $('#paysliprow').offset().top - 80
    }, 600, function () {

        $('#filterYear').focus();

    });

});

</script>


{{-- DEPARTMENT
LOCATION
DESIGNATION
DATE OF BIRTH
DATE OF JOINING
PAN NUMBER
PF UAN NO
EMAIL ID


House Rent Allowance
Conveyance Allowance
Medical Allowance	0.00
Travelling Allowance	0.00
CEA	0.00
Special Allowance	0.00
Income Tax	0.00
ESI	0.00
Salary Advance Recovery	0.00 --}}