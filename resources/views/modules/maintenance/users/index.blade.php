@extends('core.layouts.layout-basic')

@section('content')
    <div class="main-content">
        <div class="page-header">
            <h3 class="page-title">Users</h3>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item"><a href="#">Maintenance</a></li>
                <li class="breadcrumb-item"><a href="#">User Management</a></li>
                <li class="breadcrumb-item active">Users</li>
            </ol>
        </div>

        <div class="card">
            <div class="card-header">
                <h6>Manage All Module</h6>
            </div>
            <div class="card-body">
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
                                <tr>
                                    <td class="text-center">Edit | Delete</td>
                                    <td>Module</td>
                                    <td>ShortCode</td>
                                    <td>Model Name</td>
                                    <td>Controller Name</td>
                                    <td>Table Name</td>
                                    <td>Primary Key</th>
                                </tr>
                                <tr>
                                    <td class="text-center">Edit | Delete</td>
                                    <td>Module</td>
                                    <td>ShortCode</td>
                                    <td>Model Name</td>
                                    <td>Controller Name</td>
                                    <td>Table Name</td>
                                    <td>Primary Key</th>
                                </tr>
                                <tr>
                                    <td class="text-center">Edit | Delete</td>
                                    <td>Module</td>
                                    <td>ShortCode</td>
                                    <td>Model Name</td>
                                    <td>Controller Name</td>
                                    <td>Table Name</td>
                                    <td>Primary Key</th>
                                </tr>
                            </tbody>                                
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('scripts')
 <script>
    $.iDataTable({
        tableName: 'table'
    })
 </script>   
@stop