<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;
use function PHPUnit\Framework\matches;

class ModuleMake extends Command
{

    private $files;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:module {name}
    {--all}
    {--migration}
    {--vue}
    {--view}
    {--controller}
    {--model}
    {--api}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * @param $files
     */
    public function __construct(Filesystem $filesystem)
    {
        parent::__construct();

        $this->files = $filesystem;
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle() : void
    {

        if ($this->option('all')) {
            $this->input->setOption('migration', true);
            $this->input->setOption('vue', true);
            $this->input->setOption('view', true);
            $this->input->setOption('controller', true);
            $this->input->setOption('model', true);
            $this->input->setOption('api', true);
        }
        if ($this->option('model')) {
            $this->createModel();
        }
        if ($this->option('controller')) {
            $this->createController();
        }
        if ($this->option('api')) {
            $this->createController(true);
        }
        if ($this->option('migration')) {
            $this->createMigration();
        }
        if ($this->option('vue')) {
            $this->createVueComponent();
        }
        if ($this->option('view')) {
            $this->createView();
        }
    }

    private function createModel() : void
    {
        $model = Str::singular(Str::studly(class_basename($this->argument('name'))));
        $this->call('make:model', [
            'name' => "App\\Modules\\".trim($this->argument('name'))."\\Models\\".$model
        ]);
    }

    private function createController(bool $isApi = false) : void
    {
        $this->name = str_replace('/', '\\' , $this->argument('name'));
        $controller = Str::studly(class_basename($this->argument('name')));
        $modelName = Str::singular(Str::studly(class_basename($this->argument('name'))));

        $path = $this->getControllerPath($this->argument('name'), $isApi);

        if ($this->alreadyExists($path)) {
            $this->error($isApi ? 'Api controller already exists!' : 'Controller already exists!');
        }
        else {
            $this->makeDirectory($path);

            $stub = $this->files->get(base_path('resources/stubs/controller.model.api.stub'));
                $controllerNamespace = "App\\Modules\\".trim($this->name)."\\Controllers" . ($isApi ? "\\Api" : "");

            $stub = str_replace(
                [
                    'DummyNamespace',
                    'DummyRootNamespace',
                    'DummyClass',
                    'DummyFullModelClass',
                    'DummyModelClass',
                    'DummyModelVariable'
                ],
                [
                    $controllerNamespace,
                    $this->laravel->getNamespace(),
                    $controller.'Controller',
                    "App\\Modules\\".trim($this->name)."\\Models\\{$modelName}",
                    $modelName,
                    lcfirst(($modelName))
                ],
                $stub
            );

            $this->files->put($path, $stub);
            $this->info($isApi ? 'Api controller created successfully.' : 'Controller created successfully.');
        }
        $this->updateModularConfig();

        $this->createRoutes($controller, $modelName, $isApi);
    }

    private function updateModularConfig() {
        $group = explode('/', $this->argument('name'))[0];
        $module = Str::studly(class_basename($this->argument('name')));

        $modular = $this->files->get(base_path('config/modular.php'));

        $matches = [];

        preg_match("/'modules' => \[.*?'{$group}' => \[(.*?)\]/s", $modular, $matches);

        if (count($matches) == 2) {
            if (!preg_match("/'{$module}'/", $matches[1])) {
                $parts = preg_split("/('modules' => \[.*?'{$group}' => \[)/s", $modular, 2, PREG_SPLIT_DELIM_CAPTURE);
                if(count($parts) == 3) {
                    $configStr = $parts[0].$parts[1]."\n            '$module',".$parts[2];
                    $this->files->put(base_path('config/modular.php'), $configStr);
                }
            }
        }

    }

    private function createMigration()
    {
        $table = Str::plural(Str::snake(class_basename($this->argument('name'))));

        try {
            $this->call('make:migration', [
                'name' => "create_{$table}_table",
                '--create'=>$table,
                '--path' => "app/Modules/".trim($this->argument('name'))."/Migrations"
            ]);
        }
        catch (\Exception $e) {
            $this->error($e->getMessage());
        }

    }

    private function createVueComponent() : void
    {
        $path = $this->getVueComponentPath($this->argument('name'));

        $component = Str::studly(class_basename($this->argument('name')));

        if ($this->alreadyExists($path)) {
            $this->error('Vue Component already exists!');
        }
        else {
            $this->makeDirectory($path);

            $stub = $this->files->get(base_path('resources/stubs/vue.component.stub'));

            $stub = str_replace(
                [
                    'DummyClass',
                ],
                [
                    $component,
                ],
                $stub
            );

            $this->files->put($path, $stub);
            $this->info('Vue Component created successfully.');
        }
    }

    private function createView() : void
    {
        $paths = $this->getViewPath($this->argument('name'));

        foreach ($paths as $path) {
            $view = Str::studly(class_basename($this->argument('name')));

            if ($this->alreadyExists($path)) {
                $this->error('View already exists!');
            }
            else {
                $this->makeDirectory($path);

                $stub = $this->files->get(base_path('resources/stubs/view.stub'));

                $stub = str_replace(
                    [
                        '',
                    ],
                    [

                    ],
                    $stub
                );

                $this->files->put($path, $stub);
                $this->info("View {$path} created successfully.");
            }
        }
    }

    private function getControllerPath($argument, $isApi = false) : string
    {
        $controller = Str::studly(class_basename($argument));
        return $isApi ? $this->laravel['path'].'/Modules/'.str_replace('\\', '/', $argument)."/Controllers/Api/{$controller}Controller.php" : $this->laravel['path'].'/Modules/'.str_replace('\\', '/', $argument)."/Controllers/{$controller}Controller.php";
    }

    private function makeDirectory(string $path) : string
    {
        if (!$this->files->isDirectory(dirname($path))) {
            $this->files->makeDirectory(dirname($path), 0777, true, true);
        }

        return $path;
    }

    private function createRoutes(string $controller, string $modelName, bool $isApi) :void
    {
        $routePath = $this->getRoutePath($this->argument('name'), $isApi);

        if ($this->alreadyExists($routePath)) {
            $this->error('Routes already exists!');
        }
        else {
            $this->makeDirectory($routePath);

            $stub = $this->files->get(base_path('resources/stubs/routes.' . ($isApi ? 'api' : 'web') . '.stub'));

            $className = ($isApi ? 'Api\\' : '').$controller.'Controller';

            $stub = str_replace(
                [
                    'DummyClass',
                    'DummyRoutePrefix',
                    'DummyModelVariable'
                ],
                [
                    $className,
                    Str::plural(Str::snake(lcfirst($modelName), '-')),
                    lcfirst($modelName)
                ],
                $stub
            );

            $this->files->put($routePath, $stub);
            $this->info($isApi ? 'Api routes created successfully.' : 'Routes created successfully.');
        }
    }

    private function getRoutePath(string $name, bool $isApi = false) :string
    {
        return $this->laravel['path'].'/Modules/'.str_replace('\\', '/', $name)."/Routes/" . ($isApi ? "api" : "web") . ".php";
    }

    protected function alreadyExists(string $path) : bool
    {
        return $this->files->exists($path);
    }

    private function getVueComponentPath(string $name) : string
    {
        return base_path('resources/js/components/'.str_replace('\\', '/', $name).".vue");
    }

    private function getViewPath(string $name) :object
    {
        $arrFiles = collect([
            'create',
            'edit',
            'index',
            'show'
        ]);

        $paths = $arrFiles->map(function ($item) use ($name){
            return base_path('resources/views/'.str_replace('\\', '/', $name).'/'.$item.".blade.php");
        });
        return $paths;
    }
}
