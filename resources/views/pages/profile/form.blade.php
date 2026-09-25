<div class="mx-auto max-w-4xl space-y-6">
    <div>
        <h2 class="text-2xl font-bold text-gray-800 dark:text-white/90">Profil Saya</h2>
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
            Kelola data akun dan informasi profil kamu.
        </p>
    </div>

    @if (session('success'))
        <div class="rounded-lg border border-success-200 bg-success-50 px-4 py-3 text-sm text-success-700 dark:border-success-500/30 dark:bg-success-500/15 dark:text-success-400">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="rounded-lg border border-error-200 bg-error-50 px-4 py-3 text-sm text-error-700 dark:border-error-500/30 dark:bg-error-500/15 dark:text-error-400">
            <p class="font-medium">Periksa kembali data yang diisi.</p>
            <ul class="mt-1 list-inside list-disc">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route($profileUpdateRoute) }}" class="space-y-6">
        @csrf
        @method('PATCH')

        <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-gray-900 sm:p-6">
            <h3 class="text-base font-semibold text-gray-800 dark:text-white/90">Informasi Akun</h3>
            <div class="mt-5 grid grid-cols-1 gap-5 sm:grid-cols-2">
                @foreach ([
                    ['name', 'Nama', 'text', true],
                    ['email', 'Email', 'email', true],
                    ['phone', 'Nomor Telepon', 'text', false],
                    ['nama_toko', 'Nama Toko', 'text', false],
                    ['kota', 'Kota', 'text', false],
                ] as [$field, $label, $type, $required])
                    <div>
                        <label for="{{ $field }}" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">{{ $label }}</label>
                        <input id="{{ $field }}" name="{{ $field }}" type="{{ $type }}"
                            value="{{ old($field, $user->{$field}) }}" @required($required)
                            class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90" />
                    </div>
                @endforeach

                <div class="sm:col-span-2">
                    <label for="alamat" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Alamat</label>
                    <textarea id="alamat" name="alamat" rows="3"
                        class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">{{ old('alamat', $user->alamat) }}</textarea>
                </div>
            </div>
        </div>

        <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-gray-900 sm:p-6">
            <h3 class="text-base font-semibold text-gray-800 dark:text-white/90">Ubah Password</h3>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Kosongkan jika tidak ingin mengubah password.</p>
            <div class="mt-5 grid grid-cols-1 gap-5 sm:grid-cols-2">
                <div class="sm:col-span-2">
                    <label for="current_password" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Password Saat Ini</label>
                    <input id="current_password" name="current_password" type="password" autocomplete="current-password"
                        class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90" />
                </div>
                <div>
                    <label for="password" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Password Baru</label>
                    <input id="password" name="password" type="password" autocomplete="new-password"
                        class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90" />
                </div>
                <div>
                    <label for="password_confirmation" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Konfirmasi Password Baru</label>
                    <input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password"
                        class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90" />
                </div>
            </div>
        </div>

        <div class="flex justify-end">
            <button type="submit" class="w-full rounded-lg bg-brand-500 px-5 py-2.5 text-sm font-medium text-white transition hover:bg-brand-600 sm:w-auto">
                Simpan Perubahan
            </button>
        </div>
    </form>
</div>
