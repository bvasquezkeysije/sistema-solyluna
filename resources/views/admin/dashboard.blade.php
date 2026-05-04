<x-layouts.admin>
    <x-slot name="title">Dashboard</x-slot>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-white rounded-lg p-5 border">
            <p class="text-sm text-gray-500">Usuarios registrados</p>
            <p class="text-3xl font-bold text-blue-900">{{ \App\Models\User::count() }}</p>
        </div>
        <div class="bg-white rounded-lg p-5 border">
            <p class="text-sm text-gray-500">Roles activos</p>
            <p class="text-3xl font-bold text-blue-900">{{ \Spatie\Permission\Models\Role::count() }}</p>
        </div>
        <div class="bg-white rounded-lg p-5 border">
            <p class="text-sm text-gray-500">Estado</p>
            <p class="text-xl font-semibold text-green-600">Operativo</p>
        </div>
    </div>
</x-layouts.admin>
