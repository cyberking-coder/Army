<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\Setting;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        Admin::updateOrCreate(
            ['email' => env('ADMIN_EMAIL', 'admin@jointerritorialarmy.com')],
            [
                'name'     => env('ADMIN_NAME', 'Super Admin'),
                'password' => Hash::make(env('ADMIN_PASSWORD', 'ChangeMe123!')),
                'role'     => 'super_admin',
            ]
        );

        $defaults = [
            ['site_title',       'Territorial Army',                              'string'],
            ['site_tagline',     'Join the Nation. Serve with Pride.',            'string'],
            ['contact_email',    'contact@jointerritorialarmy.com',               'string'],
            ['contact_phone',    '+91-00000-00000',                               'string'],
            ['icon_favicon',     'icons/favicon.ico',                             'string'],
            ['icon_apple_touch', 'icons/apple-touch-icon.png',                    'string'],
        ];

        foreach ($defaults as [$key, $value, $type]) {
            Setting::updateOrCreate(['key' => $key], ['value' => $value, 'type' => $type]);
        }

        $this->command->info('Seeded admin: '.env('ADMIN_EMAIL'));
    }
}
