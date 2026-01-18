@extends('layouts.main')

@section('content')
    <x-guest-layout>
        <form method="POST" action="{{ route('register') }}" class="grid grid-cols-1 gap-5 md:grid-cols-2">
            @csrf

            <div>
                <div>
                    <x-input-label for="name" :value="__('Name')" />
                    <x-text-input id="name" class="block w-full mt-1" type="text" name="name" :value="old('name')"
                        required autofocus autocomplete="name" />
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>

                <div class="mt-4">
                    <x-input-label for="email" :value="__('Email')" />
                    <x-text-input id="email" class="block w-full mt-1" type="email" name="email" :value="old('email')"
                        required autocomplete="username" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <div class="flex flex-col gap-5 lg:flex-row">

                    <div class="mt-4">
                        <x-input-label for="password" :value="__('Password')" />

                        <x-text-input id="password" class="block w-full mt-1" type="password" name="password" required
                            autocomplete="new-password" />

                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <div class="mt-4">
                        <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

                        <x-text-input id="password_confirmation" class="block w-full mt-1" type="password"
                            name="password_confirmation" required autocomplete="new-password" />

                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                    </div>
                </div>

                <div class="mt-4">
                    <x-input-label for="telp" value="Telp/ No. HP" />

                    <x-text-input id="telp" class="block w-full mt-1" type="text" name="telp" required />

                    <x-input-error :messages="$errors->get('telp')" class="mt-2" />
                </div>
            </div>

            <div>
                <div>
                    <x-input-label for="npwp">NPWP (optional)</x-input-label>
                    <x-text-input id="npwp" class="block w-full mt-1" type="text" name="npwp" :value="old('npwp')"
                        autofocus autocomplete="npwp" />
                    <x-input-error :messages="$errors->get('npwp')" class="mt-2" />
                </div>

                <div class="mt-4">
                    <x-input-label for="no_identitas">No. Identitas (SIM/ KTP/ Passport/ KITAS)</x-input-label>
                    <x-text-input id="no_identitas" class="block w-full mt-1" type="text" name="no_identitas"
                        :value="old('no_identitas')" required autofocus autocomplete="no_identitas" />
                    <x-input-error :messages="$errors->get('no_identitas')" class="mt-2" />
                </div>

                <div class="mt-4">
                    <x-input-label for="pekerjaan">Pekerjaan</x-input-label>
                    <x-text-input id="pekerjaan" class="block w-full mt-1" type="text" name="pekerjaan"
                        :value="old('pekerjaan')" required autofocus autocomplete="pekerjaan" />
                    <x-input-error :messages="$errors->get('pekerjaan')" class="mt-2" />
                </div>

                <div class="mt-4">
                    <x-input-label for="pendidikan">Pendidikan terakhir</x-input-label>
                    <select name="pendidikan" id="pendidikan"
                        class="block w-full mt-1 truncate border-gray-300 rounded-md shadow-sm dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600">
                        <option value="">Pilih pendidikan</option>
                        <option value="sd">SD</option>
                        <option value="smp">SMP</option>
                        <option value="sma">SMA</option>
                        <option value="d3">D3</option>
                        <option value="s1">S1</option>
                        <option value="s2">S2</option>
                        <option value="s3">S3</option>
                    </select>
                    <x-input-error :messages="$errors->get('pendidikan')" class="mt-2" />
                </div>
            </div>

            <div class="md:col-span-2">
                <x-input-label for="alamat">Alamat</x-input-label>
                <textarea name="alamat" id="alamat" rows="3"
                    class="block w-full mt-1 truncate border-gray-300 rounded-md shadow-sm dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600"></textarea>
                <x-input-error :messages="$errors->get('alamat')" class="mt-2" />
            </div>

            <div class="flex items-center justify-end mt-4 md:col-span-2">
                <a class="text-sm text-gray-600 underline rounded-md dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800"
                    href="{{ route('login') }}">
                    {{ __('Already registered?') }}
                </a>

                <x-primary-button class="ms-4">
                    {{ __('Register') }}
                </x-primary-button>
            </div>
        </form>
    </x-guest-layout>
@endsection
