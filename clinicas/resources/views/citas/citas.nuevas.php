<x-app-layout>
    <x-slot name="encabezado">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Nueva Cita Médica') }}
            </h2>
            <a href="{{ route('citas.index') }}" class="text-sm text-gray-600 hover:text-gray-900">
                ← Volver al listado
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow sm:rounded-lg p-6">
                
                <form method="POST" action="{{ route('citas.store') }}">
                    @csrf

                    {{-- Información del Paciente --}}
                    <div class="mb-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Información del Paciente</h3>
                        
                        <div class="mb-4">
                            <label for="nombre_paciente" class="block text-sm font-medium text-gray-700">Nombre del Paciente *</label>
                            <input type="text" name="nombre_paciente" id="nombre_paciente" value="{{ old('nombre_paciente') }}" 
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                            @error('nombre_paciente')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Información del Doctor --}}
                    <div class="mb-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Información del Doctor</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label for="nombre_doctor" class="block text-sm font-medium text-gray-700">Nombre del Doctor *</label>
                                <input type="text" name="nombre_doctor" id="nombre_doctor" value="{{ old('nombre_doctor') }}" 
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                @error('nombre_doctor')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="especialidad" class="block text-sm font-medium text-gray-700">Especialidad *</label>
                                <select name="especialidad" id="especialidad" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                    <option value="">Seleccionar...</option>
                                    <option value="Medicina General" {{ old('especialidad') == 'Medicina General' ? 'selected' : '' }}>Medicina General</option>
                                    <option value="Cardiología" {{ old('especialidad') == 'Cardiología' ? 'selected' : '' }}>Cardiología</option>
                                    <option value="Dermatología" {{ old('especialidad') == 'Dermatología' ? 'selected' : '' }}>Dermatología</option>
                                    <option value="Pediatría" {{ old('especialidad') == 'Pediatría' ? 'selected' : '' }}>Pediatría</option>
                                    <option value="Traumatología" {{ old('especialidad') == 'Traumatología' ? 'selected' : '' }}>Traumatología</option>
                                    <option value="Oftalmología" {{ old('especialidad') == 'Oftalmología' ? 'selected' : '' }}>Oftalmología</option>
                                    <option value="Odontología" {{ old('especialidad') == 'Odontología' ? 'selected' : '' }}>Odontología</option>
                                    <option value="Ginecología" {{ old('especialidad') == 'Ginecología' ? 'selected' : '' }}>Ginecología</option>
                                    <option value="Psiquiatría" {{ old('especialidad') == 'Psiquiatría' ? 'selected' : '' }}>Psiquiatría</option>
                                    <option value="Neurología" {{ old('especialidad') == 'Neurología' ? 'selected' : '' }}>Neurología</option>
                                </select>
                                @error('especialidad')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- Fecha y Hora --}}
                    <div class="mb-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Fecha y Hora de la Cita</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label for="fecha_cita" class="block text-sm font-medium text-gray-700">Fecha *</label>
                                <input type="date" name="fecha_cita" id="fecha_cita" value="{{ old('fecha_cita') }}" 
                                       min="{{ date('Y-m-d') }}"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                @error('fecha_cita')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="hora_cita" class="block text-sm font-medium text-gray-700">Hora *</label>
                                <input type="time" name="hora_cita" id="hora_cita" value="{{ old('hora_cita') }}" 
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                @error('hora_cita')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- Motivo y Notas --}}
                    <div class="mb-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Detalles Adicionales</h3>
                        
                        <div class="mb-4">
                            <label for="razon" class="block text-sm font-medium text-gray-700">Motivo de la Consulta</label>
                            <textarea name="razon" id="razon" rows="3" 
                                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" 
                                      placeholder="Describe brevemente el motivo de la consulta...">{{ old('razon') }}</textarea>
                            @error('razon')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="notas" class="block text-sm font-medium text-gray-700">Notas Adicionales</label>
                            <textarea name="notas" id="notas" rows="3" 
                                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" 
                                      placeholder="Información adicional, recordatorios, etc...">{{ old('notas') }}</textarea>
                            @error('notas')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Botones --}}
                    <div class="flex justify-end space-x-3">
                        <a href="{{ route('citas.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400">
                            Cancelar
                        </a>
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700">
                            💾 Crear Cita
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>