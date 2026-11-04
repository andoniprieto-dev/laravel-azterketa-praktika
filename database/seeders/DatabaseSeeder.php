<?php

namespace Database\Seeders;

use App\Enums\Role;
use App\Models\Jarduera;
use App\Models\Kategoria;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@kirolzentroa.eus',
            'role' => Role::Admin,
        ]);

        $begiraleak = User::factory(2)->create(['role' => Role::Begiralea]);
        $bazkideak = User::factory(5)->create(['role' => Role::Bazkidea]);

        foreach (['Igeriketa', 'Yoga', 'Spinning'] as $izena) {
            Kategoria::create(['izena' => $izena]);
        }

        $jarduerak = Jarduera::factory(6)->create([
            'begiralea_id' => fn () => $begiraleak->random()->id,
        ]);

        foreach ($bazkideak as $bazkidea) {
            $bazkidea->jarduerak()->attach(
                $jarduerak->random(2)->pluck('id'),
                ['ordainduta' => fake()->boolean()]
            );
        }
    }
}
