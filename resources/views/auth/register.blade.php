@extends('layouts.main')

@section('content')
<x-guest-layout wide>
    
    <div class="w-full px-4 md:px-0">
        <div class="max-w-7xl mx-auto bg-white dark:bg-gray-800 shadow-lg rounded-xl p-10 md:p-12">
            
            <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-200 mb-6">
                Registrasi Akun
            </h1>

            <form method="POST" action="{{ route('register') }}" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @csrf

                <div class="space-y-4">
                    <div>
                        <x-input-label for="name" :value="__('Name')" />
                        <x-text-input id="name" class="block w-full mt-1" type="text" name="name" :value="old('name')" required />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="email" :value="__('Email')" />
                        <x-text-input id="email" class="block w-full mt-1" type="email" name="email" :value="old('email')" required />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="password" :value="__('Password')" />
                        <x-text-input id="password" class="block w-full mt-1" type="password" name="password" required />
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
                        <x-text-input id="password_confirmation" class="block w-full mt-1" type="password" name="password_confirmation" required />
                    </div>

                    <div>
                        <x-input-label for="telp" value="Telp / No. HP" />
                        <x-text-input id="telp" class="block w-full mt-1" type="text" name="telp" required />
                        <x-input-error :messages="$errors->get('telp')" class="mt-2" />
                    </div>
                </div>

                <div class="space-y-4">
                    <div>
                        <x-input-label for="npwp">NPWP (optional)</x-input-label>
                        <x-text-input id="npwp" class="block w-full mt-1" type="text" name="npwp" :value="old('npwp')" />
                    </div>

                    <div>
                        <x-input-label for="no_identitas">No. Identitas</x-input-label>
                        <x-text-input id="no_identitas" class="block w-full mt-1" type="text" name="no_identitas" :value="old('no_identitas')" required />
                    </div>

                    <div>
                        <x-input-label for="pekerjaan">Pekerjaan</x-input-label>
                        <x-text-input id="pekerjaan" class="block w-full mt-1" type="text" name="pekerjaan" :value="old('pekerjaan')" required />
                    </div>

                    <div>
                        <x-input-label for="pendidikan">Pendidikan Terakhir</x-input-label>
                        <select name="pendidikan" id="pendidikan"
                            class="block w-full mt-1 border-gray-300 rounded-md shadow-sm dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">Pilih pendidikan</option>
                            <option value="sd">SD</option>
                            <option value="smp">SMP</option>
                            <option value="sma">SMA</option>
                            <option value="d3">D3</option>
                            <option value="s1">S1</option>
                            <option value="s2">S2</option>
                            <option value="s3">S3</option>
                        </select>
                    </div>
                </div>

                <div class="md:col-span-2">
                    <x-input-label for="alamat">Alamat</x-input-label>
                    <textarea name="alamat" id="alamat" rows="3"
                        class="block w-full mt-1 border-gray-300 rounded-md shadow-sm dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                </div>

                <div class="flex items-center justify-between mt-6 md:col-span-2">
                    <a href="{{ route('login') }}" class="text-sm text-gray-600 underline dark:text-gray-400">
                        Sudah punya akun?
                    </a>

                    <x-primary-button>
                        Register
                    </x-primary-button>
                </div>

            </form>
        </div>
    </div>

</x-guest-layout>
@endsection
