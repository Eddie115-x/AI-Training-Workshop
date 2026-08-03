<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ItemResource;
use App\Models\Item;
use Illuminate\Http\Request;

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

        return ItemResource::collection($items);
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

        return (new ItemResource($item))->response()->setStatusCode(201);
    }

    public function show(Item $item)
    {
        return new ItemResource($item);
    }

    public function markClaimed(Item $item)
    {
        $item->update(['is_claimed' => true]);

        return new ItemResource($item);
    }
}
