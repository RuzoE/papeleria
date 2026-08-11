<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // Define permissions grouped by module
        $permissions = [
            // Dashboard
            'ver dashboard',

            // Products
            'ver productos',
            'crear productos',
            'editar productos',
            'desactivar productos',

            // Categories
            'ver categorias',
            'crear categorias',
            'editar categorias',

            // Locations
            'ver ubicaciones',
            'crear ubicaciones',
            'editar ubicaciones',

            // Suppliers
            'ver proveedores',
            'crear proveedores',
            'editar proveedores',

            // Inventory
            'ver inventario',
            'ajustar inventario',
            'ver movimientos',

            // Purchases
            'ver compras',
            'crear compras',

            // Sales
            'ver ventas',
            'crear ventas',
            'cancelar ventas',
            'aplicar descuentos',

            // Transactions
            'ver transacciones',
            'crear transacciones',
            'cancelar transacciones',
            'ver tipos servicio',
            'gestionar tipos servicio',
            'ver tarifas',
            'gestionar tarifas',

            // Cash
            'ver caja',
            'abrir caja',
            'cerrar caja',
            'registrar movimientos caja',

            // Reports
            'ver reportes',

            // Users
            'ver usuarios',
            'crear usuarios',
            'editar usuarios',

            // Settings
            'ver configuracion',
            'editar configuracion',

            // Audit
            'ver auditoria',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // ─── Admin ────────────────────────────────────────────────────────────
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $admin->syncPermissions(Permission::all());

        // ─── Employee ─────────────────────────────────────────────────────────
        $employee = Role::firstOrCreate(['name' => 'employee']);
        $employee->syncPermissions([
            'ver dashboard',
            'ver productos',
            'crear productos',
            'editar productos',
            'ver categorias',
            'ver ubicaciones',
            'ver proveedores',
            'ver inventario',
            'ver movimientos',
            'ver compras',
            'crear compras',
            'ver ventas',
            'crear ventas',
            'ver transacciones',
            'crear transacciones',
            'ver tipos servicio',
            'ver tarifas',
            'ver caja',
            'registrar movimientos caja',
        ]);

        // ─── Cashier ──────────────────────────────────────────────────────────
        $cashier = Role::firstOrCreate(['name' => 'cashier']);
        $cashier->syncPermissions([
            'ver dashboard',
            'ver productos',
            'ver categorias',
            'ver ubicaciones',
            'ver ventas',
            'crear ventas',
            'ver transacciones',
            'crear transacciones',
            'ver tipos servicio',
            'ver tarifas',
            'ver caja',
            'abrir caja',
            'cerrar caja',
            'registrar movimientos caja',
        ]);

        // ─── Viewer ───────────────────────────────────────────────────────────
        $viewer = Role::firstOrCreate(['name' => 'viewer']);
        $viewer->syncPermissions([
            'ver dashboard',
            'ver productos',
            'ver categorias',
            'ver ubicaciones',
            'ver proveedores',
            'ver inventario',
            'ver movimientos',
            'ver compras',
            'ver ventas',
            'ver transacciones',
            'ver tipos servicio',
            'ver tarifas',
            'ver caja',
        ]);

        $this->command->info('Roles y permisos creados correctamente.');
    }
}
