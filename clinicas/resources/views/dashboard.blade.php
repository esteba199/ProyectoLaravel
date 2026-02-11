<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Panel de Control') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    {{ __("¡Has iniciado sesión!") }}

                    @if(isset($clima))
                        <div class="mt-6 p-4 bg-gray-50 rounded-xl border flex items-center justify-between">
                            <div class="flex items-center">
                                <img src="http://openweathermap.org/img/wn/{{ $clima['weather'][0]['icon'] }}@2x.png" class="w-20 h-20" alt="icon">
                                <div>
                                    <p class="text-sm text-gray-500 font-bold uppercase">Clima en {{ $clima['name'] ?? 'tu ubicación' }}</p>
                                    <p class="text-3xl font-black text-indigo-600">{{ round($clima['main']['temp'] ?? 0) }}°C</p>
                                    <p class="text-gray-600 capitalize font-medium">{{ $clima['weather'][0]['description'] ?? '' }}</p>
                                </div>
                            </div>

                            @if($climaAdverso)
                                <div class="bg-red-50 p-4 rounded-lg border border-red-200 max-w-xs">
                                    <p class="text-red-700 font-bold flex items-center">
                                        <span class="mr-2">⚠️</span> AVISO CLIMÁTICO
                                    </p>
                                    <p class="text-red-600 text-xs mt-1">Se detectan condiciones adversas. Por favor, toma las precauciones necesarias para tus citas de hoy.</p>
                                </div>
                            @endif
                        </div>
                    @else
                        <div class="mt-6 p-4 bg-yellow-50 rounded-lg border border-yellow-200 text-yellow-700 text-sm italic">
                            💡 Configura tu latitud y longitud en tu <b>Perfil</b> para ver el clima de tu región.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
