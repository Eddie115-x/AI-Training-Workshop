<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-800 leading-tight">
            {{ __('Report an Item') }}
        </h2>
        <p class="text-sm text-slate-500 mt-1">Share the details below to help reunite this item with its owner.</p>
    </x-slot>

    <div class="py-10">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-xl border border-slate-200 p-6 sm:p-8">
                <form method="POST" action="{{ route('items.store') }}" enctype="multipart/form-data" class="space-y-6">
                    @csrf

                    <div>
                        <x-input-label value="Is this item lost or found?" />
                        <div class="mt-2 grid grid-cols-2 gap-3">
                            <label class="flex items-center justify-center gap-2 rounded-lg border border-slate-300 px-4 py-3 cursor-pointer has-[:checked]:border-red-500 has-[:checked]:bg-red-50 transition">
                                <input type="radio" name="type" value="lost" class="text-red-600 focus:ring-red-500" {{ old('type', 'lost') === 'lost' ? 'checked' : '' }}>
                                <span class="text-sm font-medium text-slate-700">Lost</span>
                            </label>
                            <label class="flex items-center justify-center gap-2 rounded-lg border border-slate-300 px-4 py-3 cursor-pointer has-[:checked]:border-emerald-500 has-[:checked]:bg-emerald-50 transition">
                                <input type="radio" name="type" value="found" class="text-emerald-600 focus:ring-emerald-500" {{ old('type') === 'found' ? 'checked' : '' }}>
                                <span class="text-sm font-medium text-slate-700">Found</span>
                            </label>
                        </div>
                        <x-input-error :messages="$errors->get('type')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="title" value="Title" />
                        <x-text-input id="title" name="title" type="text" class="mt-1 block w-full" value="{{ old('title') }}" placeholder="e.g. Black Leather Wallet" required autofocus />
                        <x-input-error :messages="$errors->get('title')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="description" value="Description" />
                        <textarea id="description" name="description" rows="4" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Color, brand, distinguishing features..." required>{{ old('description') }}</textarea>
                        <x-input-error :messages="$errors->get('description')" class="mt-2" />
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div>
                            <x-input-label for="location" value="Location" />
                            <x-text-input id="location" name="location" type="text" class="mt-1 block w-full" value="{{ old('location') }}" placeholder="Where was it lost / found?" required />
                            <x-input-error :messages="$errors->get('location')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="contact" value="Contact info" />
                            <x-text-input id="contact" name="contact" type="text" class="mt-1 block w-full" value="{{ old('contact') }}" placeholder="Email or phone number" required />
                            <x-input-error :messages="$errors->get('contact')" class="mt-2" />
                        </div>
                    </div>

                    <div>
                        <x-input-label for="photo" value="Photo (optional)" />
                        <div class="mt-1 flex items-center justify-center rounded-lg border-2 border-dashed border-slate-300 px-6 py-6 hover:border-indigo-400 transition">
                            <div class="text-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-8 w-8 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="3" y="3" width="18" height="18" rx="2"></rect>
                                    <circle cx="8.5" cy="8.5" r="1.5"></circle>
                                    <path d="M21 15l-5-5L5 21"></path>
                                </svg>
                                <input id="photo" name="photo" type="file" accept="image/*" class="mt-2 block w-full text-sm text-slate-600 file:mr-3 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-semibold file:uppercase file:tracking-wide file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                            </div>
                        </div>
                        <x-input-error :messages="$errors->get('photo')" class="mt-2" />
                    </div>

                    <div class="flex items-center gap-4 pt-2">
                        <x-primary-button>{{ __('Submit') }}</x-primary-button>
                        <a href="{{ route('items.index') }}" class="text-sm text-slate-500 hover:text-slate-700">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
