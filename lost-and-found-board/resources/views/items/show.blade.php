<x-app-layout>
    <x-slot name="header">
        <nav class="text-sm">
            <a href="{{ route('items.index') }}" class="text-slate-500 hover:text-indigo-600">&larr; Back to board</a>
        </nav>
    </x-slot>

    <div class="py-10">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if (session('status'))
                <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-lg text-sm">
                    {{ session('status') }}
                </div>
            @endif

            <div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
                <div class="relative h-72 bg-slate-100 flex items-center justify-center">
                    @if ($item->photoUrl())
                        <img src="{{ $item->photoUrl() }}" alt="{{ $item->title }}" class="h-full w-full object-cover">
                    @else
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-14 w-14 text-slate-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="3" width="18" height="18" rx="2"></rect>
                            <circle cx="8.5" cy="8.5" r="1.5"></circle>
                            <path d="M21 15l-5-5L5 21"></path>
                        </svg>
                    @endif

                    <div class="absolute top-3 right-3">
                        @if ($item->is_claimed)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-slate-700/90 text-white backdrop-blur-sm">
                                Claimed
                            </span>
                        @elseif ($item->type === 'lost')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-600/90 text-white backdrop-blur-sm">
                                Lost
                            </span>
                        @else
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-emerald-600/90 text-white backdrop-blur-sm">
                                Found
                            </span>
                        @endif
                    </div>
                </div>

                <div class="p-6 sm:p-8 space-y-6">
                    @if ($item->is_claimed)
                        <div class="flex items-center gap-2 text-sm text-slate-600 bg-slate-50 border border-slate-200 rounded-lg px-4 py-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-emerald-600 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M20 6L9 17l-5-5"></path>
                            </svg>
                            This item has been claimed and reunited with its owner.
                        </div>
                    @endif

                    <div>
                        <h1 class="text-2xl font-semibold text-slate-800">{{ $item->title }}</h1>
                        <p class="text-sm text-slate-400 mt-1">Reported {{ $item->created_at->diffForHumans() }}</p>
                    </div>

                    <p class="text-slate-600 leading-relaxed">{{ $item->description }}</p>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="rounded-lg border border-slate-200 p-4">
                            <dt class="flex items-center gap-1.5 text-xs font-medium text-slate-500 uppercase tracking-wide">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                                    <circle cx="12" cy="10" r="3"></circle>
                                </svg>
                                Location
                            </dt>
                            <dd class="text-slate-800 mt-1">{{ $item->location }}</dd>
                        </div>
                        <div class="rounded-lg border border-slate-200 p-4">
                            <dt class="flex items-center gap-1.5 text-xs font-medium text-slate-500 uppercase tracking-wide">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"></path>
                                </svg>
                                Contact
                            </dt>
                            <dd class="text-slate-800 mt-1">{{ $item->contact }}</dd>
                        </div>
                    </div>

                    <div class="flex items-center gap-4 pt-4 border-t border-slate-100">
                        <a href="{{ route('items.index') }}" class="text-sm text-slate-500 hover:text-slate-700">&larr; Back to board</a>

                        @unless ($item->is_claimed)
                            <form method="POST" action="{{ route('items.markClaimed', $item) }}" class="ms-auto">
                                @csrf
                                @method('PATCH')
                                <x-primary-button>
                                    Mark as Claimed
                                </x-primary-button>
                            </form>
                        @endunless
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
