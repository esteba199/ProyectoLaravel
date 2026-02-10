<x-app-layout>
    <x-slot name="encabezado">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Perfil de Usuario') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            {{-- Mensajes de éxito --}}
            @if (session('exito'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('exito') }}</span>
                </div>
            @endif

            {{-- Información básica del usuario --}}
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Información del Perfil</h3>
                    
                    <form method="POST" action="{{ route('perfil.actualizar') }}">
                        @csrf
                        @method('PATCH')

                        <div class="mb-4">
                            <label for="nombre" class="block text-sm font-medium text-gray-700">Nombre</label>
                            <input type="text" name="nombre" id="nombre" value="{{ old('nombre', $usuario->nombre) }}" 
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                            @error('nombre')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                            <input type="email" name="email" id="email" value="{{ old('email', $usuario->email) }}" 
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                            @error('email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700">
                            Actualizar Perfil
                        </button>
                    </form>
                </div>
            </div>

            {{-- Datos del clima (si hay ubicación) --}}
            @if($datosClima)
                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Estado del Clima en tu Ubicación</h3>
                    
                    @if($datosClima['es_adverso'])
                        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                            <strong class="font-bold">⚠️ Alerta Climática:</strong>
                            <span class="block sm:inline">Se detectan condiciones climáticas adversas ({{ $datosClima['descripcion'] }}). Se recomienda precaución al salir.</span>
                        </div>
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="flex items-center space-x-3">
                            <img src="https://openweathermap.org/img/wn/{{ $datosClima['icono'] }}@2x.png" alt="Icono del clima" class="w-16 h-16">
                            <div>
                                <p class="text-3xl font-bold">{{ $datosClima['temperatura'] }}°C</p>
                                <p class="text-gray-600">{{ $datosClima['descripcion'] }}</p>
                            </div>
                        </div>
                        
                        <div>
                            <p class="text-sm text-gray-600">Sensación térmica</p>
                            <p class="text-xl font-semibold">{{ $datosClima['sensacion_termica'] }}°C</p>
                        </div>
                        
                        <div>
                            <p class="text-sm text-gray-600">Humedad</p>
                            <p class="text-xl font-semibold">{{ $datosClima['humedad'] }}%</p>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Mapa de Google Maps --}}
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Mi Ubicación</h3>
                
                @if($usuario->tieneUbicacion())
                    <div class="mb-4">
                        <p class="text-sm text-gray-600"><strong>Dirección:</strong> {{ $usuario->direccion }}</p>
                        <p class="text-sm text-gray-600"><strong>Ciudad:</strong> {{ $usuario->ciudad }}</p>
                        <p class="text-sm text-gray-600"><strong>País:</strong> {{ $usuario->pais }}</p>
                        <p class="text-sm text-gray-600"><strong>Coordenadas:</strong> {{ $usuario->latitud }}, {{ $usuario->longitud }}</p>
                    </div>
                @endif

                <div id="mapa" class="w-full h-96 rounded-lg mb-4"></div>

                <form id="formularioUbicacion" method="POST" action="{{ route('perfil.actualizar-ubicacion') }}">
                    @csrf
                    <input type="hidden" name="latitud" id="latitud" value="{{ $usuario->latitud }}">
                    <input type="hidden" name="longitud" id="longitud" value="{{ $usuario->longitud }}">
                    
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700">
                        Guardar Ubicación
                    </button>
                    
                    <button type="button" id="obtenerUbicacionActual" class="ml-2 inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">
                        📍 Usar mi ubicación actual
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- Script de Google Maps --}}
    <script src="https://maps.googleapis.com/maps/api/js?key={{ config('servicios.google_maps.clave_api') }}&libraries=places&language=es"></script>
    <script>
        let mapa;
        let marcador;
        
         // Obtener valores del HTML directamente
        const latitudInput = document.getElementById('latitud');
        const longitudInput = document.getElementById('longitud');

        // Usar los valores de los inputs o valores por defecto
        const latitudPorDefecto = latitudInput.value ? parseFloat(latitudInput.value) : 40.4168;
        const longitudPorDefecto = longitudInput.value ? parseFloat(longitudInput.value) : -3.7038;
        
        function inicializarMapa() {
            // Crear mapa
            mapa = new google.maps.Map(document.getElementById('mapa'), {
                center: { lat: latitudInput.value, lng: longitudInput.value },
                zoom: 13,
                mapTypeControl: true,
                streetViewControl: true,
                fullscreenControl: true,
            });

            // Crear marcador
            marcador = new google.maps.Marker({
                position: { lat: latitudPorDefecto, lng: longitudPorDefecto },
                map: mapa,
                draggable: true,
                title: 'Mi ubicación'
            });

            // Actualizar coordenadas cuando se mueva el marcador
            marcador.addListener('dragend', function(evento) {
                actualizarCoordenadas(evento.latLng.lat(), evento.latLng.lng());
            });

            // Permitir hacer clic en el mapa para mover el marcador
            mapa.addListener('click', function(evento) {
                marcador.setPosition(evento.latLng);
                actualizarCoordenadas(evento.latLng.lat(), evento.latLng.lng());
            });
        }

        function actualizarCoordenadas(lat, lng) {
            document.getElementById('latitud').value = lat;
            document.getElementById('longitud').value = lng;
        }

        // Obtener ubicación actual del navegador
        document.getElementById('obtenerUbicacionActual').addEventListener('click', function() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function(posicion) {
                    const pos = {
                        lat: posicion.coords.latitude,
                        lng: posicion.coords.longitude
                    };
                    
                    mapa.setCenter(pos);
                    marcador.setPosition(pos);
                    actualizarCoordenadas(pos.lat, pos.lng);
                }, function() {
                    alert('Error al obtener la ubicación');
                });
            } else {
                alert('Tu navegador no soporta geolocalización');
            }
        });

        // Inicializar mapa al cargar la página
        window.onload = inicializarMapa;
    </script>
</x-app-layout>