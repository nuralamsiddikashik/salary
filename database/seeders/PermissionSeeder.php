<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder {
    public function run() {
        $permissions = [
            'employee.create',
            'employee.view',
            'employee.delete',

            'payroll.create',
            'payroll.delete',

            'loan.create',
            'advance.create',
            'salary.pay',
            'report.view',

            'user.manage',
        ];

        foreach ( $permissions as $p ) {
            Permission::firstOrCreate( ['name' => $p] );
        }
    }
}
