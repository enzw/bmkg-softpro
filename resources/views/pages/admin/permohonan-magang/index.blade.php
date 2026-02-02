@extends('layouts.admin')

@section('content')
                <div class="mb-8">
                    <div class="flex items-center justify-between">
                        <div>
                            <h1 class="text-4xl font-bold text-gray-900 dark:text-white mb-2">
                                Pelayanan Informasi Geofisika
                            </h1>
                            <p class="text-gray-600 dark:text-gray-400">
                                Kelola semua permohonan pelayanan informasi geofisika di sini.
                            </p>
                        </div>
                        <a href="{{ route('admin.pelayanan-jasa.create') }}"
                            class="px-4 py-3 text-white bg-green-600 rounded hover:bg-green-500 transition">
                            + Permohonan
                        </a>
                    </div>
                </div>

                <!-- Tabs Navigation -->
                <div class="mb-6">
                    <div class="flex flex-wrap gap-2 border-b border-gray-200 dark:border-gray-700">
                        <button type="button" 
                            onclick="switchTab('all')"
                            id="tab-all" 
                            class="tab-button px-4 py-3 font-semibold text-gray-700 dark:text-gray-300 border-b-2 border-transparent hover:border-blue-500 transition active-tab">
                            <i class="fa-solid fa-list mr-2"></i> Semua
                        </button>
                        <button type="button" 
                            onclick="switchTab('magang')"
                            id="tab-magang" 
                            class="tab-button px-4 py-3 font-semibold text-gray-700 dark:text-gray-300 border-b-2 border-transparent hover:border-amber-500 transition">
                            <i class="fa-solid fa-graduation-cap mr-2"></i> Magang
                        </button>
                        <button type="button" 
                            onclick="switchTab('klaim-asuransi')"
                            id="tab-klaim-asuransi" 
                            class="tab-button px-4 py-3 font-semibold text-gray-700 dark:text-gray-300 border-b-2 border-transparent hover:border-red-500 transition">
                            <i class="fa-solid fa-file-contract mr-2"></i> Klaim Asuransi
                        </button>
                        <button type="button" 
                            onclick="switchTab('layanan-data')"
                            id="tab-layanan-data" 
                            class="tab-button px-4 py-3 font-semibold text-gray-700 dark:text-gray-300 border-b-2 border-transparent hover:border-blue-500 transition">
                            <i class="fa-solid fa-database mr-2"></i> Layanan Data
                        </button>
                        <button type="button" 
                            onclick="switchTab('pemetaan')"
                            id="tab-pemetaan" 
                            class="tab-button px-4 py-3 font-semibold text-gray-700 dark:text-gray-300 border-b-2 border-transparent hover:border-green-500 transition">
                            <i class="fa-solid fa-map mr-2"></i> Pemetaan
                        </button>
                        <button type="button" 
                            onclick="switchTab('survey')"
                            id="tab-survey" 
                            class="tab-button px-4 py-3 font-semibold text-gray-700 dark:text-gray-300 border-b-2 border-transparent hover:border-purple-500 transition">
                            <i class="fa-solid fa-chart-bar mr-2"></i> Survey
                        </button>
                    </div>
                </div>

                <!-- Tab Content -->
                <div id="tab-content-all" class="tab-content bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 border border-gray-200 dark:border-gray-700">
                    @include('components.table-permohonan-magang-admin', ['permohonan' => $permohonan, 'showAllServices' => true])
                </div>

                <div id="tab-content-magang" class="tab-content hidden bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 border border-gray-200 dark:border-gray-700">
                    @php
                        $magangOnly = $permohonan->filter(fn($item) => $item->jenis_layanan === 'Magang');
                    @endphp
                    @include('components.table-permohonan-magang-admin', ['permohonan' => $magangOnly, 'serviceName' => 'Magang'])
                </div>

                <div id="tab-content-klaim-asuransi" class="tab-content hidden bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 border border-gray-200 dark:border-gray-700">
                    @php
                        $klaimOnly = $permohonan->filter(fn($item) => $item->jenis_layanan === 'Layanan Klaim Asuransi');
                    @endphp
                    @include('components.table-permohonan-magang-admin', ['permohonan' => $klaimOnly, 'serviceName' => 'Klaim Asuransi'])
                </div>

                <div id="tab-content-layanan-data" class="tab-content hidden bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 border border-gray-200 dark:border-gray-700">
                    @php
                        $dataOnly = $permohonan->filter(fn($item) => $item->jenis_layanan === 'Layanan Data');
                    @endphp
                    @include('components.table-permohonan-magang-admin', ['permohonan' => $dataOnly, 'serviceName' => 'Layanan Data'])
                </div>

                <div id="tab-content-peta-sebaran" class="tab-content hidden bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 border border-gray-200 dark:border-gray-700">
                    @php
                        $petaSebaranOnly = $permohonan->filter(fn($item) => $item->jenis_layanan === 'Layanan Peta Sebaran');
                    @endphp
                    @include('components.table-permohonan-magang-admin', ['permohonan' => $petaSebaranOnly, 'serviceName' => 'Peta Sebaran'])
                </div>

                <div id="tab-content-survey" class="tab-content hidden bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 border border-gray-200 dark:border-gray-700">
                    @php
                        $surveyOnly = $permohonan->filter(fn($item) => $item->jenis_layanan === 'Layanan Survey');
                    @endphp
                    @include('components.table-permohonan-magang-admin', ['permohonan' => $surveyOnly, 'serviceName' => 'Survey'])
                </div>

                <script>
                    function switchTab(tabName) {
                        // Hide all tab contents
                        document.querySelectorAll('.tab-content').forEach(content => {
                            content.classList.add('hidden');
                        });

                        // Remove active state from all tab buttons
                        document.querySelectorAll('.tab-button').forEach(button => {
                            button.classList.remove('active-tab');
                            button.classList.add('border-transparent');
                        });

                        // Show selected tab content
                        const contentId = 'tab-content-' + tabName;
                        const tabElement = document.getElementById(contentId);
                        if (tabElement) {
                            tabElement.classList.remove('hidden');
                        }

                        // Highlight active tab button
                        const tabId = 'tab-' + tabName;
                        const tabButton = document.getElementById(tabId);
                        if (tabButton) {
                            tabButton.classList.add('active-tab');
                            tabButton.classList.remove('border-transparent');
                            
                            // Set active tab color based on tab type
                            const colors = {
                                'all': 'border-blue-500 text-blue-600 dark:text-blue-400',
                                'magang': 'border-amber-500 text-amber-600 dark:text-amber-400',
                                'klaim-asuransi': 'border-red-500 text-red-600 dark:text-red-400',
                                'layanan-data': 'border-blue-500 text-blue-600 dark:text-blue-400',
                                'peta-sebaran': 'border-green-500 text-green-600 dark:text-green-400',
                                'survey': 'border-purple-500 text-purple-600 dark:text-purple-400'
                            };
                            
                            tabButton.className = 'tab-button px-4 py-3 font-semibold border-b-2 transition active-tab ' + (colors[tabName] || colors['all']);
                        }

                        // Save active tab to sessionStorage
                        sessionStorage.setItem('activeTab', tabName);
                    }

                    // Restore active tab on page load
                    document.addEventListener('DOMContentLoaded', function() {
                        const activeTab = sessionStorage.getItem('activeTab') || 'all';
                        switchTab(activeTab);
                    });
                </script>

                <style>
                    .active-tab {
                        @apply border-b-2;
                    }
                </style>
@endsection
