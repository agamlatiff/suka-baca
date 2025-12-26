<section>
    <h2 class="text-xl font-bold text-primary dark:text-white mb-6 flex items-center gap-2">
        <span class="material-symbols-rounded text-secondary-accent">face</span>
        Informasi Pribadi
    </h2>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="space-y-6">
        @csrf
        @method('patch')

        <div class="grid md:grid-cols-2 gap-6">
            {{-- Name --}}
            <div class="space-y-2">
                <x-input-label for="name" :value="__('Nama Lengkap')" />
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                        <span class="material-symbols-rounded text-[20px]">badge</span>
                    </span>
                    <x-text-input id="name" name="name" type="text" class="pl-10" :value="old('name', $user->name)" required autofocus autocomplete="name" placeholder="Nama Lengkap" />
                </div>
                <x-input-error class="mt-2" :messages="$errors->get('name')" />
            </div>

            {{-- Email --}}
            <div class="space-y-2">
                <x-input-label for="email" :value="__('Email')" />
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                        <span class="material-symbols-rounded text-[20px]">mail</span>
                    </span>
                    <x-text-input id="email" name="email" type="email" class="pl-10" :value="old('email', $user->email)" required autocomplete="username" placeholder="Email" />
                </div>
                <x-input-error class="mt-2" :messages="$errors->get('email')" />

                @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                    <div class="mt-2">
                        <p class="text-sm text-gray-800">
                            {{ __('Your email address is unverified.') }}

                            <button form="send-verification" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                {{ __('Click here to re-send the verification email.') }}
                            </button>
                        </p>

                        @if (session('status') === 'verification-link-sent')
                            <p class="mt-2 font-medium text-sm text-green-600">
                                {{ __('A new verification link has been sent to your email address.') }}
                            </p>
                        @endif
                    </div>
                @endif
            </div>

            {{-- Phone --}}
            <div class="space-y-2">
                <x-input-label for="phone" :value="__('Nomor HP')" />
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                        <span class="material-symbols-rounded text-[20px]">phone_iphone</span>
                    </span>
                    <x-text-input id="phone" name="phone" type="tel" class="pl-10" :value="old('phone', $user->phone)" placeholder="08xxxxxxxx" />
                </div>
                <x-input-error class="mt-2" :messages="$errors->get('phone')" />
            </div>
            
            {{-- Address (Full width in design, but putting in grid for now or break out?) --}}
            {{-- Design had address as full width below grid. Let's start a new div after the grid. --}}
        </div>

        {{-- Address --}}
        <div class="space-y-2">
            <x-input-label for="address" :value="__('Alamat Lengkap')" />
            <div class="relative">
                <span class="absolute top-3 left-3 flex items-start pointer-events-none text-gray-400">
                    <span class="material-symbols-rounded text-[20px]">home_pin</span>
                </span>
                <textarea class="w-full pl-10 pr-4 py-3 bg-gray-50 dark:bg-white/5 border-gray-200 dark:border-white/10 rounded-xl focus:ring-primary focus:border-primary text-sm dark:text-white dark:placeholder-gray-500 transition-all min-h-[100px] shadow-sm" id="address" name="address" placeholder="Masukkan alamat lengkap pengiriman buku">{{ old('address', $user->address) }}</textarea>
            </div>
            <x-input-error class="mt-2" :messages="$errors->get('address')" />
        </div>

        <div class="flex justify-end pt-4">
            <button type="submit" class="bg-primary hover:bg-primary-light text-white font-bold py-3 px-8 rounded-xl transition-all shadow-lg shadow-primary/20 flex items-center gap-2 hover:translate-y-[-2px] w-full md:w-auto justify-center md:justify-start">
                <span class="material-symbols-rounded">save</span>
                {{ __('Simpan Perubahan') }}
            </button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600 ml-4 self-center"
                >{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>
