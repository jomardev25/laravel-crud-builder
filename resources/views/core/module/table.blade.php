@extends('admin.layouts.layout-basic')

@section('content')

@php 

$formats = array(
    'date'	=> 'Date',
    'image'	=> 'Image',
    'link'	=> 'Link',
    'checkbox'	=> 'Checkbox/Radio',
    'radio'	=> 'Radio',
    'file'	=> 'Files',
    'database'	=> 'Database'						
);
@endphp
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
                <h6>Checkbox & Radios</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.modules.table.update', ['module_id' => $module_id])  }}" method="POST">
                    {{ csrf_field() }} {!! method_field('patch') !!}
                    <div class="row">
                        <div class="col-sm-12">
                            <table class="table table-striped table-bordered" id="table">
                                <thead class="no-border">
                                  <tr>
                                    <th scope="col">No</th>
                                    <th scope="col">Table</th>
                                    <th scope="col">Field</th>
                                    <th scope="col" width="70"><i class="fa fa-key"></i> Limit To</th>
                                    <th scope="col"><i class="icon-fa icon-fa-link"></i></th>
                                    <th scope="col" data-hide="phone">Title / Caption </th>
                                    <th scope="col" data-hide="phone">Show</th>
                                    <th scope="col" data-hide="phone">View </th>
                                    <th scope="col" data-hide="phone">Sortable</th>
                                    <th scope="col" data-hide="phone">Download</th>
                                    <th scope="col" data-hide="phone"> Format As </th>
                                  </tr>
                                </thead>
                                <tbody>
                                    @php $id = 0; @endphp
                                    @foreach ($table as $row)
                                        @php $id++; @endphp
                                        <tr>
                                            <td class="text-center">{{ $id }}</td>
                                            <td>{{ $row['alias'] }}</td>
                                            <td>
                                                {{ $row['field'] }}
                                                <input type="hidden" name="field[{{ $id }}]" id="field" value="{{ $row['alias']}}" />			
                                            </td>
                                            <td>
                                                <input type="text" class="form-control" name="limited[{{ $id }}]" class="limited" value="{{ isset($row['limited']) ? $row['limited'] : '' }}" />
                                            </td>
                                            <td class="text-center">	
                                                <span class="" title="Lookup Display">
                                                    <i class="icon-fa icon-fa-external-link"></i>
                                                </span>
                                            </td>
                                            <td>           
                                                <input name="label[{{ $id }}]" type="text" class="form-control" 
                                                id="label" value="{{ $row['label'] }} " />
                                            </td>
                                            <td class="text-center">
                                                <label>
                                                    <input name="view[{{ $id }}]" type="checkbox" id="view" value="1" 
                                                        {{ $row['view'] == 1 ? 'checked="checked"' : ''}}
                                                    />
                                                </label>
                                            </td>
                                            <td class="text-center">
                                                <label>
                                                    <input name="detail[1{{ $id }}]" type="checkbox" id="detail" value="1" 
                                                        {{ $row['detail'] == 1 ? 'checked="checked"': ''}}
                                                    />
                                                </label>
                                            </td>
                                            <td class="text-center">
                                                <label>
                                                    <input name="sortable[{{ $id }}]" type="checkbox" id="sortable" value="1" 
                                                        {{ $row['sortable'] == 1 ? 'checked="checked"' : ''}}
                                                    />
                                                </label>
                                            </td>
                                            <td class="text-center">
                                                <label>
                                                <input name="download[{{ $id }}]" type="checkbox" id="download" value="1" 
                                                    {{ $row['download'] == 1 ? 'checked="checked"' : '' }}
                                                />
                                                </label>
                                            </td>
                                            <td>
                                                <select class="select-alt" name="format_as[{{ $id }}]">
                                                    <option value=''> None </option>
                                                    @foreach($formats as $key => $val)
                                                    <option 
                                                        value="{{ $key }}" 
                                                        {{ 
                                                            isset($row['format_as']) && $row['format_as'] == $key ? 'selected' : '' 
                                                        }}
                                                    > {{ $val }} 

                                                    </option>
                                                    @endforeach
                                                </select>	
                                                    
                                                <input type="text" name="format_value[{{ $id }}]" 
                                                    value="{{ isset($row['format_value']) ? $row['format_value'] : ''}}" class="form-control"
                                                />
                                                
                                                <input type="hidden" name="frozen[{{ $id }}]" value="{{ $row['frozen'] }}" />
                                                <input type="hidden" name="search[{{ $id }}]" value="{{ $row['search'] }}" />
                                                <input type="hidden" name="hidden[{{ $id }}]" value="{{ isset($row['hidden']) ? $row['hidden'] : '' }}" />
                                                <input type="hidden" name="align[{{ $id }}]" value="{{ isset($row['align']) ? $row['align'] : '' }}" />
                                                <input type="hidden" name="width[{{ $id }}]" value="{{ $row['width'] }}" />
                                                <input type="hidden" name="alias[{{ $id }}]" value="{{ $row['alias'] }}" />
                                                <input type="hidden" name="field[{{ $id }}]" value="{{ $row['field'] }}" />
                                                <input type="hidden" name="sortlist[{{ $id }}]" class="reorder" value="{{ $row['sortlist'] }}" />
                                    
                                                <input type="hidden" name="conn_valid[{{ $id }}]"   
                                                value="{{ isset($row['conn']['valid']) ? $row['conn']['valid'] : ''}} "/>
                                                <input type="hidden" name="conn_db[{{ $id }}]"   
                                                value="{{ isset($row['conn']['db']) ? $row['conn']['db'] : ''}}" /> 	
                                                <input type="hidden" name="conn_key[{{ $id }}]"  
                                                value="{{ isset($row['conn']['key']) ? $row['conn']['key'] : '' }}" />
                                                <input type="hidden" name="conn_display[{{ $id }}]" 
                                                value="{{ isset($row['conn']['display']) ? $row['conn']['display'] : ''}}"/>	                                                 
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-action">
                                <button type="button" class="btn btn-danger mr-3">
                                    <i class="icon-fa icon-fa-arrow-left"></i>Cancel
                                </button>
                                <button type="submit" class="btn btn-primary"><i class="icon-fa icon-fa-save"></i>Save</button>
                            </div>
                        </div>
                    </div> 
                </form>
            </div>
        </div>
    </div>
@stop