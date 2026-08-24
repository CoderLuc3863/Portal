
@php

$pages = config('access.page')[session('department_id')] ?? [];

@endphp
 <!--begin::Sidebar-->
      <aside class="app-sidebar bg-body">
        <!--begin::Sidebar Brand-->
        <div class="sidebar-brand">
          <!--begin::Brand Link-->
          <a href="{{ route('dashboard') }}" class="brand-link" >
            <!--begin::Brand Image-->
            <img
              src="{{ asset('assets/images/logo.svg') }}"
              alt="Exacore EPortal Logo"
              class="brand-image opacity-75" style="width: 180px; height: auto;"
            />
            <!--end::Brand Image-->
            <!--begin::Brand Text-->
            <span class="brand-text fw-light" ></span>
            <!--end::Brand Text-->
          </a>
          <!--end::Brand Link-->
        </div>
        <!--end::Sidebar Brand-->
        <!--begin::Sidebar Wrapper-->
        <div class="sidebar-wrapper">
          <nav class="mt-2">
            <!--begin::Sidebar Menu-->
            <ul
              class="nav sidebar-menu flex-column"
              role="navigation"
              aria-label="Main navigation"
              id="navigation">
              @if(in_array('dashboard',$pages))
              <li class="nav-item ">
                <a href="javascript:void(0)"
                  class="nav-link menu-link active"
                  data-page="{{ route('dashboard.content') }}">

                    <i class="bi bi-speedometer"></i>
                    <p>Dashboard</p>
                </a>
              </li>

              @endif
              @if(in_array('employee-management',$pages))
              <li class="nav-item has-treeview">
                <a href="javascript:void(0)" class="nav-link">
                  <i class="bi bi-person-plus-fill"></i>
                  <p>
                    Employee Management
                    <i class="nav-arrow bi bi-chevron-right"></i>
                  </p>
                </a>
                <ul class="nav nav-treeview">
                  @if(in_array('employee-onboard',$pages))
                  <li class="nav-item ">
                    <a href="javascript:void(0)"
                      data-page="{{ route('employeedirectory.index') }}" class="nav-link menu-link">
                      <i class="nav-icon bi bi-circle"></i>
                      <p>Onboarding Employee</p>
                    </a>
                  </li>
                  @endif
                  @if(in_array('employees',$pages))
                  <li class="nav-item ">
                    <a href="javascript:void(0)"
                  data-page="{{ route('employees.index') }}" class="nav-link menu-link">
                      <i class="nav-icon bi bi-circle"></i>
                      <p>Employee Directory</p>
                    </a>
                  </li>
                  @endif
                  @if(in_array('employee-offboard',$pages))
                  <li class="nav-item ">
                    <a href="javascript:void(0)"
                  data-page="{{ route('offboard.index') }}" class="nav-link menu-link">
                      <i class="nav-icon bi bi-circle"></i>
                      <p>Offboarding Management</p>
                    </a>
                  </li>
                  @endif
                  @if(in_array('payroll',$pages))
                  <li class="nav-item ">
                    <a href="javascript:void(0)"
                  data-page="{{ route('payroll.index') }}" class="nav-link menu-link">
                      <i class="nav-icon bi bi-circle"></i>
                      <p>Payroll Management</p>
                    </a>
                  </li>
                  @endif
                   @if(in_array('payslip',$pages))
                  <li class="nav-item ">
                    <a href="javascript:void(0)"
                  data-page="{{ route('payslip.index') }}" class="nav-link menu-link">
                      <i class="nav-icon bi bi-circle"></i>
                      <p>PaySlip</p>
                    </a>
                  </li>
                  @endif
                </ul>
              </li>
              @endif
              @if(in_array('asset-management',$pages))
              <li class="nav-item has-treeview">
                <a href="javascript:void(0)" class="nav-link">
                  <i class="bi bi-laptop"></i>
                  <p>
                    Asset Management
                    <i class="nav-arrow bi bi-chevron-right"></i>
                  </p>
                </a>
                <ul class="nav nav-treeview">
                  @if(in_array('assets-requests',$pages))
                  <li class="nav-item ">
                    <a href="javascript:void(0)"
                      data-page="{{ route('assets.requests') }}" class="nav-link menu-link">
                      <i class="nav-icon bi bi-circle"></i>
                      <p>Asset Requests</p>
                    </a>
                  </li>
                  @endif
                  @if(in_array('assigned-assets',$pages))
                  <li class="nav-item ">
                    <a href="javascript:void(0)"
                  data-page="{{ route('assets.assigned') }}" class="nav-link menu-link">
                      <i class="nav-icon bi bi-circle"></i>
                      <p>Assigned Assets</p>
                    </a>
                  </li>
                  @endif
                </ul>
              </li>
              @endif
              @if(in_array('time-attendance',$pages))
              <li class="nav-item has-treeview">
                <a href="javascript:void(0)" class="nav-link">
                  <i class="bi bi-calendar-week-fill"></i>
                  <p>
                    Time & Attendance
                    <i class="nav-arrow bi bi-chevron-right"></i>
                  </p>
                </a>
                <ul class="nav nav-treeview">
                  
                  @if(in_array('attendance-capture',$pages))
                  <li class="nav-item ">
                    <a href="javascript:void(0)"
                  data-page="{{ route('attendance.capture') }}" class="nav-link menu-link">
                      <i class="nav-icon bi bi-circle"></i>
                      <p>Attendance Capture</p>
                    </a>
                  </li>
                   @endif
                  @if(in_array('calender-schedule',$pages))
                  <li class="nav-item ">
                    <a href="javascript:void(0)"
                      data-page="{{ route('calender.schedule') }}" class="nav-link menu-link">
                      <i class="nav-icon bi bi-circle"></i>
                      <p>Work Schedule Config.</p>
                    </a>
                  </li>
                   @endif
                  @if(in_array('attendance-tracking',$pages))
                   <li class="nav-item ">
                    <a href="javascript:void(0)"
                      data-page="{{ route('attendance.tracking') }}" class="nav-link menu-link">
                      <i class="nav-icon bi bi-circle"></i>
                      <p>Attendance Tracking</p>
                    </a>
                  </li>
                   @endif
                  @if(in_array('attendance-regularization',$pages))
                   <li class="nav-item ">
                    <a href="javascript:void(0)"
                  data-page="{{ route('attendance.regularization') }}" class="nav-link menu-link">
                      <i class="nav-icon bi bi-circle"></i>
                      <p>Regularization Request</p>
                    </a>
                  </li>
                  @endif
                  @if(in_array('attendance-summary',$pages))
                   <li class="nav-item ">
                    <a href="javascript:void(0)"
                  data-page="{{ route('attendance.summary') }}" class="nav-link menu-link">
                      <i class="nav-icon bi bi-circle"></i>
                      <p>Attendance Summary</p>
                    </a>
                  </li>
                  @endif
                  
                   {{-- <li class="nav-item ">
                    <a href="javascript:void(0)"
                  data-page="{{ route('attendance.reports') }}" class="nav-link menu-link">
                      <i class="nav-icon bi bi-circle"></i>
                      <p>Reports</p>
                    </a>
                  </li> --}}
                </ul>
              </li>
              @endif
              @if(in_array('leave-management',$pages))
              <li class="nav-item has-treeview">
                <a href="javascript:void(0)" class="nav-link">
                  <i class="bi bi-airplane"></i>
                  <p>
                    Leave Management
                    <i class="nav-arrow bi bi-chevron-right"></i>
                  </p>
                </a>
                <ul class="nav nav-treeview">
                  @if(in_array('leave-request',$pages))
                  <li class="nav-item ">
                    <a href="javascript:void(0)"
                  data-page="{{ route('leave.index') }}" class="nav-link menu-link">
                      <i class="nav-icon bi bi-circle"></i>
                      <p>Leave Requests</p>
                    </a>
                  </li>
                  @endif
                  @if(in_array('wfh-request',$pages))
                  <li class="nav-item ">
                    <a href="javascript:void(0)"
                  data-page="{{ route('wfh.index') }}" class="nav-link menu-link">
                      <i class="nav-icon bi bi-circle"></i>
                      <p>WFH Requests</p>
                    </a>
                  </li>
                  @endif
                  @if(in_array('leave-count',$pages))
                  <li class="nav-item ">
                    <a href="javascript:void(0)"
                  data-page="{{ route('leavecount.index') }}" class="nav-link menu-link">
                      <i class="nav-icon bi bi-circle"></i>
                      <p>Leave Counts</p>
                    </a>
                  </li>
                  @endif
                </ul>
              </li>
              @endif
              @if(in_array('expense-management',$pages))
              <li class="nav-item has-treeview">
                <a href="javascript:void(0)"
                  data-page="{{ route('expense.index') }}" class="nav-link menu-link">
                  <i class="bi bi-receipt"></i>
                  <p>
                    Expense Requests
                  </p>
                </a>
                
              </li>
              @endif
              @if(in_array('project-management',$pages))
              <li class="nav-item has-treeview">
                <a href="javascript:void(0)" class="nav-link">
                  <i class="bi bi-kanban-fill"></i>
                  <p>
                    Project Management
                    <i class="nav-arrow bi bi-chevron-right"></i>
                  </p>
                </a>
                <ul class="nav nav-treeview">
                  
                  @if(in_array('all-projects',$pages))
                  <li class="nav-item ">
                    <a href="javascript:void(0)"
                      data-page="{{ route('project.index') }}" class="nav-link menu-link">
                      <i class="nav-icon bi bi-circle"></i>
                      <p>
                        All Projects
                      </p>
                    </a>
                  </li>
                  @endif
                  @if(in_array('project-costs',$pages))
                  <li class="nav-item ">
                    <a href="javascript:void(0)"
                      data-page="{{ route('project.costs') }}" class="nav-link menu-link">
                      <i class="nav-icon bi bi-circle"></i>
                      <p>
                        Project Cost
                      </p>
                    </a>
                  </li>
                  @endif
                  @if(in_array('tasks-allocation',$pages))
                  <li class="nav-item ">
                    <a href="javascript:void(0)"
                  data-page="{{ route('tasks.allocation') }}" class="nav-link menu-link">
                      <i class="nav-icon bi bi-circle"></i>
                      <p>Task Allocation</p>
                    </a>
                  </li>
                  @endif
                  @if(in_array('my-tasks',$pages))
                  <li class="nav-item ">
                    <a href="javascript:void(0)"
                 id="menuMyTasks" data-page="{{ route('tasks.mytask') }}" class="nav-link menu-link">
                      <i class="nav-icon bi bi-circle"></i>
                      <p>My Tasks</p>
                    </a>
                  </li>
                  @endif
                  @if(in_array('tasks-utilization',$pages))
                  <li class="nav-item ">
                    <a href="javascript:void(0)"
                      data-page="{{ route('tasks.utilization.index') }}" class="nav-link menu-link">
                      <i class="nav-icon bi bi-circle"></i>
                      <p>
                        Resource Utilization
                      </p>
                    </a>
                  </li>
                  @endif
                </ul>
              </li>
              @endif
              @if(in_array('performance-tracking',$pages))
              <li class="nav-item has-treeview">
                <a href="javascript:void(0)" class="nav-link">
                  <i class="bi bi-speedometer2"></i>
                  <p>
                    Performance Tracking
                    <i class="nav-arrow bi bi-chevron-right"></i>
                  </p>
                </a>
                <ul class="nav nav-treeview">
                  
                  @if(in_array('evaluation-forms',$pages))
                  <li class="nav-item ">
                    <a href="javascript:void(0)"
                  data-page="{{ route('evaluation.forms') }}" class="nav-link menu-link">
                      <i class="nav-icon bi bi-circle"></i>
                      <p>Evaluation Form</p>
                    </a>
                  </li>
                  @endif
                  @if(in_array('evaluation-scheduling',$pages))
                  <li class="nav-item ">
                    <a href="javascript:void(0)"
                  data-page="{{ route('evaluation.scheduling') }}" class="nav-link menu-link">
                      <i class="nav-icon bi bi-circle"></i>
                      <p>Evaluation Scheduling</p>
                    </a>
                  </li>
                  @endif
                  @if(in_array('evaluation-report',$pages))
                  <li class="nav-item ">
                    <a href="javascript:void(0)"
                  data-page="{{ route('evaluation.report') }}" class="nav-link menu-link">
                      <i class="nav-icon bi bi-circle"></i>
                      <p>Evaluation Report</p>
                    </a>
                  </li>
                  @endif
                  @if(in_array('pip',$pages))
                  <li class="nav-item ">
                    <a href="javascript:void(0)"
                  data-page="{{ route('evaluation.pip') }}" class="nav-link menu-link">
                      <i class="nav-icon bi bi-circle"></i>
                      <p>PIP</p>
                    </a>
                  </li>
                  @endif
                </ul>
              </li>
              @endif
              @if(in_array('learning-developing',$pages))
              <li class="nav-item has-treeview">
                <a href="javascript:void(0)" class="nav-link">
                  <i class="bi bi-journal-bookmark-fill"></i>
                  <p>
                    Learning & Developing
                    <i class="nav-arrow bi bi-chevron-right"></i>
                  </p>
                </a>
                <ul class="nav nav-treeview">
                  @if(in_array('training-phase',$pages))
                  <li class="nav-item ">
                    <a href="javascript:void(0)"
                  data-page="{{ route('training.phase.index') }}" class="nav-link menu-link">
                      <i class="nav-icon bi bi-circle"></i>
                      <p>Training Phases</p>
                    </a>
                  </li>
                  @endif
                  @if(in_array('training-assign',$pages))
                  <li class="nav-item ">
                    <a href="javascript:void(0)"
                  data-page="{{ route('training.assign.index') }}" class="nav-link menu-link">
                      <i class="nav-icon bi bi-circle"></i>
                      <p>Training Management</p>
                    </a>
                  </li>
                  @endif
                  {{-- <li class="nav-item ">
                    <a href="javascript:void(0)"
                  data-page="{{ route('training.report.index') }}" class="nav-link menu-link">
                      <i class="nav-icon bi bi-circle"></i>
                      <p>Report</p>
                    </a>
                  </li> --}}
                  
                </ul>
              </li>
              @endif

              @if(in_array('exitform',$pages) && session("emp_status")==2)
              <li class="nav-item has-treeview">
                <a href="javascript:void(0)"
                  data-page="{{ route('employee.exitform') }}" class="nav-link menu-link">
                  <i class="bi bi-box-arrow-right"></i>
                  <p>Exit Form</p>
                </a>
              </li>
              @endif
                  
            </ul>
            <!--end::Sidebar Menu-->
          </nav>
        </div>
        <!--end::Sidebar Wrapper-->
      </aside>
{{-- @endif --}}
