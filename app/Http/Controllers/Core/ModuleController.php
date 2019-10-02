<?php

namespace App\Http\Controllers\Core;

use App;
use App\Http\Requests;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Contracts\Core\ModuleRepository;
use App\Contracts\Core\MenuItemRepository;
use App\Contracts\Core\PlantZoneRepository;
use App\Http\Requests\Core\ModuleCreateRequest;
use App\Http\Requests\Core\ModuleUpdateRequest;

class ModuleController extends Controller
{
    /**
    * @var ModuleRepository
    */
    protected $repository;

    private $menuRepo;

    private $plantZoneRepo;

    /**
     * ModulesController constructor.
     *
     * @param ModuleRepository $repository
     */
    public function __construct(ModuleRepository $repository, MenuItemRepository $menuRepo, PlantZoneRepository $plantZoneRepo)
    {
        $this->repository = $repository;
        $this->menuRepo = $menuRepo;
        $this->plantZoneRepo = $plantZoneRepo;
    }

    public function index()
    {
        //$this->menuRepo->all();
        $menuItems = $this->menuRepo->with('posts')->where('customer_id', 1)->where('account_num', 1)->all();       
        //$modules = $this->repository->all();

        $modules = $this->repository->onlyTrashed()->where('module_id', 131)->where('name', 'class_code')->get();
        
        //$customers = \DB::table('customers')->get();

        $this->menuRepo->disableCache()->create(
            ['customer_id' => '12', 'account_num' => '12']
        );
        

        //$plants = Plant::limit(7)->get();
        //$configs = $this->repository->jsonDecode($module->config);
        //$tableGrid = $configs['grid'];
        //$tableRows = $plants;
        //return view('modules.maintenance.plants.index', compact('tableGrid', 'tableRows'));
        //$this->repository->build(131);
        return view('core.module.index', compact('modules'));


    }

    public function create()
    {
        $routeGenerator = app()->make('App\Generators\RoutesGenerator');
        $routeGenerator->prepend("Route::post('/daily-sales/storessss', 'Core\ModuleController@posssst');");

        
        $tables = $this->repository->getTableList(config('database.default'));
        $engines = DB::select('SHOW ENGINES');
        $charsets = DB::select('SHOW CHARACTER SET');
        return view('core.module.create', compact('tables', 'engines', 'charsets'));
    }

    public function getTableList($connection)
    {
        $tables = $this->repository->getTableList($connection);  
        return $tables; 
    }

    public function fieldTemplate()
    {
        return view('core.module.field-template');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  App\Http\Core\Requests\ModuleCreateRequest $request
     *
     * @return \Illuminate\Http\Response
     *
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function store(ModuleCreateRequest $request)
    {
        $this->repository->store($request);       
    }

    public function build($module_id)
    {
       
    } 

    public function edit($module_id)
    {
        $module = $this->repository->all();
        dd($module);
    }

    private function validateFields($fields)
    {
        $fieldsGroupBy = collect($fields)->groupBy(function ($item) {
            return strtolower($item['name']);
        });

        $duplicateFields = $fieldsGroupBy->filter(function (Collection $groups) {
            return $groups->count() > 1;
        });
        if (count($duplicateFields)) {
            throw new \Exception('Duplicate fields are not allowed');
        }

        return true;
    }
}
