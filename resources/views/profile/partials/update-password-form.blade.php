<section>
    <form method="post" action="{{ route('password.update') }}" class="space-y-6">
        @csrf
        @method('put')

        <div>
            <x-input-label for="update_password_current_password" :value="__('Password Saat Ini')" />
            <div class="relative">
                <x-text-input id="update_password_current_password" name="current_password" type="password" class="block w-full" autocomplete="current-password" placeholder="••••••••" />
            </div>
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
        </div>

        <div class="grid md:grid-cols-2 gap-6">
            <div>
                <x-input-label for="update_password_password" :value="__('Password Baru')" />
                <div class="relative">
                    <x-text-input id="update_password_password" name="password" type="password" class="block w-full" autocomplete="new-password" placeholder="••••••••" />
                </div>
                <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="update_password_password_confirmation" :value="__('Konfirmasi Password Baru')" />
                <div class="relative">
                    <x-text-input id="update_password_password_confirmation" name="password_confirmation" type="password" class="block w-full" autocomplete="new-password" placeholder="••••••••" />
                </div>
                <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
            </div>
        </div>

        <div class="flex justify-end pt-4">
            <button type="submit" class="bg-gray-200 dark:bg-white/10 hover:bg-gray-300 dark:hover:bg-white/20 text-gray-800 dark:text-white font-bold py-3 px-8 rounded-xl transition-all flex items-center gap-2 w-full md:w-auto justify-center md:justify-start">
                <span class="material-symbols-rounded">key</span>
                {{ __('Ganti Password') }}
            </button>

            @if (session('status') === 'password-updated')
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
