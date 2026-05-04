<x-layouts.admin>
    <x-slot name="title">Usuarios</x-slot>

    <div
        x-data="{
            showFilters: false,
            showRolesModal: {{ ($errors->any() || session('open_roles_modal')) ? 'true' : 'false' }},
            showCreateRoleModal: false,
            roleSearch: '',
            showEditRoleModal: false,
            editRoleId: null,
            editRoleName: '',
            showEditUserModal: false,
            editUserId: null,
            editUserName: '',
            editUserUsername: '',
            editUserEmail: '',
            selectedRoleId: null,
            rolesData: @js($roles->map(fn($r) => [
                'id' => $r->id,
                'name' => $r->name,
                'is_active' => (bool) $r->is_active,
                'permissions' => $r->permissions->pluck('name')->values(),
            ])->values()),
            get filteredRoles() {
                if (!this.roleSearch) return this.rolesData;
                return this.rolesData.filter(r => r.name.toLowerCase().includes(this.roleSearch.toLowerCase()));
            },
            get selectedRole() {
                return this.rolesData.find(r => Number(r.id) === Number(this.selectedRoleId)) ?? null;
            },
            openEditRole(role) {
                this.editRoleId = role.id;
                this.editRoleName = role.name;
                this.showEditRoleModal = true;
            },
            openEditUser(user) {
                this.editUserId = user.id;
                this.editUserName = user.name;
                this.editUserUsername = user.username;
                this.editUserEmail = user.email;
                this.showEditUserModal = true;
            }
        }"
        class="space-y-4"
    >
        @if (session('success'))
            <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5">
            <form method="GET" action="{{ route('admin.users.index') }}" class="space-y-4">
                <div class="flex flex-col gap-3 lg:flex-row lg:items-center">
                    <input
                        type="text"
                        name="q"
                        value="{{ $search }}"
                        class="w-full lg:flex-1 rounded-xl border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                        placeholder="Buscar por nombre, usuario o correo"
                    >

                    <button
                        type="button"
                        @click="showFilters = !showFilters"
                        class="px-5 py-2.5 rounded-xl border border-slate-300 text-slate-700 text-sm font-semibold hover:bg-slate-50"
                    >
                        Filtros
                    </button>

                    <button
                        type="button"
                        @click="showRolesModal = true"
                        class="px-5 py-2.5 rounded-xl border border-blue-200 bg-blue-50 text-blue-800 text-sm font-semibold hover:bg-blue-100"
                    >
                        Roles y permisos
                    </button>

                    <button
                        type="submit"
                        class="px-5 py-2.5 rounded-xl bg-blue-900 text-white text-sm font-semibold hover:bg-blue-800"
                    >
                        Buscar
                    </button>
                </div>

                <div
                    x-show="showFilters"
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 -translate-y-1"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    x-transition:leave="transition ease-in duration-150"
                    x-transition:leave-start="opacity-100 translate-y-0"
                    x-transition:leave-end="opacity-0 -translate-y-1"
                    class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-3 rounded-xl border border-slate-200 bg-slate-50/60 p-4"
                    style="display: none;"
                >
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Tipo de usuario</label>
                        <select name="role" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 text-sm">
                            <option value="">Todos</option>
                            <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                            <option value="recepcionista" {{ request('role') === 'recepcionista' ? 'selected' : '' }}>Recepcionista</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Desde</label>
                        <input type="date" name="date_from" value="{{ request('date_from') }}" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 text-sm">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Hasta</label>
                        <input type="date" name="date_to" value="{{ request('date_to') }}" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 text-sm">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Estado</label>
                        <select name="status" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 text-sm">
                            <option value="">Todos</option>
                            <option value="activo" {{ request('status') === 'activo' ? 'selected' : '' }}>Activo</option>
                            <option value="inactivo" {{ request('status') === 'inactivo' ? 'selected' : '' }}>Inactivo</option>
                        </select>
                    </div>
                </div>
            </form>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-50 text-left">
                        <tr>
                            <th class="px-4 py-3">Nombre</th>
                            <th class="px-4 py-3">Usuario</th>
                            <th class="px-4 py-3">Correo</th>
                            <th class="px-4 py-3">Rol</th>
                            <th class="px-4 py-3">Fecha</th>
                            <th class="px-4 py-3">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($users as $user)
                            <tr class="border-t">
                                <td class="px-4 py-3">{{ $user->name }}</td>
                                <td class="px-4 py-3">{{ $user->username }}</td>
                                <td class="px-4 py-3">{{ $user->email }}</td>
                                <td class="px-4 py-3">{{ $user->roles->pluck('name')->join(', ') ?: 'Sin rol' }}</td>
                                <td class="px-4 py-3">{{ $user->created_at->format('d/m/Y H:i') }}</td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-2">
                                        <button
                                            type="button"
                                            data-id="{{ $user->id }}"
                                            data-name="{{ e($user->name) }}"
                                            data-username="{{ e($user->username) }}"
                                            data-email="{{ e($user->email) }}"
                                            @click="openEditUser({
                                                id: $el.dataset.id,
                                                name: $el.dataset.name,
                                                username: $el.dataset.username,
                                                email: $el.dataset.email
                                            })"
                                            class="px-3 py-1.5 rounded-lg bg-amber-500 text-white text-xs font-semibold hover:bg-amber-600"
                                        >
                                            Editar
                                        </button>
                                        <form action="{{ route('admin.users.toggle-status', $user) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="px-3 py-1.5 rounded-lg text-white text-xs font-semibold {{ $user->is_active ? 'bg-red-600 hover:bg-red-700' : 'bg-emerald-600 hover:bg-emerald-700' }}">
                                                {{ $user->is_active ? 'Desactivar' : 'Activar' }}
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-6 text-center text-gray-500">No hay usuarios registrados.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div>
            {{ $users->links() }}
        </div>

        <div x-show="showRolesModal" x-transition class="fixed inset-0 z-50 flex items-center justify-center p-4" style="display: none;">
            <div class="absolute inset-0 bg-slate-900/50" @click="showRolesModal = false"></div>

            <div class="relative w-full max-w-5xl rounded-2xl bg-white border border-slate-200 shadow-2xl overflow-hidden">
                <div class="flex items-center justify-between px-6 py-4 border-b border-slate-200 bg-slate-50">
                    <h3 class="text-lg font-bold text-slate-900">Roles y permisos</h3>
                    <button type="button" @click="showRolesModal = false" class="w-9 h-9 rounded-lg text-red-600 hover:bg-red-50 hover:text-red-700" aria-label="Cerrar">&times;</button>
                </div>

                <div class="p-6 space-y-6 max-h-[80vh] overflow-y-auto">
                    @if (session('roles_success'))
                        <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
                            {{ session('roles_success') }}
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700">
                            {{ $errors->first() }}
                        </div>
                    @endif

                    <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">
                        <div class="space-y-4">
                            <div class="rounded-xl border border-slate-200 p-4">
                                <h4 class="font-semibold text-slate-800 mb-3">Roles</h4>
                                <div class="flex flex-col md:flex-row gap-2">
                                    <input
                                        type="text"
                                        x-model="roleSearch"
                                        class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 text-sm"
                                        placeholder="Buscar rol por nombre"
                                    >
                                    <button
                                        type="button"
                                        @click="showCreateRoleModal = true"
                                        class="px-4 py-2 rounded-lg bg-blue-900 text-white text-sm font-semibold hover:bg-blue-800 whitespace-nowrap"
                                    >
                                        Añadir rol
                                    </button>
                                </div>
                            </div>

                            <div class="rounded-xl border border-slate-200 overflow-hidden">
                                <div class="px-4 py-3 bg-slate-50 border-b border-slate-200 font-semibold text-slate-800">Roles actuales</div>
                                <div class="max-h-64 overflow-y-auto">
                                    <table class="min-w-full text-sm">
                                        <thead class="bg-white text-left">
                                            <tr>
                                                <th class="px-4 py-2">Rol</th>
                                                <th class="px-4 py-2">Permisos</th>
                                                <th class="px-4 py-2">Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <template x-for="role in filteredRoles" :key="role.id">
                                                <tr
                                                    class="border-t cursor-pointer hover:bg-blue-50"
                                                    :class="Number(selectedRoleId) === Number(role.id) ? 'bg-blue-100' : ''"
                                                    @click="selectedRoleId = role.id"
                                                >
                                                    <td class="px-4 py-2 font-semibold text-slate-800" x-text="role.name"></td>
                                                    <td class="px-4 py-2 text-slate-600" x-text="role.permissions.length"></td>
                                                    <td class="px-4 py-2">
                                                        <div class="flex items-center gap-2">
                                                            <button
                                                                type="button"
                                                                @click.stop="openEditRole(role)"
                                                                class="px-2.5 py-1 rounded-md bg-amber-500 text-white text-xs font-semibold hover:bg-amber-600"
                                                            >
                                                                Editar
                                                            </button>
                                                            <form :action="`{{ url('/admin/usuarios/roles') }}/${role.id}/estado`" method="POST" @click.stop>
                                                                @csrf
                                                                @method('PATCH')
                                                                <button
                                                                    type="submit"
                                                                    class="px-2.5 py-1 rounded-md text-xs font-semibold"
                                                                    :class="role.is_active ? 'bg-red-600 text-white hover:bg-red-700' : 'bg-emerald-600 text-white hover:bg-emerald-700'"
                                                                    x-text="role.is_active ? 'Desactivar' : 'Activar'"
                                                                ></button>
                                                            </form>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </template>
                                            <tr x-show="filteredRoles.length === 0">
                                                <td colspan="3" class="px-4 py-4 text-center text-slate-500">No se encontraron roles.</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                        </div>

                        <div class="rounded-xl border border-slate-200 p-4">
                            <template x-if="selectedRole">
                                <div class="space-y-4">
                                    <div class="rounded-lg border border-blue-200 bg-blue-50 px-3 py-2">
                                        <p class="text-xs text-blue-700">Rol seleccionado</p>
                                        <p class="font-semibold text-blue-900" x-text="selectedRole.name"></p>
                                    </div>

                                    <form method="POST" action="{{ route('admin.users.roles.permissions.sync') }}" class="space-y-4">
                                        @csrf
                                        <input type="hidden" name="role_id" :value="selectedRoleId">

                                        <div>
                                            <p class="block text-xs font-semibold text-slate-700 mb-2">Permisos del rol</p>
                                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 max-h-72 overflow-y-auto rounded-lg border border-slate-200 p-3">
                                                @foreach ($permissions as $permission)
                                                    <label class="inline-flex items-center gap-2 text-sm text-slate-700">
                                                        <input
                                                            type="checkbox"
                                                            name="permissions[]"
                                                            value="{{ $permission->name }}"
                                                            class="rounded border-slate-300 text-blue-600 focus:ring-blue-500"
                                                            :checked="selectedRole && selectedRole.permissions.includes('{{ $permission->name }}')"
                                                        >
                                                        <span>{{ $permission->name }}</span>
                                                    </label>
                                                @endforeach
                                            </div>
                                        </div>

                                        <button type="submit" class="px-4 py-2 rounded-lg bg-emerald-600 text-white text-sm font-semibold hover:bg-emerald-700">Actualizar permisos</button>
                                    </form>
                                </div>
                            </template>

                            <template x-if="!selectedRole">
                                <div class="h-full min-h-56 flex items-center justify-center rounded-lg border border-dashed border-slate-300 bg-slate-50 px-4 text-center text-slate-600">
                                    Selecciona un rol de la tabla o crea uno nuevo para administrar sus permisos.
                                </div>
                            </template>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div x-show="showCreateRoleModal" x-transition class="fixed inset-0 z-[60] flex items-center justify-center p-4" style="display: none;">
            <div class="absolute inset-0 bg-slate-900/55" @click="showCreateRoleModal = false"></div>
            <div class="relative w-full max-w-2xl rounded-2xl bg-white border border-slate-200 shadow-2xl overflow-hidden">
                <div class="flex items-center justify-between px-6 py-4 border-b border-slate-200 bg-slate-50">
                    <h3 class="text-lg font-bold text-slate-900">Crear rol</h3>
                    <button type="button" @click="showCreateRoleModal = false" class="w-9 h-9 rounded-lg text-red-600 hover:bg-red-50 hover:text-red-700">&times;</button>
                </div>
                <form method="POST" action="{{ route('admin.users.roles.store') }}" class="p-6 space-y-4">
                    @csrf
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Nombre del rol</label>
                        <input type="text" name="name" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 text-sm" placeholder="Ej: supervisor" required>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" class="px-4 py-2 rounded-lg bg-blue-900 text-white text-sm font-semibold hover:bg-blue-800">Guardar rol</button>
                    </div>
                </form>
            </div>
        </div>

        <div x-show="showEditRoleModal" x-transition class="fixed inset-0 z-[60] flex items-center justify-center p-4" style="display: none;">
            <div class="absolute inset-0 bg-slate-900/55" @click="showEditRoleModal = false"></div>
            <div class="relative w-full max-w-lg rounded-2xl bg-white border border-slate-200 shadow-2xl overflow-hidden">
                <div class="flex items-center justify-between px-6 py-4 border-b border-slate-200 bg-slate-50">
                    <h3 class="text-lg font-bold text-slate-900">Editar rol</h3>
                    <button type="button" @click="showEditRoleModal = false" class="w-9 h-9 rounded-lg text-red-600 hover:bg-red-50 hover:text-red-700">&times;</button>
                </div>
                <form :action="`{{ url('/admin/usuarios/roles') }}/${editRoleId}`" method="POST" class="p-6 space-y-4">
                    @csrf
                    @method('PATCH')
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Nombre del rol</label>
                        <input type="text" name="name" x-model="editRoleName" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 text-sm" required>
                    </div>
                    <div class="flex justify-end">
                        <button type="submit" class="px-4 py-2 rounded-lg bg-blue-900 text-white text-sm font-semibold hover:bg-blue-800">Guardar cambios</button>
                    </div>
                </form>
            </div>
        </div>

        <div x-show="showEditUserModal" x-transition class="fixed inset-0 z-[60] flex items-center justify-center p-4" style="display: none;">
            <div class="absolute inset-0 bg-slate-900/55" @click="showEditUserModal = false"></div>
            <div class="relative w-full max-w-xl rounded-2xl bg-white border border-slate-200 shadow-2xl overflow-hidden">
                <div class="flex items-center justify-between px-6 py-4 border-b border-slate-200 bg-slate-50">
                    <h3 class="text-lg font-bold text-slate-900">Editar usuario</h3>
                    <button type="button" @click="showEditUserModal = false" class="w-9 h-9 rounded-lg text-red-600 hover:bg-red-50 hover:text-red-700">&times;</button>
                </div>
                <form :action="`{{ url('/admin/usuarios') }}/${editUserId}`" method="POST" class="p-6 space-y-4">
                    @csrf
                    @method('PATCH')
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Nombre</label>
                        <input type="text" name="name" x-model="editUserName" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 text-sm" required>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Usuario</label>
                        <input type="text" name="username" x-model="editUserUsername" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 text-sm" required>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Correo</label>
                        <input type="email" name="email" x-model="editUserEmail" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 text-sm" required>
                    </div>
                    <div class="flex justify-end">
                        <button type="submit" class="px-4 py-2 rounded-lg bg-blue-900 text-white text-sm font-semibold hover:bg-blue-800">Guardar cambios</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-layouts.admin>
