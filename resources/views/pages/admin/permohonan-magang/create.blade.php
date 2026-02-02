@extends('layouts.admin')

@section('content')
                <div class="mb-8">
                    <div class="flex items-center justify-between">
                        <div>
                            <h1 class="text-4xl font-bold text-gray-900 dark:text-white mb-2">
                                Tambah Permohonan Pelayanan Informasi Geofisika
                            </h1>
                            <p class="text-gray-600 dark:text-gray-400">
                                Pilih jenis layanan dan isi formulir di bawah untuk membuat permohonan baru.
                            </p>
                        </div>
                        <a href="{{ route('admin.pelayanan-jasa.index') }}"
                            class="px-4 py-3 text-white bg-red-600 rounded hover:bg-red-500 transition">
                            Kembali
                        </a>
                    </div>
                </div>

                <!-- Service Type Tabs -->
                <div class="mb-6">
                    <div class="flex flex-wrap gap-2 border-b border-gray-200 dark:border-gray-700">
                        <button type="button" 
                            onclick="selectService('magang')"
                            class="service-tab px-4 py-3 font-semibold text-gray-700 dark:text-gray-300 border-b-2 border-transparent hover:border-amber-500 transition"
                            data-service="magang">
                            <i class="fa-solid fa-graduation-cap mr-2"></i> Magang
                        </button>
                        <button type="button" 
                            onclick="selectService('klaim-asuransi')"
                            class="service-tab px-4 py-3 font-semibold text-gray-700 dark:text-gray-300 border-b-2 border-transparent hover:border-red-500 transition"
                            data-service="klaim-asuransi">
                            <i class="fa-solid fa-file-contract mr-2"></i> Klaim Asuransi
                        </button>
                        <button type="button" 
                            onclick="selectService('layanan-data')"
                            class="service-tab px-4 py-3 font-semibold text-gray-700 dark:text-gray-300 border-b-2 border-transparent hover:border-blue-500 transition"
                            data-service="layanan-data">
                            <i class="fa-solid fa-database mr-2"></i> Layanan Data
                        </button>
                        <button type="button" 
                            onclick="selectService('pemetaan')"
                            class="service-tab px-4 py-3 font-semibold text-gray-700 dark:text-gray-300 border-b-2 border-transparent hover:border-green-500 transition"
                            data-service="pemetaan">
                            <i class="fa-solid fa-map mr-2"></i> Pemetaan
                        </button>
                        <button type="button" 
                            onclick="selectService('survey')"
                            class="service-tab px-4 py-3 font-semibold text-gray-700 dark:text-gray-300 border-b-2 border-transparent hover:border-purple-500 transition"
                            data-service="survey">
                            <i class="fa-solid fa-chart-bar mr-2"></i> Survey
                        </button>
                        <button type="button" 
                            onclick="selectService('konsultasi')"
                            class="service-tab px-4 py-3 font-semibold text-gray-700 dark:text-gray-300 border-b-2 border-transparent hover:border-indigo-500 transition"
                            data-service="konsultasi">
                            <i class="fa-solid fa-comments mr-2"></i> Konsultasi
                        </button>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 border border-gray-200 dark:border-gray-700">
                    @include('components.form-permohonan-magang-admin', ['is_edit' => false])
                </div>

                <script>
                    const serviceMapping = {
                        'magang': 'Magang',
                        'klaim-asuransi': 'Layanan Klaim Asuransi',
                        'layanan-data': 'Layanan Data',
                        'pemetaan': 'Layanan Pemetaan',
                        'survey': 'Layanan Survey',
                        'konsultasi': 'Layanan Konsultasi'
                    };

                    const colorMapping = {
                        'magang': 'border-amber-500 text-amber-600 dark:text-amber-400',
                        'klaim-asuransi': 'border-red-500 text-red-600 dark:text-red-400',
                        'layanan-data': 'border-blue-500 text-blue-600 dark:text-blue-400',
                        'pemetaan': 'border-green-500 text-green-600 dark:text-green-400',
                        'survey': 'border-purple-500 text-purple-600 dark:text-purple-400',
                        'konsultasi': 'border-indigo-500 text-indigo-600 dark:text-indigo-400'
                    };

                    function selectService(service) {
                        // Update all tab styles
                        document.querySelectorAll('.service-tab').forEach(tab => {
                            tab.classList.remove('active-service-tab');
                            tab.className = 'service-tab px-4 py-3 font-semibold text-gray-700 dark:text-gray-300 border-b-2 border-transparent hover:border-gray-400 transition';
                        });

                        // Highlight active tab
                        const activeTab = document.querySelector(`[data-service="${service}"]`);
                        if (activeTab) {
                            activeTab.classList.add('active-service-tab');
                            activeTab.className = `service-tab px-4 py-3 font-semibold border-b-2 transition active-service-tab ${colorMapping[service]}`;
                        }

                        // Update form field
                        const serviceSelect = document.getElementById('jenis_layanan');
                        if (serviceSelect) {
                            serviceSelect.value = serviceMapping[service];
                            // Trigger change event to update form fields
                            serviceSelect.dispatchEvent(new Event('change'));
                            // Update form visibility
                            if (typeof updateFormFields === 'function') {
                                updateFormFields();
                            }
                        }

                        // Save selected service
                        sessionStorage.setItem('selectedService', service);
                    }

                    // Restore selected service on page load
                    document.addEventListener('DOMContentLoaded', function() {
                        const savedService = sessionStorage.getItem('selectedService') || 'magang';
                        selectService(savedService);
                    });
                </script>

                <style>
                    .active-service-tab {
                        @apply border-b-2;
                    }
                </style>
@endsection