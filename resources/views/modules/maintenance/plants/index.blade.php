@extends('core.layouts.layout-basic')

@section('scripts')
    <script>

            var DataTables = function () {


                var handleTables = function(){
                    $('#default-datatable').DataTable();
            
                    $('#responsive-datatable').DataTable({
                        responsive: true
                    });
                };
            
            
                return {
                    //main function to initiate the module
                    init: function () {
                        handleTables();
                    }
                };
            
            }();
            
            jQuery(document).ready(function() {
                DataTables.init();
            });

    </script>
@stop

@section('content')
    <div class="main-content">
        <div class="page-header">
            <h3 class="page-title"></h3>
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
                <div class="row">
                    <div class="col-sm-12">
                        <table id="default-datatable" class="table table-striped table-bordered" cellspacing="0"
                        width="100%">
                            <thead class="no-border">
                                <tr>
                                    @foreach ($tableGrid as $grid)
                                        <th>{{ $grid['label'] }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($tableRows as $row)
                                <tr>
                                    @foreach ($tableGrid as $field)
                                    <td>
                                        {!! ModuleHelper::formatRows($row->{$field['field']}, $field, $row) !!}
                                    </td>
                                    @endforeach
                                </tr>                                    
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
