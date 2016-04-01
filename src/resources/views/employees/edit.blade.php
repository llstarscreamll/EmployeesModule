@extends('CoreModule::app')

@section('title')
    Actualizar Empleado
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
                <h2 class="box-title">
                    Actualizar Empleado
                </h2>
                @include ('CoreModule::layout.notifications')
            </div>

            <div class="panel-body">
				
				{!! Form::model($employee, ['route' => ['employee.update', $employee->id], 'method' => 'PUT']) !!}
				
				@include ('EmployeesModule::employees.partials.create-edit-form')
                
                <div class="form-group col-md-10 col-md-offset-1">
				    <button type="submit" class="btn btn-warning">
						<span class="glyphicon glyphicon-pencil"></span>
						Actulizar
					</button>
					<span id="helpBlock" class="help-block">Los campos marcados con <strong>*</strong> son obligatorios.</span>
			    </div>
				
				{!! Form::close() !!}
				
            </div>
        </div>
    </div>
    
@endsection

@section('script')

@stop()