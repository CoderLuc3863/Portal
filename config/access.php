<?php

$commonMenus = [
    'dashboard',
    'employee-management',
    'payslip',
    'time-attendance',
    "calender-schedule",
    'attendance-tracking',
    'attendance-regularization',
    'attendance-summary',
    'leave-management',
    'leave-request',
    'wfh-request',
    'expense-management',
    'project-management',
    'tasks-allocation',
    'my-tasks',

    'performance-tracking',
    'evaluation-report',
    'learning-developing',
    'training-assign',
    'exitform',
    

];
$adminMenus = array_merge($commonMenus, [
    'dashboard',
    'employee-management',
    'employee-onboard',
    'employees',
    'employee-offboard',
    'payroll',
    'payslip',

    'asset-management',
    'assets-requests',
    'assigned-assets',

    'time-attendance',
    'attendance-capture',
    'calender-schedule',
    'attendance-tracking',
    'attendance-regularization',
    'attendance-summary',

    'leave-management',
    'leave-request',
    'wfh-request',
    'leave-count',

    'expense-management',

    'project-management',
    'all-projects',
    'tasks-allocation',
    'project-costs',
    'my-tasks',
    'tasks-utilization',

    'performance-tracking',
    'evaluation-forms',
    'evaluation-scheduling',
    'evaluation-report',
    'pip',

    'learning-developing',
    'training-phase',
    'training-assign',
    'exitform',

]);
return [

    'page' => [
        // HR
        1 => $adminMenus,
        // Admin
        2 => $adminMenus,
        // Technical
        3 => array_merge($commonMenus, [
        ]),

        // Finance
        4 => $commonMenus,
        //SCM
        5 => $commonMenus,
        //Management
        6 => array_merge($commonMenus, [
            'all-projects',
        ]),
        //Digital
        7 => $commonMenus,
        //Sales
        8 => $commonMenus,
        // Marketing
        9 => array_merge($commonMenus, [
        ]),
        // Accounts
        10 => $commonMenus,
        //IT
        11 => array_merge($commonMenus, [
            'asset-management',
            'assets-requests',
            'assigned-assets',
        ]),
    ],
];