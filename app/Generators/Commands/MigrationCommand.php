<?php
namespace App\Generators\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Prettus\Repository\Generators\MigrationGenerator;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

/**
 * Class EntityCommand
 * @package Prettus\Repository\Generators\Commands
 * @author Anderson Andrade <contato@andersonandra.de>
 */
class MigrationCommand extends Command
{

    /**
     * The name of command.
     *
     * @var string
     */
    protected $name = 'make:crudbuilder-migration';

    /**
     * The description of command.
     *
     * @var string
     */
    protected $description = 'Create a new crud builder migration.';

    /**
     * @var Collection
     */
    protected $generators = null;

    /**
     * Execute the command.
     *
     * @see fire()
     * @return void
     */
    public function handle(){
        $this->laravel->call([$this, 'fire'], func_get_args());
    }

    /**
     * Execute the command.
     *
     * @return void
     */
    public function fire()
    {
        $migrationGenerator = new MigrationGenerator([
            'name'   => 'create_' . snake_case(str_plural($this->argument('name'))) . '_table',
            'fields' => $this->option('fillable'),
            'force'  => $this->option('force'),
        ]);

        $migrationGenerator->run();
    }


    /**
     * The array of command arguments.
     *
     * @return array
     */
    public function getArguments()
    {
        return [
            [
                'name',
                InputArgument::REQUIRED,
                'The name of migration.',
                null
            ],
        ];
    }


    /**
     * The array of command options.
     *
     * @return array
     */
    public function getOptions()
    {
        return [
            [
                'fillable',
                null,
                InputOption::VALUE_OPTIONAL,
                'The fillable attributes.',
                null
            ],
            [
                'force',
                'f',
                InputOption::VALUE_NONE,
                'Force the creation if file already exists.',
                null
            ]
        ];
    }
}
