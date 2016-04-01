@extends('CoreModule::app')

@section('title')
    Importar Empleados
@stop

@section('content')
    
    <div class="content-header">
		<h1>
			<a href="{{route('employee.index')}}">Empleados</a>
		</h1>
	</div>
	
    <div class="content">
        
        <div class="box">
            
            <div class="box-header">
                <div class="col-md-10 col-md-offset-1">
                    <h2 class="box-title">
                        Importar Empleados
                    </h2>
                </div>
                
                @include ('CoreModule::layout.notifications')
                
            </div>

            <div class="panel-body">
				
				{!! Form::open(['route' => 'employee.post_import', 'method' => 'POST', 'files' => true]) !!}
				
				    @if(\Session::has('file_data'))
				    <div class="form-group col-md-10 col-md-offset-1">
				        
				        <div class="box box-success">
                            <div class="box-header with-border">
                                <h3 class="box-title">Importación Realizada Correctamente, aquí el resumen de la operación:</h3>
                                <div class="box-tools pull-right">
                                    <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                                </div><!-- /.box-tools -->
                            </div><!-- /.box-header -->
                            <div class="box-body">
                                <ul class="list-group">
                                    @if($data = \Session::get('file_data'))
                                    
                                    {{-- Estadisticas del Archivo --}}
                                    <li class="list-group-item list-group-item-success">
                                        <span class="badge">{{count($data['employees'])}}</span>
                                        Empleados encontrados en el archivo
                                    </li>
                                    
                                    <li class="list-group-item list-group-item-success">
                                        <span class="badge">{{count($data['positions'])}}</span>
                                        Cargos encontrados en el archivo
                                    </li>
                                    
                                    <li class="list-group-item list-group-item-success">
                                        <span class="badge">{{count($data['cost_centers'])}}</span>
                                        Centros de Costo encontrados en el archivo
                                    </li>
                                    
                                    <li class="list-group-item list-group-item-success">
                                        <span class="badge">{{count($data['sub_cost_centers'])}}</span>
                                        Subcentros de Costo encontrados en el archivo
                                    </li>
                                    
                                    {{-- Estadisticas de Empleados --}}
                                    
                                    <li class="list-group-item list-group-item-info">
                                        <span class="badge">{{count($data['employee_statistics']['create'])}}</span>
                                        Empleados Creados
                                    </li>
                                    
                                    <li class="list-group-item list-group-item-info">
                                        <span class="badge">{{count($data['employee_statistics']['update'])}}</span>
                                        Empleados Actualizados
                                    </li>
                                    
                                    <li class="list-group-item list-group-item-info">
                                        <span class="badge">{{count($data['employee_statistics']['ignore'])}}</span>
                                        Empleados Ignorados
                                    </li>
                                    
                                    {{-- Estadisticas de Cargos --}}
                                    
                                    <li class="list-group-item list-group-item-info">
                                        <span class="badge">{{count($data['position_statistics']['create'])}}</span>
                                        Cargos Creados
                                    </li>
                                    
                                    <li class="list-group-item list-group-item-info">
                                        <span class="badge">{{count($data['position_statistics']['update'])}}</span>
                                        Cargos Actualizados
                                    </li>
                                    
                                    <li class="list-group-item list-group-item-info">
                                        <span class="badge">{{count($data['position_statistics']['ignore'])}}</span>
                                        Cargos Ignorados
                                    </li>
                                    
                                    {{-- Estadisticas de Centros de Costo --}}
                                    
                                    <li class="list-group-item list-group-item-info">
                                        <span class="badge">{{count($data['cost_center_statistics']['create'])}}</span>
                                        Centros de Costo Creados
                                    </li>
                                    
                                    <li class="list-group-item list-group-item-info">
                                        <span class="badge">{{count($data['cost_center_statistics']['update'])}}</span>
                                        Centros de Costo Actualizados
                                    </li>
                                    
                                    <li class="list-group-item list-group-item-info">
                                        <span class="badge">{{count($data['cost_center_statistics']['ignore'])}}</span>
                                        Centros de Costo Ignorados
                                    </li>
                                    
                                    {{-- Estadisticas de Centros de Costo --}}
                                    
                                    <li class="list-group-item list-group-item-info">
                                        <span class="badge">{{count($data['sub_cost_center_statistics']['create'])}}</span>
                                        Subcentros de Costo Creados
                                    </li>
                                    
                                    <li class="list-group-item list-group-item-info">
                                        <span class="badge">{{count($data['sub_cost_center_statistics']['update'])}}</span>
                                        Subcentros de Costo Actualizados
                                    </li>
                                    
                                    <li class="list-group-item list-group-item-info">
                                        <span class="badge">{{count($data['sub_cost_center_statistics']['ignore'])}}</span>
                                        Subcentros de Costo Ignorados
                                    </li>
                                    
                                    <li class="list-group-item list-group-item-warning">
                                        <span class="badge">{{round($data['total_time_elapsed'], 2)}}</span>
                                        Tiempo Total de Importación (seg.)
                                    </li>
                                    @endif
                                </ul>
                                
                            </div><!-- /.box-body -->
                        </div>
				        
				    </div>
				    @endif
				
				    <div class="form-group col-md-10 col-md-offset-1">
				        
				        <div class="box box-warning collapsed-box">
                            <div class="box-header with-border">
                                <h3 class="box-title">Por favor pon atención a éstas indicaciones para que la importación de los datos sea exitosa</h3>
                                <div class="box-tools pull-right">
                                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>
                                </div><!-- /.box-tools -->
                            </div><!-- /.box-header -->
                            <div class="box-body">
                                <p class="lead">El documento debe cumplir con las siguientes condiciones:</p>
                                    
                                <ol>
                                    <li>El formato debe ser .xlsx o .xls.</li>
                                    <li>Sólo debe estar presente la hoja 'Empleados' en donde se van a importar los datos, las demás deben ser eliminadas.</li>
                                    <li>
                                        Los siguientes datos son obligatorios por cada registro:
                                        <ul>
                                            <li>Centro de Costo</li>
                                            <li>Cargo</li>
                                            <li>Nombres</li>
                                            <li>Apellidos</li>
                                            <li>Número de Identificación</li>
                                            <li>Código Interno</li>
                                        </ul>
                                    </li>
                                    <li>El número de cédula, el código interno y el correo electrónico deben ser únicos para cada registro, no debe haber duplicados.</li>
                                    <li>Los nombres y apellidos deben ir en columnas separadas.</li>
                                </ol>
                                
                                <p class="lead">Si el documento que has preparado cumple los requisitos, entonces puedes subir ahora la base de datos de tus empleados...</p>
                            </div><!-- /.box-body -->
                        </div>
                        
                    </div>
                    
                	<div class="form-group col-md-10 col-md-offset-1">
                        <label for="file">Selecciona archivo .xlsx</label>
                        <input type="file" name="file" id="file">
                        <p class="help-block">Selecciona el archivo que contiene los datos a importar.</p>
                        @if ($errors->has('file'))
                            <div class="text-danger">
                                {!! $errors->first('file') !!}
                            </div>
                        @endif
                    </div>

                    <div class="form-group col-md-10 col-md-offset-1">
    				    <button type="submit" class="btn btn-primary">
    						<span class="glyphicon glyphicon-import"></span>
    						Importar
    					</button>
    			    </div>
				
				{!! Form::close() !!}
				
            </div>
        </div>
    </div>
    
@endsection

@section('script')

@stop()