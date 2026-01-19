<h3 class="text-gray-900 dark:text-gray-100">Summary Layanan</h3>
<div class="w-full -mr-6 overflow-x-auto">
    <table class="w-full overflow-hidden rounded table-auto text-slate-600 dark:text-slate-400">
        <thead class="border-b bg-slate-100 dark:bg-slate-900 border-b-slate-300 dark:border-b-slate-500">
            <tr>
                <th class="p-3 text-left">No</th>
                <th class="p-3 text-left">Layanan</th>
                <th class="p-3 text-left">Jumlah</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($layanan as $item)
            <tr>
                <th class="p-3 align-top max-w-[50px]" scope="row">{{ $loop->iteration }}</th>
                <td class="p-3 align-top max-w-[200px]">
                    {{ $item->layanan }}
                </td>
                <td class="p-3 align-top max-w-[50px]">
                    {{ $item->jumlah }}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
<br>
<br>
<br>
<h3 class="text-gray-900 dark:text-gray-100"> 10 Besar jumlah Pesan yang diterima</h3>
<div class="w-full -mr-6 overflow-x-auto">
    <table class="w-full overflow-hidden rounded table-auto text-slate-600 dark:text-slate-400">
        <thead class="border-b bg-slate-100 dark:bg-slate-900 border-b-slate-300 dark:border-b-slate-500">
            <tr>
                <th class="p-3 text-left">No</th>
                <th class="p-3 text-left">Pesan</th>
                <th class="p-3 text-left">Jumlah</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($permohonan as $item)
            <tr>
                <th class="p-3 align-top max-w-[50px]" scope="row">{{ $loop->iteration }}</th>
                <td class="p-3 align-top max-w-[200px]">
                    {{ $item->question }}
                </td>
                <td class="p-3 align-top max-w-[50px]">
                    {{ $item->count }}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
<br>
<h3 class="text-gray-900 dark:text-gray-100">History All Chat</h3>
<dd>
    <a href="{{URL::to('/admin/download-excel')}}">
        <button class="hover:bg-green-700 hover:text-white {{ request()->is('tentang-kami*') ? 'bg-green-200 text-green-900 hover:text-white' : '' }} dark:text-white rounded-full visited:text-green-900 dark:visited:text-green-600 dark:visited:hover:text-white px-5 py-3 transition duration-200"><i class="fa fa-download"></i> Download File</button>
    </a>
</dd>