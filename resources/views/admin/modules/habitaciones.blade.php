<x-layouts.admin>
    <x-slot name="title">Habitaciones</x-slot>

    <div
        x-data="{
            showFilters: false,
            showCreateRoomModal: {{ (session('open_room_modal') || $errors->room->any()) ? 'true' : 'false' }},
            showPisosModal: {{ (session('open_floors_modal') || $errors->floor->any()) ? 'true' : 'false' }},
            showTypesModal: {{ (session('open_types_modal') || $errors->roomType->any()) ? 'true' : 'false' }},
            showEditFloorModal: {{ session('open_edit_floor_modal') ? 'true' : 'false' }},
            showEditTypeModal: {{ session('open_edit_type_modal') ? 'true' : 'false' }},
            editFloorId: {{ session('edit_floor.id') ? (int) session('edit_floor.id') : 'null' }},
            editFloorName: @js((string) session('edit_floor.name', '')),
            editFloorNumber: @js((string) session('edit_floor.number', '')),
            editTypeId: {{ session('edit_type.id') ? (int) session('edit_type.id') : 'null' }},
            editTypeName: @js((string) session('edit_type.name', '')),
            openEditFloor(floor) {
                this.editFloorId = floor.id;
                this.editFloorName = floor.name;
                this.editFloorNumber = floor.number;
                this.showEditFloorModal = true;
            },
            openEditType(type) {
                this.editTypeId = type.id;
                this.editTypeName = type.name;
                this.showEditTypeModal = true;
            }
        }"
        class="space-y-4"
    >
        @if (session('room_success'))
            <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
                {{ session('room_success') }}
            </div>
        @endif
        @if (session('room_type_success'))
            <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
                {{ session('room_type_success') }}
            </div>
        @endif

        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5">
            <form method="GET" action="{{ route('admin.habitaciones') }}" class="space-y-4">
                <div class="flex flex-col gap-3 lg:flex-row lg:items-center">
                    <input type="text" name="q" value="{{ request('q') }}" class="w-full lg:flex-1 rounded-xl border-gray-300 focus:border-blue-500 focus:ring-blue-500" placeholder="Buscar habitación por número, piso o tipo">

                    <button type="button" @click="showFilters = !showFilters" class="px-5 py-2.5 rounded-xl border border-slate-300 text-slate-700 text-sm font-semibold hover:bg-slate-50">Filtros</button>
                    <button type="button" @click="showCreateRoomModal = true" class="px-5 py-2.5 rounded-xl border border-emerald-200 bg-emerald-50 text-emerald-800 text-sm font-semibold hover:bg-emerald-100">Añadir habitación</button>
                    <button type="button" @click="showTypesModal = true" class="px-5 py-2.5 rounded-xl border border-indigo-200 bg-indigo-50 text-indigo-800 text-sm font-semibold hover:bg-indigo-100">Tipos</button>
                    <button type="button" @click="showPisosModal = true" class="px-5 py-2.5 rounded-xl border border-blue-200 bg-blue-50 text-blue-800 text-sm font-semibold hover:bg-blue-100">Pisos</button>
                    <button type="submit" class="px-5 py-2.5 rounded-xl bg-blue-900 text-white text-sm font-semibold hover:bg-blue-800">Buscar</button>
                </div>

                <div x-show="showFilters" x-transition class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-3 rounded-xl border border-slate-200 bg-slate-50/60 p-4" style="display: none;">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Piso</label>
                        <select name="floor_id" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 text-sm">
                            <option value="">Todos</option>
                            @foreach ($floors as $floor)
                                <option value="{{ $floor->id }}" {{ (string) request('floor_id') === (string) $floor->id ? 'selected' : '' }}>{{ $floor->number }} - {{ $floor->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Estado</label>
                        <select name="active" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 text-sm">
                            <option value="">Todos</option>
                            <option value="1" {{ request('active') === '1' ? 'selected' : '' }}>Activa</option>
                            <option value="0" {{ request('active') === '0' ? 'selected' : '' }}>Inactiva</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Precio máx.</label>
                        <input type="number" name="price_max" step="0.01" value="{{ request('price_max') }}" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 text-sm" placeholder="0.00">
                    </div>
                </div>
            </form>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-50 text-left">
                        <tr>
                            <th class="px-4 py-3">Código</th>
                            <th class="px-4 py-3">N° Habitación</th>
                            <th class="px-4 py-3">Piso</th>
                            <th class="px-4 py-3">Tipo</th>
                            <th class="px-4 py-3">Estado</th>
                            <th class="px-4 py-3">Tarifa Hora</th>
                            <th class="px-4 py-3">Tarifa Día</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($rooms as $room)
                            <tr class="border-t">
                                <td class="px-4 py-3 font-semibold">{{ $room->code }}</td>
                                <td class="px-4 py-3">{{ $room->room_number }}</td>
                                <td class="px-4 py-3">Piso {{ $room->floor?->number }}</td>
                                <td class="px-4 py-3">{{ $room->type }}</td>
                                <td class="px-4 py-3">{{ $room->active ? 'Activa' : 'Inactiva' }}</td>
                                <td class="px-4 py-3">S/ {{ number_format((float) $room->hourly_rate, 2) }}</td>
                                <td class="px-4 py-3">S/ {{ number_format((float) $room->daily_rate, 2) }}</td>
                            </tr>
                        @empty
                            <tr class="border-t"><td class="px-4 py-3" colspan="7">Sin datos por ahora.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div>{{ $rooms->links() }}</div>

        <div x-cloak x-show="showCreateRoomModal" x-transition class="fixed inset-0 z-[70] flex items-center justify-center p-4" style="display: none;">
            <div class="absolute inset-0 bg-slate-900/50" @click="showCreateRoomModal = false"></div>
            <div class="relative w-full max-w-2xl rounded-2xl bg-white border border-slate-200 shadow-2xl overflow-hidden">
                <div class="flex items-center justify-between px-6 py-4 border-b border-slate-200 bg-slate-50">
                    <h3 class="text-lg font-bold text-slate-900">Añadir habitación</h3>
                    <button type="button" @click="showCreateRoomModal = false" class="w-9 h-9 rounded-lg text-red-600 hover:bg-red-50 hover:text-red-700">&times;</button>
                </div>
                <form method="POST" action="{{ route('admin.habitaciones.store') }}" class="p-6 space-y-4">
                    @csrf
                    @if ($errors->room->any())
                        <div class="rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700">
                            {{ $errors->room->first() }}
                        </div>
                    @endif
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1">Piso</label>
                            <select name="floor_id" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 text-sm" required>
                                <option value="">Selecciona un piso</option>
                                @foreach ($floors as $floor)
                                    <option value="{{ $floor->id }}" {{ (string) old('floor_id') === (string) $floor->id ? 'selected' : '' }}>
                                        Piso {{ $floor->number }} - {{ $floor->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1">N° habitación</label>
                            <input type="text" name="room_number" value="{{ old('room_number') }}" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 text-sm" placeholder="Ej: 101" required>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1">Tipo</label>
                            <div class="flex gap-2">
                                <select name="type" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 text-sm" required>
                                    <option value="">Selecciona tipo</option>
                                    @foreach ($roomTypes as $type)
                                        <option value="{{ $type->name }}" {{ old('type') === $type->name ? 'selected' : '' }}>{{ $type->name }}</option>
                                    @endforeach
                                </select>
                                <button type="button" @click="showTypesModal = true" class="px-3 rounded-lg border border-indigo-200 bg-indigo-50 text-indigo-700 text-sm font-semibold hover:bg-indigo-100">+</button>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1">Estado</label>
                            <select name="active" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 text-sm">
                                <option value="1" {{ old('active', '1') === '1' ? 'selected' : '' }}>Activa</option>
                                <option value="0" {{ old('active') === '0' ? 'selected' : '' }}>Inactiva</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1">Tarifa por hora</label>
                            <input type="number" step="0.01" min="0" name="hourly_rate" value="{{ old('hourly_rate') }}" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 text-sm" placeholder="0.00">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1">Tarifa por día</label>
                            <input type="number" step="0.01" min="0" name="daily_rate" value="{{ old('daily_rate') }}" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 text-sm" placeholder="0.00">
                        </div>
                    </div>
                    <div class="flex justify-end">
                        <button type="submit" class="px-4 py-2 rounded-lg bg-blue-900 text-white text-sm font-semibold hover:bg-blue-800">
                            Guardar habitación
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div x-cloak x-show="showTypesModal" x-transition class="fixed inset-0 z-[75] flex items-center justify-center p-4" style="display: none;">
            <div class="absolute inset-0 bg-slate-900/50" @click="showTypesModal = false"></div>
            <div class="relative w-full max-w-3xl rounded-2xl bg-white border border-slate-200 shadow-2xl overflow-hidden">
                <div class="flex items-center justify-between px-6 py-4 border-b border-slate-200 bg-slate-50">
                    <h3 class="text-lg font-bold text-slate-900">Gestión de tipos de habitación</h3>
                    <button type="button" @click="showTypesModal = false" class="w-9 h-9 rounded-lg text-red-600 hover:bg-red-50 hover:text-red-700">&times;</button>
                </div>
                <div class="p-6 space-y-4 max-h-[75vh] overflow-y-auto">
                    @if ($errors->roomType->any())
                        <div class="rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700">
                            {{ $errors->roomType->first() }}
                        </div>
                    @endif
                    <form method="POST" action="{{ route('admin.habitaciones.types.store') }}" class="grid grid-cols-1 md:grid-cols-3 gap-3">
                        @csrf
                        <input type="text" name="name" class="md:col-span-2 rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 text-sm" placeholder="Nombre del tipo (Ej: Simple)" required>
                        <button type="submit" class="px-4 py-2 rounded-lg bg-indigo-700 text-white text-sm font-semibold hover:bg-indigo-800">
                            Guardar tipo
                        </button>
                    </form>
                    <div class="rounded-xl border border-slate-200 overflow-hidden">
                        <table class="min-w-full text-sm">
                            <thead class="bg-slate-50 text-left">
                                <tr>
                                    <th class="px-4 py-3">Código</th>
                                    <th class="px-4 py-3">Tipo</th>
                                    <th class="px-4 py-3">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($roomTypeRows as $type)
                                    <tr class="border-t">
                                        <td class="px-4 py-3 font-semibold">{{ $type->code }}</td>
                                        <td class="px-4 py-3">{{ $type->name }}</td>
                                        <td class="px-4 py-3">
                                            <button
                                                type="button"
                                                x-on:click="openEditType({{ \Illuminate\Support\Js::from(['id' => $type->id, 'name' => $type->name]) }})"
                                                class="px-3 py-1.5 rounded-lg bg-amber-500 text-white text-xs font-semibold hover:bg-amber-600"
                                            >
                                                Editar
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="3" class="px-4 py-4 text-center text-slate-500">No hay tipos registrados.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div x-cloak x-show="showPisosModal" x-transition class="fixed inset-0 z-50 flex items-center justify-center p-4" style="display: none;">
            <div class="absolute inset-0 bg-slate-900/50" @click="showPisosModal = false"></div>
            <div class="relative w-full max-w-3xl rounded-2xl bg-white border border-slate-200 shadow-2xl overflow-hidden">
                <div class="flex items-center justify-between px-6 py-4 border-b border-slate-200 bg-slate-50">
                    <h3 class="text-lg font-bold text-slate-900">Gestión de pisos</h3>
                    <button type="button" @click="showPisosModal = false" class="w-9 h-9 rounded-lg text-red-600 hover:bg-red-50 hover:text-red-700">&times;</button>
                </div>

                <div class="p-6 space-y-4 max-h-[75vh] overflow-y-auto">
                    @if (session('floor_success'))
                        <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
                            {{ session('floor_success') }}
                        </div>
                    @endif
                    @if ($errors->floor->any())
                        <div class="rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700">
                            {{ $errors->floor->first() }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('admin.habitaciones.floors.store') }}" class="grid grid-cols-1 md:grid-cols-3 gap-3">
                        @csrf
                        <input
                            type="text"
                            name="name"
                            class="rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 text-sm"
                            placeholder="Nombre del piso"
                            required
                        >
                        <input
                            type="number"
                            name="number"
                            min="1"
                            class="rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 text-sm"
                            placeholder="N° piso"
                            required
                        >
                        <button type="submit" class="px-4 py-2 rounded-lg bg-blue-900 text-white text-sm font-semibold hover:bg-blue-800">
                            Guardar piso
                        </button>
                    </form>

                    <div class="rounded-xl border border-slate-200 overflow-hidden">
                        <table class="min-w-full text-sm">
                            <thead class="bg-slate-50 text-left">
                                <tr>
                                    <th class="px-4 py-3">Código</th>
                                    <th class="px-4 py-3">N° Piso</th>
                                    <th class="px-4 py-3">Nombre</th>
                                    <th class="px-4 py-3">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($floors as $floor)
                                    <tr class="border-t">
                                        <td class="px-4 py-3 font-semibold">{{ $floor->code }}</td>
                                        <td class="px-4 py-3">{{ $floor->number }}</td>
                                        <td class="px-4 py-3">{{ $floor->name }}</td>
                                        <td class="px-4 py-3">
                                            <button
                                                type="button"
                                                @click='openEditFloor(@json(["id" => $floor->id, "name" => $floor->name, "number" => $floor->number]))'
                                                class="px-3 py-1.5 rounded-lg bg-amber-500 text-white text-xs font-semibold hover:bg-amber-600"
                                            >
                                                Editar
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-4 py-4 text-center text-slate-500">No hay pisos registrados.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div x-cloak x-show="showEditFloorModal" x-transition class="fixed inset-0 z-[80] flex items-center justify-center p-4" style="display: none;">
            <div class="absolute inset-0 bg-slate-900/55" @click="showEditFloorModal = false"></div>
            <div class="relative w-full max-w-lg rounded-2xl bg-white border border-slate-200 shadow-2xl overflow-hidden">
                <div class="flex items-center justify-between px-6 py-4 border-b border-slate-200 bg-slate-50">
                    <h3 class="text-lg font-bold text-slate-900">Editar piso</h3>
                    <button type="button" @click="showEditFloorModal = false" class="w-9 h-9 rounded-lg text-red-600 hover:bg-red-50 hover:text-red-700">&times;</button>
                </div>
                <form :action="'{{ route('admin.habitaciones.floors.update', ['floor' => '__ID__']) }}'.replace('__ID__', editFloorId ?? '')" method="POST" class="p-6 space-y-4">
                    @csrf
                    @method('PATCH')
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Nombre del piso</label>
                        <input type="text" name="name" x-model="editFloorName" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 text-sm" required>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">N° piso</label>
                        <input type="number" min="1" name="number" x-model="editFloorNumber" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 text-sm" required>
                    </div>
                    <div class="flex justify-end">
                        <button type="submit" class="px-4 py-2 rounded-lg bg-blue-900 text-white text-sm font-semibold hover:bg-blue-800">Guardar cambios</button>
                    </div>
                </form>
            </div>
        </div>

        <div x-cloak x-show="showEditTypeModal" x-transition class="fixed inset-0 z-[85] flex items-center justify-center p-4" style="display: none;">
            <div class="absolute inset-0 bg-slate-900/55" @click="showEditTypeModal = false"></div>
            <div class="relative w-full max-w-lg rounded-2xl bg-white border border-slate-200 shadow-2xl overflow-hidden">
                <div class="flex items-center justify-between px-6 py-4 border-b border-slate-200 bg-slate-50">
                    <h3 class="text-lg font-bold text-slate-900">Editar tipo de habitación</h3>
                    <button type="button" @click="showEditTypeModal = false" class="w-9 h-9 rounded-lg text-red-600 hover:bg-red-50 hover:text-red-700">&times;</button>
                </div>
                <form :action="'{{ route('admin.habitaciones.types.update', ['type' => '__ID__']) }}'.replace('__ID__', editTypeId ?? '')" method="POST" class="p-6 space-y-4">
                    @csrf
                    @method('PATCH')
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Nombre del tipo</label>
                        <input type="text" name="name" x-model="editTypeName" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 text-sm" required>
                    </div>
                    <div class="flex justify-end">
                        <button type="submit" class="px-4 py-2 rounded-lg bg-indigo-700 text-white text-sm font-semibold hover:bg-indigo-800">Guardar cambios</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-layouts.admin>
