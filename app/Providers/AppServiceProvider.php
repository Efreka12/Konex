<?php

namespace App\Providers;

use App\Models\Usuario;
use Carbon\Carbon;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use PDO;
use Pdo\Mysql;
use Throwable;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        if (! $this->onWasmer()) {
            return;
        }

        $storage = '/tmp/konex-storage';
        foreach ([
            'framework/views',
            'framework/cache/data',
            'framework/sessions',
            'logs',
            'app/private',
            'app/public',
        ] as $dir) {
            $path = $storage.'/'.$dir;
            if (! is_dir($path)) {
                mkdir($path, 0777, true);
            }
        }

        $this->app->useStoragePath($storage);

        config([
            'view.compiled' => $storage.'/framework/views',
            'cache.stores.file.path' => $storage.'/framework/cache/data',
            'session.files' => $storage.'/framework/sessions',
            'logging.channels.single.path' => $storage.'/logs/laravel.log',
            'filesystems.disks.local.root' => $storage.'/app/private',
            'filesystems.disks.public.root' => $storage.'/app/public',
        ]);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Carbon::setLocale('es');

        if (! $this->onWasmer() || $this->app->runningInConsole()) {
            return;
        }

        $this->alignWasmerDatabase();

        try {
            if (! Schema::hasTable('sessions') || ! Schema::hasTable('usuarios')) {
                Artisan::call('migrate', ['--force' => true]);
            }

            if (Schema::hasTable('usuarios') && Usuario::query()->doesntExist()) {
                Artisan::call('db:seed', [
                    '--class' => 'Database\\Seeders\\KonexSeeder',
                    '--force' => true,
                ]);
            }
        } catch (Throwable $e) {
            report($e);
        }
    }

    private function onWasmer(): bool
    {
        return $this->app->basePath() === '/app'
            || str_contains((string) env('APP_URL'), 'wasmer.app')
            || str_contains((string) env('DB_HOST', ''), 'wasmer');
    }

    private function alignWasmerDatabase(): void
    {
        $configured = (string) env('DB_DATABASE', '');
        $wasmerName = (string) env('DB_NAME', '');

        if ($wasmerName !== '' && ($configured === '' || str_contains($configured, 'DB_NAME') || str_contains($configured, '$'))) {
            config(['database.connections.mysql.database' => $wasmerName]);
        }

        $options = config('database.connections.mysql.options', []);
        if (defined('Pdo\\Mysql::ATTR_SSL_VERIFY_SERVER_CERT')) {
            $options[Mysql::ATTR_SSL_VERIFY_SERVER_CERT] = false;
        } elseif (defined('PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT')) {
            $options[PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT] = false;
        }
        config(['database.connections.mysql.options' => $options]);
        DB::purge('mysql');
    }
}
