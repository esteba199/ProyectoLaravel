<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Mis Citas Médicas') }}
            </h2>
            <a href="{{ route('citas.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700">
                ➕ Nueva Cita
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Widget de Clima --}}
            @if(isset($clima))
                <div class="mb-6 bg-white overflow-hidden shadow sm:rounded-lg p-4 flex items-center justify-between border-l-4 {{ $climaAdverso ? 'border-red-500' : 'border-blue-500' }}">
                    <div class="flex items-center">
                        <img src="http://openweathermap.org/img/wn/{{ $clima['weather'][0]['icon'] }}@2x.png" class="w-16 h-16" alt="clima">
                        <div class="ml-4">
                            <p class="text-sm text-gray-500 uppercase font-bold tracking-wider">Estado del Clima en {{ $clima['name'] ?? 'tu zona' }}</p>
                            <p class="text-xl font-bold text-gray-900">{{ round($clima['main']['temp'] ?? 0) }}°C - {{ $clima['weather'][0]['description'] ?? '' }}</p>
                        </div>
                    </div>
                    @if($climaAdverso)
                        <div class="text-right">
                            <span class="px-3 py-1 bg-red-100 text-red-800 rounded-full text-xs font-bold uppercase ring-1 ring-red-400">
                                ⚠️ Aviso de Clima Adverso
                            </span>
                        </div>
                    @endif
                </div>
            @endif

            {{-- Mensajes de éxito --}}
            @if (session('exito'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('exito') }}</span>
                </div>
            @endif

            {{-- Tabla de citas --}}
            <div class="bg-white shadow sm:rounded-lg overflow-hidden">
                @if($citas->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Hora</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Paciente</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Doctor</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Especialidad</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($citas as $cita)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $cita->fecha->format('d/m/Y') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ \Carbon\Carbon::parse($cita->hora)->format('H:i') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            {{ $cita->paciente }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $cita->doctor }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $cita->especialidad }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $cita->claseEstado() }}">
                                                {{ $cita->etiquetaEstado() }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                                            <a href="{{ route('citas.show', $cita) }}" class="text-indigo-600 hover:text-indigo-900">Ver</a>
                                            <a href="{{ route('citas.edit', $cita) }}" class="text-yellow-600 hover:text-yellow-900">Editar</a>
                                            <form action="{{ route('citas.destroy', $cita) }}" method="POST" class="inline" onsubmit="return confirm('¿Estás seguro de eliminar esta cita?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900">Eliminar</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- Paginación --}}
                    <div class="px-6 py-4 bg-gray-50">
                        {{ $citas->links() }}
                    </div>
                @else
                    <div class="px-6 py-12 text-center">
                        <p class="text-gray-500 text-lg">No hay citas registradas</p>
                        <a href="{{ route('citas.create') }}" class="mt-4 inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700">
                            Crear Primera Cita
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
