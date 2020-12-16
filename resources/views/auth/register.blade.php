<x-guest-layout>
    <x-jet-authentication-card>
        <x-slot name="logo">
            <x-jet-authentication-card-logo/>
        </x-slot>

        <x-jet-validation-errors class="mb-4"/>

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div>
                <x-jet-label value="{{ __('Name') }}"/>
                <x-jet-input class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus
                             autocomplete="name"/>
            </div>

            <div class="mt-4">
                <x-jet-label value="{{ __('Email') }}"/>
                <x-jet-input class="block mt-1 w-full" type="email" name="email" :value="old('email')" required/>
            </div>

            <div class="mt-4">
                <x-jet-label value="{{ __('Password') }}"/>
                <x-jet-input class="block mt-1 w-full" type="password" name="password" required
                             autocomplete="new-password"/>
            </div>

            <div class="mt-4">
                <x-jet-label value="{{ __('Confirm Password') }}"/>
                <x-jet-input class="block mt-1 w-full" type="password" name="password_confirmation" required
                             autocomplete="new-password"/>
            </div>

            <div class="flex items-center justify-end mt-4 flex-col">
                <div class="flex items-center align-content-center">
                    <a class="underline text-sm text-gray-600 hover:text-gray-900 mr-3" href="{{ route('login') }}">
                        {{ __('Already registered?') }}
                    </a>

                    <a class="underline text-sm text-gray-600 hover:text-gray-900" href="{{ route('facebook-login') }}">
                        {{ __('Facebook register') }}
                    </a>
                </div>
                <div class="flex mt-3">
                    <x-jet-button class="ml-4">
                        {{ __('Register') }}
                    </x-jet-button>
                    <a href="{{route('home')}}"
                       class="inline-flex items-center px-4 py-2 bg-red-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 active:bg-red-900 focus:outline-none focus:border-red-900 focus:shadow-outline-gray disabled:opacity-25 transition ease-in-out duration-150 ml-4">
                        {{ __('Back') }}
                    </a>
                </div>
            </div>
        </form>
    </x-jet-authentication-card>
</x-guest-layout>
