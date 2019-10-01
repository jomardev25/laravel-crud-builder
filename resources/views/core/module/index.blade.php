@extends('core.layouts.layout-basic')

@section('content')
    <div class="main-content">
        <div class="page-header">
            <h3 class="page-title">Modules</h3>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active">Modules</li>
            </ol>
        </div>

        <div class="card">
            <div class="card-header">
                <h6>Manage All Module</h6>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-sm-12 ">
                        <a class="btn btn-primary" href="{{ route('core.modules.create') }}">Add New Module</a>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <table class="table table-striped table-bordered" id="table">
                            <thead class="no-border">
                                <tr>
                                    <th scope="col" class="text-center">Action</th>
                                    <th scope="col">Module</th>
                                    <th scope="col">ShortCode</th>
                                    <th scope="col">Model Name</th>
                                    <th scope="col">Controller Name</th>
                                    <th scope="col">Table Name</th>
                                    <th scope="col">Primary Key</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (count($modules))
                                    @foreach ($modules as $module)
                                        <tr>
                                            <td class="text-center">
                                                <div class="btn-group" role="group">
                                                    <button id="btn-group-action" type="button" class="btn btn-outline-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                                            <i class="icon-im icon-im-cogs"></i>
                                                    </button>
                                                    <div class="dropdown-menu" aria-labelledby="btn-group-action">
                                                        <a class="dropdown-item" href="#">View Module</a>
                                                        <a class="dropdown-item" href="#">Duplicate/Clone</a>
                                                        <a class="dropdown-item" href="{{ route('core.modules.edit', $module->module_id) }}">Edit</a>
                                                        <a class="dropdown-item" href="#">Delete</a>
                                                        <div class="dropdown-divider"></div>
                                                        <a class="dropdown-item" href="#">Rebuild All Codes</a>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>{{ $module->module_title}}</td>
                                            <td>

                                                <b>Form Shortcode : </b>
                                                {{ "!!SximoHelpers|showForm|'".$module->module_name."'!!" }} <br />
                                                <b>Table Shortcode : </b>
                                                {{ "<php> use \App\Http\Controllers\ucwords($module->module_name)Controller" }}  <br />
                                                {{ ucwords($module->module_name)."Controller::display()</php>" }}

                                            </td>
                                            <td>{{ $module->model_name }}</td>
                                            <td>{{ $module->controller_name }}</td>
                                            <td>{{ $module->module_table }}</td>
                                            <td>{{ $module->module_table_key }}</td>
                                        </tr>  
                                    @endforeach
                                @endif
                                <tr>
                                        <td class="text-center">
                                            <div class="btn-group" role="group">
                                                <button id="btn-group-action" type="button" class="btn btn-outline-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                                        <i class="icon-im icon-im-cogs"></i>
                                                </button>
                                                <div class="dropdown-menu" aria-labelledby="btn-group-action">
                                                    <a class="dropdown-item" href="#">View Module</a>
                                                    <a class="dropdown-item" href="#">Duplicate/Clone</a>
                                                    <a class="dropdown-item" href="#">Edit</a>
                                                    <a class="dropdown-item" href="#">Delete</a>
                                                    <div class="dropdown-divider"></div>
                                                    <a class="dropdown-item" href="#">Rebuild All Codes</a>
                                                </div>
                                            </div>
                                        </td>
                                        <td>Module</td>
                                        <td>ShortCode</td>
                                        <td>Model Name</td>
                                        <td>Controller Name</td>
                                        <td>Table Name</td>
                                        <td>Primary Key</td>
                                    </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop