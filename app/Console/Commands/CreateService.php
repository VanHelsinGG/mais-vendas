<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class CreateService extends Command
{
    protected $signature = 'make:service {serviceName}';
    protected $description = 'Create a service';
    protected $servicesPath = '/app/services';

    public function __construct()
    {
        parent::__construct();
        $this->servicesPath = base_path() . $this->servicesPath;
    }

    public function handle()
    {
        $serviceName = $this->argument('serviceName');

        if (!is_dir($this->servicesPath)) {
            $relativePath = str_replace(base_path(), '', $this->servicesPath);
            if ($this->confirm("INFO: Directory '$relativePath' does not exist! Do you want to create it?")) {
                $this->makeDir($this->servicesPath);
                $this->info('INFO: Directory created successfully!');
            } else {
                return $this->info('INFO: Operation canceled. The directory was not created.');
            }
        }

        if (file_exists($this->servicesPath . "/$serviceName.php")) {
            return $this->error("ERROR: Service '$serviceName' already exists.");
        }

        $payload = "<?php\n\nnamespace App\Services;\n\nclass $serviceName\n{\n//\n}";

        file_put_contents($this->servicesPath . "/$serviceName.php", $payload);
        $this->info("INFO: Service '$serviceName' created successfully.");

        $this->registerService($serviceName);
    }

    private function makeDir($path)
    {
        return is_dir($path) || mkdir($path);
    }

    private function registerService($serviceName)
    {
        $providerPath = app_path('Providers/AppServiceProvider.php');
        $providerContent = File::get($providerPath);

        $newInclude = 'use App\Services\\'. $serviceName . ";" . PHP_EOL . '/* New Providers include here */';

        $providerContent = str_replace(
            '/* New Providers include here */',
            $newInclude,
            $providerContent
        );

        $newService = '       $this->app->bind('. $serviceName .'::class, function () {
            return new '. $serviceName .'();
        });' . PHP_EOL . PHP_EOL. '        /* New Providers here  */';

        $providerContent = str_replace(
            '       /* New Providers here  */',
            $newService,
            $providerContent
        );

        File::put($providerPath, $providerContent);

        $this->info("INFO: Service '$serviceName' registered successfully!");
    }
}
