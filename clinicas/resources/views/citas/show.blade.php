<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Detalles de la Cita') }}
            </h2>
            <a href="{{ route('citas.index') }}" class="text-sm text-gray-600 hover:text-gray-900">
                ← Volver al listado
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow sm:rounded-lg p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 uppercase">Paciente</h3>
                        <p class="mt-1 text-lg text-gray-900">{{ $cita->paciente }}</p>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 uppercase">Doctor</h3>
                        <p class="mt-1 text-lg text-gray-900">{{ $cita->doctor }} ({{ $cita->especialidad }})</p>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 uppercase">Fecha y Hora</h3>
                        <p class="mt-1 text-lg text-gray-900">{{ $cita->fecha->format('d/m/Y') }} a las {{ \Carbon\Carbon::parse($cita->hora)->format('H:i') }}</p>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 uppercase">Estado</h3>
                        <span class="mt-1 px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $cita->claseEstado() }}">
                            {{ $cita->etiquetaEstado() }}
                        </span>
                    </div>
                    <div class="md:col-span-2">
                        <h3 class="text-sm font-medium text-gray-500 uppercase">Motivo</h3>
                        <p class="mt-1 text-gray-900">{{ $cita->motivo ?? 'Sin motivo especificado' }}</p>
                    </div>
                    <div class="md:col-span-2">
                        <h3 class="text-sm font-medium text-gray-500 uppercase">Notas</h3>
                        <p class="mt-1 text-gray-900">{{ $cita->notas ?? 'Sin notas' }}</p>
                    </div>
                </div>

                <div class="mt-6 flex justify-end space-x-3">
                    <a href="{{ route('citas.edit', $cita) }}" class="inline-flex items-center px-4 py-2 bg-yellow-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-700">
                        Editar
                    </a>
                    <form action="{{ route('citas.destroy', $cita) }}" method="POST" class="inline" onsubmit="return confirm('¿Estás seguro de eliminar esta cita?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700">
                            Eliminar
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
