<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Edit Pegawai') }}
            </h2>
            <div class="flex space-x-3">
                <a href="{{ route('pegawai.show', $pegawai) }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg flex items-center text-sm">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                    </svg>
                    Lihat Detail
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
            
            <!-- Current Data Info -->
            <div class="mb-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-blue-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                    </svg>
                    <div class="text-sm text-blue-700">
                        <p class="font-semibold">Mengubah data: {{ $pegawai->nama_lengkap }}{{ $pegawai->gelar }}</p>
                        <p>Perubahan data akan otomatis disinkronkan ke akun user terkait.</p>
                    </div>
                </div>
            </div>

            <!-- Alert Errors -->
            @if($errors->any())
                <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                    <div class="flex">
                        <svg class="w-5 h-5 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                        </svg>
                        <div>
                            <strong>Terdapat kesalahan:</strong>
                            <ul class="mt-2 list-disc list-inside">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Form -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <form action="{{ route('pegawai.update', $pegawai) }}" method="POST" class="p-6 space-y-6">
                    @csrf
                    @method('PUT')
                    
                    <!-- Data Pribadi -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-4 border-b pb-2 flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            Data Pribadi
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <x-input-label for="nama_lengkap" :value="__('Nama Lengkap')" />
                                <x-text-input id="nama_lengkap" name="nama_lengkap" type="text" class="mt-1 block w-full" 
                                             :value="old('nama_lengkap', $pegawai->nama_lengkap)" placeholder="Masukkan nama lengkap" required />
                                <x-input-error class="mt-2" :messages="$errors->get('nama_lengkap')" />
                            </div>

                            <div>
                                <x-input-label for="jenis_kelamin" :value="__('Jenis Kelamin')" />
                                <select id="jenis_kelamin" name="jenis_kelamin" 
                                        class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                    <option value="">Pilih Jenis Kelamin</option>
                                    <option value="L" {{ old('jenis_kelamin', $pegawai->jenis_kelamin) == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                    <option value="P" {{ old('jenis_kelamin', $pegawai->jenis_kelamin) == 'P' ? 'selected' : '' }}>Perempuan</option>
                                </select>
                                <x-input-error class="mt-2" :messages="$errors->get('jenis_kelamin')" />
                            </div>

                            <div>
                                <x-input-label for="gelar" :value="__('Gelar')" />
                                <x-text-input id="gelar" name="gelar" type="text" class="mt-1 block w-full" 
                                             :value="old('gelar', $pegawai->gelar)" placeholder="Contoh: , S.Kom., M.T." />
                                <x-input-error class="mt-2" :messages="$errors->get('gelar')" />
                            </div>

                            <div>
                                <x-input-label for="alias" :value="__('Nama Panggilan')" />
                                <x-text-input id="alias" name="alias" type="text" class="mt-1 block w-full" 
                                             :value="old('alias', $pegawai->alias)" placeholder="Nama panggilan sehari-hari" />
                                <x-input-error class="mt-2" :messages="$errors->get('alias')" />
                            </div>
                        </div>
                    </div>

                    <!-- Data Kepegawaian -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-4 border-b pb-2 flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V4a2 2 0 112 0v2m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"></path>
                            </svg>
                            Data Kepegawaian
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <x-input-label for="nip_lama" :value="__('NIP Lama')" />
                                <x-text-input id="nip_lama" name="nip_lama" type="text" class="mt-1 block w-full" 
                                             :value="old('nip_lama', $pegawai->nip_lama)" placeholder="NIP Lama" required />
                                <x-input-error class="mt-2" :messages="$errors->get('nip_lama')" />
                                <p class="mt-1 text-xs text-gray-500">
                                    ⚠️ Mengubah NIP Lama tidak akan mengubah password user yang sudah ada
                                </p>
                            </div>

                            <div>
                                <x-input-label for="nip_baru" :value="__('NIP Baru')" />
                                <x-text-input id="nip_baru" name="nip_baru" type="text" class="mt-1 block w-full" 
                                             :value="old('nip_baru', $pegawai->nip_baru)" placeholder="18 digit NIP baru" />
                                <x-input-error class="mt-2" :messages="$errors->get('nip_baru')" />
                            </div>

                            <div>
                                <x-input-label for="nik" :value="__('NIK')" />
                                <x-text-input id="nik" name="nik" type="text" class="mt-1 block w-full" 
                                             :value="old('nik', $pegawai->nik)" placeholder="16 digit NIK" />
                                <x-input-error class="mt-2" :messages="$errors->get('nik')" />
                            </div>

                            <div>
                                <x-input-label for="pangkat" :value="__('Pangkat')" />
                                <select id="pangkat" name="pangkat" 
                                        class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                    <option value="">Pilih Pangkat</option>
                                    @foreach(['1A', '1B', '1C', '1D', '2A', '2B', '2C', '2D', '3A', '3B', '3C', '3D', '4A', '4B'] as $pangkat)
                                        <option value="{{ $pangkat }}" {{ old('pangkat', $pegawai->pangkat) == $pangkat ? 'selected' : '' }}>{{ $pangkat }}</option>
                                    @endforeach
                                </select>
                                <x-input-error class="mt-2" :messages="$errors->get('pangkat')" />
                            </div>

                            <div class="md:col-span-2">
                                <x-input-label for="jabatan" :value="__('Jabatan')" />
                                <x-text-input id="jabatan" name="jabatan" type="text" class="mt-1 block w-full" 
                                             :value="old('jabatan', $pegawai->jabatan)" placeholder="Contoh: Statistisi Ahli Muda" />
                                <x-input-error class="mt-2" :messages="$errors->get('jabatan')" />
                            </div>
                        </div>
                    </div>

                    <!-- Kontak -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-4 border-b pb-2 flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                            Informasi Kontak
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <x-input-label for="email" :value="__('Email')" />
                                <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" 
                                             :value="old('email', $pegawai->email)" placeholder="nama@bps.go.id" required />
                                <x-input-error class="mt-2" :messages="$errors->get('email')" />
                                <p class="mt-1 text-xs text-gray-500">
                                    💡 Mengubah email akan mengubah username login user
                                </p>
                            </div>

                            <div>
                                <x-input-label for="nomor_hp" :value="__('Nomor HP')" />
                                <x-text-input id="nomor_hp" name="nomor_hp" type="text" class="mt-1 block w-full" 
                                             :value="old('nomor_hp', $pegawai->nomor_hp)" placeholder="08xxxxxxxxxx" />
                                <x-input-error class="mt-2" :messages="$errors->get('nomor_hp')" />
                            </div>
                        </div>
                    </div>

                    <!-- Pendidikan -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-4 border-b pb-2 flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"></path>
                            </svg>
                            Data Pendidikan
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <x-input-label for="pendidikan_tertinggi" :value="__('Pendidikan Tertinggi')" />
                                <select id="pendidikan_tertinggi" name="pendidikan_tertinggi" 
                                        class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                    <option value="">Pilih Pendidikan</option>
                                    @foreach(['SMA', 'D3', 'D4/S1', 'S2', 'S3'] as $pendidikan)
                                        <option value="{{ $pendidikan }}" {{ old('pendidikan_tertinggi', $pegawai->pendidikan_tertinggi) == $pendidikan ? 'selected' : '' }}>{{ $pendidikan }}</option>
                                    @endforeach
                                </select>
                                <x-input-error class="mt-2" :messages="$errors->get('pendidikan_tertinggi')" />
                            </div>

                            <div>
                                <x-input-label for="program_studi" :value="__('Program Studi')" />
                                <x-text-input id="program_studi" name="program_studi" type="text" class="mt-1 block w-full" 
                                             :value="old('program_studi', $pegawai->program_studi)" placeholder="Contoh: Statistika" />
                                <x-input-error class="mt-2" :messages="$errors->get('program_studi')" />
                            </div>

                            <div>
                                <x-input-label for="universitas" :value="__('Universitas')" />
                                <x-text-input id="universitas" name="universitas" type="text" class="mt-1 block w-full" 
                                             :value="old('universitas', $pegawai->universitas)" placeholder="Nama universitas" />
                                <x-input-error class="mt-2" :messages="$errors->get('universitas')" />
                            </div>
                        </div>
                    </div>

                    <!-- User Account Info (if exists) -->
                    @if($pegawai->user)
                        <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                            <h4 class="text-md font-semibold text-gray-900 mb-3 flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Informasi Akun User Terkait
                            </h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Status Akun:</span>
                                    <span class="font-medium">
                                        @if($pegawai->user->is_active && !$pegawai->user->trashed())
                                            <span class="text-green-600">✓ Aktif</span>
                                        @else
                                            <span class="text-red-600">✗ Nonaktif</span>
                                        @endif
                                    </span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Username:</span>
                                    <span class="font-medium">{{ $pegawai->user->email }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Dibuat:</span>
                                    <span class="font-medium">{{ $pegawai->user->created_at->format('d/m/Y') }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Roles:</span>
                                    <span class="font-medium">
                                        @if($pegawai->user->roles->count() > 0)
                                            {{ $pegawai->user->roles->pluck('name')->map(fn($role) => str_replace('_', ' ', ucwords($role, '_')))->join(', ') }}
                                        @else
                                            Belum ada role
                                        @endif
                                    </span>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Warning Box -->
                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                        <div class="flex">
                            <svg class="w-5 h-5 text-yellow-400 mt-0.5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                            </svg>
                            <div class="text-sm text-yellow-800">
                                <p class="font-semibold mb-2">Perhatian saat mengubah data:</p>
                                <ul class="list-disc list-inside space-y-1">
                                    <li>Mengubah <strong>Nama Lengkap</strong> akan mengupdate nama di akun user</li>
                                    <li>Mengubah <strong>Email</strong> akan mengupdate username login user</li>
                                    <li>Mengubah <strong>NIP Lama</strong> tidak akan mengubah password user yang sudah ada</li>
                                    <li>Semua perubahan akan disimpan dan disinkronkan secara otomatis</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Buttons -->
                    <div class="flex justify-end space-x-4 pt-6 border-t">
                        <x-secondary-button type="button" onclick="window.location.href='{{ route('pegawai.show', $pegawai) }}'">
                            {{ __('Batal') }}
                        </x-secondary-button>
                        <x-primary-button>
                            {{ __('Update Data Pegawai') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Auto format input
        document.getElementById('nip_baru').addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length > 18) {
                value = value.slice(0, 18);
            }
            e.target.value = value;
        });

        document.getElementById('nik').addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length > 16) {
                value = value.slice(0, 16);
            }
            e.target.value = value;
        });

        document.getElementById('nomor_hp').addEventListener('input', function(e) {
            let value = e.target.value.replace(/[^\d+]/g, '');
            e.target.value = value;
        });

        // Email validation dan suggestion
        document.getElementById('email').addEventListener('blur', function(e) {
            const email = e.target.value;
            if (email && !email.includes('@bps.go.id') && !email.includes('@example.com')) {
                // Optional: Show suggestion untuk domain BPS
                console.log('Consider using @bps.go.id domain');
            }
        });

        // Konfirmasi jika ada perubahan penting
        document.querySelector('form').addEventListener('submit', function(e) {
            const originalEmail = '{{ $pegawai->email }}';
            const newEmail = document.getElementById('email').value;
            
            if (originalEmail !== newEmail) {
                if (!confirm('Anda mengubah email pegawai. Ini akan mengubah username login user. Yakin ingin melanjutkan?')) {
                    e.preventDefault();
                    return false;
                }
            }
        });
    </script>
</x-app-layout>