<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear Cache
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // Permission list
        $permissions = [
            // Patients
            'view-patients',
            'create-patients',
            'edit-patients',
            
            // Prescriptions
            'write-prescription',
            'view-prescriptions',
            'edit-prescriptions',
            'print-prescription',
            
            // Diagnostics and Documents
            'upload-documents',
            'view-documents',
            'delete-documents',
            
            // Pharmacy and Stock
            'sync-pharmacy-stock',
            'view-stock-reports',
            
            // Clinic Settings and Staff
            'manage-clinic-settings',
            'manage-staff-accounts',
            'manage-users',
            'manage-roles',
            'manage-api-webhooks',
            'view-analytics-dashboard',
        ];

        // Laravel Permissions Create
        foreach ($permissions ?? [] as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'web'
            ]);
        }

        // Core Roles create
        $superAdminRole = Role::firstOrCreate(['name' => 'SuperAdmin', 'guard_name' => 'web']);
        $doctorRole      = Role::firstOrCreate(['name' => 'Doctor', 'guard_name' => 'web']);
        $assistantRole   = Role::firstOrCreate(['name' => 'Assistant', 'guard_name' => 'web']);

        // Sync Permissions to Roles
        $doctorRole->syncPermissions($permissions);

        $assistantRole->syncPermissions([
            'view-patients', 'create-patients',
            'view-prescriptions', 'print-prescription',
            'upload-documents', 'view-documents'
        ]);

    
        $superAdmin = User::updateOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name'     => 'SmartRx Administrator',
                'password' => '12345678', // লারাভেল ১৩-এর মডেল কাস্টের কারণে অটো হ্যাশ হবে
            ]
        );
        $superAdmin->assignRole($superAdminRole);

        $doctor = User::updateOrCreate(
            ['email' => 'doctor@gmail.com'],
            [
                'name'     => 'Dr. Shahanewas Shawon',
                'password' => '12345678',
            ]
        );
        $doctor->assignRole($doctorRole);
        $assistant = User::updateOrCreate(
            ['email' => 'assistant@gmail.com'],
            [
                'name'     => 'Assistant Staff',
                'password' => '12345678',
            ]
        );
        $assistant->assignRole($assistantRole);
    }
}