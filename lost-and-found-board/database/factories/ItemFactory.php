<?php

namespace Database\Factories;

use App\Models\Item;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Item>
 */
class ItemFactory extends Factory
{
    /**
     * A curated pool of realistic lost & found scenarios in plain English,
     * since Faker's text providers generate Latin placeholder text regardless of locale.
     */
    protected static array $items = [
        ['title' => 'Black Leather Wallet', 'description' => 'A folded black leather wallet with a few cards inside and a small amount of cash. Has some wear on the corners.'],
        ['title' => 'iPhone 13 - Blue Case', 'description' => 'An iPhone 13 in a clear blue silicone case. Lock screen shows a photo of a dog.'],
        ['title' => 'Silver House Keys with Keychain', 'description' => 'A set of three silver house keys on a keyring with a small red keychain shaped like a star.'],
        ['title' => 'Blue Hydro Flask Water Bottle', 'description' => 'A 32oz blue Hydro Flask with a few stickers on the side, including a mountain logo.'],
        ['title' => 'Prescription Glasses - Tortoiseshell Frame', 'description' => 'Tortoiseshell prescription glasses in a black hard case. Moderate strength lenses.'],
        ['title' => 'Student ID Card', 'description' => 'A student ID card, slightly bent at one corner. Found near the main entrance.'],
        ['title' => 'Grey Nike Hoodie', 'description' => 'A medium-sized grey Nike hoodie with a small tear near the left pocket.'],
        ['title' => 'Black and Red Umbrella', 'description' => 'A compact folding umbrella, black with red trim, still in good condition.'],
        ['title' => 'White AirPods Pro Case', 'description' => 'A white AirPods Pro charging case with a small scuff on the lid. No AirPods inside.'],
        ['title' => 'Textbook - Introduction to Psychology', 'description' => 'A used psychology textbook with highlighted passages and sticky notes throughout.'],
        ['title' => 'Toyota Car Keys with Blue Lanyard', 'description' => 'A single car key on a blue lanyard, along with a small bottle opener attachment.'],
        ['title' => 'Navy Blue JanSport Backpack', 'description' => 'A navy blue JanSport backpack containing a notebook and a pencil case.'],
        ['title' => 'Logitech Wireless Mouse', 'description' => 'A black Logitech wireless mouse, battery cover slightly loose.'],
        ['title' => 'Brown Leather Wallet with Cards', 'description' => 'A brown leather bifold wallet with several loyalty cards but no cash.'],
        ['title' => 'Stainless Steel Water Bottle', 'description' => 'A dented stainless steel water bottle with a carabiner clip attached to the cap.'],
        ['title' => 'Reading Glasses in Red Case', 'description' => 'A pair of reading glasses stored in a hard red case with a cleaning cloth.'],
        ['title' => 'Black Bluetooth Speaker', 'description' => 'A small portable JBL bluetooth speaker, still powers on and pairs fine.'],
        ['title' => 'Red Plaid Scarf', 'description' => 'A soft red and black plaid scarf, looks handmade or knitted.'],
        ['title' => 'Ray-Ban Aviator Sunglasses', 'description' => 'Classic gold-frame aviator sunglasses in a black zip case.'],
        ['title' => 'Silver Apple Watch', 'description' => 'A silver Apple Watch with a white sport band, screen has a small crack.'],
        ['title' => 'Black Umbrella - Compact', 'description' => 'A small compact black umbrella, automatic open button still works.'],
        ['title' => 'Grey Beanie Hat', 'description' => 'A plain grey knit beanie, appears handmade with a small logo tag inside.'],
        ['title' => 'Blue Insulated Lunch Bag', 'description' => 'An insulated lunch bag with a reusable ice pack still inside.'],
        ['title' => 'Wireless Earbuds - Single Left Earbud', 'description' => 'One white wireless earbud, left side only, found on the floor near the seating area.'],
    ];

    protected static array $locations = [
        'Library - 2nd Floor Study Room',
        'Main Cafeteria',
        'Room 204, Engineering Building',
        'Parking Lot B',
        'Gym Locker Room',
        'Bus Stop on Elm Street',
        'Front Office Reception',
        'Auditorium - Row 12',
        'Coffee Shop near Building C',
        'Computer Lab 3',
        'Student Union Lounge',
        'Outdoor Basketball Court',
    ];

    protected static array $names = [
        'Alex Morgan', 'Jamie Chen', 'Sarah Johnson', 'Michael Lee', 'Priya Patel',
        'Chris Evans', 'Taylor Brooks', 'Jordan Smith', 'Emily Davis', 'Daniel Kim',
    ];

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $item = fake()->randomElement(static::$items);
        $name = fake()->randomElement(static::$names);

        return [
            'title' => $item['title'],
            'description' => $item['description'],
            'type' => fake()->randomElement(['lost', 'found']),
            'location' => fake()->randomElement(static::$locations),
            'contact' => fake()->boolean()
                ? strtolower(str_replace(' ', '.', $name)).'@example.com'
                : '(555) '.fake()->numberBetween(100, 999).'-'.fake()->numberBetween(1000, 9999),
            'photo' => null,
            'is_claimed' => fake()->boolean(20),
        ];
    }
}
