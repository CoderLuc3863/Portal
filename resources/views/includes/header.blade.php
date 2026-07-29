<!doctype html>
<html lang="en">
  <!--begin::Head-->
  <!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>@yield('title', 'Dashboard')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- AdminLTE CSS -->
    <link rel="stylesheet" href="{{ asset('css/adminlte.css') }}">
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <!-- OverlayScrollbars -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.11.0/styles/overlayscrollbars.min.css">
    <!-- ApexCharts -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/apexcharts@3.37.1/dist/apexcharts.css">
    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.9.2/dist/confetti.browser.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/party-js@latest/bundle/party.min.js"></script>
    @stack('styles')
</head>


  <body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
    <!--begin::App Wrapper-->
    <div class="app-wrapper">
      <!--begin::Header-->
      <nav class="app-header navbar navbar-expand bg-body">
        <!--begin::Container-->
        <div class="container-fluid">
          <!--begin::Start Navbar Links-->
          <ul class="navbar-nav">
            <li class="nav-item">
              <a class="nav-link" data-lte-toggle="sidebar" href="#" role="button">
                <i class="bi bi-list"></i>
              </a>
            </li>
            <li class="nav-item d-none d-md-block">
              <a href="{{ route('dashboard') }}" class="nav-link">Home</a>
            </li>
           
          </ul>
          <!--end::Start Navbar Links-->

          <!--begin::End Navbar Links-->
          <ul class="navbar-nav ms-auto">
            

            <!--begin::Messages Dropdown Menu remove s-->
            
            <li class="nav-item dropdown d-none">
              <a class="nav-link" data-bs-toggle="dropdown" href="#">
                <i class="bi bi-backpack4-fill"></i>
                <span class="navbar-badge badge text-bg-danger">3</span>
              </a>
              <div class="d-none dropdown-menu dropdown-menu-lg dropdown-menu-end">
                <a href="#" class="dropdown-item">
                  <!--begin::Message-->
                  <div class="d-flex">
                    <div class="flex-shrink-0">
                      <img
                        src="./assets/img/user1-128x128.jpg"
                        alt="User Avatar"
                        class="img-size-50 rounded-circle me-3"
                      />
                    </div>
                    <div class="flex-grow-1">
                      <h3 class="dropdown-item-title">
                        Brad Diesel
                        <span class="float-end fs-7 text-danger"
                          ><i class="bi bi-star-fill"></i
                        ></span>
                      </h3>
                      <p class="fs-7">Call me whenever you can...</p>
                      <p class="fs-7 text-secondary">
                        <i class="bi bi-clock-fill me-1"></i> 4 Hours Ago
                      </p>
                    </div>
                  </div>
                  <!--end::Message-->
                </a>
                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item">
                  <!--begin::Message-->
                  <div class="d-flex">
                    <div class="flex-shrink-0">
                      <img
                        src="./assets/img/user8-128x128.jpg"
                        alt="User Avatar"
                        class="img-size-50 rounded-circle me-3"
                      />
                    </div>
                    <div class="flex-grow-1">
                      <h3 class="dropdown-item-title">
                        John Pierce
                        <span class="float-end fs-7 text-secondary">
                          <i class="bi bi-star-fill"></i>
                        </span>
                      </h3>
                      <p class="fs-7">I got your message bro</p>
                      <p class="fs-7 text-secondary">
                        <i class="bi bi-clock-fill me-1"></i> 4 Hours Ago
                      </p>
                    </div>
                  </div>
                  <!--end::Message-->
                </a>
                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item">
                  <!--begin::Message-->
                  <div class="d-flex">
                    <div class="flex-shrink-0">
                      <img
                        src="./assets/img/user3-128x128.jpg"
                        alt="User Avatar"
                        class="img-size-50 rounded-circle me-3"
                      />
                    </div>
                    <div class="flex-grow-1">
                      <h3 class="dropdown-item-title">
                        Nora Silvester
                        <span class="float-end fs-7 text-warning">
                          <i class="bi bi-star-fill"></i>
                        </span>
                      </h3>
                      <p class="fs-7">The subject goes here</p>
                      <p class="fs-7 text-secondary">
                        <i class="bi bi-clock-fill me-1"></i> 4 Hours Ago
                      </p>
                    </div>
                  </div>
                  <!--end::Message-->
                </a>
                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item dropdown-footer">See All Messages</a>
              </div>
            </li>
            <!--end::Messages Dropdown Menu-->

            <!--begin::Notifications Dropdown Menu  remove s-->
            <li class="nav-item dropdowns d-none">
              <a class="nav-link" data-bs-toggle="dropdown" href="#">
                <i class="bi bi-bell-fill"></i>
                <span class="navbar-badge badge text-bg-warning">15</span>
              </a>
              <div class="d-none dropdown-menu dropdown-menu-lg dropdown-menu-end">
                <span class="dropdown-item dropdown-header">15 Notifications</span>
                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item">
                  <i class="bi bi-envelope me-2"></i> 4 new messages
                  <span class="float-end text-secondary fs-7">3 mins</span>
                </a>
                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item">
                  <i class="bi bi-people-fill me-2"></i> 8 friend requests
                  <span class="float-end text-secondary fs-7">12 hours</span>
                </a>
                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item">
                  <i class="bi bi-file-earmark-fill me-2"></i> 3 new reports
                  <span class="float-end text-secondary fs-7">2 days</span>
                </a>
                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item dropdown-footer"> See All Notifications </a>
              </div>
            </li>
            <!--end::Notifications Dropdown Menu-->

            <!--begin::Fullscreen Toggle-->
            <li class="nav-item">
              <a class="nav-link" href="#" data-lte-toggle="fullscreen">
                <i data-lte-icon="maximize" class="bi bi-arrows-fullscreen"></i>
                <i data-lte-icon="minimize" class="bi bi-fullscreen-exit" style="display: none"></i>
              </a>
            </li>
            <!--end::Fullscreen Toggle-->
            
            <!--begin::User Menu Dropdown-->
            <li class="nav-item dropdown user-menu">
              <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
               <img
                src="{{ session('user_photo') ? asset('storage/employees/photos/' . session('user_photo')) : asset('assets/img/user.png') }}"
                class="user-image rounded-circle shadow"
                alt="User Image"
            />
                <span class="d-none d-md-inline">{{ session('user_name') }}</span>
              </a>
              <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
                <!--begin::User Image-->
                <li class="user-header">
                  <img
                    src="{{ session('user_photo') ? asset('storage/employees/photos/' . session('user_photo')) : asset('assets/img/user.png') }}"
                    class="rounded-circle shadow"
                    alt="User Image"
                  />
                  <p>
                    Name: {{ session('user_name') }} <br>
                    Designation:  {{ session('designation') }}
                    {{-- <small>Member since Nov. 2023</small> --}}
                  </p>
                </li>
                <!--end::User Image-->
                
                <!--begin::Menu Footer-->
                <li class="user-footer d-flex align-items-center w-100">

                  <button class="btn btn-outline-secondary"  
                            @if(session('onboard_status') == 'Completed')
                                onclick="viewProEmployee({{ session('id') }})"
                            @else
                                onclick="editProEmployee({{ session('id') }})"
                            @endif>
                      Profile
                  </button>

                  <form method="POST" action="{{ route('logout') }}" class="ms-auto">
                      @csrf

                      <button type="submit" class="btn btn-outline-danger">
                          Sign out
                      </button>
                  </form>

              </li>
                <!--end::Menu Footer-->
              </ul>
            </li>
            <!--end::User Menu Dropdown-->
          </ul>
          <!--end::End Navbar Links-->
        </div>
        <!--end::Container-->
      </nav>
      <!--end::Header-->



 <!-- View Profile Modal -->
<div class="modal fade"
     id="pro_employeeProfileModal"
     tabindex="-1"
     aria-hidden="true">

    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content border-0 rounded-4">

            <!-- Header -->
            <div class="modal-header border-0">

                <div class="d-flex align-items-center gap-3">

                    <img src="{{ asset('assets/img/user.png') }}"
                         id="pro_employee_profile_image"
                         class="rounded-circle"
                         width="70"
                         height="70">

                    <div>
                        <h5 class="fw-bold mb-1" id="pro_employee_name"></h5>
                        <div class="text-muted small" id="pro_employee_designation"></div>
                        <div class="text-muted small" id="pro_employee_code"></div>
                    </div>

                </div>

                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"></button>

            </div>

            <div class="modal-body">

                <hr>

                <ul class="nav nav-pills mb-4 gap-2"
                    id="pro_employeeProfileTabs"
                    role="tablist">

                    <li class="nav-item">
                        <button class="nav-link active"
                                data-bs-toggle="pill"
                                data-bs-target="#pro_employeeProfileInfo">
                            Profile
                        </button>
                    </li>

                    <li class="nav-item">
                        <button class="nav-link"
                                data-bs-toggle="pill"
                                data-bs-target="#pro_employeeOfficialInfo">
                            Official Information
                        </button>
                    </li>

                    <li class="nav-item">
                        <button class="nav-link"
                                data-bs-toggle="pill"
                                data-bs-target="#pro_employeeIdentityInfo">
                            Documents
                        </button>
                    </li>

                    <li class="nav-item">
                        <button class="nav-link"
                                data-bs-toggle="pill"
                                data-bs-target="#pro_employeeEducationInfo">
                            Educational Information
                        </button>
                    </li>

                    <li class="nav-item">
                        <button class="nav-link"
                                data-bs-toggle="pill"
                                data-bs-target="#pro_employeeBankInfo">
                            Banking Details
                        </button>
                    </li>

                    <li class="nav-item">
                        <button class="nav-link"
                                data-bs-toggle="pill"
                                data-bs-target="#pro_employeeExperienceInfo">
                            Experience Details
                        </button>
                    </li>
                     <li class="nav-item">
                        <button class="nav-link"
                                data-bs-toggle="pill"
                                data-bs-target="#resetPassword">
                            Password Setting
                        </button>
                    </li>


                </ul>

                <div class="tab-content">

                    <div class="tab-pane fade show active" id="pro_employeeProfileInfo">

                        <h5 class="mb-4">Profile Information</h5>

                        <div class="row">

                            <!-- Left -->
                            <div class="col-md-5">

                                <div class="mb-4">
                                    <small class="text-muted d-block">Personal Email</small>
                                    <span id="pro_profile_email" class="fw-semibold">-</span>
                                </div>

                                <div class="mb-4">
                                    <small class="text-muted d-block">Phone</small>
                                    <span id="pro_profile_phone" class="fw-semibold">-</span>
                                </div>

                                <div class="mb-4">
                                    <small class="text-muted d-block">Gender</small>
                                    <span id="pro_profile_gender" class="fw-semibold">-</span>
                                </div>

                                <div class="mb-4">
                                    <small class="text-muted d-block">Blood Group</small>
                                    <span id="pro_profile_blood_group" class="fw-semibold">-</span>
                                </div>

                                <div class="mb-4">
                                    <small class="text-muted d-block">Nationality</small>
                                    <span id="pro_profile_nationality" class="fw-semibold">-</span>
                                </div>

                            </div>

                            <!-- Right -->
                            <div class="col-md-7">

                                <div class="mb-4">
                                    <small class="text-muted d-block">Date of Birth</small>
                                    <span id="pro_profile_dob" class="fw-semibold">-</span>
                                </div>

                                <div class="mb-4">
                                    <small class="text-muted d-block">Emergency Phone</small>
                                    <span id="pro_profile_emergency_phone" class="fw-semibold">-</span>
                                </div>

                                <div class="mb-4">
                                    <small class="text-muted d-block">Marital Status</small>
                                    <span id="pro_profile_marital_status" class="fw-semibold">-</span>
                                </div>

                                <div class="mb-4">
                                    <small class="text-muted d-block">Father's/Husband's Name</small>
                                    <span id="pro_profile_parent_name" class="fw-semibold">-</span>
                                </div>

                                <div class="mb-4">
                                    <small class="text-muted d-block">Address</small>
                                    <span id="pro_profile_address" class="fw-semibold">-</span>
                                </div>

                            </div>

                        </div>

                    </div>

                    <div class="tab-pane fade" id="pro_employeeOfficialInfo">

                        <h5 class="mb-4">Official Information</h5>

                        <div class="row">

                            <div class="col-md-6 mb-3">
                                <small class="text-muted">Employee ID</small>
                                <div id="view_pro_emp_id" class="fw-semibold"></div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <small class="text-muted">Official Email</small>
                                <div id="view_pro_email" class="fw-semibold"></div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <small class="text-muted">Department</small>
                                <div id="view_pro_department" class="fw-semibold"></div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <small class="text-muted">Designation</small>
                                <div id="view_pro_designation" class="fw-semibold"></div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <small class="text-muted">Reporting Manager</small>
                                <div id="view_pro_reporting_manager" class="fw-semibold"></div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <small class="text-muted">Date of Joining</small>
                                <div id="view_pro_joining_date" class="fw-semibold"></div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <small class="text-muted">Employment Type</small>
                                <div id="view_pro_job_type" class="fw-semibold"></div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <small class="text-muted">Work Mode</small>
                                <div id="view_pro_job_location" class="fw-semibold"></div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <small class="text-muted">Work Location</small>
                                <div id="view_pro_work_location" class="fw-semibold"></div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <small class="text-muted">Employee Status</small>
                                <div id="view_pro_status" class="fw-semibold"></div>
                            </div>

                        </div>

                    </div>

                    <div class="tab-pane fade" id="pro_employeeIdentityInfo">

                        <h5 class="mb-4">Documents and ID Cards</h5>

                        <div class="row">
                            <div class="col-2 mb-4">
                                <small class="text-muted d-block">Aadhaar Number</small>
                                <span id="pro_identity_aadhar" class="fw-semibold">-</span>
                            </div>
                            <div class="col-4 mb-4" id="pro_doc_aadhar"></div>

                            <div class="col-2 mb-4">
                                <small class="text-muted d-block">PAN Number</small>
                                <span id="pro_identity_pan" class="fw-semibold">-</span>
                            </div>
                            <div class="col-4 mb-4" id="pro_doc_pan"></div>
                            
                            <div class="col-2 mb-4">
                                <small class="text-muted d-block">Passport Number</small>
                                <span id="pro_identity_passport" class="fw-semibold">-</span>
                            </div>
                            <div class="col-4 mb-4" id="pro_doc_passport" class="mt-3"></div>
                            
                            <div class="col-2 mb-4">
                                <small class="text-muted d-block">UAN (Universal Account Number)</small>
                                <span id="pro_identity_uan" class="fw-semibold">-</span>
                            </div>
                            <div class="col-4 mb-4" id="pro_doc_passbook" class="mt-3"></div>

                            <div class="col-2 mb-4">
                                <small class="text-muted d-block">Insurance Number</small>
                                <span id="pro_identity_insurance" class="fw-semibold">-</span>
                            </div>
                            <div class="col-4 mb-4" id="pro_doc_insurance" class="mt-3"></div>

                            <div class="col-2 mb-4">
                                <small class="text-muted d-block">Resume</small>
                            </div>
                            <div class="col-4 mb-4" id="pro_doc_resume" class="mt-3"></div>


                        </div>


                    </div>

                    <div class="tab-pane fade" id="pro_employeeEducationInfo">

                        <h5 class="mb-4">Educational Information</h5>

                        <div class="table-responsive">
                            <table class="table table-striped align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Qualification</th>
                                        <th>University / Board</th>
                                        <th>Year of Passing</th>
                                        <th>Percentage / CGPA</th>
                                        <th>Attachement</th>
                                    </tr>
                                </thead>
                                <tbody id="pro_educationTableBody">
                                    <tr>
                                        <td colspan="5" class="text-center text-muted">
                                            No education details found.
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                    </div>
                    <div class="tab-pane fade" id="pro_employeeBankInfo">

                        <h5 class="mb-4">Banking Details</h5>

                        <div class="row">

                            <div class="col-md-5">

                                <div class="mb-4">

                                    <small class="text-muted d-block">
                                        Bank Account Number
                                    </small>

                                    <span id="pro_bank_account_no" class="fw-semibold"></span>

                                </div>

                                <div class="mb-4">

                                    <small class="text-muted d-block">
                                        Bank Name
                                    </small>

                                    <span id="pro_bank_name" class="fw-semibold"></span>

                                </div>

                            </div>

                            <div class="col-md-7">

                                <div class="mb-4">

                                    <small class="text-muted d-block">
                                        IFSC Code
                                    </small>

                                    <span id="pro_bank_ifsc" class="fw-semibold"></span>

                                </div>

                                <div class="mb-4">

                                    <small class="text-muted d-block">
                                        Branch
                                    </small>

                                    <span id="pro_bank_branch" class="fw-semibold"></span>

                                </div>

                            </div>
                            <div class="col-md-7">

                                <div class="mb-4">

                                    <small class="text-muted d-block">
                                        Passbook
                                    </small>

                                    <div id="pro_passbook" class="fw-semibold"></div>

                                </div>

                                

                            </div>

                        </div>

                    </div>
                    <div class="tab-pane fade" id="pro_employeeExperienceInfo">

                        <h5 class="mb-4">Experience Details</h5>
                        <div class="table-responsive">
                            <table class="table table-striped align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Company Name</th>
                                        <th>Job Role</th>
                                        <th>Year of Experience</th>
                                        <th>Certificate</th>
                                    </tr>
                                </thead>
                                <tbody id="pro_experienceTableBody">
                                    <tr>
                                        <td colspan="4" class="text-center text-muted">
                                            No experience details found.
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="tab-pane fade"
                         id="resetPassword">

                        <input type="hidden" id="pro_reset_employee_id" value="{{ session('id') }}">

                        <div class="row">

                            <div class="col-md-6 mb-3">
                                <label class="form-label">New Password</label>

                                <div class="input-group">
                                    <input type="password"
                                        class="form-control"
                                        id="pro_new_password"
                                        placeholder="Enter new password">

                                    <button class="btn btn-outline-secondary"
                                            type="button"
                                            onclick="togglePassword('pro_new_password', this)">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Re-enter New Password</label>

                                <div class="input-group">
                                    <input type="password"
                                        class="form-control"
                                        id="pro_confirm_password"
                                        placeholder="Re-enter new password">

                                    <button class="btn btn-outline-secondary"
                                            type="button"
                                            onclick="togglePassword('pro_confirm_password', this)">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </div>
                            </div>

                        </div>

                        <div class="d-flex justify-content-end mt-4">
                            <button type="button"
                                    class="btn btn-light"
                                    data-bs-dismiss="modal">
                                Cancel
                            </button>
                            &nbsp;
                            <button type="button"
                                    class="btn btn-primary"
                                    id="pro_changePasswordBtn">
                                Save Changes
                            </button>
                        </div>

                    </div>

                </div>

            </div>

        </div>
    </div>

</div>


<!-- Edit Profile Modal -->
<div class="modal fade" id="pro_employeeEditModal" tabindex="-1" aria-hidden="true">

    <div class="modal-dialog modal-xl modal-dialog-scrollable">

        <div class="modal-content">

            <!-- Header -->
            <div class="modal-header">

                <div class="d-flex align-items-center">

                    <div class="position-relative me-3">

                        <img src="{{ asset('assets/img/user.png') }}"
                            id="pro_edit_profile_image"
                            class="rounded-circle border"
                            width="80"
                            height="80"
                            style="object-fit:cover;">

                        <button type="button"
                                class="btn btn-primary btn-sm rounded-circle position-absolute"
                                id="pro_changePhotoBtn"
                                style="bottom:0;right:0;width:30px;height:30px;padding:0;">
                            <i class="bi bi-pencil"></i>
                        </button>

                        <input type="file"
                            id="pro_profile_photo"
                            accept="image/*"
                            hidden>

                    </div>

                    <div>

                        <h5 class="mb-0 fw-bold">
                            Edit Employee
                        </h5>

                        <small id="pro_edit_employee_name"></small>

                    </div>

                </div>

                <button class="btn-close"
                        data-bs-dismiss="modal">
                </button>

            </div>

            <div class="modal-body">

                <input type="hidden"
                       id="pro_edit_employee_id">

                <!-- Tabs -->

                <ul class="nav nav-pills mb-4">

                    <li class="nav-item">
                        <button class="nav-link active"
                                data-bs-toggle="pill"
                                data-bs-target="#pro_editProfile">
                            Profile
                        </button>
                    </li>

                    <li class="nav-item">
                        <button class="nav-link"
                                data-bs-toggle="pill"
                                data-bs-target="#pro_editOfficial">
                            Official
                        </button>
                    </li>

                    <li class="nav-item">
                        <button class="nav-link"
                                data-bs-toggle="pill"
                                data-bs-target="#pro_editDocuments">
                            Documents
                        </button>
                    </li>

                    <li class="nav-item">
                        <button class="nav-link"
                                data-bs-toggle="pill"
                                data-bs-target="#pro_editEducation">
                            Education
                        </button>
                    </li>

                    <li class="nav-item">
                        <button class="nav-link"
                                data-bs-toggle="pill"
                                data-bs-target="#pro_editBank">
                            Banking
                        </button>
                    </li>

                    <li class="nav-item">
                        <button class="nav-link"
                                data-bs-toggle="pill"
                                data-bs-target="#pro_editExperience">
                            Experience
                        </button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link"
                                data-bs-toggle="pill"
                                data-bs-target="#pro_resetPassword">
                            Password Setting
                        </button>
                    </li>

                </ul>

                <div class="tab-content">

                    <!-- ====================== -->
                    <!-- Profile -->
                    <!-- ====================== -->

                    <div class="tab-pane fade show active"
                         id="pro_editProfile">

                        <form id="pro_profileForm">

                            <input type="hidden" name="id" id="pro_profile_id">

                            <div class="row">

                                <!-- Left Column -->
                                <div class="col-md-6">

                                    <div class="mb-3">
                                        <label class="form-label">Full Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="pro_edit_name" name="name" placeholder="Enter full name">
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Date of Birth</label>
                                        <input type="date" class="form-control" id="pro_edit_dob" name="dob">
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Emergency Phone</label>
                                        <input type="text" class="form-control" id="pro_edit_emergency_phone" name="emergency_phone" placeholder="Enter emergency phone number">
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Marital Status</label>
                                        <select class="form-select" id="pro_edit_marital_status" name="marital_status">
                                            <option value="">select</option>
                                            <option value="Single">Single</option>
                                            <option value="Married">Married</option>
                                            <option value="Divorced">Divorced</option>
                                            <option value="Widowed">Widowed</option>
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Father's / Husband's Name</label>
                                        <input type="text" class="form-control" id="pro_edit_guardian_name" name="guardian_name" placeholder="Enter father's / husband's name">
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Address</label>
                                        <textarea class="form-control" id="pro_edit_address" name="address" rows="3" placeholder="Enter address"></textarea>
                                    </div>

                                </div>

                                <!-- Right Column -->
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Employee ID <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="pro_edit_emp_id" name="emp_id" placeholder="Enter ID">
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Personal Email <span class="text-danger">*</span></label>
                                        <input type="email" class="form-control" id="pro_edit_personal_email" name="email" placeholder="Enter personal email address">
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Phone</label>
                                        <input type="text" class="form-control" id="pro_edit_phone" name="phone" placeholder="Enter phone number">
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Gender</label>
                                        <select class="form-select" id="pro_edit_gender" name="gender">
                                            <option value="">Select gender</option>
                                            <option value="Male">Male</option>
                                            <option value="Female">Female</option>
                                            <option value="Other">Other</option>
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Blood Group</label>
                                        <select class="form-select" id="pro_edit_blood_group" name="blood_group">
                                            <option value="">Select blood group</option>
                                            <option value="A+">A+</option>
                                            <option value="A-">A-</option>
                                            <option value="B+">B+</option>
                                            <option value="B-">B-</option>
                                            <option value="AB+">AB+</option>
                                            <option value="AB-">AB-</option>
                                            <option value="O+">O+</option>
                                            <option value="O-">O-</option>
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Nationality</label>
                                        <input type="text"
                                            class="form-control"
                                            id="pro_edit_nationality"
                                            name="nationality"
                                            placeholder="Enter nationality">
                                    </div>

                                </div>

                            </div>

                            <div class="d-flex justify-content-end mt-4">

                                <button type="button"
                                        class="btn btn-light me-2"
                                        data-bs-dismiss="modal">
                                    Cancel
                                </button>

                                <button type="submit"
                                        class="btn btn-primary" id="pro_save">
                                    Save Changes
                                </button>

                            </div>

                        </form>

                    </div>

                    <!-- ====================== -->
                    <!-- Official -->
                    <!-- ====================== -->

                    <div class="tab-pane fade"
                         id="pro_editOfficial">

                        <div class="row">

                            <div class="col-md-6 mb-3">
                                <small class="text-muted">Employee ID</small>
                                <div id="pro_view_emp_id" class="fw-semibold"></div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <small class="text-muted">Official Email</small>
                                <div id="pro_view_email" class="fw-semibold"></div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <small class="text-muted">Department</small>
                                <div id="pro_view_department" class="fw-semibold"></div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <small class="text-muted">Designation</small>
                                <div id="pro_view_designation" class="fw-semibold"></div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <small class="text-muted">Reporting Manager</small>
                                <div id="pro_view_reporting_manager" class="fw-semibold"></div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <small class="text-muted">Date of Joining</small>
                                <div id="pro_view_joining_date" class="fw-semibold"></div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <small class="text-muted">Employment Type</small>
                                <div id="pro_view_job_type" class="fw-semibold"></div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <small class="text-muted">Work Mode</small>
                                <div id="pro_view_job_location" class="fw-semibold"></div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <small class="text-muted">Work Location</small>
                                <div id="pro_view_work_location" class="fw-semibold"></div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <small class="text-muted">Employee Status</small>
                                <div id="pro_view_status" class="fw-semibold"></div>
                            </div>

                        </div>

                    </div>

                    <!-- ====================== -->
                    <!-- Documents -->
                    <!-- ====================== -->

                    <div class="tab-pane fade"
                         id="pro_editDocuments">

                        <form id="pro_documentForm"
                              enctype="multipart/form-data">

                            <div class="row">

                                <div class="col-md-6 mb-3">

                                    <label>Aadhaar</label>

                                    <input type="file"
                                           class="form-control"
                                           name="aadhar">

                                </div>

                                <div class="col-md-6 mb-3">

                                    <label>PAN</label>

                                    <input type="file"
                                           class="form-control"
                                           name="pan">

                                </div>

                            </div>

                            <div class="text-end">

                                <button class="btn btn-primary">

                                    Save Documents

                                </button>

                            </div>

                        </form>

                    </div>

                    <!-- Education -->
                    <div class="tab-pane fade"
                         id="pro_editEducation">
                        <div class="text-end">
                        <button class="btn btn-success mb-2" id="addEducationRow">
                            Add Qualification
                        </button>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-striped align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Qualification</th>
                                        <th>University / Board</th>
                                        <th>Year of Passing</th>
                                        <th>Percentage / CGPA</th>
                                        <th>Attachement</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody id="pro_edit_educationTableBody">
                                    <tr id="noeducation">
                                        <td colspan="6" class="text-center text-muted">
                                            No education details found.
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        
                        <div class="text-start">
                        <button class="btn btn-primary" id="saveEducation">
                            Save Education
                        </button>
                        </div>

                    </div>

                    <!-- Bank -->

                    <div class="tab-pane fade"
                         id="pro_editBank">

                        <form id="pro_bankForm">
                            <div class="row">                               
                                <div class="col-md-6 mb-3">
                                    <label>Account No</label>
                                    <input type="text"
                                           class="form-control"
                                           name="account_no"
                                           id="pro_edit_account_no">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label>IFSC</label>
                                    <input type="text"
                                           class="form-control"
                                           name="ifsc"
                                           id="pro_edit_ifsc">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label>Bank Name</label>
                                    <input type="text"
                                           class="form-control"
                                           name="bank_name"
                                           id="pro_edit_bank_name">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label>Branch</label>
                                    <input type="text"
                                           class="form-control"
                                           name="branch"
                                           id="pro_edit_branch">
                                </div>
                                <div class="col-md-6">
                                    <label>Passbook</label>
                                    <input type="file"
                                           class="form-control"
                                           name="passbook"
                                           id="pro_edit_passbook">
                                </div>
                                <div class="col-md-6 d-flex align-items-end" id="edit_passbook">
                                </div>
                                
                            </div>

                            <div class="text-end mt-3">
                                <button class="btn btn-primary" id="proSaveBank">
                                    Save Bank
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Experience -->

                    <div class="tab-pane fade"
                         id="pro_editExperience">

                        <div class="text-end">
                        <button class="btn btn-success mb-2" id="addExperienceRow">
                            Add Experience
                        </button>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-striped align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Company Name</th>
                                        <th>Job Role</th>
                                        <th>Year of Experience</th>
                                        <th>Certificate</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody id="pro_edit_experienceTableBody">
                                    <tr id="noexperience">
                                        <td colspan="6" class="text-center text-muted">
                                            No experience details found.
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        
                        <div class="text-start">
                        <button class="btn btn-primary" id="saveExperience">
                            Save Experience
                        </button>
                        </div>

                    </div>

                    <!--Setting-->
                     <div class="tab-pane fade"
                         id="pro_resetPassword">

                        <input type="hidden" id="reset_employee_id">

                        <div class="row">

                            <div class="col-md-6 mb-3">
                                <label class="form-label">New Password</label>

                                <div class="input-group">
                                    <input type="password"
                                        class="form-control"
                                        id="new_password"
                                        placeholder="Enter new password">

                                    <button class="btn btn-outline-secondary"
                                            type="button"
                                            onclick="togglePassword('new_password', this)">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Re-enter New Password</label>

                                <div class="input-group">
                                    <input type="password"
                                        class="form-control"
                                        id="confirm_password"
                                        placeholder="Re-enter new password">

                                    <button class="btn btn-outline-secondary"
                                            type="button"
                                            onclick="togglePassword('confirm_password', this)">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </div>
                            </div>

                        </div>

                        <div class="d-flex justify-content-end mt-4">
                            <button type="button"
                                    class="btn btn-light"
                                    data-bs-dismiss="modal">
                                Cancel
                            </button>
                            &nbsp;
                            <button type="button"
                                    class="btn btn-primary"
                                    id="changePasswordBtn">
                                Save Changes
                            </button>
                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

<!--Attachement view Modal-->
<div class="modal fade" id="pro_attachmentViewerModal" tabindex="-1">

    <div class="modal-dialog modal-xl modal-dialog-centered">

        <div class="modal-content">

            <div class="modal-header">

                <h5 class="modal-title">
                    Attachment Preview
                </h5>

                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="modal">
                </button>

            </div>

            <div class="modal-body text-center">

                <img id="pro_attachmentImage"
                     class="img-fluid d-none"
                     style="max-height:75vh;">

                <iframe id="pro_attachmentPdf"
                        class="d-none"
                        width="100%"
                        height="700"
                        frameborder="0">
                </iframe>

            </div>

            <div class="modal-footer">

                <a id="pro_attachmentDownload"
                   href="#"
                   download
                   class="btn btn-success">

                    <i class="bi bi-download"></i>
                    Download

                </a>

                <button class="btn btn-secondary"
                        data-bs-dismiss="modal">

                    Close

                </button>

            </div>

        </div>

    </div>

</div>
