<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Editar Cita') }}
            </h2>
            <a href="{{ route('citas.index') }}" class="text-sm text-gray-600 hover:text-gray-900">
                ← Volver al listado
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow sm:rounded-lg p-6">
                
                <form method="POST" action="{{ route('citas.update', $cita) }}">
                    @csrf
                    @method('PUT')

                    {{-- Información del Paciente --}}
                    <div class="mb-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Información del Paciente</h3>
                        
                        <div class="mb-4">
                            <label for="paciente" class="block text-sm font-medium text-gray-700">Nombre del Paciente *</label>
                            <input type="text" name="paciente" id="paciente" value="{{ old('paciente', $cita->paciente) }}" 
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                            @error('paciente')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Información del Doctor --}}
                    <div class="mb-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Información del Doctor</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label for="doctor" class="block text-sm font-medium text-gray-700">Nombre del Doctor *</label>
                                <input type="text" name="doctor" id="doctor" value="{{ old('doctor', $cita->doctor) }}" 
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                @error('doctor')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="especialidad" class="block text-sm font-medium text-gray-700">Especialidad *</label>
                                <input type="text" name="especialidad" id="especialidad" value="{{ old('especialidad', $cita->especialidad) }}" 
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                @error('especialidad')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- Fecha, Hora y Estado --}}
                    <div class="mb-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Fecha, Hora y Estado</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                            <div>
                                <label for="fecha" class="block text-sm font-medium text-gray-700">Fecha *</label>
                                <input type="date" name="fecha" id="fecha" value="{{ old('fecha', $cita->fecha->format('Y-m-d')) }}" 
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                @error('fecha')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="hora" class="block text-sm font-medium text-gray-700">Hora *</label>
                                <input type="time" name="hora" id="hora" value="{{ old('hora', \Carbon\Carbon::parse($cita->hora)->format('H:i')) }}" 
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                @error('hora')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="estado" class="block text-sm font-medium text-gray-700">Estado *</label>
                                <select name="estado" id="estado" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                    <option value="pendiente" {{ old('estado', $cita->estado) == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                                    <option value="confirmada" {{ old('estado', $cita->estado) == 'confirmada' ? 'selected' : '' }}>Confirmada</option>
                                    <option value="completada" {{ old('estado', $cita->estado) == 'completada' ? 'selected' : '' }}>Completada</option>
                                    <option value="cancelada" {{ old('estado', $cita->estado) == 'cancelada' ? 'selected' : '' }}>Cancelada</option>
                                </select>
                                @error('estado')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- Motivo y Notas --}}
                    <div class="mb-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Detalles Adicionales</h3>
                        
                        <div class="mb-4">
                            <label for="motivo" class="block text-sm font-medium text-gray-700">Motivo de la Consulta</label>
                            <textarea name="motivo" id="motivo" rows="3" 
                                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('motivo', $cita->motivo) }}</textarea>
                            @error('motivo')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="notas" class="block text-sm font-medium text-gray-700">Notas Adicionales</label>
                            <textarea name="notas" id="notas" rows="3" 
                                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('notas', $cita->notas) }}</textarea>
                            @error('notas')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Botones --}}
                    <div class="flex justify-end space-x-3">
                        <a href="{{ route('citas.show', $cita) }}" class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400">
                            Cancelar
                        </a>
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700">
                            💾 Guardar Cambios
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>
