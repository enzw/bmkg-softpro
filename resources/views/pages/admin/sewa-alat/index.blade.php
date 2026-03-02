@extends('layouts.admin')

@section('content')
    <div class="space-y-6">
        <!-- Header Section -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-2">
            <div>
                <h1 class="text-4xl font-black text-gray-900 dark:text-white uppercase tracking-tight mb-2">Sewa Alat</h1>
                <div class="flex items-center gap-2">
                    <span
                        class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-green-50 dark:bg-emerald-900/20 text-green-600 dark:text-emerald-400 text-[10px] font-bold uppercase tracking-widest shadow-sm">
                        <span class="w-1.5 h-1.5 rounded-full bg-green-500 animate-pulse"></span>
                        Update Terakhir
                    </span>
                    <span
                        class="text-[10px] text-gray-400 font-semibold uppercase tracking-widest">{{ $permohonan->count() }}
                        Data Ditemukan</span>
                </div>
            </div>
            <a href="{{ route('admin.sewa-alat.create') }}"
                class="inline-flex items-center justify-center gap-2 px-8 py-4 bg-green-500 hover:bg-green-600 dark:bg-emerald-600 dark:hover:bg-emerald-500 text-white rounded-[1.5rem] transition-all duration-300 shadow-lg shadow-green-200 dark:shadow-none font-semibold text-xs uppercase tracking-widest group">
                <i class="fas fa-plus group-hover:rotate-90 transition-transform duration-300"></i>
                Permohonan Baru
            </a>
        </div>

        <!-- Content Card -->
        <div
            class="bg-white dark:bg-gray-800 rounded-[2.5rem] border border-gray-100 dark:border-gray-700 shadow-sm overflow-hidden">
            <div
                class="p-8 border-b border-gray-50 dark:border-gray-700 flex items-center justify-between bg-gray-50/30 dark:bg-gray-900/10">
                <div class="flex items-center gap-4">
                    <div
                        class="w-12 h-12 rounded-2xl bg-green-100 dark:bg-emerald-900/40 flex items-center justify-center text-green-600 dark:text-emerald-400">
                        <i class="fas fa-list text-lg"></i>
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-gray-900 dark:text-white uppercase tracking-tight">Daftar
                            Permohonan</h2>
                        <p class="text-[10px] text-gray-400 font-semibold uppercase tracking-widest">Managemen penyewaan
                            peralatan teknis</p>
                    </div>
                </div>
            </div>

            <!-- Search Bar -->
            <div class="px-8 pt-8">
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <i class="fas fa-search text-gray-400 dark:text-gray-500 text-sm"></i>
                    </div>
                    <input type="text" id="searchPermohonan" 
                        placeholder="Cari nama alat, pemohon, atau status permohonan..."
                        class="w-full pl-12 pr-4 py-4 bg-gray-50 dark:bg-gray-900/40 border border-gray-200 dark:border-gray-700 rounded-2xl text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-300">
                </div>
            </div>

            <div class="p-8">
                <div id="table-container">
                    @include('components.table-sewa-alat-admin', ['permohonan' => $permohonan])
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchPermohonan');
            const tableContainer = document.getElementById('table-container');

            searchInput.addEventListener('input', function() {
                const searchQuery = this.value.toLowerCase().trim();
                const items = tableContainer.querySelectorAll('.space-y-4 > div[class*="bg-white"]');
                let visibleCount = 0;
                let emptyStateShown = false;

                // Get the empty state div if it exists
                const emptyState = tableContainer.querySelector('.flex.flex-col.items-center.justify-center');

                items.forEach(item => {
                    // Extract searchable text from the item
                    const itemText = item.innerText.toLowerCase();
                    const matches = itemText.includes(searchQuery);

                    if (searchQuery === '') {
                        item.style.display = '';
                        visibleCount++;
                    } else if (matches) {
                        item.style.display = '';
                        visibleCount++;
                    } else {
                        item.style.display = 'none';
                    }
                });

                // Handle empty state
                if (emptyState) {
                    if (searchQuery === '' && visibleCount === 0) {
                        emptyState.style.display = '';
                    } else if (searchQuery !== '' && visibleCount === 0) {
                        emptyState.style.display = 'none';
                    } else {
                        emptyState.style.display = 'none';
                    }
                }

                // Show no results message if search is active but no results
                if (searchQuery !== '' && visibleCount === 0) {
                    let noResultsMsg = tableContainer.querySelector('.no-results-message');
                    if (!noResultsMsg) {
                        noResultsMsg = document.createElement('div');
                        noResultsMsg.className = 'no-results-message flex flex-col items-center justify-center py-16';
                        noResultsMsg.innerHTML = `
                            <div class="w-24 h-24 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center mb-4">
                                <i class="fas fa-search text-4xl text-gray-400 dark:text-gray-500"></i>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Tidak Ada Hasil Pencarian</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Coba gunakan kata kunci yang berbeda</p>
                        `;
                        tableContainer.appendChild(noResultsMsg);
                    }
                    noResultsMsg.style.display = '';
                } else {
                    const noResultsMsg = tableContainer.querySelector('.no-results-message');
                    if (noResultsMsg) {
                        noResultsMsg.style.display = 'none';
                    }
                }
            });
        });
    </script>
@endsection