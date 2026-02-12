<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-bold text-2xl text-indigo-900 leading-tight">
                {{ __('Nueva Cita Médica') }}
            </h2>
            <a href="{{ route('citas.index') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-800 transition duration-150 ease-in-out flex items-center gap-1">
                ← Volver al listado
            </a>
        </div>
    </x-slot>

    <div class="py-12 bg-[url('https://images.unsplash.com/photo-1576091160399-112ba8d25d1d?q=80&w=2070&auto=format&fit=crop')] bg-cover bg-fixed bg-center min-h-screen relative">
        <div class="absolute inset-0 bg-slate-50/90 backdrop-blur-sm z-0"></div>
        
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 relative z-10">
            <div class="bg-white/70 backdrop-blur-xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] sm:rounded-3xl p-8 border border-white/50">
                
                <form method="POST" action="{{ route('citas.store') }}">
                    @csrf

                    {{-- Información del Paciente --}}
                    <div class="mb-10">
                        <h3 class="text-xl font-bold text-slate-800 mb-6 flex items-center gap-3">
                            <span class="bg-gradient-to-br from-indigo-500 to-violet-500 text-white rounded-xl h-8 w-8 flex items-center justify-center text-sm shadow-lg shadow-indigo-500/20">1</span>
                            Información del Paciente
                        </h3>
                        
                        <div class="mb-4 group">
                            <label for="paciente" class="block text-sm font-semibold text-slate-600 mb-2 ml-1 transition-colors group-focus-within:text-indigo-600">Nombre del Paciente <span class="text-indigo-500">*</span></label>
                            <input type="text" name="paciente" id="paciente" value="{{ old('paciente') }}" 
                                   class="mt-1 block w-full rounded-2xl border-0 bg-white/60 ring-1 ring-slate-200 shadow-sm focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all duration-300 py-3 px-4 text-slate-800 placeholder-slate-400" placeholder="Ej. Juan Pérez" required>
                            @error('paciente')
                                <p class="mt-2 text-sm text-rose-500 font-medium flex items-center gap-1 animate-pulse">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>

                    {{-- Información del Doctor --}}
                    <div class="mb-10">
                        <h3 class="text-xl font-bold text-slate-800 mb-6 flex items-center gap-3">
                            <span class="bg-gradient-to-br from-indigo-500 to-violet-500 text-white rounded-xl h-8 w-8 flex items-center justify-center text-sm shadow-lg shadow-indigo-500/20">2</span>
                            Información del Doctor
                        </h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-4">
                            <div class="group">
                                <label for="doctor" class="block text-sm font-semibold text-slate-600 mb-2 ml-1 transition-colors group-focus-within:text-indigo-600">Nombre del Doctor <span class="text-indigo-500">*</span></label>
                                <input type="text" name="doctor" id="doctor" value="{{ old('doctor') }}" 
                                       class="mt-1 block w-full rounded-2xl border-0 bg-white/60 ring-1 ring-slate-200 shadow-sm focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all duration-300 py-3 px-4 text-slate-800 placeholder-slate-400" required>
                                @error('doctor')
                                    <p class="mt-2 text-sm text-rose-500 font-medium">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="group">
                                <label for="especialidad" class="block text-sm font-semibold text-slate-600 mb-2 ml-1 transition-colors group-focus-within:text-indigo-600">Especialidad <span class="text-indigo-500">*</span></label>
                                <select name="especialidad" id="especialidad" class="mt-1 block w-full rounded-2xl border-0 bg-white/60 ring-1 ring-slate-200 shadow-sm focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all duration-300 py-3 px-4 text-slate-800" required>
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
                                    <p class="mt-2 text-sm text-rose-500 font-medium">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- Fecha y Hora --}}
                    <div class="mb-10">
                        <h3 class="text-xl font-bold text-slate-800 mb-6 flex items-center gap-3">
                             <span class="bg-gradient-to-br from-indigo-500 to-violet-500 text-white rounded-xl h-8 w-8 flex items-center justify-center text-sm shadow-lg shadow-indigo-500/20">3</span>
                            Fecha y Hora de la Cita
                        </h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-4">
                            <div class="group">
                                <label for="fecha" class="block text-sm font-semibold text-slate-600 mb-2 ml-1 transition-colors group-focus-within:text-indigo-600">Fecha <span class="text-indigo-500">*</span></label>
                                <input type="date" name="fecha" id="fecha" value="{{ old('fecha') }}" 
                                       min="{{ date('Y-m-d') }}"
                                       class="mt-1 block w-full rounded-2xl border-0 bg-white/60 ring-1 ring-slate-200 shadow-sm focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all duration-300 py-3 px-4 text-slate-800" required>
                                @error('fecha')
                                    <p class="mt-2 text-sm text-rose-500 font-medium">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="group">
                                <label for="hora" class="block text-sm font-semibold text-slate-600 mb-2 ml-1 transition-colors group-focus-within:text-indigo-600">Hora <span class="text-indigo-500">*</span></label>
                                <input type="time" name="hora" id="hora" value="{{ old('hora') }}" 
                                       class="mt-1 block w-full rounded-2xl border-0 bg-white/60 ring-1 ring-slate-200 shadow-sm focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all duration-300 py-3 px-4 text-slate-800" required>
                                @error('hora')
                                    <p class="mt-2 text-sm text-rose-500 font-medium">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- Motivo y Notas --}}
                    <div class="mb-20">
                         <h3 class="text-xl font-bold text-slate-800 mb-6 flex items-center gap-3">
                             <span class="bg-gradient-to-br from-indigo-500 to-violet-500 text-white rounded-xl h-8 w-8 flex items-center justify-center text-sm shadow-lg shadow-indigo-500/20">4</span>
                            Detalles Adicionales
                        </h3>
                        
                        <div class="mb-6 group">
                            <label for="motivo" class="block text-sm font-semibold text-slate-600 mb-2 ml-1 transition-colors group-focus-within:text-indigo-600">Motivo de la Consulta</label>
                            <textarea name="motivo" id="motivo" rows="3" 
                                      class="mt-1 block w-full rounded-2xl border-0 bg-white/60 ring-1 ring-slate-200 shadow-sm focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all duration-300 py-3 px-4 text-slate-800 placeholder-slate-400" 
                                      placeholder="Describe brevemente el motivo de la consulta...">{{ old('motivo') }}</textarea>
                            @error('motivo')
                                <p class="mt-2 text-sm text-rose-500 font-medium">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-6 group">
                            <label for="notas" class="block text-sm font-semibold text-slate-600 mb-2 ml-1 transition-colors group-focus-within:text-indigo-600">Notas Adicionales</label>
                            <textarea name="notas" id="notas" rows="3" 
                                      class="mt-1 block w-full rounded-2xl border-0 bg-white/60 ring-1 ring-slate-200 shadow-sm focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all duration-300 py-3 px-4 text-slate-800 placeholder-slate-400" 
                                      placeholder="Información adicional, recordatorios, etc...">{{ old('notas') }}</textarea>
                            @error('notas')
                                <p class="mt-2 text-sm text-rose-500 font-medium">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Botones Flotantes (Dock Moderno) --}}
                    <div class="fixed bottom-6 left-1/2 transform -translate-x-1/2 z-50">
                        <div class="flex items-center gap-3 bg-white/80 backdrop-blur-2xl border border-white/40 shadow-2xl rounded-full p-2 pl-3 pr-2 ring-1 ring-black/5">
                            <a href="{{ route('citas.index') }}" class="inline-flex items-center justify-center w-10 h-10 rounded-full text-slate-500 hover:text-slate-800 hover:bg-slate-100 transition-all duration-300" title="Cancelar">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                            </a>
                            <div class="h-6 w-px bg-slate-200 mx-1"></div>
                            <button type="submit" class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-indigo-600 to-violet-600 text-white rounded-full font-bold text-sm tracking-wide shadow-lg shadow-indigo-500/30 hover:shadow-indigo-500/50 hover:scale-105 active:scale-95 transition-all duration-300">
                                <span>Crear Cita</span>
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                            </button>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>
