<?php

/*
 * Compiling seluruh file blade tanpa mengeksekusinya, lalu memeriksa
 * hasil kompilasi dengan php -l Equivalent (token_get_all + eval parse check).
 *
 * Jalankan: php artisan views:lint
 */

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Str;

class LintViewsCommand extends Command
{
    protected $signature = 'views:lint {--path=* : Batasi pemeriksaan ke folder tertentu}';

    protected $description = 'Kompilasi setiap file blade dan laporkan syntax error PHP.';

    public function handle(): int
    {
        $paths = $this->option('path') ?: [resource_path('views')];

        $files = [];

        foreach ($paths as $path) {
            $path = Str::startsWith($path, '/') || preg_match('/^[A-Za-z]:/', $path)
                ? $path
                : base_path($path);

            if (is_file($path)) {
                $files[] = $path;

                continue;
            }

            foreach (new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($path)) as $file) {
                if ($file->isFile() && str_ends_with($file->getFilename(), '.blade.php')) {
                    $files[] = $file->getPathname();
                }
            }
        }

        $errors = 0;

        foreach ($files as $file) {
            $source = file_get_contents($file);

            try {
                $compiled = Blade::compileString($source);

                // Tokenisasi akan melempar ParseError bila ada syntax PHP salah.
                token_get_all('<?php '.$compiled);
            } catch (\Throwable $e) {
                $errors++;
                $this->components->error(sprintf(
                    '%s: %s',
                    str_replace(base_path().'/', '', $file),
                    $e->getMessage()
                ));
            }
        }

        if ($errors === 0) {
            $this->components->info(sprintf('%d file blade dikompilasi tanpa error.', count($files)));

            return self::SUCCESS;
        }

        $this->components->error(sprintf('%d file blade bermasalah.', $errors));

        return self::FAILURE;
    }
}
