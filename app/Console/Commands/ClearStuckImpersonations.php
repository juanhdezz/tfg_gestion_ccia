<?php


namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ClearStuckImpersonations extends Command
{
    protected $signature = 'impersonate:clear-stuck {--force : Forzar limpieza sin confirmación}';
    
    protected $description = 'Limpiar impersonaciones que no se cerraron correctamente';

    public function handle()
    {
        try {
            if (!$this->option('force')) {
                if (!$this->confirm('¿Estás seguro de que quieres limpiar todas las impersonaciones activas?')) {
                    $this->info('❌ Operación cancelada');
                    return 0;
                }
            }
            
            // Limpiar sesiones que contengan datos de impersonación
            $affected = DB::table('sessions')
                ->where('payload', 'like', '%impersonate_user_id%')
                ->delete();
                
            $this->info("✅ Se han limpiado {$affected} sesiones de impersonación");
            
            return 0;
        } catch (\Exception $e) {
            $this->error("❌ Error al limpiar sesiones: " . $e->getMessage());
            return 1;
        }
    }
}