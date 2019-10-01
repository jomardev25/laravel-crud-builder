@extends('core.layouts.layout-basic')


@section('content')
    <div class="main-content">

        <div class="page-header">
            <h3 class="page-title">Create New Module</h3>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item"><a href="#">Modules</a></li>
                <li class="breadcrumb-item active">Create New Module</li>
            </ol>
        </div>
        <div class="card">
            <div class="card-header">
                <h6>Module Builder</h6>
            </div>
            <div class="card-body">
                <div class="content">
                    <form id="form-create-module" class="form-wizard-3" method="post" action="{{ route('core.modules.store') }}">
                        {{ csrf_field() }}
                        <h3><i class="icon-fa icon-fa-home"></i>MODULE INFORMATION</h3>
                        <section>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="name">Module Name<span class="required">*</span></label> 
                                        <input type="text" id="name" name="name" class="form-control">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="controller">Title</label> 
                                        <input type="text" id="title" name="title" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="controller">Custom Controller Name</label> 
                                        <input type="text" id="controller" name="controller" class="form-control">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="name">Custome Model Name</label> 
                                        <input type="text" id="model" name="model" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="name">Breadcrumbs</label> 
                                        <input type="text" id="breadcrumbs" name="breadcrumbs" class="form-control">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="name">Description</label> 
                                        <input type="text" id="description" name="description" class="form-control">
                                    </div>
                                </div>
                            </div>
                        </section>

                        <h3><i class="icon-fa icon-fa-key"></i>Options</h3>
                        <section>
                            <h5 class="section-semi-title">
                                Options
                            </h5>
                            <div class="row mb-3">
                                <div class="col-sm-12">    
                                    <div class="checkbox has-success">
                                        <label>
                                            <input type="checkbox" id="soft_delete" value="1">
                                            Enable Softdeletes
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <h5 class="section-semi-title">
                                Template
                            </h5>
                            <div class="row mb-3">
                                <div class="col-sm-3">
                                    <div class="form-check">
                                        <input type="radio" name="template" id="full_crud" value="1" class="form-check-input" checked> 
                                        <label for="full_crud" class="form-check-label">
                                            Full CRUD
                                        </label>
                                    </div>
                                    <small class="text-muted">
                                        Add, Edit, View in new page, Sorting and Pagination
                                    </small>
                                </div>
                                <div class="col-sm-3">    
                                    <div class="form-check">
                                        <label class="mb-0">
                                            <input type="radio" name="template" id="blank_module" value="2" class="form-check-input">
                                            Blank Module
                                        </label>
                                    </div>
                                    <small class="text-muted">
                                        Create template controller , model and views files for your own custom module
                                    </small>
                                </div>
                                <div class="col-sm-3">    
                                    <div class="form-check">
                                        <label class="mb-0">
                                            <input type="radio" name="template" id="report_module" value="3" class="form-check-input">
                                            Report Module
                                        </label>
                                    </div>
                                    <small class="text-muted">
                                        Used for table view ( MySQL View Table Schema)
                                    </small>
                                </div>
                            </div>
                        </section>

                        <h3><i class="icon-fa icon-fa-map-marker"></i>Table Schema</h3>
                        <section>
                            <div class="row mb-3">
                                <div class="col-sm-3">
                                    <div class="form-check"> 
                                        <input type="radio" value="0" name="table_schema" id="creation_migration" class="form-check-input" checked>

                                        <label for="creation_migration" class="form-check-label">
                                            Create Migration
                                        </label>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-check">
                                        <input type="radio" name="table_schema" id="crud_from_schema" value="1" class="form-check-input" > 
                                        <label for="crud_from_schema" class="form-check-label">
                                            CRUD From Schema
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="row" id="crud_existing_table" style="display: none;">
                                <div class="col-sm-12">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="db_connection_existing_table">Database Connection<span class="required">*</span></label>
                                                <select
                                                    id="db_connection_existing_table" 
                                                    name="db_connection_existing_table" 
                                                    class="form-control ls-select2">
                                                    @foreach(config()->get('database')['connections'] as $key => $connection)
                                                        <option 
                                                            value="{{ $key }}"
                                                            {{ 
                                                                config('database.default') == $key ? 'selected' : ''
                                                            }}
                                                        >
                                                            {{ $key }}
                                                        </option>
                                                    @endforeach            
                                                </select>
                                            </div>                                    
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="db_connection_existing_table">Database Connection<span class="required">*</span></label>
                                                <select 
                                                    id="db_connection_existing_table" 
                                                    name="db_connection_existing_table" 
                                                    class="form-control ls-select2"
                                                >
                                                    @foreach($tables as $table)
                                                        <option 
                                                            value="{{ $table }}"
                                                        >
                                                            {{ $table }}
                                                        </option>
                                                    @endforeach            
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row" id="crud_migration">
                                <div class="col-sm-12">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="db_connection">Database Connection<span class="required">*</span></label>
                                                <select 
                                                    id="db_connection" 
                                                    name="db_connection" 
                                                    class="form-control ls-select2"
                                                >
                                                    @foreach(config()->get('database')['connections'] as $key => $connection)
                                                        <option 
                                                            value="{{ $key }}"
                                                            {{ 
                                                                config('database.default') == $key ? 'selected' : ''
                                                            }}
                                                        >
                                                            {{ $key }}
                                                        </option>
                                                    @endforeach          
                                                </select>
                                            </div>                                    
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="table">Custom Table Name</label> 
                                                <input type="text" id="table" name="table" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label for="engine">Engine</label> 
                                                <select class="form-control ls-select2 required" id="engine" name="engine">
                                                    @foreach ($engines as $item)
                                                        <option value="{{ $item->Engine }}">{{ $item->Engine }}</option>
                                                    @endforeach
                                                </select>
                                            </div>                                    
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label for="charset">Character Set</label> 
                                                <select class="form-control ls-select2 required" id="charset" name="charset">
                                                    @foreach ($charsets as $item)
                                                        <option 
                                                            value="{{ $item->Charset }}" 
                                                            {{ $item->Charset === 'utf8' ? 'selected' : '' }}
                                                        >
                                                            {{ $item->Description }}</option>
                                                    @endforeach
                                                </select>
                                            </div>                                    
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label for="collation">Collation</label> 
                                                <select class="form-control ls-select2 required" id="collation" name="collation">
                                                    @foreach ($charsets as $item)
                                                        <option 
                                                            value=" {{ $item->{'Default collation'} }}"
                                                            {{ $item->Charset === 'utf8' ? 'selected' : '' }}
                                                        >
                                                            {{ $item->{'Default collation'} }}
                                                            </option>
                                                    @endforeach
                                                </select>
                                            </div>                                    
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label for="table_comment">Table Comment</label>
                                                <textarea class="form-control" id="table_comment" name="table_comment"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12" >
                                            <table class="table table-striped" id="table-migration">
                                                <thead>
                                                <tr>
                                                    <th>Name</th>
                                                    <th>Type</th>
                                                    <th>Length/Values</th>
                                                    <th>Decimals</th>
                                                    <th>Default</th>
                                                    <th class="text-center">Not Null</th>
                                                    <th>Index</th>
                                                    <th class="text-center">Action</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <tr>
                                                    <td>
                                                        <input type="text" class="form-control required" name="field_name[]" id="field_name"/>
                                                    </td>
                                                    <td>
                                                        <select class="form-control ls-select2 required" id="db_type" name="db_type[]">
                                                            <option value="bigIncrements" selected>Big Increments</option>
                                                            <option value="bigInteger">Big Integer</option>
                                                            <option value="binary">Binary</option>
                                                            <option value="boolean">Boolean</option>
                                                            <option value="char">Char</option>
                                                            <option value="date">Date</option>
                                                            <option value="dateTime">DateTime</option>
                                                            <option value="dateTimeTz">DateTimeTz</option>
                                                            <option value="decimal">Decimal</option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control" name="length[]"/>
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control" name="decimals[]"/>
                                                    <td>
                                                        <select class="form-control ls-select2 required default" name="default[]">
                                                            <option value="None" selected>None</option>
                                                            <option value="User_Defined">As defined</option>
                                                            <option value="Null">Null</option>
                                                            <option value="Curren_Timestamp">Curren_Timestamp</option>
                                                        </select>
                                                        <input type="text" name="user_defined[]" class="form-control mt-2 d-none user_defined" value="None"/>
                                                    </td>
                                                    <td class="text-right">
                                                        <input type="checkbox" value="1" class="form-check-input" name="not_null[]"/>
                                                    </td>
                                                    <td>
                                                        <select class="form-control ls-select2" name="index[]">
                                                            <option value="none" selected>None</option>
                                                            <option value="primary">Primary</option>
                                                            <option value="unique">Unique</option>
                                                            <option value="index">Index</option>
                                                            <option value="spatial">Spatial</option>
                                                        </select>
                                                    </td>
                                                    <td class="text-right">
                                                        <i class="icon-fa icon-fa-trash"></i>
                                                    </td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <button id="btn-add" type="button" class="btn btn-success mr-3">
                                                <i class="icon-fa icon-fa-plus"></i>Add Field
                                            </button>
                                            <button type="submit" class="btn btn-success mr-3">
                                                <i class="icon-fa icon-fa-plus"></i>Add Timestamps
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop

@section('styles')
    <style>
        .wizard > .content{
            min-height: 50em;
        }
    </style>
@stop


@section('scripts')

    {{--  {!! JsValidator::formRequest('App\Http\Requests\Core\ModuleCreateRequest', '#form-create-module') !!}  --}}
    <script>
        $(function(){
            var htmlStr = '<tr class="item" style="display: table-row;"></tr>';
            var commonComponent = $(htmlStr).filter("tr").load("{!! route('core.modules.fieldtemplate') !!}");
            $('#btn-add').on("click", function () { 
                var item = $(commonComponent).clone();
                $(item).find("select").select2({width: '100%'});
                $('#table-migration tbody').append(item);
            });
        })   
    </script>
@stop