 <div class="modal fade" id="celebrationModal" tabindex="-1">

    <div class="modal-dialog modal-dialog-centered">

        <div class="modal-content text-center border-0 shadow-lg">

            <div class="modal-body p-5">

                <img id="empPhoto"
                     class="rounded-circle mb-3"
                     width="130"
                     height="130">

                <h2 id="modalTitle"></h2>

                <h5 id="empName"></h5>

                <p id="modalMessage"></p>

                <h4 id="yearsText"></h4>

                <button class="btn btn-primary mt-3"
                        data-bs-dismiss="modal">
                    Close
                </button>

            </div>

        </div>

    </div>

</div>
<footer class="app-footer">
        <!--begin::To the end-->
        <div class="float-end d-none d-sm-inline">2026</div>
        <!--end::To the end-->
        <!--begin::Copyright-->
        <strong>
          Developed by
          <a href="https://exacoreitsolutions.com" class="text-decoration-none">exacoreitsolutions.com</a>.
        </strong>
        <!--end::Copyright-->
      </footer>
      <!--end::Footer-->
    </div>
    <!--end::App Wrapper-->
    <!--begin::Script-->
    <!--begin::Third Party Plugin(OverlayScrollbars)-->
    <script
      src="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.11.0/browser/overlayscrollbars.browser.es6.min.js"
      crossorigin="anonymous"
    ></script>
    <!--end::Third Party Plugin(OverlayScrollbars)--><!--begin::Required Plugin(popperjs for Bootstrap 5)-->
    <script
      src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
      crossorigin="anonymous"
    ></script>
    <!--end::Required Plugin(popperjs for Bootstrap 5)--><!--begin::Required Plugin(Bootstrap 5)-->
    <script
      src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.min.js"
      crossorigin="anonymous"
    ></script>
    <!--end::Required Plugin(Bootstrap 5)--><!--begin::Required Plugin(AdminLTE)-->
    <script src="./js/adminlte.js"></script>
    <!--end::Required Plugin(AdminLTE)--><!--begin::OverlayScrollbars Configure-->
    <!-- jQuery -->
      <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
      <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

      <!-- DataTables CSS -->
      <link rel="stylesheet"
            href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">

      <!-- DataTables JS -->
      <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
      <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
function setCookie(name, value, days) {

    let expires = "";

    if (days) {
        let date = new Date();
        date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
        expires = "; expires=" + date.toUTCString();
    }

    document.cookie = name + "=" + encodeURIComponent(value) + expires + "; path=/";
}
function getCookie(name) {

    let nameEQ = name + "=";
    let ca = document.cookie.split(';');

    for (let i = 0; i < ca.length; i++) {

        let c = ca[i].trim();

        if (c.indexOf(nameEQ) == 0) {
            return decodeURIComponent(c.substring(nameEQ.length));
        }
    }

    return null;
}
$('#pro_employeeEditModal').find('form').each(function () {
    this.reset();
});
function loadPage(pageUrl) {
    if (!pageUrl) return;
    setCookie('activeMenu', pageUrl, 7);
    // $('#main-content').load(pageUrl);
    $('#main-content').load(pageUrl, function () {

        // Dashboard page loaded
        if (pageUrl.includes('dashboard')) {

            @php
                $departmentId = session('department_id');
            @endphp

            @if($departmentId == 1 || $departmentId == 2)
                loadDashboardStats();
                loadTaskStatus();
                initEmployeeDistributionChart();
                loadOnboardingChart();
                loadAttendanceOverview();
                
            @else
                loademployeeDashboardStats();
                loadAttendanceSummary();
                loadEmployeeProjects();
            @endif
            loadMyTasks();
            loadAttendance();

            if (window.dashboardInterval) {
                clearInterval(window.dashboardInterval);
            }

            window.dashboardInterval = setInterval(function () {
                loadDashboardStats();
                loadAttendance();
            }, 60000);
        } else {

            // Stop dashboard timer when leaving dashboard
            if (window.dashboardInterval) {
                clearInterval(window.dashboardInterval);
                window.dashboardInterval = null;
            }
        }
    });
}
$(document).ready(function () {

    
    // Restore last page
    let activeMenu = getCookie('activeMenu');
    if (activeMenu) {
        loadPage(activeMenu);
    } else {
        let firstMenu = $('.menu-link').first();
        if (firstMenu.length) {
            loadPage(firstMenu.data('page'));
        }
    }
    // Parent Menu Click
    $(document).on(
        'click',
        '.nav-item.has-treeview > .nav-link',
        function (e) {

            e.preventDefault();

            let parent = $(this).parent();

            let isOpen = parent.hasClass('menu-open');

            // Close all menus
            $('.nav-item.has-treeview')
                .removeClass('menu-open');

            $('.nav-item.has-treeview > .nav-link')
                .removeClass('active');

            // Open clicked menu only
            if (!isOpen) {

                parent.addClass('menu-open');

                $(this).addClass('active');
            }
        }
    );

    // Sub Menu Click
    $(document).on(
        'click',
        '.menu-link',
        function (e) {

            e.preventDefault();

            let pageUrl = $(this).data('page');

            // Remove submenu active only
            $('.menu-link').removeClass('active');

            $(this).addClass('active');

            // Keep parent open
            let parent = $(this)
                .closest('.nav-item.has-treeview');

            if (parent.length) {

                $('.nav-item.has-treeview')
                    .not(parent)
                    .removeClass('menu-open');

                $('.nav-item.has-treeview > .nav-link')
                    .not(parent.children('.nav-link'))
                    .removeClass('active');

                parent.addClass('menu-open');

                parent.children('.nav-link')
                    .addClass('active');
            }

            loadPage(pageUrl);

        }
    );
});

function viewProEmployee(id)
{
    $.ajax({
        url: '/employee/details/' + id,
        type: 'GET',

        success: function(response) {

            $('#pro_employee_name').text(response.name);
            $('#pro_employee_profile_image').attr('src', response.photo_url);
            $('#pro_employee_designation').text(response.designation?.name ?? '-');
            $('#pro_employee_code').text(response.emp_id ?? '-');
            $('#pro_profile_email').text(response.email ?? '-');
            $('#pro_profile_phone').text(response.contact_no ?? '-');
            $('#pro_profile_gender').text(response.gender ?? '-');
            $('#pro_profile_blood_group').text(response.blood_group ?? '-');
            $('#pro_profile_nationality').text(response.nationality ?? '-');

            $('#pro_profile_dob').text(response.dob ?? '-');
            $('#pro_profile_emergency_phone').text(response.alt_contact_no ?? '-');
            $('#pro_profile_marital_status').text(response.marital_status ?? '-');
            $('#pro_profile_parent_name').text(response.parent_name ?? '-');
            $('#pro_profile_address').text(response.address ?? '-');
            // Official Information

            $('#view_pro_emp_id').text(response.emp_id ?? '-');
            $('#view_pro_email').text(response.email ?? '-');
            $('#view_pro_department').text(response.department?.name ?? '-');
            $('#view_pro_designation').text(response.designation?.name ?? '-');
            $('#view_pro_reporting_manager').text(response.reporting_manager?.name ?? '-');
            $('#view_pro_joining_date').text(response.joining_date ?? '-');
            $('#view_pro_job_type').text(response.job_type ?? '-');
            $('#view_pro_job_location').text(response.job_location ?? '-');
            $('#view_pro_work_location').text(response.work_location ?? '-');
            $('#view_pro_status').text(response.status == 1 ? 'Active' : 'Inactive');

            // Identity Information

            $('#pro_identity_aadhar').text(
                response.aadhar_no ?? '-'
            );

            $('#pro_identity_pan').text(
                response.pan_no ?? '-'
            );

            $('#pro_identity_passport').text(
                response.passport_no ?? '-'
            );

            $('#pro_identity_uan').text(
                response.uan ?? '-'
            );

            $('#pro_identity_insurance').text(
                response.insurance_no ?? '-'
            );
            $('#pro_employee_status').text(
                response.status == 1 ? 'Active' : 'Inactive'
            );

            //banks
            $('#pro_bank_account_no').text(response.account_no ?? '-');

            $('#pro_bank_name').text(response.bank_name ?? '-');

            $('#pro_bank_ifsc').text(response.ifsc ?? '-');

            $('#pro_bank_branch').text(response.branch ?? '-');

            if (response.passbook != null && response.passbook != '') {
                let fileUrl = '/storage/employees/passbook/' + response.passbook;
                $('#pro_passbook').html(`
                <button type="button"
                        class="btn btn-sm btn-outline-primary"
                        onclick="viewProAttachment('${fileUrl}')"
                        title="View">
                    <i class="bi bi-eye-fill"></i>
                </button> &nbsp;&nbsp;
                    <a href="${fileUrl}" download class="btn btn-sm btn-success" title="Download">
                        <i class="bi bi-download"></i>
                    </a>
                `);
            } else {
                $('#pro_passbook').html('');
            }
            
            //education
            $('#pro_educationTableBody').html('');
            if (response.educations && response.educations.length > 0) {
                $.each(response.educations, function(index, education){
                    let fileUrl = '/storage/employees/education/' + education.attachment;
                    $('#pro_educationTableBody').append(`
                        <tr>
                            <td>${education.qualification ?? '-'}</td>
                            <td>${education.university_board ?? '-'}</td>
                            <td>${education.passing_year ?? '-'}</td>
                            <td>${education.percentage ?? '-'}%</td>
                            <td>${education.attachment ? `<a href="javascript:void(0)"
                            onclick="viewProAttachment('${fileUrl}')"
                            class="btn btn-sm btn-primary">
                                <i class="bi bi-eye-fill"></i>
                                </a>
                                <a href="${education.attachment}" download class="btn btn-sm btn-success">
                                    <i class="bi bi-download"></i>
                                </a>` : '-'}
                            </td>
                        </tr>
                    `);
                });
            }
            else{
                $('#pro_educationTableBody').html(`
                    <tr>
                        <td colspan="5" class="text-center">
                            No education details found.
                        </td>
                    </tr>
                `);
            }
           

            //experience
            $('#pro_experienceTableBody').html('');
            if (response.experiences && response.experiences.length > 0) {
                $.each(response.experiences, function(index, experience){
                    let fileUrl = '/storage/employees/experience/' + experience.attachment;
                    $('#pro_experienceTableBody').append(`
                        <tr>
                            <td>${experience.company_name ?? '-'}</td>
                            <td>${experience.job_role ?? '-'}</td>
                            <td>${experience.year_of_experience ?? '-'}</td>
                            <td>${experience.attachment ? `<a href="javascript:void(0)"
                            onclick="viewProAttachment('${fileUrl}')"
                            class="btn btn-sm btn-primary">
                                <i class="bi bi-eye-fill"></i>
                                </a>
                                <a href="${experience.attachment}" download class="btn btn-sm btn-success">
                                    <i class="bi bi-download"></i>
                                </a>` : '-'}
                            </td>
                        </tr>
                    `);
                });
            }
            else{
                $('#pro_experienceTableBody').html(`
                    <tr>
                        <td colspan="4" class="text-center">
                            No education details found.
                        </td>
                    </tr>
                `);
            }


            $('#pro_doc_aadhar').html(
                documentProCard('Aadhaar Card',response.adhar_card)
            );

            $('#pro_doc_pan').html(
                documentProCard('PAN Card',response.pan_card)
            );

            $('#pro_doc_passport').html(
                documentProCard('Passport',response.passport)
            );

            $('#pro_doc_resume').html(
                documentProCard('Resume',response.resume)
            );

            $('#pro_doc_passbook').html(
                documentProCard('Passbook',response.passbook)
            );

            $('#pro_doc_insurance').html(
                documentProCard('Insurance Card',response.insurance)
            );
            $('#pro_employeeProfileModal').modal('show');
        },
            error: function(xhr, status, error) {

            console.error(error);

            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Unable to fetch employee details. Please try again.'
            });

            // Optional: clear modal fields
            $('#pro_employee_name').text('');
            $('#pro_employee_email').text('');
        }
    });
}

function documentProCard(title,file){

    if(!file){

        return `
            <label class="fw-semibold">${title}</label>

            <div class="border rounded p-3 text-muted">

                No document uploaded

            </div>
        `;
    }

    let fileName=file.split('/').pop();

    return `
    <label class="fw-semibold">${title}</label>
    <div class="border rounded p-3 d-flex justify-content-between align-items-center">
        <div>
           <i class="bi bi-file-earmark-pdf-fill text-danger fs-4 me-2"></i>
            ${fileName}
        </div>
        <div>
            <a href="${file}" target="_blank" class="btn btn-sm btn-primary">
                <i class="bi bi-eye-fill"></i>
            </a>
            <a href="${file}" download class="btn btn-sm btn-success">
                <i class="bi bi-download"></i>
            </a>
        </div>
    </div>
    `;
}

function viewProAttachment(file)
{
    if (!file) return;

    let extension = file.split('.').pop().toLowerCase();

    $('#pro_attachmentImage').addClass('d-none');
    $('#pro_attachmentPdf').addClass('d-none');

    $('#pro_attachmentDownload').attr('href', file);

    if (['jpg','jpeg','png','gif','webp'].includes(extension)) {

        $('#pro_attachmentImage')
            .attr('src', file)
            .removeClass('d-none');

    }
    else if (extension === 'pdf') {

        $('#pro_attachmentPdf')
            .attr('src', file)
            .removeClass('d-none');

    }
    else {

        window.open(file,'_blank');
        return;

    }

    $('#pro_attachmentViewerModal').modal('show');
}

function editProEmployee(id)
{
    $.ajax({        
        url:'/employee/edit/'+id,
        type:'GET',
        beforeSend:function(){
            Swal.fire({
                title:'Loading...',
                text:'Fetching employee details',
                allowOutsideClick:false,
                didOpen:()=>{
                    Swal.showLoading();
                }
            });
        },
        success:function(emp){
            Swal.close();
            // Hidden ID
            $('#pro_edit_employee_id').val(emp.id);
            $('#reset_employee_id').val(emp.id);
            $('#pro_profile_id').val(emp.id);
            $('#pro_official_id').val(emp.id);

            // Header
            $('#pro_edit_employee_name').text(emp.name);

            if(emp.photo){
                $('#pro_edit_profile_image').attr('src',emp.photo);
            }

            // Profile

            $('#pro_edit_name').val(emp.name);
            $('#pro_edit_personal_email').val(emp.personal_email);
            $('#pro_edit_dob').val(emp.dob);
            $('#pro_edit_emergency_phone').val(emp.alt_contact_no);
            $('#pro_edit_marital_status').val(emp.marital_status);
            $('#pro_edit_guardian_name').val(emp.parent_name);
            $('#pro_edit_address').val(emp.address);
            $('#pro_edit_emp_id').val(emp.emp_id);

            $('#pro_edit_phone').val(emp.contact_no);
            $('#pro_edit_gender').val(emp.gender);
            $('#pro_edit_blood_group').val(emp.blood_group);
            $('#pro_edit_nationality').val(emp.nationality);

            // Official Information
            $('#pro_view_emp_id').text(emp.emp_id ?? '-');
            $('#pro_view_email').text(emp.email ?? '-');
            $('#pro_view_department').text(emp.department?.name ?? '-');
            $('#pro_view_designation').text(emp.designation?.name ?? '-');
            $('#pro_view_reporting_manager').text(emp.reporting_manager?.name ?? '-');
            $('#pro_view_joining_date').text(emp.joining_date ?? '-');
            $('#pro_view_job_type').text(emp.job_type ?? '-');
            $('#pro_view_job_location').text(emp.job_location ?? '-');
            $('#pro_view_work_location').text(emp.work_location ?? '-');
            $('#pro_view_status').text(emp.status == 1 ? 'Active' : 'Inactive');

            //education
            $('#pro_edit_educationTableBody').html('');

            if (emp.educations && emp.educations.length > 0) {

                $.each(emp.educations, function(index, education){
                    let fileUrl = '/storage/employees/education/' + education.attachment;
                    $('#pro_edit_educationTableBody').append(`
                        <tr data-id="${education.id}">

                            <td>
                                <input type="text"
                                    class="form-control qualification"
                                    value="${education.qualification ?? ''}">
                            </td>

                            <td>
                                <input type="text"
                                    class="form-control university_board"
                                    value="${education.university_board ?? ''}">
                            </td>

                            <td>
                                <input type="number"
                                    class="form-control passing_year"
                                    value="${education.passing_year ?? ''}">
                            </td>

                            <td>
                                <input type="text"
                                    class="form-control percentage"
                                    value="${education.percentage ?? ''}">
                            </td>

                            <td style="white-space: nowrap;">

                                ${
                                    education.attachment
                                    ? `
                                        <button type="button"
                                                class="btn btn-sm btn-outline-primary"
                                                onclick="viewProAttachment('${fileUrl}')"
                                                title="View">
                                            <i class="bi bi-eye-fill"></i>
                                        </button>

                                        <a href="${fileUrl}"
                                        download
                                        class="btn btn-sm btn-outline-success"
                                        title="Download">
                                            <i class="bi bi-download"></i>
                                        </a>
                                    `
                                    : `
                                        <span class="badge bg-secondary">No File</span>
                                    `
                                }

                                <input type="file"
                                    class="form-control form-control-sm attachment d-inline-block ms-2"
                                    style="width:180px;"
                                    accept=".pdf,.jpg,.jpeg,.png">

                            </td>

                            <td>
                                <button type="button"
                                        class="btn btn-sm btn-danger removeProfileRow">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>

                        </tr>
                        `);

                });

            }
            else{

                $('#pro_edit_educationTableBody').html(`
                    <tr id="noeducation">
                        <td colspan="6" class="text-center" >
                            No education details found.
                        </td>
                    </tr>
                `);

            }

             //experience
            $('#pro_edit_experienceTableBody').html('');

            if (emp.experiences && emp.experiences.length > 0) {

                $.each(emp.experiences, function(index, experience){
                    let fileUrl = '/storage/employees/experience/' + experience.attachment;
                    $('#pro_edit_experienceTableBody').append(`
                        <tr data-id="${experience.id}">

                            <td>
                                <input type="text"
                                    class="form-control company_name"
                                    value="${experience.company_name ?? ''}">
                            </td>

                            <td>
                                <input type="text"
                                    class="form-control job_role"
                                    value="${experience.job_role ?? ''}">
                            </td>

                            <td>
                                <input type="number"
                                    class="form-control year_of_experience"
                                    value="${experience.year_of_experience ?? ''}">
                            </td>

                            

                            <td style="white-space: nowrap;">

                                ${
                                    experience.attachment
                                    ? `
                                        <button type="button"
                                                class="btn btn-sm btn-outline-primary"
                                                onclick="viewProAttachment('${fileUrl}')"
                                                title="View">
                                            <i class="bi bi-eye-fill"></i>
                                        </button>

                                        <a href="${fileUrl}"
                                        download
                                        class="btn btn-sm btn-outline-success"
                                        title="Download">
                                            <i class="bi bi-download"></i>
                                        </a>
                                    `
                                    : `
                                        <span class="badge bg-secondary">No File</span>
                                    `
                                }

                                <input type="file"
                                    class="form-control form-control-sm attachment d-inline-block ms-2"
                                    style="width:180px;"
                                    accept=".pdf,.jpg,.jpeg,.png">

                            </td>

                            <td>
                                <button type="button"
                                        class="btn btn-sm btn-danger removeProfileRow">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>

                        </tr>
                        `);

                });

            }
            else{

                $('#pro_edit_experienceTableBody').html(`
                    <tr id="noexperience">
                        <td colspan="6" class="text-center" >
                            No experience details found.
                        </td>
                    </tr>
                `);

            }

            // Banking
            $('#pro_edit_bank_name').val(emp.bank_name);
            $('#pro_edit_account_no').val(emp.account_no);
            $('#pro_edit_ifsc').val(emp.ifsc);
            $('#pro_edit_branch').val(emp.branch);
            // $("#edit_passbook").html();
            if (emp.passbook != null && emp.passbook != '') {
                let fileUrl = '/storage/employees/passbook/' + emp.passbook;
                $('#edit_passbook').html(`
                <button type="button"
                        class="btn btn-sm btn-outline-primary"
                        onclick="viewProAttachment('${fileUrl}')"
                        title="View">
                    <i class="bi bi-eye-fill"></i>
                </button> &nbsp;&nbsp;
                    <a href="${fileUrl}" download class="btn btn-sm btn-success" title="Download">
                        <i class="bi bi-download"></i>
                    </a>
                `);
            } else {
                $('#edit_passbook').html('');
            }
            // Open first tab

            $('#pro_employeeEditModal').modal('show');

            $('#pro_employeeEditModal .nav-link:first').tab('show');

        },

        error:function(xhr){

            Swal.fire({
                icon:'error',
                title:'Error',
                text:xhr.responseJSON?.message ?? 'Unable to load employee.'
            });

        }

    });

}

$('#addEducationRow').click(function () {
    $("#noeducation").remove();
    let row = `
    <tr data-id="">

        <td>
            <input type="text"
                class="form-control qualification"
                placeholder="Qualification">
        </td>

        <td>
            <input type="text"
                class="form-control university_board"
                placeholder="University / Board">
        </td>

        <td>
            <input type="number"
                class="form-control passing_year"
                min="1950"
                max="2100">
        </td>

        <td>
            <input type="text"
                class="form-control percentage"
                placeholder="Percentage / CGPA">
        </td>

        <td>

            <input type="file"
                class="form-control attachment"
                accept=".pdf,.jpg,.jpeg,.png">
        </td>
            
        <td>   
            <button type="button"
                class="btn btn-sm btn-danger mt-1 removeProfileRow">
                <i class="bi bi-trash"></i>
            </button> 
        </td>

    </tr>
    `;

    $('#pro_edit_educationTableBody').append(row);

});
$('#saveEducation').click(function () {

    let isValid = true;
    let errorMessage = '';

    $('#pro_edit_educationTableBody tr').each(function (index) {

        let qualification = $(this).find('.qualification').val()?.trim();
        let university = $(this).find('.university_board').val()?.trim();
        let passingYear = $(this).find('.passing_year').val()?.trim();
        let percentage = $(this).find('.percentage').val()?.trim();
        
        let attachment = $(this).find('.attachment')[0];

        if ($(this).data('id') == '' || $(this).data('id') == undefined) {

            if (!attachment || attachment.files.length == 0) {
                isValid = false;
                errorMessage = 'Please upload an attachment in row ' + (index + 1);
                return false;
            }
        }
        if (!qualification) {
            isValid = false;
            errorMessage = 'Please enter Qualification in row ' + (index + 1);
            return false;
        }

        if (!university) {
            isValid = false;
            errorMessage = 'Please enter University / Board in row ' + (index + 1);
            return false;
        }

        if (!passingYear) {
            isValid = false;
            errorMessage = 'Please enter Passing Year in row ' + (index + 1);
            return false;
        }

        if (!percentage) {
            isValid = false;
            errorMessage = 'Please enter Percentage / CGPA in row ' + (index + 1);
            return false;
        }
    });

    if (!isValid) {

        Swal.fire({
            icon: 'warning',
            title: 'Validation',
            text: errorMessage
        });

        return;
    }

    // -----------------------------
    // Validation passed
    // -----------------------------

    let formData = new FormData();
    let employeeId = $('#pro_edit_employee_id').val();

    formData.append('_token', '{{ csrf_token() }}');
    formData.append('employee_id', employeeId);

    $('#pro_edit_educationTableBody tr').each(function (index) {
        let educationId = $(this).attr('data-id') || '';

        formData.append(
            `education[${index}][id]`,
            educationId
        );
        formData.append(`education[${index}][qualification]`,
            $(this).find('.qualification').val());

        formData.append(`education[${index}][university_board]`,
            $(this).find('.university_board').val());

        formData.append(`education[${index}][passing_year]`,
            $(this).find('.passing_year').val());

        formData.append(`education[${index}][percentage]`,
            $(this).find('.percentage').val());

        let fileInput = $(this).find('.attachment')[0];

        if (fileInput && fileInput.files.length > 0) {
            formData.append(`education[${index}][attachment]`, fileInput.files[0]);
        }

    });

    $.ajax({
        url: "{{ route('employee.education.store') }}",
        type: "POST",
        data: formData,
        processData: false,
        contentType: false,

        success: function (res) {

            Swal.fire(
                'Success',
                res.message,
                'success'
            );

        },

        error: function (xhr) {

            Swal.fire(
                'Error',
                xhr.responseJSON.message,
                'error'
            );

        }

    });

});

$('#addExperienceRow').click(function () {
    $("#noexperience").remove();
    let row = `
    <tr data-id="">

        <td>
            <input type="text"
                class="form-control company_name"
                placeholder="Company Name">
        </td>

        <td>
            <input type="text"
                class="form-control job_role"
                placeholder="Job Role">
        </td>

        <td>
            <input type="text"
                class="form-control year_of_experience">
        </td>


        <td>

            <input type="file"
                class="form-control attachment"
                accept=".pdf,.jpg,.jpeg,.png">
        </td>
            
        <td>   
            <button type="button"
                class="btn btn-sm btn-danger mt-1 removeProfileRow">
                <i class="bi bi-trash"></i>
            </button> 
        </td>

    </tr>
    `;

    $('#pro_edit_experienceTableBody').append(row);

});
$('#saveExperience').click(function () {

    let isValid = true;
    let errorMessage = '';

    $('#pro_edit_experienceTableBody tr').each(function (index) {

        let company_name = $(this).find('.company_name').val()?.trim();
        let job_role = $(this).find('.job_role').val()?.trim();
        let year_of_experience = $(this).find('.year_of_experience').val()?.trim();
        
        let attachment = $(this).find('.attachment')[0];

        if ($(this).data('id') == '' || $(this).data('id') == undefined) {

            if (!attachment || attachment.files.length == 0) {
                isValid = false;
                errorMessage = 'Please upload an attachment in row ' + (index + 1);
                return false;
            }
        }
        if (!company_name) {
            isValid = false;
            errorMessage = 'Please enter Company Name in row ' + (index + 1);
            return false;
        }

        if (!job_role) {
            isValid = false;
            errorMessage = 'Please enter Job Role in row ' + (index + 1);
            return false;
        }

        if (!year_of_experience) {
            isValid = false;
            errorMessage = 'Please enter Experience in row ' + (index + 1);
            return false;
        }

    });

    if (!isValid) {

        Swal.fire({
            icon: 'warning',
            title: 'Validation',
            text: errorMessage
        });

        return;
    }

    // -----------------------------
    // Validation passed
    // -----------------------------

    let formData = new FormData();
    let employeeId = $('#pro_edit_employee_id').val();

    formData.append('_token', '{{ csrf_token() }}');
    formData.append('employee_id', employeeId);

    $('#pro_edit_experienceTableBody tr').each(function (index) {
        let experienceId = $(this).attr('data-id') || '';

        formData.append(
            `experience[${index}][id]`,
            experienceId
        );
        formData.append(`experience[${index}][company_name]`,
            $(this).find('.company_name').val());

        formData.append(`experience[${index}][job_role]`,
            $(this).find('.job_role').val());

        formData.append(`experience[${index}][year_of_experience]`,
            $(this).find('.year_of_experience').val());

        
        let fileInput = $(this).find('.attachment')[0];

        if (fileInput && fileInput.files.length > 0) {
            formData.append(`experience[${index}][attachment]`, fileInput.files[0]);
        }

    });

    $.ajax({
        url: "{{ route('employee.experience.store') }}",
        type: "POST",
        data: formData,
        processData: false,
        contentType: false,

        success: function (res) {

            Swal.fire(
                'Success',
                res.message,
                'success'
            );

        },

        error: function (xhr) {

            Swal.fire(
                'Error',
                xhr.responseJSON.message,
                'error'
            );

        }

    });

});
function togglePassword(id, button) {

    let input = $('#' + id);

    if (input.attr('type') === 'password') {

        input.attr('type', 'text');
        $(button).find('i').removeClass('bi-eye').addClass('bi-eye-slash');

    } else {

        input.attr('type', 'password');
        $(button).find('i').removeClass('bi-eye-slash').addClass('bi-eye');

    }

}
$(document).on('click', '.removeProfileRow', function () {

    $(this).closest('tr').remove();

});

function loadDashboardStats() {

    $.ajax({
        url: "{{ route('dashboard.stats') }}",
        type: "GET",
        dataType: "json",
        success: function (response) {

            $("#totalEmployees").text(response.total_employees);
            $("#presentToday").text(response.present_today);
            $("#onLeave").text(response.on_leave);
            $("#totalProjects").text(response.total_projects);
            $("#totalTasks").text(response.total_tasks);

        }
    });

}
function loademployeeDashboardStats() {

    $.ajax({
        url: "{{ route('dashboard.employee.stats') }}",
        type: "GET",
        dataType: "json",
        success: function (response) {

            $("#totalTasks").text(response.total_tasks);
            $("#completedTasks").text(response.completed_tasks);
            $("#totalProjects").text(response.total_projects);
            $("#leaveBalance").text(response.leave_balance);
            $("#attendanceDays").text(response.attendance_days);

        },
        error: function (xhr) {
            console.log(xhr.responseText);
        }
    });

}
$('#changePasswordBtn').click(function () {

    let employee_id = $('#reset_employee_id').val();
    let password = $('#new_password').val();
    let confirmPassword = $('#confirm_password').val();

    if (password == '') {

        Swal.fire({
            icon: 'warning',
            title: 'Warning',
            text: 'Please enter new password.'
        });

        return;
    }

    if (password != confirmPassword) {

        Swal.fire({
            icon: 'error',
            title: 'Password Mismatch',
            text: 'Both passwords must be the same.'
        });

        return;
    }

    Swal.fire({

        title: 'Are you sure?',
        text: 'Do you want to change this employee password?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, Change Password',
        cancelButtonText: 'Cancel'

    }).then((result) => {

        if (result.isConfirmed) {

            $.ajax({

                url: '/employee/change-password/' + employee_id,
                type: 'POST',

                data: {

                    password: password,
                    _token: $('meta[name="csrf-token"]').attr('content')

                },

                success: function (response) {

                    Swal.fire({

                        icon: 'success',
                        title: 'Success',
                        text: response.message

                    });

                    $('#new_password').val('');
                    $('#confirm_password').val('');

                },

                error: function () {

                    Swal.fire({

                        icon: 'error',
                        title: 'Error',
                        text: 'Unable to change password.'

                    });

                }

            });

        }

    });

});


var working = "00:00:00";
var breaking = "00:00:00";
var lastDirection = "";
var timer = null;



function loadAttendance() {

    $.ajax({
        url: "{{ route('dashboard.attendance') }}",
        type: "GET",

        success: function(res){

            working = res.workingHours;
            breaking = res.breakHours;
            lastDirection = res.lastDirection;

            if(res.checkIn == "Not Checked In"){

                $("#checkInTime")
                    .removeClass("text-success")
                    .addClass("text-danger")
                    .html('<i class="bi bi-x-circle-fill"></i> Not Checked');

            }else{

                $("#checkInTime")
                    .removeClass("text-danger")
                    .addClass("text-success")
                    .html('<i class="bi bi-check-circle-fill"></i> ' + res.checkIn);

            }

            $("#workingHours").text(working);
            $("#breakHours").text(breaking);

            startTimer();
        }
    });

}

function tick(time){

    let p = time.split(':');

    let sec =
        parseInt(p[0]) * 3600 +
        parseInt(p[1]) * 60 +
        parseInt(p[2]);

    sec++;

    let h = String(Math.floor(sec / 3600)).padStart(2,'0');
    let m = String(Math.floor((sec % 3600) / 60)).padStart(2,'0');
    let s = String(sec % 60).padStart(2,'0');

    return h + ":" + m + ":" + s;
}

function startTimer(){

    if(timer){
        clearInterval(timer);
    }

    timer = setInterval(function(){

        if(lastDirection === "in"){

            working = tick(working);
            $("#workingHours").text(working);

        }else if(lastDirection === "out"){

            breaking = tick(breaking);
            $("#breakHours").text(breaking);

        }

    },1000);

}


let taskChart = null;

const doughnutcenterText = {

    id: 'doughnutcenterText',

    afterDraw(chart, args, options) {

        const { ctx } = chart;
        const meta = chart.getDatasetMeta(0);

        if (!meta.data.length) return;

        const x = meta.data[0].x;
        const y = meta.data[0].y;

        ctx.save();

        ctx.textAlign = 'center';

        ctx.font = 'bold 28px sans-serif';
        ctx.fillStyle = '#212529';
        ctx.fillText(options.total, x, y - 8);

        ctx.font = '14px sans-serif';
        ctx.fillStyle = '#6c757d';
        ctx.fillText('Total Tasks', x, y + 18);

        ctx.restore();
    }
};

function drawTaskChart(labels, values, colors, total) {

    if (taskChart) {
        taskChart.destroy();
    }

    taskChart = new Chart(
        document.getElementById('taskStatusChart'),
        {
            type: 'doughnut',

            data: {
                labels: labels,
                datasets: [{
                    data: values,
                    backgroundColor: colors,
                    borderWidth: 0
                }]
            },

            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '72%',
                plugins: {
                    legend: {
                        display: false
                    },
                    doughnutcenterText: {
                        total: total
                    }
                }
            },

            plugins: [doughnutcenterText]
        }
    );
}

function loadTaskStatus() {

    $.ajax({
        url: "{{ route('dashboard.task.status') }}",
        type: "GET",
        dataType: "json",

        success: function(response) {

            let labels = [];
            let values = [];
            let colors = [];
            let legend = '';

            response.statuses.forEach(function(item){

                labels.push(item.name);
                values.push(item.count);
                colors.push(item.color);

                legend += `
                    <div class="d-flex justify-content-between mb-3">
                        <div>
                            <span class="badge rounded-pill"
                                  style="background:${item.color};width:14px;height:14px;">
                            </span>

                            ${item.name}
                        </div>

                        <strong>${item.count}</strong>
                    </div>
                `;
            });

            $("#taskStatusLegend").html(legend);

            drawTaskChart(
                labels,
                values,
                colors,
                response.total
            );

        }
    });

}

let employeeChart = null;
function initEmployeeDistributionChart() {

    $.get("{{ route('dashboard.employee.distribution') }}", function(response) {

        let labels = [];
        let values = [];
        let colors = [];
        let html = '';

        response.departments.forEach(function(item){

            labels.push(item.name);
            values.push(item.count);
            colors.push(item.color);

            html += `
                <div class="d-flex justify-content-between mb-3">
                    <div>
                        <span class="badge rounded-pill"
                              style="background:${item.color};width:14px;height:14px;">
                        </span>

                        ${item.name}
                    </div>

                    <strong>${item.count}</strong>
                </div>
            `;

        });

        $("#departmentLegend").html(html);

        drawEmployeeChart(
            labels,
            values,
            colors,
            response.total
        );

    });

}

const employeeCenterText = {

    id:'employeeCenterText',

    afterDraw(chart,args,options){

        const {ctx} = chart;
        const meta = chart.getDatasetMeta(0);

        if(!meta.data.length) return;

        const x = meta.data[0].x;
        const y = meta.data[0].y;

        ctx.save();

        ctx.textAlign='center';

        ctx.font='bold 28px sans-serif';
        ctx.fillStyle='#212529';
        ctx.fillText(options.total,x,y-8);

        ctx.font='14px sans-serif';
        ctx.fillStyle='#6c757d';
        ctx.fillText('Employees',x,y+18);

        ctx.restore();

    }

};

function drawEmployeeChart(labels,data,colors,total){

    if(employeeChart){
        employeeChart.destroy();
    }

    employeeChart = new Chart(
        document.getElementById('employeeDistributionChart'),
        {
            type:'doughnut',

            data:{
                labels:labels,
                datasets:[{
                    data:data,
                    backgroundColor:colors,
                    borderWidth:0
                }]
            },

            options:{
                responsive:true,
                maintainAspectRatio:false,
                cutout:'72%',
                plugins:{
                    legend:{
                        display:false
                    },
                    employeeCenterText:{
                        total:total
                    }
                }
            },

            plugins:[employeeCenterText]
        }
    );

}

function resetPassword(id) {
    Swal.fire({
        title: 'Are you sure?',
        text: "You want to reset this employee's password.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, Reset Password',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {

            // Your AJAX call here
            $.ajax({
                url: '/employee/reset-password/' + id,
                type: 'POST',
                data: {
                    id: id,
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    Swal.fire(
                        'Reset!',
                        'Employee password has been reset successfully.',
                        'success'
                    );
                },
                error: function() {
                    Swal.fire(
                        'Error!',
                        'Something went wrong.',
                        'error'
                    );
                }
            });

        }
    });
}

function loadOnboardingChart() {

    $.ajax({

        url: "{{ route('dashboard.onboarding.chart') }}",

        data: {
            year: $("#onboardingYear").val()
        },

        success: function(response) {

            drawOnboardingChart(
                response.labels,
                response.data
            );

        }

    });

}

$(document).on("change", "#onboardingYear", function () {
    loadOnboardingChart();
});

let onboardingChart = null;

function drawOnboardingChart(labels, data) {

    if (onboardingChart) {
        onboardingChart.destroy();
    }

    onboardingChart = new Chart(
        document.getElementById("onboardingChart"),
        {
            type: "line",

            data: {
                labels: labels,

                datasets: [{
                    label: "Onboarding",
                    data: data,

                    borderColor: "#3b82f6",
                    backgroundColor: "#3b82f6",

                    borderWidth: 2,

                    fill: false,

                    tension: 0.4,      // Smooth curve

                    pointRadius: 3,

                    pointHoverRadius: 5,

                    pointBackgroundColor: "#2563eb",

                    pointBorderColor: "#ffffff",

                    pointBorderWidth: 2
                }]
            },

            options: {

                responsive: true,

                maintainAspectRatio: false,

                interaction: {
                    intersect: false,
                    mode: "index"
                },

                plugins: {

                    legend: {
                        display: false
                    },

                    tooltip: {
                        backgroundColor: "#ffffff",
                        titleColor: "#212529",
                        bodyColor: "#212529",
                        borderColor: "#dee2e6",
                        borderWidth: 1,
                        displayColors: true
                    }

                },

                scales: {

                    x: {

                        grid: {
                            display: false
                        }

                    },

                    y: {

                        beginAtZero: true,

                        ticks: {
                            precision: 0
                        },

                        grid: {
                            color: "#f1f3f5"
                        }

                    }

                }

            }

        }
    );

}

let attendanceChart = null;

function loadAttendanceOverview() {

    $.ajax({

        url: "{{ route('dashboard.attendance.overview') }}",

        data: {
            year: $("#attendanceYear").val()
        },

        success: function(response){

            drawAttendanceChart(
                response.labels,
                response.present,
                response.absent
            );

        }

    });

}

$(document).on("change","#attendanceYear",function(){

    loadAttendanceOverview();

});

function drawAttendanceChart(labels,present,absent){

    if(attendanceChart){
        attendanceChart.destroy();
    }

    attendanceChart = new Chart(
        document.getElementById("attendanceChart"),
        {
            type:'bar',

            data:{
                labels:labels,

                datasets:[
                    {
                        label:'Present',
                        data:present,
                        backgroundColor:'#2563eb',
                        borderRadius:5,
                        barThickness:12
                    },
                    {
                        label:'Absent',
                        data:absent,
                        backgroundColor:'#fbbf24',
                        borderRadius:5,
                        barThickness:12
                    }
                ]
            },

            options:{

                responsive:true,

                maintainAspectRatio:false,

                plugins:{
                    legend:{
                        position:'top',
                        align:'end'
                    }
                },

                scales:{
                    x:{
                        grid:{
                            display:false
                        }
                    },
                    y:{
                        beginAtZero:true,
                        ticks:{
                            precision:0
                        }
                    }
                }

            }

        }
    );

}
function loadMyTasks(){

    $.get("{{ route('dashboard.myTasks') }}",function(response){

        let html='';

        if(response.length===0){

            html=`<tr>
                    <td colspan="4" class="text-center text-muted">
                        No Tasks Found
                    </td>
                  </tr>`;

        }else{

            response.forEach(function(task){

                html+=`
                <tr>
                    <td>${task.latest_update.task_name}</td>
                    <td>${task.project.project_name}</td>
                    <td>
                        <span class="badge bg-primary">
                            ${task.latest_update.priority}
                        </span>
                    </td>
                    <td>${formatDate(task.latest_update.end_date)}</td>
                </tr>`;
            });

        }

        $("#dashboardMyTasks").html(html);

    });

}

function formatDate(date){
    let d=new Date(date);
    return d.toLocaleDateString('en-GB');
}

let empattendanceChart = null;
function loadAttendanceSummary() {

    $.get("{{ route('dashboard.employeeAttendanceSummary') }}", {
        month: $('#attendanceMonth').val()
    }, function (res) {
        $('#presentCount').text(res.present);
        $('#leaveCount').text(res.leave);
        $('#wfhCount').text(res.wfh);
        $('#workingDays').text(res.total);
        $('#workingDays').text(res.total);

        const ctx = document.getElementById('attendanceDonutChart');

        if (empattendanceChart) {
            empattendanceChart.destroy();
        }

        empattendanceChart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Present','Leave','WFH'],
                datasets: [{
                    data: [
                        res.present,
                        res.leave,
                        res.wfh
                    ],
                    backgroundColor: [
                        '#16a34a',
                        '#f59e0b',
                        '#0d6efd'
                    ],
                    borderWidth:0
                }]
            },
            options: {
                cutout: '70%',
                plugins: {
                    legend: {
                        display:false
                    }
                }
            },
            plugins: [{
                id:'centerText',
                afterDraw(chart){

                    let width = chart.width;
                    let height = chart.height;
                    let ctx = chart.ctx;

                    ctx.restore();

                    ctx.font='bold 30px Arial';
                    ctx.fillStyle='#2563eb';
                    ctx.textAlign='center';
                    ctx.fillText(
                        res.total,
                        width/2,
                        height/2-5
                    );

                    ctx.font='16px Arial';
                    ctx.fillStyle='#666';

                    ctx.fillText(
                        'Working Days',
                        width/2,
                        height/2+22
                    );

                    ctx.save();
                }
            }]
        });

    });

}


$(document).on("change","#attendanceMonth",function(){
    loadAttendanceSummary();
});

$(document).on('click', '#viewAllMyTasks', function (e) {
    e.preventDefault();
    $('#menuMyTasks').trigger('click');
});
function loadEmployeeProjects() {

    $.get("{{ route('dashboard.employee.projects') }}", function (response) {

        let html = '';

        if(response.length == 0){

            html = `
                <tr>
                    <td colspan="2" class="text-center py-4">
                        No Projects Assigned
                    </td>
                </tr>
            `;

            $('#employeeProjectsBody').html(html);
            return;
        }

        $.each(response,function(i,row){

            let color='bg-success';

            if(row.progress < 30){
                color='bg-danger';
            }else if(row.progress < 60){
                color='bg-warning';
            }else if(row.progress < 90){
                color='bg-primary';
            }

            html += `
            <tr>

                <td>${row.project_name}</td>

                <td>

                    <div class="d-flex justify-content-between mb-1">

                        <small>${row.progress}%</small> <small class="text-muted">
                        Tasks: ${row.completed} / ${row.total} 
                    </small>

                    </div>

                    <div class="progress" style="height:6px;">

                        <div class="progress-bar ${color}"
                            style="width:${row.progress}%">
                        </div>

                    </div>

                    

                </td>

            </tr>`;
        });

        $('#employeeProjectsBody').html(html);

    });

}

$(function () {

    // Don't show again today
    let today = new Date().toISOString().slice(0,10);

    // if(localStorage.getItem('celebration') == today)
    //     return;

    $.get('/employee/celebration', function(res){

        if(!res.status)
            return;

        $("#empPhoto").attr("src",res.photo);
        $("#modalTitle").html(res.title);
        $("#empName").html(res.name);
        $("#modalMessage").html(res.message);

        if(res.type=="anniversary")
        {
            $("#yearsText").html("🏆 "+res.years+" Years");
        }
        else
        {
            $("#yearsText").html("");
        }

        $("#celebrationModal").modal('show');

        

        localStorage.setItem('celebration',today);

    });

});
$('#celebrationModal').on('shown.bs.modal', function () {

    const modal = document.querySelector("#celebrationModal .modal-content");

    party.confetti(modal, {
        count: party.variation.range(150, 250),
        spread: 120,
        speed: party.variation.range(500, 900),
        size: party.variation.range(0.8, 1.5),
    });

});

function celebrationBlast(){

    // Left cannon
    confetti({
        particleCount:180,
        angle:60,
        spread:90,
        origin:{x:0,y:0.8}
    });

    // Right cannon
    confetti({
        particleCount:180,
        angle:120,
        spread:90,
        origin:{x:1,y:0.8}
    });

    // Top rain
    setTimeout(function(){

        confetti({
            particleCount:250,
            spread:360,
            origin:{x:0.5,y:0},
            gravity:0.8,
            scalar:1.4
        });

    },400);

}
$('#celebrationModal').on('shown.bs.modal', function () {
    celebrationBlast();
});

$('#pro_changePasswordBtn').click(function () {

    let employee_id = $('#pro_reset_employee_id').val();
    let password = $('#pro_new_password').val();
    let confirmPassword = $('#pro_confirm_password').val();

    if (password == '') {

        Swal.fire({
            icon: 'warning',
            title: 'Warning',
            text: 'Please enter new password.'
        });

        return;
    }

    if (password != confirmPassword) {

        Swal.fire({
            icon: 'error',
            title: 'Password Mismatch',
            text: 'Both passwords must be the same.'
        });

        return;
    }

    Swal.fire({

        title: 'Are you sure?',
        text: 'Do you want to change your password?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, Change Password',
        cancelButtonText: 'Cancel'

    }).then((result) => {

        if (result.isConfirmed) {

            $.ajax({

                url: '/employee/change-password/' + employee_id,
                type: 'POST',

                data: {

                    password: password,
                    _token: $('meta[name="csrf-token"]').attr('content')

                },

                success: function (response) {

                    Swal.fire({

                        icon: 'success',
                        title: 'Success',
                        text: response.message

                    });

                    $('#new_password').val('');
                    $('#confirm_password').val('');

                },

                error: function () {

                    Swal.fire({

                        icon: 'error',
                        title: 'Error',
                        text: 'Unable to change password.'

                    });

                }

            });

        }

    });

});
$('#pro_changePhotoBtn').click(function () {

    $('#pro_profile_photo').click();

});
// Upload automatically after selecting image
$('#pro_profile_photo').change(function () {

    let file = this.files[0];

    if (!file) return;

    // Preview image
    let reader = new FileReader();

    reader.onload = function (e) {

        $('#pro_edit_profile_image').attr('src', e.target.result);

    }

    reader.readAsDataURL(file);


    // Upload

    let formData = new FormData();

    formData.append('photo', file);
    formData.append('id', $('#pro_reset_employee_id').val());
    formData.append('_token', $('meta[name="csrf-token"]').attr('content'));

    $.ajax({

        url: "{{ route('employee.update.photo') }}",

        type: "POST",

        data: formData,

        processData: false,

        contentType: false,

        beforeSend:function(){

            Swal.fire({
                title:'Uploading...',
                allowOutsideClick:false,
                didOpen:()=>{
                    Swal.showLoading();
                }
            });

        },

        success:function(res){

            Swal.fire({
                icon:'success',
                title:'Success',
                text:res.message,
                timer:1500,
                showConfirmButton:false
            });

            // $('#employeeTable').DataTable().ajax.reload(null,false);

        },

        error:function(xhr){

            Swal.fire({
                icon:'error',
                title:'Error',
                text:xhr.responseJSON?.message ?? 'Unable to upload image.'
            });

        }

    });

});

$(document).on('click', '#proSaveBank', function(e) {
    e.preventDefault();

    let formData = new FormData($('#pro_bankForm')[0]);

    formData.append('employee_id', $('#pro_edit_employee_id').val()); // Hidden field

    $.ajax({
        url: "{{ route('employee.save.bank') }}",
        type: "POST",
        data: formData,
        processData: false,
        contentType: false,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        beforeSend: function () {
            $('#proSaveBank').prop('disabled', true).text('Saving...');
        },
        success: function(response) {

            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: response.message
            });

            $('#proSaveBank').prop('disabled', false).text('Save Bank');

        },
        error: function(xhr) {

            $('#proSaveBank').prop('disabled', false).text('Save Bank');

            if (xhr.status == 422) {

                let errors = xhr.responseJSON.errors;
                let msg = '';

                $.each(errors, function(key, value){
                    msg += value[0] + '<br>';
                });

                Swal.fire({
                    icon: 'error',
                    title: 'Validation Error',
                    html: msg
                });

            } else {

                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Something went wrong.'
                });

            }
        }
    });

});

</script>

    <!--end::Script-->
  </body>
  <!--end::Body-->
</html>
