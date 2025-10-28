<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class DireccionesPacientesSeeder extends Seeder
{
    public function run(): void
    {
        // Use a consistent "now" in La Paz timezone
        $now = Carbon::now('America/La_Paz');

        // === SUSCRIPCION ===
        DB::table('suscripcion')->insert([
            [
                'nombre' => "Mantener peso",
                'created_at' => $now
            ],
            [
                'nombre' => "Bajar peso",
                'created_at' => $now
            ],
            [
                'nombre' => "Masa muscular",
                'created_at' => $now
            ],
        ]);

        // === PACIENTE ===
        DB::table('paciente')->insertGetId([
            'nombre' => 'Juan Pérez',
            'documento' => 'CI-1234567',
            'suscripcion_id' => 1,
            'created_at' => $now
        ]);

        DB::table('paciente')->insertGetId([
            'nombre' => 'María Gómez',
            'documento' => 'CI-7654321',
            'suscripcion_id' => 2,
            'created_at' => $now
        ]);

        DB::table('paciente')->insertGetId([
            'nombre' => 'Luis Andrade',
            'documento' => 'CI-8178772',
            'suscripcion_id' => 3,
            'created_at' => $now
        ]);

        // === DIRECCION ===
        DB::table('direccion')->insert([
            [
                'nombre' => 'Casa Juan',
                'linea1' => 'Av. 16 de Julio 123',
                'linea2' => 'Depto 4B',
                'ciudad' => 'La Paz',
                'provincia' => 'La Paz',
                'pais' => 'Bolivia',
                'geo' => json_encode(['latitud' => -17.7333000, 'longitud' => -63.1667000]),
                'created_at' => $now
            ],
            [
                'nombre' => 'Oficina María',
                'linea1' => 'Calle España 456',
                'linea2' => null,
                'ciudad' => 'Cochabamba',
                'provincia' => 'Cochabamba',
                'pais' => 'Bolivia',
                'geo' => json_encode(['latitud' => -17.7333000, 'longitud' => -63.1667000]),
                'created_at' => $now
            ],
            [
                'nombre' => 'Casa Luis',
                'linea1' => 'Av. Cristo Redentor Km 6.5',
                'linea2' => 'Zona Norte',
                'ciudad' => 'Santa Cruz de la Sierra',
                'provincia' => 'Santa Cruz',
                'pais' => 'Bolivia',
                'geo' => json_encode(['latitud' => -17.7333000, 'longitud' => -63.1667000]),
                'created_at' => $now
            ],
        ]);

        // === VENTANA_ENTREGA ===
        // Three practical delivery windows: morning, afternoon, evening
        $today = $now->copy()->startOfDay();

        DB::table('ventana_entrega')->insert([
            [
                'desde' => $today->copy()->setTime(8, 0, 0),  // 08:00
                'hasta' => $today->copy()->setTime(12, 0, 0), // 12:00
                'created_at' => $now
            ],
            [
                'desde' => $today->copy()->setTime(13, 0, 0), // 13:00
                'hasta' => $today->copy()->setTime(17, 0, 0), // 17:00
                'created_at' => $now
            ],
            [
                'desde' => $today->copy()->setTime(18, 0, 0), // 18:00
                'hasta' => $today->copy()->setTime(21, 0, 0), // 21:00
                'created_at' => $now
            ],
        ]);
    }
}