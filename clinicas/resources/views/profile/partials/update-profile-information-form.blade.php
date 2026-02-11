<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Información del Perfil') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __("Actualiza la información de tu cuenta, correo electrónico y coordenadas de ubicación.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <div>
            <x-input-label for="nombre" :value="__('Nombre')" />
            <x-text-input id="nombre" name="nombre" type="text" class="mt-1 block w-full" :value="old('nombre', $user->nombre)" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('nombre')" />
        </div>

        <div>
            <x-input-label for="correo" :value="__('Correo Electrónico')" />
            <x-text-input id="correo" name="correo" type="email" class="mt-1 block w-full" :value="old('correo', $user->correo)" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('correo')" />
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <x-input-label for="latitud" :value="__('Latitud')" />
                <x-text-input id="latitud" name="latitud" type="text" class="mt-1 block w-full" :value="old('latitud', $user->latitud)" placeholder="Ej: 40.4168" />
                <x-input-error class="mt-2" :messages="$errors->get('latitud')" />
            </div>

            <div>
                <x-input-label for="longitud" :value="__('Longitud')" />
                <x-text-input id="longitud" name="longitud" type="text" class="mt-1 block w-full" :value="old('longitud', $user->longitud)" placeholder="Ej: -3.7038" />
                <x-input-error class="mt-2" :messages="$errors->get('longitud')" />
            </div>
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Guardar') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600"
                >{{ __('Guardado correctamente.') }}</p>
            @endif
        </div>
    </form>
</section>
