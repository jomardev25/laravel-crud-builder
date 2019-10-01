<?php

namespace App\Http\Controllers\Core;

use App\Http\Requests;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Contracts\Core\ModuleRepository;
use App\Http\Requests\Core\ModuleCreateRequest;
use App\Http\Requests\Core\ModuleUpdateRequest;
use App\Models\Modules\Maintenance\Plant;

class ModuleController extends Controller
{
    /**
    * @var ModuleRepository
    */
    protected $repository;

    /**
     * ModulesController constructor.
     *
     * @param ModuleRepository $repository
     */
    public function __construct(ModuleRepository $repository)
    {
        $this->repository = $repository;
    }

    public function index()
    {
        
        $modules = $this->repository->all();
        //$plants = Plant::limit(7)->get();
        //$configs = $this->repository->jsonDecode($module->config);
        //$tableGrid = $configs['grid'];
        //$tableRows = $plants;
        //return view('modules.maintenance.plants.index', compact('tableGrid', 'tableRows'));
        $this->repository->build(131);
        return view('core.module.index', compact('modules'));
    }

    public function create()
    {
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
