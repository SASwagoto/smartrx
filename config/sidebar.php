<?php
// config/sidebar.php

return [
    'menu' => [
        [
            'title' => 'Dashboard',
            'icon'  => '<svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2v-4zM14 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2v-4z"/></svg>',
            'route' => 'dashboard',
            'permission' => null,
        ],
        [
            'title' => 'Patient Registry',
            'icon'  => '<svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>',
            'permission' => 'view-patients',
            'submenu' => [
                [
                    'title' => 'All Patients List',
                    'route' => 'patients.index', // কাল্পনিক আলাদা রাউট (এরর এড়াতে ড্যাশবোর্ড টেস্টের পর আসল রাউট দিন)
                    'permission' => 'view-patients',
                ],
                [
                    'title' => 'Admit New Patient',
                    'route' => 'patients.create',
                    'permission' => 'create-patients',
                ],
            ]
        ],
        [
            'title' => 'Prescriptions',
            'icon'  => '<svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>',
            'permission' => 'view-prescriptions',
            'submenu' => [
                [
                    'title' => 'Write Prescription',
                    'route' => 'prescriptions.create',
                    'permission' => 'write-prescription',
                ],
                [
                    'title' => 'Prescription History',
                    'route' => 'prescriptions.index',
                    'permission' => 'view-prescriptions',
                ],
            ]
        ],
        [
            'title' => 'Pharmacy Stock',
            'icon'  => '<svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/></svg>',
            'route' => 'pharmacy',
            'permission' => 'sync-pharmacy-stock',
        ],
        [
            'title' => 'User Control',
            'icon'  => '<svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>',
            'permission' => 'manage-users',
            'submenu' => [
                [
                    'title' => 'Manage Users',
                    'route' => 'users.index',
                    'permission' => 'manage-users',
                ],
                [
                    'title' => 'Role Management',
                    'route' => 'roles.index',
                    'permission' => 'manage-roles',
                ],
            ]
        ],
    ],
];