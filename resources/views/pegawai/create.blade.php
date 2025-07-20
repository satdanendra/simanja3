<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Tambah Pegawai') }}
            </h2>
            <a href="{{ route('pegawai.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg flex items-center text-sm">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            
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
                <form action="{{ route('pegawai.store') }}" method="POST" class="p-6 space-y-6">
                    @csrf
                    
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
                                             :value="old('nama_lengkap')" placeholder="Masukkan nama lengkap" required />
                                <x-input-error class="mt-2" :messages="$errors->get('nama_lengkap')" />
                            </div>

                            <div>
                                <x-input-label for="jenis_kelamin" :value="__('Jenis Kelamin')" />
                                <select id="jenis_kelamin" name="jenis_kelamin" 
                                        class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                    <option value="">Pilih Jenis Kelamin</option>
                                    <option value="L" {{ old('jenis_kelamin') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                    <option value="P" {{ old('jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan</option>
                                </select>
                                <x-input-error class="mt-2" :messages="$errors->get('jenis_kelamin')" />
                            </div>

                            <div>
                                <x-input-label for="gelar" :value="__('Gelar')" />
                                <x-text-input id="gelar" name="gelar" type="text" class="mt-1 block w-full" 
                                             :value="old('gelar')" placeholder="Contoh: , S.Kom., M.T." />
                                <x-input-error class="mt-2" :messages="$errors->get('gelar')" />
                            </div>

                            <div>
                                <x-input-label for="alias" :value="__('Nama Panggilan')" />
                                <x-text-input id="alias" name="alias" type="text" class="mt-1 block w-full" 
                                             :value="old('alias')" placeholder="Nama panggilan sehari-hari" />
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
                                             :value="old('nip_lama')" placeholder="NIP Lama (akan digunakan sebagai password)" required />
                                <x-input-error class="mt-2" :messages="$errors->get('nip_lama')" />
                                <p class="mt-1 text-xs text-gray-500">
                                    💡 NIP Lama akan digunakan sebagai password default
                                </p>
                            </div>

                            <div>
                                <x-input-label for="nip_baru" :value="__('NIP Baru')" />
                                <x-text-input id="nip_baru" name="nip_baru" type="text" class="mt-1 block w-full" 
                                             :value="old('nip_baru')" placeholder="18 digit NIP baru" />
                                <x-input-error class="mt-2" :messages="$errors->get('nip_baru')" />
                            </div>

                            <div>
                                <x-input-label for="nik" :value="__('NIK')" />
                                <x-text-input id="nik" name="nik" type="text" class="mt-1 block w-full" 
                                             :value="old('nik')" placeholder="16 digit NIK" />
                                <x-input-error class="mt-2" :messages="$errors->get('nik')" />
                            </div>

                            <div>
                                <x-input-label for="pangkat" :value="__('Pangkat')" />
                                <select id="pangkat" name="pangkat" 
                                        class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                    <option value="">Pilih Pangkat</option>
                                    @foreach(['1A', '1B', '1C', '1D', '2A', '2B', '2C', '2D', '3A', '3B', '3C', '3D', '4A', '4B'] as $pangkat)
                                        <option value="{{ $pangkat }}" {{ old('pangkat') == $pangkat ? 'selected' : '' }}>{{ $pangkat }}</option>
                                    @endforeach
                                </select>
                                <x-input-error class="mt-2" :messages="$errors->get('pangkat')" />
                            </div>

                            <div class="md:col-span-2">
                                <x-input-label for="jabatan" :value="__('Jabatan')" />
                                <x-text-input id="jabatan" name="jabatan" type="text" class="mt-1 block w-full" 
                                             :value="old('jabatan')" placeholder="Contoh: Statistisi Ahli Muda" />
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
                                             :value="old('email')" placeholder="nama@bps.go.id" required />
                                <x-input-error class="mt-2" :messages="$errors->get('email')" />
                                <p class="mt-1 text-xs text-gray-500">
                                    💡 Email akan digunakan untuk login ke sistem
                                </p>
                            </div>

                            <div>
                                <x-input-label for="nomor_hp" :value="__('Nomor HP')" />
                                <x-text-input id="nomor_hp" name="nomor_hp" type="text" class="mt-1 block w-full" 
                                             :value="old('nomor_hp')" placeholder="08xxxxxxxxxx" />
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
                                        <option value="{{ $pendidikan }}" {{ old('pendidikan_tertinggi') == $pendidikan ? 'selected' : '' }}>{{ $pendidikan }}</option>
                                    @endforeach
                                </select>
                                <x-input-error class="mt-2" :messages="$errors->get('pendidikan_tertinggi')" />
                            </div>

                            <div>
                                <x-input-label for="program_studi" :value="__('Program Studi')" />
                                <x-text-input id="program_studi" name="program_studi" type="text" class="mt-1 block w-full" 
                                             :value="old('program_studi')" placeholder="Contoh: Statistika" />
                                <x-input-error class="mt-2" :messages="$errors->get('program_studi')" />
                            </div>

                            <div>
                                <x-input-label for="universitas" :value="__('Universitas')" />
                                <x-text-input id="universitas" name="universitas" type="text" class="mt-1 block w-full" 
                                             :value="old('universitas')" placeholder="Nama universitas" />
                                <x-input-error class="mt-2" :messages="$errors->get('universitas')" />
                            </div>
                        </div>
                    </div>

                    <!-- Info Box -->
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <div class="flex">
                            <svg class="w-5 h-5 text-blue-500 mt-1 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                            </svg>
                            <div class="text-sm text-blue-700">
                                <p class="font-semibold mb-2">Informasi Penting:</p>
                                <ul class="list-disc list-inside space-y-1">
                                    <li>Setelah data pegawai disimpan, sistem akan otomatis membuat akun user</li>
                                    <li>Email akan digunakan sebagai username untuk login</li>
                                    <li>Password default adalah NIP Lama yang dimasukkan</li>
                                    <li>Role default yang akan diberikan adalah "Anggota Tim"</li>
                                    <li>Pegawai dapat mengubah password setelah login pertama kali</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Buttons -->
                    <div class="flex justify-end space-x-4 pt-6 border-t">
                        <x-secondary-button type="button" onclick="window.location.href='{{ route('pegawai.index') }}'">
                            {{ __('Batal') }}
                        </x-secondary-button>
                        <x-primary-button>
                            {{ __('Simpan Pegawai') }}
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

        // Email suggestion
        document.getElementById('nama_lengkap').addEventListener('blur', function(e) {
            const emailInput = document.getElementById('email');
            if (!emailInput.value && e.target.value) {
                const nama = e.target.value.toLowerCase()
                    .replace(/[^a-z\s]/g, '')
                    .split(' ')
                    .filter(word => word.length > 2)
                    .slice(0, 2)
                    .join('.');
                
                if (nama) {
                    emailInput.placeholder = `${nama}@bps.go.id`;
                }
            }
        });
    </script>
</x-app-layout>