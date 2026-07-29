<div class="content-wrapper p-3">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h3 class="fw-bold mb-1">Employee Directory</h3>
        </div>
       <div>
            <button class="btn btn-success" id="exportBtn">
                <i class="bi bi-file-earmark-excel me-1"></i>
                Export
            </button>
            {{-- <button class="btn btn-primary">
                <i class="bi bi-plus-lg me-1"></i>
                Add Employee
            </button>  --}}
        </div> 

    </div>
    <div class="pb-3 mb-3 border-bottom ">
        <div class="card-body">

            <div class="row g-3 align-items-end">

                <!-- Onboarding Status -->
                <div class="col-lg-2 col-md-3 col-sm-6">
                    <label class="form-label">Status</label>
                    <select class="form-select" id="filter_status">
                        <option value="" selected>Select</option>
                        <option value="1">Active</option>
                        <option value="0">Inactive</option>
                    </select>
                </div>

                <!-- Type -->
                <div class="col-lg-2 col-md-3 col-sm-6">
                    <label class="form-label">Type</label>

                    <select class="form-select" id="filter_job_type" >
                        <option selected  value="" >Select type</option>
                        @foreach($jobTypes as $jobType)

                            <option value="{{ $jobType->type }}">
                                {{ $jobType->type }}
                            </option>

                        @endforeach
                    </select>
                </div>

                <!-- Department -->
                <div class="col-lg-2 col-md-3 col-sm-6">
                    <label class="form-label">Department</label>

                    <select id="filter_department"  class="form-select">
                        <option selected value="" >Select department</option>
                        @foreach($departments as $department)

                            <option value="{{ $department->id }}">

                                {{ $department->name }}

                            </option>

                        @endforeach
                    </select>
                </div>

                <!-- Designation -->
                <div class="col-lg-2 col-md-3 col-sm-6">
                    <label class="form-label">Designation</label>

                    <select class="form-select"  id="filter_designation">
                        <option  value=""  selected>Select designation</option>
                        @foreach($designations as $designation)

                            <option value="{{ $designation->id }}">

                                {{ $designation->name }}

                            </option>

                        @endforeach

                    </select>
                </div>

                <!-- Search Button -->
                <div class="col-lg-2 col-md-3 col-sm-6">
                    <button type="button" class="btn btn-primary w-100" id="searchBtn">
                        Search
                    </button>
                </div>

            </div>

        </div>
    </div>

    

    <div class="table-responsive">

       <table class="table table-striped table-hover align-middle w-100 data-table"
       id="employeeTable">

            <thead class="table-light">

                <tr>

                    <th>Sl No.</th>

                    <th>Employee Name</th>
                    
                    <th>Employee ID</th>

                    <th>Status</th>

                    <th>Job Type</th>

                    <th>Designation</th>

                    <th>Reporting Manager</th>

                    <th>Contact</th>

                    <th>Emergency Contact</th>

                    <th width="120">Action</th>

                </tr>

            </thead>

        </table>

    </div>

        

</div>

<!-- View Modal -->
<div class="modal fade"
     id="employeeProfileModal"
     tabindex="-1"
     aria-hidden="true">

    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content border-0 rounded-4">

            <!-- Header -->
            <div class="modal-header border-0">

                <div class="d-flex align-items-center gap-3">

                    <img src="{{ asset('assets/img/user.png') }}"
                         id="employee_profile_image"
                         class="rounded-circle"
                         width="70"
                         height="70">

                    <div>
                        <h5 class="fw-bold mb-1" id="employee_name"></h5>

                        <div class="text-muted small" id="employee_designation"></div>

                        <div class="text-muted small" id="employee_code"></div>
                    </div>

                </div>

                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"></button>

            </div>

            <div class="modal-body">

                <hr>
                 <ul class="nav nav-pills mb-4 gap-2"
                    id="employeeProfileTabs"
                    role="tablist">

                    <li class="nav-item">
                        <button class="nav-link active"
                                data-bs-toggle="pill"
                                data-bs-target="#employeeProfileInfo">
                            Profile Information
                        </button>
                    </li>

                    <li class="nav-item">
                        <button class="nav-link"
                                data-bs-toggle="pill"
                                data-bs-target="#employeeOfficialInfo">
                            Official Information
                        </button>
                    </li>

                    <li class="nav-item">
                        <button class="nav-link"
                                data-bs-toggle="pill"
                                data-bs-target="#employeeIdentityInfo">
                            Documents
                        </button>
                    </li>

                    <li class="nav-item">
                        <button class="nav-link"
                                data-bs-toggle="pill"
                                data-bs-target="#employeeEducationInfo">
                            Educational Information
                        </button>
                    </li>

                    <li class="nav-item">
                        <button class="nav-link"
                                data-bs-toggle="pill"
                                data-bs-target="#employeeBankInfo">
                            Banking Details
                        </button>
                    </li>

                    <li class="nav-item">
                        <button class="nav-link"
                                data-bs-toggle="pill"
                                data-bs-target="#employeeExperienceInfo">
                            Experience Detasils
                        </button>
                    </li>
                    


                </ul>

                <div class="tab-content">

                    <div class="tab-pane fade show active" id="employeeProfileInfo">

                        <h5 class="mb-4">Profile Information</h5>

                        <div class="row">

                            <!-- Left -->
                            <div class="col-md-5">

                                <div class="mb-4">
                                    <small class="text-muted d-block">Personal Email</small>
                                    <span id="profile_email" class="fw-semibold">-</span>
                                </div>

                                <div class="mb-4">
                                    <small class="text-muted d-block">Phone</small>
                                    <span id="profile_phone" class="fw-semibold">-</span>
                                </div>

                                <div class="mb-4">
                                    <small class="text-muted d-block">Gender</small>
                                    <span id="profile_gender" class="fw-semibold">-</span>
                                </div>

                                <div class="mb-4">
                                    <small class="text-muted d-block">Blood Group</small>
                                    <span id="profile_blood_group" class="fw-semibold">-</span>
                                </div>

                                <div class="mb-4">
                                    <small class="text-muted d-block">Nationality</small>
                                    <span id="profile_nationality" class="fw-semibold">-</span>
                                </div>

                            </div>

                            <!-- Right -->
                            <div class="col-md-7">

                                <div class="mb-4">
                                    <small class="text-muted d-block">Date of Birth</small>
                                    <span id="profile_dob" class="fw-semibold">-</span>
                                </div>

                                <div class="mb-4">
                                    <small class="text-muted d-block">Emergency Phone</small>
                                    <span id="profile_emergency_phone" class="fw-semibold">-</span>
                                </div>

                                <div class="mb-4">
                                    <small class="text-muted d-block">Marital Status</small>
                                    <span id="profile_marital_status" class="fw-semibold">-</span>
                                </div>

                                <div class="mb-4">
                                    <small class="text-muted d-block">Father's/Husband's Name</small>
                                    <span id="profile_parent_name" class="fw-semibold">-</span>
                                </div>

                                <div class="mb-4">
                                    <small class="text-muted d-block">Address</small>
                                    <span id="profile_address" class="fw-semibold">-</span>
                                </div>

                            </div>

                        </div>

                    </div>

                    <div class="tab-pane fade" id="employeeOfficialInfo">

                        <h5 class="mb-4">Official Information</h5>

                        <div class="row">

                            <!-- Left -->
                            <div class="col-md-5">

                                <div class="mb-4">
                                    <small class="text-muted d-block">Employee ID</small>
                                    <span id="official_emp_id" class="fw-semibold">-</span>
                                </div>

                                <div class="mb-4">
                                    <small class="text-muted d-block">Department</small>
                                    <span id="official_department" class="fw-semibold">-</span>
                                </div>

                                <div class="mb-4">
                                    <small class="text-muted d-block">Reporting Manager</small>
                                    <span id="official_reporting_manager" class="fw-semibold">-</span>
                                </div>

                                <div class="mb-4">
                                    <small class="text-muted d-block">Employment Type</small>
                                    <span id="official_employment_type" class="fw-semibold">-</span>
                                </div>

                                <div class="mb-4">
                                    <small class="text-muted d-block">Employee Status</small>
                                    <span id="employee_status" class="fw-semibold">-</span>
                                </div>

                            </div>

                            <!-- Right -->
                            <div class="col-md-7">

                                <div class="mb-4">
                                    <small class="text-muted d-block">Official Email</small>
                                    <span id="official_email" class="fw-semibold">-</span>
                                </div>

                                <div class="mb-4">
                                    <small class="text-muted d-block">Designation</small>
                                    <span id="official_designation" class="fw-semibold">-</span>
                                </div>

                                <div class="mb-4">
                                    <small class="text-muted d-block">Date of Joining</small>
                                    <span id="official_joining_date" class="fw-semibold">-</span>
                                </div>

                                <div class="mb-4">
                                    <small class="text-muted d-block">Work Location</small>
                                    <span id="official_work_location" class="fw-semibold">-</span>
                                </div>

                            </div>

                        </div>

                    </div>

                    <div class="tab-pane fade" id="employeeIdentityInfo">

                        <h5 class="mb-4">Documents and ID Cards</h5>

                        <div class="row">
                            <div class="col-2 mb-4">
                                <small class="text-muted d-block">Aadhaar Number</small>
                                <span id="identity_aadhar" class="fw-semibold">-</span>
                            </div>
                            <div class="col-4 mb-4" id="doc_aadhar"></div>

                            <div class="col-2 mb-4">
                                <small class="text-muted d-block">PAN Number</small>
                                <span id="identity_pan" class="fw-semibold">-</span>
                            </div>
                            <div class="col-4 mb-4" id="doc_pan"></div>
                            
                            <div class="col-2 mb-4">
                                <small class="text-muted d-block">Passport Number</small>
                                <span id="identity_passport" class="fw-semibold">-</span>
                            </div>
                            <div class="col-4 mb-4" id="doc_passport" class="mt-3"></div>
                            
                            

                            <div class="col-2 mb-4">
                                <small class="text-muted d-block">Insurance Number</small>
                                <span id="identity_insurance" class="fw-semibold">-</span>
                            </div>
                            <div class="col-4 mb-4" id="doc_insurance" class="mt-3"></div>

                            <div class="col-2 mb-4">
                                <small class="text-muted d-block">Resume</small>
                            </div>
                            <div class="col-4 mb-4" id="doc_resume" class="mt-3"></div>


                        </div>


                    </div>

                    <div class="tab-pane fade" id="employeeEducationInfo">

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
                                <tbody id="educationTableBody">
                                    <tr>
                                        <td colspan="5" class="text-center text-muted">
                                            No education details found.
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                    </div>
                    <div class="tab-pane fade" id="employeeBankInfo">

                        <h5 class="mb-4">Banking Details</h5>

                        <div class="row">

                            <div class="col-md-5">

                                <div class="mb-4">

                                    <small class="text-muted d-block">
                                        Bank Account Number
                                    </small>

                                    <span id="bank_account_no" class="fw-semibold"></span>

                                </div>

                                <div class="mb-4">

                                    <small class="text-muted d-block">
                                        Bank Name
                                    </small>

                                    <span id="bank_name" class="fw-semibold"></span>

                                </div>
                                <div class="col-12 mb-4" id="doc_passbook" class="mt-3"></div>

                            </div>

                            <div class="col-md-7">

                                <div class="mb-4">

                                    <small class="text-muted d-block">
                                        IFSC Code
                                    </small>

                                    <span id="bank_ifsc" class="fw-semibold"></span>

                                </div>

                                <div class="mb-4">

                                    <small class="text-muted d-block">
                                        Branch
                                    </small>

                                    <span id="bank_branch" class="fw-semibold"></span>

                                </div>
                               
                                

                            </div>

                        </div>

                    </div>
                    <div class="tab-pane fade" id="employeeExperienceInfo">

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

                                <tbody id="experienceTableBody">

                                    <tr>

                                        <td colspan="4" class="text-center text-muted">
                                            No experience details found.
                                        </td>

                                    </tr>

                                </tbody>

                            </table>

                        </div>

                    </div>
                    
                </div>
               

            </div>

        </div>
    </div>

</div>

<!--edit Modal-->



<div class="modal fade" id="employeeEditModal" tabindex="-1" aria-hidden="true">

    <div class="modal-dialog modal-xl modal-dialog-scrollable">

        <div class="modal-content">

            <!-- Header -->
            <div class="modal-header">

                <div class="d-flex align-items-center">

                    <div class="position-relative me-3">

                        <img src="{{ asset('assets/img/user.png') }}"
                            id="edit_profile_image"
                            class="rounded-circle border"
                            width="80"
                            height="80"
                            style="object-fit:cover;">

                        <button type="button"
                                class="btn btn-primary btn-sm rounded-circle position-absolute"
                                id="changePhotoBtn"
                                style="bottom:0;right:0;width:30px;height:30px;padding:0;">
                            <i class="bi bi-pencil"></i>
                        </button>

                        <input type="file"
                            id="profile_photo"
                            accept="image/*"
                            hidden>

                    </div>

                    <div>

                        <h5 class="mb-0 fw-bold">
                            Edit Employee
                        </h5>

                        <small id="edit_employee_name"></small>

                    </div>

                </div>

                <button class="btn-close"
                        data-bs-dismiss="modal">
                </button>

            </div>

            <div class="modal-body">

                <input type="hidden"
                       id="edit_employee_id">

                <!-- Tabs -->

                <ul class="nav nav-pills mb-4">

                    <li class="nav-item">
                        <button class="nav-link active"
                                data-bs-toggle="pill"
                                data-bs-target="#editProfile">
                            Profile
                        </button>
                    </li>

                    <li class="nav-item">
                        <button class="nav-link"
                                data-bs-toggle="pill"
                                data-bs-target="#editOfficial">
                            Official
                        </button>
                    </li>

                    <li class="nav-item">
                        <button class="nav-link"
                                data-bs-toggle="pill"
                                data-bs-target="#editDocuments">
                            Documents
                        </button>
                    </li>

                    <li class="nav-item">
                        <button class="nav-link"
                                data-bs-toggle="pill"
                                data-bs-target="#editEducation">
                            Education
                        </button>
                    </li>

                    <li class="nav-item">
                        <button class="nav-link"
                                data-bs-toggle="pill"
                                data-bs-target="#editBank">
                            Banking
                        </button>
                    </li>

                    <li class="nav-item">
                        <button class="nav-link"
                                data-bs-toggle="pill"
                                data-bs-target="#editExperience">
                            Experience
                        </button>
                    </li>

                    
                </ul>

                <div class="tab-content">

                    <!-- ====================== -->
                    <!-- Profile -->
                    <!-- ====================== -->

                    <div class="tab-pane fade show active"
                         id="editProfile">

                        <form id="profileForm">

                            <input type="hidden" name="id" id="profile_id">

                            <div class="row">

                                <!-- Left Column -->
                                <div class="col-md-6">

                                    <div class="mb-3">
                                        <label class="form-label">Full Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="edit_name" name="name" placeholder="Enter full name">
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Date of Birth</label>
                                        <input type="date" class="form-control" id="edit_dob" name="dob">
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Emergency Phone</label>
                                        <input type="text" class="form-control" id="edit_emergency_phone" name="emergency_phone" placeholder="Enter emergency phone number">
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Marital Status</label>
                                        <select class="form-select" id="edit_marital_status" name="marital_status">
                                            <option value="">select</option>
                                            <option value="Single">Single</option>
                                            <option value="Married">Married</option>
                                            <option value="Divorced">Divorced</option>
                                            <option value="Widowed">Widowed</option>
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Father's / Husband's Name</label>
                                        <input type="text" class="form-control" id="edit_guardian_name" name="guardian_name" placeholder="Enter father's / husband's name">
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Address</label>
                                        <textarea class="form-control" id="edit_address" name="address" rows="3" placeholder="Enter address"></textarea>
                                    </div>

                                </div>

                                <!-- Right Column -->
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Employee ID <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="edit_emp_id" name="emp_id" placeholder="Enter ID">
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Personal Email <span class="text-danger">*</span></label>
                                        <input type="email" class="form-control" id="edit_personal_email" name="email" placeholder="Enter personal email address">
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Phone</label>
                                        <input type="text" class="form-control" id="edit_phone" name="phone" placeholder="Enter phone number">
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Gender</label>
                                        <select class="form-select" id="edit_gender" name="gender">
                                            <option value="">Select gender</option>
                                            <option value="Male">Male</option>
                                            <option value="Female">Female</option>
                                            <option value="Other">Other</option>
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Blood Group</label>
                                        <select class="form-select" id="edit_blood_group" name="blood_group">
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
                                            id="edit_nationality"
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
                                        class="btn btn-primary" id="save">
                                    Save Changes
                                </button>

                            </div>

                        </form>

                    </div>

                    <!-- ====================== -->
                    <!-- Official -->
                    <!-- ====================== -->

                    <div class="tab-pane fade" id="editOfficial">

                        <form id="officialForm">

                            @csrf

                            <input type="hidden"
                                name="id"
                                id="official_id">

                            <div class="row">

                                <!-- Left -->

                                <div class="col-md-6">

                                    <div class="mb-3">
                                        <label class="form-label">Employee ID</label>
                                        <input type="text"
                                            class="form-control"
                                            name="emp_id"
                                            id="edit_official_emp_id"
                                            placeholder="Enter employee ID">
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Department</label>

                                        <select class="form-select"
                                                name="department_id"
                                                id="edit_department">
                                             @foreach($departments as $department)
                                                <option value="{{ $department->id }}">
                                                    {{ $department->name }}
                                                </option>
                                            @endforeach
                                        </select>

                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Reporting Manager</label>

                                        <select class="form-select"
                                                name="reporting_manager"
                                                id="edit_reporting_manager">
                                                @foreach($managers as $manager)
                                                <option value="{{ $manager->id }}">
                                                    {{ $manager->name }}
                                                </option>
                                            @endforeach
                                        </select>

                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Job Type</label>

                                        <select class="form-select"
                                                name="job_type"
                                                id="edit_job_type">

                                            <option value="">Select Job Type</option>
                                            <option value="Permanent">Permanent</option>
                                            <option value="Temporary">Temporary</option>
                                            <option value="Trainee">Trainee</option>
                                            <option value="Contract">Contract</option>

                                        </select>

                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Work Location</label>

                                        <input type="text"
                                            class="form-control"
                                            name="work_location"
                                            id="edit_work_location"
                                            placeholder="Enter work location">

                                    </div>

                                </div>

                                <!-- Right -->

                                <div class="col-md-6">

                                    <div class="mb-3">

                                        <label class="form-label">Official Email</label>

                                        <input type="email"
                                            class="form-control"
                                            name="official_email"
                                            id="edit_official_email"
                                            placeholder="Enter official email">

                                    </div>

                                    <div class="mb-3">

                                        <label class="form-label">Designation</label>

                                        <select class="form-select"
                                                name="designation_id"
                                                id="edit_designation">
                                            @foreach($designations as $designation)
                                                <option value="{{ $designation->id }}">
                                                    {{ $designation->name }}
                                                </option>
                                            @endforeach
                                        </select>

                                    </div>

                                    <div class="mb-3">

                                        <label class="form-label">Date of Joining</label>

                                        <input type="date"
                                            class="form-control"
                                            name="joining_date"
                                            id="edit_joining_date">

                                    </div>

                                    <div class="mb-3">

                                        <label class="form-label">Work Mode</label>

                                        <select class="form-select"
                                                name="work_mode"
                                                id="edit_work_mode">

                                            <option value="">Select Work Mode</option>
                                            <option value="Office">Office</option>
                                            <option value="Remote">Remote</option>
                                            <option value="Hybrid">Hybrid</option>

                                        </select>

                                    </div>

                                    <div class="mb-3">

                                        <label class="form-label">Employee Status</label>

                                        <select class="form-select"
                                                name="status"
                                                id="edit_employee_status">

                                            <option value="">Select Status</option>
                                            <option value="1">Active</option>
                                            <option value="0">Inactive</option>
                                            <option value="2">Resign</option>

                                        </select>

                                    </div>

                                </div>

                            </div>

                            <div class="text-end mt-4">

                                <button type="button"
                                        class="btn btn-light"
                                        data-bs-dismiss="modal">
                                    Cancel
                                </button>

                                <button type="submit"
                                        class="btn btn-primary">
                                    Save Official Information
                                </button>

                            </div>

                        </form>

                    </div>

                    <!-- ====================== -->
                    <!-- Documents -->
                    <!-- ====================== -->

                    <div class="tab-pane fade"
                         id="editDocuments">

                        <form id="documentForm"
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
                         id="editEducation">

                        <div class="text-end">
                        <button class="btn btn-success mb-2" id="addEmpEducationRow">
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
                                <tbody id="edit_educationTableBody">
                                    <tr id="listnoeducation">
                                        <td colspan="6" class="text-center text-muted">
                                            No education details found.
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        
                        <div class="text-start">
                        <button class="btn btn-primary" id="saveEmpEducation">
                            Save Education
                        </button>
                        </div>

                    </div>

                    <!-- Bank -->

                    <div class="tab-pane fade" id="editBank">

                        <form id="bankForm" enctype="multipart/form-data">
                            @csrf

                            <input type="hidden" name="id" id="bank_employee_id">

                            <div class="row">

                                <div class="col-md-6">

                                    <div class="mb-3">
                                        <label class="form-label">Bank Account Number</label>
                                        <input type="text"
                                            class="form-control"
                                            name="account_no"
                                            id="bank_account_number"
                                            placeholder="Enter bank account number">
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Bank Name</label>
                                        <input type="text"
                                            class="form-control"
                                            name="bank_name"
                                            id="edit_bank_name"
                                            placeholder="Enter bank name">
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Passbook</label>
                                        <input type="file"
                                            class="form-control"
                                            name="passbook"
                                            id="passbook"
                                            accept=".pdf">
                                        <small class="text-muted">PDF (Max 5MB)</small>

                                        <div id="passbookPreview" class="mt-2"></div>
                                    </div>

                                </div>

                                <div class="col-md-6">

                                    <div class="mb-3">
                                        <label class="form-label">IFSC Code</label>
                                        <input type="text"
                                            class="form-control"
                                            name="ifsc"
                                            id="ifsc_code"
                                            placeholder="Enter IFSC code">
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Branch Name</label>
                                        <input type="text"
                                            class="form-control"
                                            name="branch"
                                            id="branch_name"
                                            placeholder="Enter branch name">
                                    </div>

                                </div>

                            </div>

                            <div class="text-end mt-4">

                                <button type="button"
                                        class="btn btn-light"
                                        data-bs-dismiss="modal">
                                    Cancel
                                </button>

                                <button type="submit"
                                        class="btn btn-primary">
                                    Save Changes
                                </button>

                            </div>

                        </form>

                    </div>

                    <!-- Experience -->

                    <div class="tab-pane fade"
                         id="editExperience">

                        <div class="text-end">
                        <button class="btn btn-success mb-2" id="addEmpExperienceRow">
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
                                        <th>Certificates</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody id="edit_experienceTableBody">
                                    <tr id="listnoexperience">
                                        <td colspan="6" class="text-center text-muted">
                                            No experience details found.
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        
                        <div class="text-start">
                        <button class="btn btn-primary" id="saveEmpExperience">
                            Save Experience
                        </button>
                        </div>

                    </div>

                    

                </div>

            </div>

        </div>

    </div>

</div>
<div class="modal fade" id="attachmentViewerModal" tabindex="-1">

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

                <img id="attachmentImage"
                     class="img-fluid d-none"
                     style="max-height:75vh;">

                <iframe id="attachmentPdf"
                        class="d-none"
                        width="100%"
                        height="700"
                        frameborder="0">
                </iframe>

            </div>

            <div class="modal-footer">

                <a id="attachmentDownload"
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
<script>
    $.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
$('#profileForm').on('submit', function (e) {

    e.preventDefault();

    $('.is-invalid').removeClass('is-invalid');
    $('.invalid-feedback').remove();

    $.ajax({

        url: "{{ route('employee.updateProfile') }}",

        type: "POST",

        data: $(this).serialize(),

        beforeSend: function () {

            $('#save').prop('disabled', true);

            Swal.fire({
                title: 'Saving...',
                text: 'Please wait',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

        },

        success: function (response) {

            Swal.close();

            $('#save').prop('disabled', false);

            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: response.message,
                timer: 1500,
                showConfirmButton: false
            });

            $('#employeeTable').DataTable().ajax.reload(null, false);

        },

        error: function (xhr) {

            Swal.close();

            $('#save').prop('disabled', false);

            if (xhr.status == 422) {

                let errors = xhr.responseJSON.errors;

                $.each(errors, function (key, value) {

                    let input = $('[name="' + key + '"]');

                    input.addClass('is-invalid');

                    input.after('<div class="invalid-feedback">' + value[0] + '</div>');

                });

            } else {

                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: xhr.responseJSON.message ?? 'Something went wrong.'
                });

            }

        }

    });

});
    // Open file browser
$('#changePhotoBtn').click(function () {

    $('#profile_photo').click();

});


// Upload automatically after selecting image
$('#profile_photo').change(function () {

    let file = this.files[0];

    if (!file) return;

    // Preview image
    let reader = new FileReader();

    reader.onload = function (e) {

        $('#edit_profile_image').attr('src', e.target.result);

    }

    reader.readAsDataURL(file);


    // Upload

    let formData = new FormData();

    formData.append('photo', file);
    formData.append('id', $('#profile_id').val());
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

            $('#employeeTable').DataTable().ajax.reload(null,false);

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
function editEmployee(id)
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
            console.log(emp);
            // Hidden ID
            $('#edit_employee_id').val(emp.id);
            $('#profile_id').val(emp.id);
            $('#official_id').val(emp.id);
            
            // Header
            $('#edit_employee_name').text(emp.name);
            
            if(emp.photo){
                $('#edit_profile_image').attr('src',emp.photo);
            }
            
            // Profile
            
            $('#edit_name').val(emp.name);
            $('#edit_personal_email').val(emp.personal_email);
            $('#edit_dob').val(emp.dob);
            $('#edit_emergency_phone').val(emp.alt_contact_no);
            $('#edit_marital_status').val(emp.marital_status);
            $('#edit_guardian_name').val(emp.parent_name);
            $('#edit_address').val(emp.address);
            $('#edit_emp_id').val(emp.emp_id);
            
            $('#edit_phone').val(emp.contact_no);
            $('#edit_gender').val(emp.gender);
            $('#edit_blood_group').val(emp.blood_group);
            $('#edit_nationality').val(emp.nationality);
            
            // Official
            
            $('#edit_official_emp_id').val(emp.emp_id);
            
            $('#edit_official_email').val(emp.email);
            $('#edit_department').val(emp.department_id).trigger('change');
            $('#edit_designation').val(emp.designation_id).trigger('change');
            
            $('#edit_reporting_manager').val(emp.reporting_manager.id).trigger('change');
            $('#edit_joining_date').val(emp.joining_date);
            $('#edit_job_type').val(emp.job_type);
            $('#edit_work_mode').val(emp.job_location);
            $('#edit_employee_status').val(emp.status).trigger('change');
            $('#edit_work_location').val(emp.work_location);
            
            // Banking
            $('#bank_employee_id').val(emp.id);
            $('#bank_account_number').val(emp.account_no);
            $('#edit_bank_name').val(emp.bank_name);
            $('#ifsc_code').val(emp.ifsc);
            $('#branch_name').val(emp.branch);
           if (emp.passbook) {
                let fileUrl = '/storage/employees/passbook/' + emp.passbook;
                $('#passbookPreview').html(`
                    <button type="button"
                            class="btn btn-sm btn-info"
                            onclick="viewAttachment('${fileUrl}')">
                        <i class="fas fa-eye"></i> View Passbook
                    </button>
                `);
            } else {
                $('#passbookPreview').html('');
            }


            //education
            $('#edit_educationTableBody').html('');

            if (emp.educations && emp.educations.length > 0) {

                $.each(emp.educations, function(index, education){
                    let fileUrl = '/storage/employees/education/' + education.attachment;
                    $('#edit_educationTableBody').append(`
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
                                                onclick="viewAttachment('${fileUrl}')"
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
                                        class="btn btn-sm btn-danger removeRow">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>

                        </tr>
                        `);

                });

            }
            else{

                $('#edit_educationTableBody').html(`
                    <tr id="listnoeducation">
                        <td colspan="6" class="text-center" >
                            No education details found.
                        </td>
                    </tr>
                `);

            }
            //experience
            $('#edit_experienceTableBody').html('');

            if (emp.experiences && emp.experiences.length > 0) {

                $.each(emp.experiences, function(index, experience){
                    let fileUrl = '/storage/employees/experience/' + experience.attachment;
                    $('#edit_experienceTableBody').append(`
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
                                <input type="text"
                                    class="form-control year_of_experience"
                                    value="${experience.year_of_experience ?? ''}">
                            </td>


                            <td style="white-space: nowrap;">

                                ${
                                    experience.attachment
                                    ? `
                                        <button type="button"
                                                class="btn btn-sm btn-outline-primary"
                                                onclick="viewAttachment('${fileUrl}')"
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
                                        class="btn btn-sm btn-danger removeRow">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>

                        </tr>
                        `);

                });

            }
            else{

                $('#edit_experienceTableBody').html(`
                    <tr id="listnoexperience">
                        <td colspan="6" class="text-center" >
                            No experience details found.
                        </td>
                    </tr>
                `);

            }
            // Open first tab
            $('#employeeEditModal').modal('show');
            $('#editProfile-tab').tab('show');

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
    function viewAttachment(file)
    {
        if (!file) return;

        let extension = file.split('.').pop().toLowerCase();

        $('#attachmentImage').addClass('d-none');
        $('#attachmentPdf').addClass('d-none');

        $('#attachmentDownload').attr('href', file);

        if (['jpg','jpeg','png','gif','webp'].includes(extension)) {

            $('#attachmentImage')
                .attr('src', file)
                .removeClass('d-none');

        }
        else if (extension === 'pdf') {

            $('#attachmentPdf')
                .attr('src', file)
                .removeClass('d-none');

        }
        else {

            window.open(file,'_blank');
            return;

        }

        $('#attachmentViewerModal').modal('show');
    }
    $('#employeeEditModal').on('hidden.bs.modal',function(){

        $(this).find('form')[0]?.reset();

        $('form').trigger('reset');

    });
    function documentCard(title,file){

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
           
            <button type="button"
                    class="btn btn-sm btn-info"
                    onclick="viewAttachment('${file}')">
                <i class="bi bi-eye-fill"></i>
            </button>
            <a href="${file}" download class="btn btn-sm btn-success">
                <i class="bi bi-download"></i>
            </a>
        </div>
    </div>
    `;
}

function viewEmployee(id)
{
    $.ajax({
        url: '/employee/details/' + id,
        type: 'GET',

        success: function(response) {

            $('#employee_name').text(response.name);
            $('#employee_profile_image').attr('src', response.photo_url);
            $('#employee_designation').text(response.designation?.name ?? '-');
            $('#employee_code').text(response.emp_id ?? '-');
            $('#profile_email').text(response.email ?? '-');
            $('#profile_phone').text(response.contact_no ?? '-');
            $('#profile_gender').text(response.gender ?? '-');
            $('#profile_blood_group').text(response.blood_group ?? '-');
            $('#profile_nationality').text(response.nationality ?? '-');

            $('#profile_dob').text(response.dob ?? '-');
            $('#profile_emergency_phone').text(response.alt_contact_no ?? '-');
            $('#profile_marital_status').text(response.marital_status ?? '-');
            $('#profile_parent_name').text(response.parent_name ?? '-');
            $('#profile_address').text(response.address ?? '-');
            // Official Information

            $('#official_emp_id').text(response.emp_id ?? '-');

            $('#official_email').text(response.email ?? '-');

            $('#official_department').text(
                response.department ? response.department.name : '-'
            );

            $('#official_designation').text(
                response.designation ? response.designation.name : '-'
            );

            $('#official_reporting_manager').text(
                response.reporting_manager ? response.reporting_manager.name : '-'
            );

            $('#official_joining_date').text(
                response.joining_date ?? '-'
            );

            $('#official_employment_type').text(
                response.employment_type ?? '-'
            );

            $('#official_work_location').text(
                response.job_location ?? '-'
            );

            // Identity Information

            $('#identity_aadhar').text(
                response.aadhar_no ?? '-'
            );

            $('#identity_pan').text(
                response.pan_no ?? '-'
            );

            $('#identity_passport').text(
                response.passport_no ?? '-'
            );

            $('#identity_uan').text(
                response.uan ?? '-'
            );

            $('#identity_insurance').text(
                response.insurance_no ?? '-'
            );
            $('#employee_status').text(
                response.status == 1 ? 'Active' : 'Inactive'
            );
            $('#bank_account_no').text(response.account_no ?? '-');

            $('#bank_name').text(response.bank_name ?? '-');

            $('#bank_ifsc').text(response.ifsc ?? '-');

            $('#bank_branch').text(response.branch ?? '-');
            $('#educationTableBody').html('');

            if (response.educations && response.educations.length > 0) {

                $.each(response.educations, function(index, education){
                    let fileUrl = '/storage/employees/education/' + education.attachment;
                    $('#educationTableBody').append(`
                        <tr>

                            <td>${education.qualification ?? '-'}</td>

                            <td>${education.university_board ?? '-'}</td>

                            <td>${education.passing_year ?? '-'}</td>

                            <td>${education.percentage ?? '-'}%</td>

                            <td>
                                
            
                            ${education.attachment ? `<a href="javascript:void(0)"
   onclick="viewAttachment('${fileUrl}')"
   class="btn btn-sm btn-primary">

    <i class="bi bi-eye-fill"></i>

    </a>
                                <a href="${fileUrl}" download class="btn btn-sm btn-success">
                                    <i class="bi bi-download"></i>
                                </a>` : '-'}
                            </td>

                        </tr>
                    `);

                });

            }
            else{

                $('#educationTableBody').html(`
                    <tr>
                        <td colspan="5" class="text-center">
                            No education details found.
                        </td>
                    </tr>
                `);

            }
            $('#experienceTableBody').html('');

            if (response.experiences && response.experiences.length > 0) {

                $.each(response.experiences, function(index, experience) {

                    let attachment = '-';
                    let fileUrl = '/storage/employees/experience/' + experience.attachment;
                    if (experience.attachment) {
                        attachment = `
                            <a href="javascript:void(0)"
                                onclick="viewAttachment('${fileUrl}')"
                                class="btn btn-sm btn-primary"
                                title="View">

                                    <i class="bi bi-eye-fill"></i>

                                </a>

                            <a href="${fileUrl}" download title="Download"  class="btn btn-sm btn-success">
                                <i class="bi bi-download"></i>
                            </a>
                        `;
                    }

                    $('#experienceTableBody').append(`
                        <tr>

                            <td>${experience.company_name ?? '-'}</td>

                            <td>${experience.job_role ?? '-'}</td>

                            <td>${experience.year_of_experience ?? '-'} Year(s)</td>

                            <td>${attachment}</td>

                        </tr>
                    `);

                });

            } else {

                $('#experienceTableBody').html(`
                    <tr>
                        <td colspan="4" class="text-center">
                            No experience details found.
                        </td>
                    </tr>
                `);

            }
            $('#doc_aadhar').html(
                documentCard('Aadhaar Card',response.adhar_card)
            );

            $('#doc_pan').html(
                documentCard('PAN Card',response.pan_card)
            );

            $('#doc_passport').html(
                documentCard('Passport',response.passport)
            );

            $('#doc_resume').html(
                documentCard('Resume',response.resume)
            );
            if (response.passbook) {
                let url = '/storage/employees/passbook/' + response.passbook;

                $('#doc_passbook').html(
                    documentCard('Passbook', url)
                );
            } else {
                $('#doc_passbook').empty();
            }

            $('#doc_insurance').html(
                documentCard('Insurance Card',response.insurance)
            );
            $('#employeeProfileModal').modal('show');
        },
            error: function(xhr, status, error) {

            console.error(error);

           Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Unable to fetch employee details. Please try again.'
            });

            // Optional: clear modal fields
            $('#employee_name').text('');
            $('#employee_email').text('');
        }
    });
}
$(document).ready(function () {

    
    $('#exportBtn').click(function () {

        let status = $('#filter_status').val();

        let job_type = $('#filter_job_type').val();

        let department_id = $('#filter_department').val();

        let designation_id = $('#filter_designation').val();

        let url =
            "{{ route('employees.export') }}" +

            '?status=' + status +

            '&job_type=' + job_type +

            '&department_id=' + department_id +

            '&designation_id=' + designation_id;

        window.location.href = url;

    });
    var table = $('#employeeTable').DataTable({

        processing: true,

        serverSide: true,

        ajax: {
            url: "{{ route('employees.list') }}",
            data: function (d) {

                    d.status = $('#filter_status').val() || '';

                    d.job_type = $('#filter_job_type').val() || '';

                    d.department_id = $('#filter_department').val() || '';

                    d.designation_id = $('#filter_designation').val() || '';

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
                data: 'employee_name',
                name: 'name'
            },
            
            {
                data: 'emp_id',
                name: 'emp_id'
            },            

            {
                data: 'status_badge',
                name: 'status',
                orderable: false,
                searchable: false,
                className: 'text-center'
            },

            {
                data: 'job_type',
                name: 'job_type'
            },

            {
                data: 'designation_name',
                name: 'designation.name'
            },

            {
                data: 'reporting_manager',
                name: 'reportingManager.name'
            },

            {
                data: 'contact_no',
                name: 'contact_no'
            },

            {
                data: 'alt_contact_no',
                name: 'alt_contact_no',
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
    $('#officialForm').submit(function(e){

    e.preventDefault();

    $.ajax({

        url:'/employee/update-official',

        type:'POST',

        data:$(this).serialize(),

        beforeSend:function(){

            $('#officialForm button[type=submit]')
                .prop('disabled',true)
                .text('Saving...');

        },

        success:function(res){
            table.ajax.reload();
            Swal.fire({

                icon:'success',
                title:'Success',

                text:res.message

            });

        },

        error:function(xhr){

            let errors=xhr.responseJSON.errors;

            let msg='';

            $.each(errors,function(k,v){

                msg+=v[0]+'<br>';

            });

            Swal.fire({

                icon:'error',

                title:'Validation',

                html:msg

            });

        },

        complete:function(){

            $('#officialForm button[type=submit]')
                .prop('disabled',false)
                .text('Save Official Information');

        }

    });

});

$('#bankForm').submit(function(e){

    e.preventDefault();

    let formData = new FormData(this);

    $.ajax({

        url:'/employee/update-bank',

        type:'POST',

        data:formData,

        processData:false,

        contentType:false,

        headers:{
            'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
        },

        success:function(response){
            table.ajax.reload();
            if(response.status){

                Swal.fire(
                    'Success',
                    response.message,
                    'success'
                );

            }else{

                Swal.fire(
                    'Error',
                    response.message,
                    'error'
                );

            }

        },

        error:function(xhr){

            let errors='';

            $.each(xhr.responseJSON.errors,function(key,value){

                errors+=value[0]+'<br>';

            });

            Swal.fire(
                'Validation Error',
                errors,
                'error'
            );

        }

    });

});

});
function resignEmployee(id)
{
    Swal.fire({
        title: 'Are you sure?',
        text: "Do you want to resign this employee from the company?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Yes, Resign',
        cancelButtonText: 'Cancel'
    }).then((result) => {

        if(result.isConfirmed){

            $.ajax({

                url: "{{ route('employees.resign') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    id: id
                },

                success: function(response){

                    Swal.fire({
                        icon: 'success',
                        title: 'Resigned!',
                        text: response.message,
                        timer: 2000,
                        showConfirmButton: false
                    });

                    $('#employeeTable').DataTable().ajax.reload(null,false);

                },

                error: function(xhr){

                    Swal.fire(
                        'Error!',
                        xhr.responseJSON?.message ?? 'Something went wrong.',
                        'error'
                    );

                }

            });

        }

    });
}
$(document).on('click', '.removeRow', function () {
    $(this).closest('tr').remove();
});
$('#addEmpEducationRow').click(function () {
    $("#listnoeducation").remove();
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
                class="btn btn-sm btn-danger mt-1 removeRow">
                <i class="bi bi-trash"></i>
            </button> 
        </td>

    </tr>
    `;

    $('#edit_educationTableBody').append(row);

});
$('#saveEmpEducation').click(function () {

    let isValid = true;
    let errorMessage = '';

    $('#edit_educationTableBody tr').each(function (index) {

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
    let employeeId = $('#edit_employee_id').val();

    formData.append('_token', '{{ csrf_token() }}');
    formData.append('employee_id', employeeId);

    $('#edit_educationTableBody tr').each(function (index) {
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

$('#addEmpExperienceRow').click(function () {
    $("#listnoexperience").remove();
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
                class="form-control year_of_experience"
                >
        </td>

        <td>

            <input type="file"
                class="form-control attachment"
                accept=".pdf,.jpg,.jpeg,.png">
        </td>
            
        <td>   
            <button type="button"
                class="btn btn-sm btn-danger mt-1 removeRow">
                <i class="bi bi-trash"></i>
            </button> 
        </td>

    </tr>
    `;

    $('#edit_experienceTableBody').append(row);

});
$('#saveEmpExperience').click(function () {

    let isValid = true;
    let errorMessage = '';

    $('#edit_experienceTableBody tr').each(function (index) {

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
            errorMessage = 'Please enter Jobe Role in row ' + (index + 1);
            return false;
        }

        if (!year_of_experience) {
            isValid = false;
            errorMessage = 'Please enter Experience Year in row ' + (index + 1);
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
    let employeeId = $('#edit_employee_id').val();

    formData.append('_token', '{{ csrf_token() }}');
    formData.append('employee_id', employeeId);

    $('#edit_experienceTableBody tr').each(function (index) {
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


</script>