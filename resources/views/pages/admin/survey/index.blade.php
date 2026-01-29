@extends('layouts.admin')

@section('content')
                <div class="mb-8">
                    <div class="flex items-center justify-between">
                        <div>
                            <h1 class="text-4xl font-bold text-gray-900 dark:text-white mb-2">
                                Data Survey
                            </h1>
                            <p class="text-gray-600 dark:text-gray-400">
                                Kelola semua permohonan survey di sini.
                            </p>
                        </div>
                        <a href="{{ route('admin.survey.create') }}"
                            class="px-4 py-3 text-white bg-green-600 rounded hover:bg-green-500 transition">
                            + Tambah Permohonan
                        </a>
                    </div>
                </div>

                @if ($survey->count() > 0)
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md dark:shadow-lg p-6 border border-gray-200 dark:border-gray-700 overflow-x-auto">
                    <table class="min-w-full">
                        <thead class="bg-gray-100 dark:bg-gray-700">
                            <tr>
                                <th class="px-6 py-3 text-left text-gray-900 dark:text-white">Nama</th>
                                <th class="px-6 py-3 text-left text-gray-900 dark:text-white">Email</th>
                                <th class="px-6 py-3 text-left text-gray-900 dark:text-white">WhatsApp</th>
                                <th class="px-6 py-3 text-left text-gray-900 dark:text-white">Status</th>
                                <th class="px-6 py-3 text-left text-gray-900 dark:text-white">Tgl Permohonan</th>
                                <th class="px-6 py-3 text-left text-gray-900 dark:text-white">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($survey as $item)
                            <tr class="border-t border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700/50">
                                <td class="px-6 py-4 text-gray-900 dark:text-white">{{ $item->nama_lengkap }}</td>
                                <td class="px-6 py-4 text-gray-900 dark:text-white">{{ $item->email }}</td>
                                <td class="px-6 py-4 text-gray-900 dark:text-white">{{ $item->no_whatsapp }}</td>
                                <td class="px-6 py-4">
                                    @php
                                        $status = $item->status ?? 'Menunggu';
                                        $approvedStatuses = ['Disetujui', 'Diterima'];
                                        $processingStatuses = ['Diproses'];
                                    @endphp
                                    @if (in_array($status, $approvedStatuses))
                                        <span class="px-3 py-1 text-sm font-semibold text-green-800 bg-green-100 dark:bg-green-900/30 dark:text-green-300 rounded-full">{{ $status }}</span>
                                    @elseif (in_array($status, $processingStatuses))
                                        <span class="px-3 py-1 text-sm font-semibold text-blue-800 bg-blue-100 dark:bg-blue-900/30 dark:text-blue-300 rounded-full">{{ $status }}</span>
                                    @else
                                        <span class="px-3 py-1 text-sm font-semibold text-yellow-800 bg-yellow-100 dark:bg-yellow-900/30 dark:text-yellow-300 rounded-full">{{ $status }}</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-gray-900 dark:text-white text-sm">{{ $item->created_at->format('d M Y') }}</td>
                                <td class="px-6 py-4 flex gap-2">
                                    <a href="{{ route('admin.survey.edit', $item) }}" class="text-blue-600 hover:text-blue-800">Edit</a>
                                    <form action="{{ route('admin.survey.destroy', $item) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800" onclick="return confirm('Yakin ingin menghapus?')">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-6 text-center">
                    <p class="text-gray-600 dark:text-gray-400">Belum ada permohonan survey.</p>
                </div>
                @endif
@endsection
