<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Veterinario;
use App\Models\Cliente;
use App\Models\Paciente;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ── Admin ─────────────────────────────────────────────────────────
        User::create([
            'name'     => 'Administrador',
            'email'    => 'admin@veterinaria.com',
            'password' => Hash::make('password'),
            'rol'      => 'admin',
        ]);

        // ── Recepcionista ─────────────────────────────────────────────────
        User::create([
            'name'     => 'María García',
            'email'    => 'recepcion@veterinaria.com',
            'password' => Hash::make('password'),
            'rol'      => 'recepcionista',
        ]);

        // ── Veterinario 1 ──────────────────────────────────────────────────
        $userVet1 = User::create([
            'name'     => 'Dr. Carlos Mendoza',
            'email'    => 'cmendoza@veterinaria.com',
            'password' => Hash::make('password'),
            'rol'      => 'veterinario',
        ]);
        Veterinario::create([
            'user_id'         => $userVet1->id,
            'especialidad'    => 'Medicina General',
            'telefono'        => '3001234567',
            'numero_licencia' => 'VET-001-2020',
            'horario_inicio'  => '08:00:00',
            'horario_fin'     => '17:00:00',
        ]);

        // ── Veterinario 2 ──────────────────────────────────────────────────
        $userVet2 = User::create([
            'name'     => 'Dra. Sofía Ramírez',
            'email'    => 'sramirez@veterinaria.com',
            'password' => Hash::make('password'),
            'rol'      => 'veterinario',
        ]);
        Veterinario::create([
            'user_id'         => $userVet2->id,
            'especialidad'    => 'Cirugía y Ortopedia',
            'telefono'        => '3109876543',
            'numero_licencia' => 'VET-002-2019',
            'horario_inicio'  => '09:00:00',
            'horario_fin'     => '18:00:00',
        ]);

        // ── Clientes ───────────────────────────────────────────────────────
        $cliente1 = Cliente::create([
            'nombre'    => 'Juan',
            'apellido'  => 'Pérez',
            'cedula'    => '1234567890',
            'telefono'  => '3151234567',
            'email'     => 'juan.perez@email.com',
            'direccion' => 'Calle 10 # 5-20, Cali',
        ]);

        $cliente2 = Cliente::create([
            'nombre'    => 'Ana',
            'apellido'  => 'López',
            'cedula'    => '0987654321',
            'telefono'  => '3204567890',
            'email'     => 'ana.lopez@email.com',
            'direccion' => 'Carrera 3 # 15-45, Cali',
        ]);

        $cliente3 = Cliente::create([
            'nombre'    => 'Roberto',
            'apellido'  => 'Torres',
            'cedula'    => '1122334455',
            'telefono'  => '3007654321',
            'email'     => 'roberto.torres@email.com',
            'direccion' => 'Av. Colombia # 8-90, Cali',
        ]);

        // ── Pacientes ──────────────────────────────────────────────────────
        Paciente::create([
            'cliente_id'       => $cliente1->id,
            'nombre'           => 'Firulais',
            'especie'          => 'Perro',
            'raza'             => 'Labrador',
            'sexo'             => 'macho',
            'fecha_nacimiento' => '2020-03-15',
            'peso'             => 28.5,
            'color'            => 'Amarillo',
            'esterilizado'     => true,
        ]);

        Paciente::create([
            'cliente_id'       => $cliente1->id,
            'nombre'           => 'Luna',
            'especie'          => 'Gato',
            'raza'             => 'Siamés',
            'sexo'             => 'hembra',
            'fecha_nacimiento' => '2021-06-10',
            'peso'             => 4.2,
            'color'            => 'Blanco y café',
            'esterilizado'     => true,
        ]);

        Paciente::create([
            'cliente_id'       => $cliente2->id,
            'nombre'           => 'Max',
            'especie'          => 'Perro',
            'raza'             => 'Golden Retriever',
            'sexo'             => 'macho',
            'fecha_nacimiento' => '2019-08-20',
            'peso'             => 32.0,
            'color'            => 'Dorado',
            'esterilizado'     => false,
            'alergias'         => 'Sensibilidad al pollo',
        ]);

        Paciente::create([
            'cliente_id'       => $cliente3->id,
            'nombre'           => 'Mia',
            'especie'          => 'Gato',
            'raza'             => 'Persa',
            'sexo'             => 'hembra',
            'fecha_nacimiento' => '2022-01-05',
            'peso'             => 3.8,
            'color'            => 'Gris',
            'esterilizado'     => true,
        ]);
    }
}
