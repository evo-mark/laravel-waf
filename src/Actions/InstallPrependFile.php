<?php

declare(strict_types=1);

namespace EvoMark\LaravelWaf\Actions;

use Illuminate\Filesystem\Filesystem;
use Spatie\LaravelPackageTools\Commands\InstallCommand;

/**
 * Creates the WAF bootstrap file and registers it as PHP's auto_prepend_file.
 *
 * Under Apache + php-fpm, `php_value` lines in .htaccess are ignored, so the
 * directive is written to public/.user.ini, which php-fpm reads per directory.
 */
class InstallPrependFile
{
    public const BOOTSTRAP_PATH = 'bootstrap/waf.php';

    public const USER_INI = '.user.ini';

    protected const STUB = __DIR__ . '/../../stubs/waf-prepend.php.stub';

    public function __construct(protected Filesystem $files)
    {
    }

    public function __invoke(InstallCommand $command): void
    {
        $bootstrap = base_path(self::BOOTSTRAP_PATH);

        $this->writeBootstrap($command, $bootstrap);
        $this->registerPrepend($command, public_path(self::USER_INI), $bootstrap);
    }

    protected function writeBootstrap(InstallCommand $command, string $path): void
    {
        if ($this->files->exists($path) && ! $command->confirm("{$path} already exists. Overwrite it?", false)) {
            $command->line("Kept existing {$path}");

            return;
        }

        $this->files->ensureDirectoryExists(dirname($path));
        $this->files->copy(self::STUB, $path);

        $command->info("Created {$path}");
    }

    protected function registerPrepend(InstallCommand $command, string $iniPath, string $bootstrap): void
    {
        $directive = sprintf('auto_prepend_file = "%s"', $bootstrap);
        $contents = $this->files->exists($iniPath) ? $this->files->get($iniPath) : '';
        $pattern = '/^[ \t]*auto_prepend_file[ \t]*=.*$/m';

        if (preg_match($pattern, $contents, $match)) {
            $existing = trim($match[0]);

            if ($existing === $directive) {
                $command->line("auto_prepend_file is already registered in {$iniPath}");

                return;
            }

            $command->warn("{$iniPath} already sets: {$existing}");

            if (! $command->confirm('Replace it with the WAF bootstrap file?', false)) {
                $command->warn('Skipped registering auto_prepend_file. The WAF bootstrap file will not run.');

                return;
            }

            $contents = preg_replace($pattern, $directive, $contents, 1);
        } else {
            $contents = rtrim($contents) . ($contents === '' ? '' : PHP_EOL) . $directive . PHP_EOL;
        }

        $this->files->put($iniPath, $contents);

        $command->info("Registered auto_prepend_file in {$iniPath}");
        $command->line('php-fpm caches .user.ini files (user_ini.cache_ttl, 300s by default); reload php-fpm to apply immediately.');
    }
}
