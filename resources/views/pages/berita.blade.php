@extends('layouts.main')

@section('content')
<div class="min-h-screen bg-gray-50 pt-[96px] py-10 px-6">
    <h1 class="text-3xl font-bold text-center text-gray-800 mb-8">
        Berita BMKG Geofisika Yogyakarta
    </h1>

    <div id="loading" class="max-w-5xl mx-auto grid grid-cols-1 md:grid-cols-2 gap-6">
        @for($i=0;$i<6;$i++)
            <div class="bg-white rounded-xl shadow overflow-hidden animate-pulse">
            <div class="h-48 bg-gray-300"></div>
            <div class="p-5 space-y-3">
                <div class="h-4 bg-gray-300 rounded w-3/4"></div>
                <div class="h-3 bg-gray-200 rounded w-1/2"></div>
            </div>
    </div>
    @endfor
</div>

{{-- REAL DATA --}}
<div id="list" class="hidden max-w-5xl mx-auto grid grid-cols-1 md:grid-cols-2 gap-6"></div>
</div>

<script>
    fetch('/api/berita')
        .then(res => {
            if (!res.ok) throw new Error('HTTP ' + res.status);
            return res.json();
        })
        .then(result => {
            const list = document.getElementById('list');
            const loading = document.getElementById('loading');

            loading.classList.add('hidden');
            list.classList.remove('hidden');

            result.data.forEach(item => {
                const card = document.createElement('div');

                const img = item.image ?
                    item.image :
                    '/images/placeholder.jpg';

                card.className = `
            bg-white rounded-xl shadow hover:shadow-xl transition
            overflow-hidden flex flex-col
          `;

                card.innerHTML = `
            <div class="h-48 bg-gray-200 overflow-hidden">
              <img src="${img}" 
                   class="w-full h-full object-cover transition hover:scale-105"
                   onerror="this.src='/images/placeholder.jpg'">
            </div>

            <div class="p-5 flex flex-col gap-3 flex-1">
              <h2 class="font-semibold text-lg text-gray-800 leading-snug line-clamp-2">
                ${item.title}
              </h2>

              <p class="text-sm text-gray-500">${item.date ?? ''}</p>

              <a href="${item.url}" target="_blank"
                 class="mt-auto inline-flex items-center gap-1 text-blue-600 font-medium hover:underline">
                Baca selengkapnya
                <span class="transition group-hover:translate-x-1">→</span>
              </a>
            </div>
          `;

                list.appendChild(card);
            });
        })
        .catch(err => {
            console.error('FETCH ERROR:', err);
            document.getElementById('loading').innerHTML =
                '<p class="text-center col-span-2 text-red-500">Gagal memuat berita.</p>';
        });
</script>
@endsection