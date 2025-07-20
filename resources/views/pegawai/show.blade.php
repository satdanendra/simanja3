<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Detail Pegawai') }}
            </h2>
            <div class="flex space-x-3">
                <a href="{{ route('pegawai.edit', $pegawai) }}" class="bg-amber-600 hover:bg-amber-700 text-white px-4 py-2 rounded-lg flex items-center text-sm">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    Edit
                </a>
                <a href="{{ route('pegawai.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg flex items-center text-sm">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Kembali
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Header Card -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <div class="flex items-center space-x-6">
                        <!-- Avatar -->
                        <div class="flex-shrink-0">
                            <div class="h-20 w-20 rounded-full bg-indigo-500 flex items-center justify-center text-white text-2xl font-bold">
                                {{ substr($pegawai->nama_lengkap, 0, 2) }}
                            </div>
                        </div>
                        
                        <!-- Basic Info -->
                        <div class="flex-1">
                            <h1 class="text-2xl font-bold text-gray-900">
                                {{ $pegawai->nama_lengkap }}{{ $pegawai->gelar }}
                            </h1>
                            @if($pegawai->alias)
                                <p class="text-lg text-indigo-600 font-medium">"{{ $pegawai->alias }}"</p>
                            @endif
                            <p class="text-gray-600">{{ $pegawai->jabatan ?? 'Belum ada jabatan' }}</p>
                            
                            <!-- Status Badge -->
                            <div class="mt-2">
                                @if($pegawai->user && $pegawai->user->is_active && !$pegawai->user->trashed())
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                        </svg>
                                        Aktif
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                        </svg>
                                        Nonaktif
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                
                <!-- Data Pribadi -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            Data Pribadi
                        </h3>
                        <div class="space-y-3">
                            <div class="flex justify-between py-2 border-b border-gray-100">
                                <span class="text-gray-600">Jenis Kelamin</span>
                                <span class="font-medium">{{ $pegawai->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</span>
                            </div>
                            @if($pegawai->nik)
                                <div class="flex justify-between py-2 border-b border-gray-100">
                                    <span class="text-gray-600">NIK</span>
                                    <span class="font-medium">{{ $pegawai->nik }}</span>
                                </div>
                            @endif
                            <div class="flex justify-between py-2 border-b border-gray-100">
                                <span class="text-gray-600">Dibuat</span>
                                <span class="font-medium">{{ $pegawai->created_at->format('d/m/Y H:i') }}</span>
                            </div>
                            <div class="flex justify-between py-2 border-b border-gray-100">
                                <span class="text-gray-600">Terakhir Update</span>
                                <span class="font-medium">{{ $pegawai->updated_at->format('d/m/Y H:i') }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Kontak -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                            Informasi Kontak
                        </h3>
                        <div class="space-y-3">
                            <div class="flex justify-between py-2 border-b border-gray-100">
                                <span class="text-gray-600">Email</span>
                                <span class="font-medium">
                                    <a href="mailto:{{ $pegawai->email }}" class="text-indigo-600 hover:text-indigo-800">
                                        {{ $pegawai->email }}
                                    </a>
                                </span>
                            </div>
                            @if($pegawai->nomor_hp)
                                <div class="flex justify-between py-2 border-b border-gray-100">
                                    <span class="text-gray-600">Nomor HP</span>
                                    <span class="font-medium">
                                        <a href="tel:{{ $pegawai->nomor_hp }}" class="text-indigo-600 hover:text-indigo-800">
                                            {{ $pegawai->nomor_hp }}
                                        </a>
                                    </span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Data Kepegawaian -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V4a2 2 0 112 0v2m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"></path>
                            </svg>
                            Data Kepegawaian
                        </h3>
                        <div class="space-y-3">
                            <div class="flex justify-between py-2 border-b border-gray-100">
                                <span class="text-gray-600">NIP Lama</span>
                                <span class="font-medium">{{ $pegawai->nip_lama }}</span>
                            </div>
                            @if($pegawai->nip_baru)
                                <div class="flex justify-between py-2 border-b border-gray-100">
                                    <span class="text-gray-600">NIP Baru</span>
                                    <span class="font-medium">{{ $pegawai->nip_baru }}</span>
                                </div>
                            @endif
                            @if($pegawai->pangkat)
                                <div class="flex justify-between py-2 border-b border-gray-100">
                                    <span class="text-gray-600">Pangkat</span>
                                    <span class="font-medium">{{ $pegawai->pangkat }}</span>
                                </div>
                            @endif
                            <div class="flex justify-between py-2 border-b border-gray-100">
                                <span class="text-gray-600">Jabatan</span>
                                <span class="font-medium">{{ $pegawai->jabatan ?? 'Belum ditentukan' }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Data Pendidikan -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"></path>
                            </svg>
                            Data Pendidikan
                        </h3>
                        <div class="space-y-3">
                            <div class="flex justify-between py-2 border-b border-gray-100">
                                <span class="text-gray-600">Pendidikan Tertinggi</span>
                                <span class="font-medium">{{ $pegawai->pendidikan_tertinggi ?? 'Belum diisi' }}</span>
                            </div>
                            @if($pegawai->program_studi)
                                <div class="flex justify-between py-2 border-b border-gray-100">
                                    <span class="text-gray-600">Program Studi</span>
                                    <span class="font-medium">{{ $pegawai->program_studi }}</span>
                                </div>
                            @endif
                            @if($pegawai->universitas)
                                <div class="flex justify-between py-2 border-b border-gray-100">
                                    <span class="text-gray-600">Universitas</span>
                                    <span class="font-medium">{{ $pegawai->universitas }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- User Account Info -->
            @if($pegawai->user)
                <div class="mt-6 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Informasi Akun User
                        </h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- User Status -->
                            <div class="space-y-3">
                                <div class="flex justify-between py-2 border-b border-gray-100">
                                    <span class="text-gray-600">Username</span>
                                    <span class="font-medium">{{ $pegawai->user->email }}</span>
                                </div>
                                <div class="flex justify-between py-2 border-b border-gray-100">
                                    <span class="text-gray-600">Status Akun</span>
                                    <span class="font-medium">
                                        @if($pegawai->user->is_active && !$pegawai->user->trashed())
                                            <span class="text-green-600">✓ Aktif</span>
                                        @else
                                            <span class="text-red-600">✗ Nonaktif</span>
                                        @endif
                                    </span>
                                </div>
                                <div class="flex justify-between py-2 border-b border-gray-100">
                                    <span class="text-gray-600">Terakhir Login</span>
                                    <span class="font-medium">
                                        {{ $pegawai->user->last_login_at ? $pegawai->user->last_login_at->format('d/m/Y H:i') : 'Belum pernah login' }}
                                    </span>
                                </div>
                                <div class="flex justify-between py-2 border-b border-gray-100">
                                    <span class="text-gray-600">Dibuat</span>
                                    <span class="font-medium">{{ $pegawai->user->created_at->format('d/m/Y H:i') }}</span>
                                </div>
                            </div>

                            <!-- User Roles -->
                            <div class="space-y-3">
                                <h4 class="font-medium text-gray-900">Role & Tim</h4>
                                @if($pegawai->user->roles->count() > 0)
                                    <div class="space-y-2">
                                        @foreach($pegawai->user->roles as $role)
                                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                                <div>
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                                        {{ str_replace('_', ' ', ucwords($role->name, '_')) }}
                                                    </span>
                                                    @if($role->pivot->tim_id)
                                                        <div class="text-xs text-gray-500 mt-1">
                                                            Tim ID: {{ $role->pivot->tim_id }}
                                                        </div>
                                                    @else
                                                        <div class="text-xs text-gray-500 mt-1">
                                                            Global Role
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="text-xs text-gray-400">
                                                    {{ $role->pivot->created_at->format('d/m/Y') }}
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="text-gray-500 italic">Belum memiliki role</div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <!-- No User Account -->
                <div class="mt-6 bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                    <div class="flex">
                        <svg class="w-5 h-5 text-yellow-400 mt-0.5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                        </svg>
                        <div class="text-sm text-yellow-800">
                            <p class="font-semibold">Perhatian</p>
                            <p>Pegawai ini belum memiliki akun user. Akun user akan dibuat otomatis jika data pegawai diupdate melalui sistem.</p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Action Buttons -->
            <div class="mt-6 flex justify-center space-x-4">
                <a href="{{ route('pegawai.edit', $pegawai) }}" 
                   class="bg-amber-600 hover:bg-amber-700 text-white px-6 py-2 rounded-lg flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    Edit Data
                </a>
                
                @if($pegawai->trashed())
                    <form action="{{ route('pegawai.restore', $pegawai->id) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" 
                                class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg flex items-center"
                                onclick="return confirm('Yakin ingin restore pegawai ini?')">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                            </svg>
                            Restore Pegawai
                        </button>
                    </form>
                @else
                    <form action="{{ route('pegawai.destroy', $pegawai) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="bg-red-600 hover:bg-red-700 text-white px-6 py-2 rounded-lg flex items-center"
                                onclick="return confirm('Yakin ingin hapus pegawai ini? User terkait akan dinonaktifkan.')">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                            Hapus Pegawai
                        </button>
                    </form>
                @endif
                
                <a href="{{ route('pegawai.index') }}" 
                   class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-2 rounded-lg flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    Kembali ke Daftar
                </a>
            </div>
        </div>
    </div>
</x-app-layout>