<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-xl text-slate-800 leading-tight">
                    {{ __('Lost & Found Board') }}
                </h2>
                <p class="text-sm text-slate-500 mt-1">Browse reported items or help reunite one with its owner.</p>
            </div>
            <a href="{{ route('items.create') }}" class="hidden sm:inline-flex items-center gap-1.5 px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="12" y1="5" x2="12" y2="19"></line>
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                </svg>
                {{ __('Report Item') }}
            </a>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if (session('status'))
                <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-lg text-sm">
                    {{ session('status') }}
                </div>
            @endif

            <!-- Stats -->
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                <div class="bg-white rounded-xl border border-slate-200 p-4">
                    <p class="text-xs font-medium text-slate-500 uppercase tracking-wide">Total Items</p>
                    <p class="mt-1 text-2xl font-semibold text-slate-800">{{ $stats['total'] }}</p>
                </div>
                <div class="bg-white rounded-xl border border-slate-200 p-4">
                    <p class="text-xs font-medium text-red-600 uppercase tracking-wide">Lost</p>
                    <p class="mt-1 text-2xl font-semibold text-slate-800">{{ $stats['lost'] }}</p>
                </div>
                <div class="bg-white rounded-xl border border-slate-200 p-4">
                    <p class="text-xs font-medium text-emerald-600 uppercase tracking-wide">Found</p>
                    <p class="mt-1 text-2xl font-semibold text-slate-800">{{ $stats['found'] }}</p>
                </div>
                <div class="bg-white rounded-xl border border-slate-200 p-4">
                    <p class="text-xs font-medium text-slate-500 uppercase tracking-wide">Claimed</p>
                    <p class="mt-1 text-2xl font-semibold text-slate-800">{{ $stats['claimed'] }}</p>
                </div>
            </div>

            <!-- Filters -->
            <form method="GET" action="{{ route('items.index') }}" class="bg-white rounded-xl border border-slate-200 p-4 flex flex-wrap items-end gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Type</label>
                    <select name="type" class="rounded-md border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                        <option value="">All</option>
                        <option value="lost" @selected(request('type') === 'lost')>Lost</option>
                        <option value="found" @selected(request('type') === 'found')>Found</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Status</label>
                    <select name="status" class="rounded-md border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                        <option value="">All</option>
                        <option value="claimed" @selected(request('status') === 'claimed')>Claimed</option>
                    </select>
                </div>

                <div class="flex gap-2">
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500">
                        Filter
                    </button>
                    <a href="{{ route('items.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-slate-300 rounded-md font-semibold text-xs text-slate-700 uppercase tracking-widest hover:bg-slate-50">
                        Reset
                    </a>
                </div>
            </form>

            <!-- Card grid -->
            @if ($items->isEmpty())
                <div class="bg-white rounded-xl border border-slate-200 p-12 text-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 mx-auto text-slate-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="11" cy="11" r="7"></circle>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                    </svg>
                    <p class="mt-3 text-slate-500 text-sm">No items match these filters yet.</p>
                    <a href="{{ route('items.create') }}" class="mt-4 inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500">
                        Report an Item
                    </a>
                </div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach ($items as $item)
                        <a href="{{ route('items.show', $item) }}" class="group bg-white rounded-xl border border-slate-200 overflow-hidden hover:shadow-lg hover:-translate-y-0.5 transition-all duration-150 flex flex-col">
                            <div class="relative h-44 bg-slate-100 flex items-center justify-center overflow-hidden">
                                @if ($item->photoUrl())
                                    <img src="{{ $item->photoUrl() }}" alt="{{ $item->title }}" class="h-full w-full object-cover group-hover:scale-105 transition-transform duration-200">
                                @else
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-slate-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                        <rect x="3" y="3" width="18" height="18" rx="2"></rect>
                                        <circle cx="8.5" cy="8.5" r="1.5"></circle>
                                        <path d="M21 15l-5-5L5 21"></path>
                                    </svg>
                                @endif

                                <div class="absolute top-2 right-2">
                                    @if ($item->is_claimed)
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-slate-700/90 text-white backdrop-blur-sm">
                                            Claimed
                                        </span>
                                    @elseif ($item->type === 'lost')
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-red-600/90 text-white backdrop-blur-sm">
                                            Lost
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-emerald-600/90 text-white backdrop-blur-sm">
                                            Found
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="p-4 flex-1 flex flex-col gap-2">
                                <h3 class="font-semibold text-slate-800 truncate group-hover:text-indigo-600 transition-colors">{{ $item->title }}</h3>
                                <p class="text-sm text-slate-500 line-clamp-2">{{ $item->description }}</p>
                                <p class="text-xs text-slate-400 mt-auto flex items-center gap-1 pt-2 border-t border-slate-100">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                                        <circle cx="12" cy="10" r="3"></circle>
                                    </svg>
                                    {{ $item->location }}
                                </p>
                            </div>
                        </a>
                    @endforeach
                </div>

                <div>
                    {{ $items->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
