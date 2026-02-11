<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Perfil de Usuario') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            {{-- Tarjeta de Clima --}}
            @if(isset($clima))
                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <div class="max-w-xl">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Estado del Tiempo</h3>
                        
                        <div class="flex items-center space-x-4">
                            <div class="text-4xl">
                                @php
                                    $icon = $clima['weather'][0]['icon'] ?? '01d';
                                @endphp
                                <img src="http://openweathermap.org/img/wn/{{ $icon }}@2x.png" alt="clima">
                            </div>
                            <div>
                                <p class="text-2xl font-bold">{{ round($clima['main']['temp'] ?? 0) }}°C</p>
                                <p class="text-gray-600 capitalize">{{ $clima['weather'][0]['description'] ?? '' }}</p>
                                <p class="text-sm text-gray-500">Ubicación: {{ $clima['name'] ?? 'Coordenadas guardadas' }}</p>
                            </div>
                        </div>

                        @if($climaAdverso)
                            <div class="mt-4 p-4 bg-red-100 border-l-4 border-red-500 text-red-700">
                                <p class="font-bold">⚠️ AVISO RESTRICTIVO</p>
                                <p>Las condiciones climáticas son adversas. Se recomienda precaución extrema o posponer traslados no urgentes.</p>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
