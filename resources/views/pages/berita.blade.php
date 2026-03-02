@extends('layouts.main')

@section('content')
<div class="min-h-screen bg-white dark:bg-gray-900 pt-[96px] py-10 px-6">
    <h1 class="text-4xl font-bold text-center text-gray-900 dark:text-white mb-12">
        Berita BMKG Geofisika Sleman
    </h1>

    <div id="loading" class="max-w-5xl mx-auto grid grid-cols-1 md:grid-cols-2 gap-6">
        @for($i=0;$i<6;$i++)
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow overflow-hidden animate-pulse">
            <div class="h-48 bg-gray-300 dark:bg-gray-700"></div>
            <div class="p-5 space-y-3">
                <div class="h-4 bg-gray-300 dark:bg-gray-700 rounded w-3/4"></div>
                <div class="h-3 bg-gray-200 dark:bg-gray-600 rounded w-1/2"></div>
            </div>
    </div>
    @endfor
</div>

<div id="list" class="hidden max-w-5xl mx-auto grid grid-cols-1 md:grid-cols-2 gap-6"></div>

<div class="flex justify-center mt-10">
    <button id="loadMoreBtn" class="hidden px-8 py-3 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg transition duration-300 transform hover:scale-105">
        Lihat Lebih Banyak
    </button>
</div>
</div>

<script>
    let allBerita = [];
    let currentPage = 0;
    const itemsPerPage = 6;

    function renderBerita(items) {
        const list = document.getElementById('list');
        
        items.forEach(item => {
            const card = document.createElement('div');

            const img = item.image ?
                item.image :
                '/images/placeholder.jpg';

            card.className = `
        bg-white dark:bg-gray-800 rounded-xl shadow-md hover:shadow-lg transition
        overflow-hidden flex flex-col border border-gray-200 dark:border-gray-700
      `;

            card.innerHTML = `
        <div class="h-48 bg-gray-100 dark:bg-gray-700 overflow-hidden">
          <img src="${img}" 
               class="w-full h-full object-cover transition hover:scale-105"
               onerror="this.src='/images/placeholder.jpg'">
        </div>

        <div class="p-5 flex flex-col gap-3 flex-1">
          <h2 class="font-semibold text-lg text-gray-900 dark:text-white leading-snug line-clamp-2">
            ${item.title}
          </h2>

          <p class="text-sm text-gray-600 dark:text-gray-400">${item.date ?? ''}</p>

          <a href="${item.url}" target="_blank"
             class="mt-auto inline-flex items-center gap-1 text-green-600 dark:text-green-400 font-medium hover:text-green-700 dark:hover:text-green-300 underline">
            Baca selengkapnya
            <span class="transition group-hover:translate-x-1">→</span>
          </a>
        </div>
      `;

            list.appendChild(card);
        });
    }

    function loadMoreBerita() {
        const start = currentPage * itemsPerPage;
        const end = start + itemsPerPage;
        const newItems = allBerita.slice(start, end);

        if (newItems.length > 0) {
            renderBerita(newItems);
            currentPage++;
        }

        const loadMoreBtn = document.getElementById('loadMoreBtn');
        if (currentPage * itemsPerPage >= allBerita.length) {
            loadMoreBtn.classList.add('hidden');
        } else {
            loadMoreBtn.classList.remove('hidden');
        }
    }

    fetch('/api/berita')
        .then(res => {
            if (!res.ok) throw new Error('HTTP ' + res.status);
            return res.json();
        })
        .then(result => {
            const list = document.getElementById('list');
            const loading = document.getElementById('loading');
            const loadMoreBtn = document.getElementById('loadMoreBtn');

            allBerita = result.data || [];

            loading.classList.add('hidden');
            list.classList.remove('hidden');

            loadMoreBerita();

            loadMoreBtn.addEventListener('click', loadMoreBerita);
        })
        .catch(err => {
            console.error('FETCH ERROR:', err);
            document.getElementById('loading').innerHTML =
                '<p class="text-center col-span-2 text-red-500">Gagal memuat berita.</p>';
        });
</script>
@endsection