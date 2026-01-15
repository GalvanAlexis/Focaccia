<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CreateAdminUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Crear usuario administrador';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $user = \App\Models\User::create([
            'name' => 'Admin La Bartola',
            'email' => 'admin@labartola.com',
            'password' => \Illuminate\Support\Facades\Hash::make('admin123'),
        ]);

        $user->assignRole('admin');

        $this->info('✅ Usuario admin creado exitosamente!');
        $this->info('Email: admin@labartola.com');
        $this->info('Password: admin123');

        return 0;
    }
}
