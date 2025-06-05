<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(name: 'create:resource-api')]
class CreateResourceApi extends Command
{
    protected $description = 'Create model, controller, resource, request, service, interface, and migration';

    protected function configure(): void
    {
        $this->addArgument('name', \Symfony\Component\Console\Input\InputArgument::REQUIRED, 'Resource name');

        $this->addOption('m', null, \Symfony\Component\Console\Input\InputOption::VALUE_NONE, 'Create model');
        $this->addOption('mm', null, \Symfony\Component\Console\Input\InputOption::VALUE_NONE, 'Create migration');
        $this->addOption('c', null, \Symfony\Component\Console\Input\InputOption::VALUE_NONE, 'Create controller');
        $this->addOption('r', null, \Symfony\Component\Console\Input\InputOption::VALUE_NONE, 'Create resource');
        $this->addOption('s', null, \Symfony\Component\Console\Input\InputOption::VALUE_NONE, 'Create service and interface');
        $this->addOption('rq', null, \Symfony\Component\Console\Input\InputOption::VALUE_NONE, 'Create request');
    }

    public function handle()
    {
        $name = $this->argument('name');

        $targets = $this->resolveTargetFiles($name);

        // Cek terlebih dahulu apakah ada file yang sudah ada
        $existing = collect($targets)->filter(fn($path) => File::exists($path));
        if ($existing->isNotEmpty()) {
            $this->warn('⚠️ Proses dibatalkan. File berikut sudah ada:');
            $existing->each(fn($file) => $this->line(" - $file"));
            return;
        }

        // Jika aman, lanjutkan membuat file sesuai opsi
        if ($this->option('m')) $this->createModel($name);
        if ($this->option('mm')) $this->createMigration($name);
        if ($this->option('c')) $this->createController($name);
        if ($this->option('r')) $this->createResource($name);
        if ($this->option('rq')) $this->createRequest($name);
        if ($this->option('s')) {
            $this->createService($name);
            $this->createInterface($name);
        }

        // Jika tidak ada opsi (flag), buat semuanya
        if (! $this->option('m') && ! $this->option('mm') && ! $this->option('c') && ! $this->option('r') && ! $this->option('rq') && ! $this->option('s')) {
            $this->createModel($name);
            $this->createController($name);
            $this->createResource($name);
            $this->createRequest($name);
            $this->createService($name);
            $this->createInterface($name);
            $this->createMigration($name);
        }

        $this->createRouteFile($name);

        $this->info("✅ Resource for '{$name}' created successfully.");
    }

    private function resolveTargetFiles(string $name): array
    {
        $modelStudly = Str::studly($name);
        $modelLower  = Str::camel($name);

        $paths = [];

        if ($this->option('m') || $this->noOption()) {
            $paths[] = app_path("Models/{$modelStudly}.php");
        }

        if ($this->option('mm') || $this->noOption()) {
            // Tidak bisa prediksi nama migration file dengan pasti, skip dari pengecekan
        }

        if ($this->option('c') || $this->noOption()) {
            $paths[] = app_path("Http/Controllers/Api/{$modelStudly}Controller.php");
        }

        if ($this->option('r') || $this->noOption()) {
            $paths[] = app_path("Http/Resources/{$modelStudly}Resource.php");
        }

        if ($this->option('rq') || $this->noOption()) {
            $paths[] = app_path("Http/Requests/{$modelStudly}Request.php");
        }

        if ($this->option('s') || $this->noOption()) {
            $paths[] = app_path("Service/{$modelLower}/{$modelStudly}Service.php");
            $paths[] = app_path("Service/{$modelLower}/{$modelStudly}Interfaces.php");
        }

        return $paths;
    }

    private function noOption(): bool
    {
        return ! $this->option('m') &&
            ! $this->option('mm') &&
            ! $this->option('c') &&
            ! $this->option('r') &&
            ! $this->option('rq') &&
            ! $this->option('s');
    }



    private function createModel($name)
    {
        $modelName = Str::studly($name);
        $modelPath = app_path("Models/{$modelName}.php");

        if (File::exists($modelPath)) {
            $this->warn("⚠️ Model {$modelName} already exists.");
            return;
        }

        $stub = File::get(app_path('Console/Stubs/model.stub'));
        $content = str_replace('{{model}}', $modelName, $stub);

        File::put($modelPath, $content);
        $this->info("✅ Model {$modelName} created.");
    }

    private function createController($name)
    {
        $controllerName = Str::studly($name) . 'Controller';
        $controllerPath = app_path("Http/Controllers/Api/{$controllerName}.php");

        if (File::exists($controllerPath)) {
            $this->warn("⚠️ Controller {$controllerName} already exists.");
            return;
        }

        $stub = File::get(app_path('Console/Stubs/controller.stub'));
        $content = str_replace(
            ['{{controller}}', '{{model}}', '{{service}}', '{{request}}', '{{resource}}'],
            [$controllerName, Str::studly($name), Str::studly($name) . 'Service', Str::studly($name) . 'Request', Str::studly($name) . 'Resource'],
            $stub
        );

        File::put($controllerPath, $content);
        $this->info("✅ Controller {$controllerName} created.");
    }

    private function createResource($name)
    {
        $resourceName = Str::studly($name) . 'Resource';
        $resourcePath = app_path("Http/Resources/{$resourceName}.php");

        if (File::exists($resourcePath)) {
            $this->warn("⚠️ Resource {$resourceName} already exists.");
            return;
        }

        $stub = File::get(app_path('Console/Stubs/resource.stub'));
        $content = str_replace(
            ['{{resource}}', '{{model}}'],
            [$resourceName, Str::studly($name)],
            $stub
        );

        File::put($resourcePath, $content);
        $this->info("✅ Resource {$resourceName} created.");
    }

    private function createRequest($name)
    {
        $requestName = Str::studly($name) . 'Request';
        $requestPath = app_path("Http/Requests/{$requestName}.php");

        if (File::exists($requestPath)) {
            $this->warn("⚠️ Request {$requestName} already exists.");
            return;
        }

        $stub = File::get(app_path('Console/Stubs/request.stub'));
        $content = str_replace(
            ['{{request}}', '{{model}}'],
            [$requestName, Str::studly($name)],
            $stub
        );

        File::put($requestPath, $content);
        $this->info("✅ Request {$requestName} created.");
    }

    private function createService($name)
    {
        $modelStudly = Str::studly($name);
        $modelLower = Str::camel($name);
        $serviceName = $modelStudly . 'Service';
        $servicePath = app_path("Service/{$modelLower}/{$serviceName}.php");

        if (File::exists($servicePath)) {
            $this->warn("⚠️ Service {$serviceName} already exists.");
            return;
        }

        $stub = File::get(app_path('Console/Stubs/services.stub'));
        $content = str_replace(
            ['{{service}}', '{{model}}', '{{model_lower}}'],
            [$serviceName, $modelStudly, $modelLower],
            $stub
        );

        File::ensureDirectoryExists(dirname($servicePath));
        File::put($servicePath, $content);
        $this->info("✅ Service {$serviceName} created.");
    }

    private function createInterface($name)
    {
        $modelStudly = Str::studly($name);
        $modelLower = Str::camel($name);
        $interfaceName = $modelStudly . 'Interfaces';
        $interfacePath = app_path("Service/{$modelLower}/{$interfaceName}.php");

        if (File::exists($interfacePath)) {
            $this->warn("⚠️ Interface {$interfaceName} already exists.");
            return;
        }

        $stub = File::get(app_path('Console/Stubs/interface.stub'));
        $content = str_replace(
            ['{{interface}}', '{{model}}', '{{model_lower}}'],
            [$interfaceName, $modelStudly, $modelLower],
            $stub
        );

        File::ensureDirectoryExists(dirname($interfacePath));
        File::put($interfacePath, $content);
        $this->info("✅ Interface {$interfaceName} created.");
    }

    private function createMigration($name)
    {
        $migrationName = 'create_' . Str::snake(Str::pluralStudly($name)) . '_table';
        $this->call('make:migration', ['name' => $migrationName]);
        $this->info("✅ Migration for '{$name}' created.");
    }

    private function createRouteFile($name)
    {
        $modelStudly = Str::studly($name);
        $modelSlug = Str::kebab($name);
        $controller = $modelStudly . 'Controller';

        $routePath = base_path("routes/api/{$modelSlug}.php");

        if (File::exists($routePath)) {
            $this->warn("⚠️ Route file for '{$modelSlug}' already exists.");
            return;
        }

        $stub = <<<EOT
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\\{$controller};

Route::controller({$controller}::class)->group(function () {
    Route::get('/{$modelSlug}', 'index');
    Route::get('/{$modelSlug}/{id}', 'show');
    Route::post('/{$modelSlug}', 'store');
    Route::put('/{$modelSlug}/{id}', 'update');
    Route::delete('/{$modelSlug}/{id}', 'destroy');
    Route::get('/{$modelSlug}/{id}/replicate', 'replicate');
    Route::post('/{$modelSlug}/create/faker', 'createFaker');
    Route::post('/{$modelSlug}/{id}/trash', 'trashed')->name('trash');
    Route::post('/{$modelSlug}/{id}/restore', 'Restore')->name('restore');
});
EOT;

        File::ensureDirectoryExists(dirname($routePath));
        File::put($routePath, $stub);
        $this->info("✅ Route file for '{$modelSlug}' created at routes/api/{$modelSlug}.php");
    }
}
