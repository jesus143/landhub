<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="font-bold text-2xl text-slate-900 dark:text-white leading-tight">
                {{ __('Profile') }}
            </h2>
            <p class="mt-1 text-sm text-slate-600 dark:text-slate-400">
                Manage your account settings and preferences
            </p>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-6 sm:p-8 bg-white dark:bg-slate-800 shadow-lg sm:rounded-xl border border-slate-200 dark:border-slate-700">
                <div class="max-w-xl">
                    <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-4">Profile Information</h3>
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="p-6 sm:p-8 bg-white dark:bg-slate-800 shadow-lg sm:rounded-xl border border-slate-200 dark:border-slate-700">
                <div class="max-w-xl">
                    <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-4">Update Password</h3>
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="p-6 sm:p-8 bg-red-50 dark:bg-red-900/20 shadow-lg sm:rounded-xl border border-red-200 dark:border-red-800">
                <div class="max-w-xl">
                    <h3 class="text-lg font-semibold text-red-900 dark:text-red-300 mb-4">Delete Account</h3>
                    <p class="text-sm text-red-700 dark:text-red-400 mb-4">
                        Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.
                    </p>
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
