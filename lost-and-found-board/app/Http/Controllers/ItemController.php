<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ItemController extends Controller
{
    public function index(Request $request)
    {
        $items = Item::query()
            ->when($request->filled('type'), fn ($query) => $query->where('type', $request->query('type')))
            ->when($request->query('status') === 'claimed', fn ($query) => $query->where('is_claimed', true))
            ->latest()
            ->paginate(9)
            ->withQueryString();

        $stats = [
            'total' => Item::count(),
            'lost' => Item::where('type', 'lost')->where('is_claimed', false)->count(),
            'found' => Item::where('type', 'found')->where('is_claimed', false)->count(),
            'claimed' => Item::where('is_claimed', true)->count(),
        ];

        return view('items.index', ['items' => $items, 'stats' => $stats]);
    }

    public function create()
    {
        return view('items.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'type' => ['required', 'in:lost,found'],
            'location' => ['required', 'string', 'max:255'],
            'contact' => ['required', 'string', 'max:255'],
            'photo' => ['nullable', 'image', 'max:4096'],
        ]);

        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('items', 'public') ?: null;
        }

        $item = Item::create([...$validated, 'is_claimed' => false]);

        return redirect()->route('items.show', $item)->with('status', 'Item reported successfully.');
    }

    public function show(Item $item)
    {
        return view('items.show', ['item' => $item]);
    }

    public function markClaimed(Item $item)
    {
        $item->update(['is_claimed' => true]);

        return redirect()->route('items.show', $item)->with('status', 'Item marked as claimed.');
    }
}
