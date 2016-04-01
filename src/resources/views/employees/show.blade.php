@extends('CoreModule::app')

@section('title')
    Detalles de Empleado
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
                    Detalles de Empleado
                </h2>
                @include ('CoreModule::layout.notifications')
            </div>

            <div class="panel-body">

                @if($employee->trashed())
                    <div class="form-group col-md-6 col-md-offset-3 alert alert-warning">Este registro se encuentra en la papelera.</div>
                @endif

				<div class="form-group col-md-5 col-md-offset-1">
    		        {!! Form::label('', 'Nombre') !!}
                    {!! Form::text('', $employee->name, ['class' => 'form-control', 'disabled']) !!}
    		    </div>
    		    
    		    
    		    <div class="form-group col-md-5">
    		        {!! Form::label('', 'Apellido') !!}
                    {!! Form::text('', $employee->lastname, ['class' => 'form-control', 'disabled']) !!}
    		    </div>
    		    
    		    <div class="form-group col-md-5 col-md-offset-1">
    		        {!! Form::label('', 'No. Identificación') !!}
                    {!! Form::text('', $employee->identification_number, ['class' => 'form-control', 'disabled']) !!}
    		    </div>
    		    
    		    <div class="form-group col-md-5">
    		        {!! Form::label('', 'Código') !!}
                    {!! Form::text('', $employee->internal_code, ['class' => 'form-control', 'disabled']) !!}
    		    </div>
    		    
    		    <div class="form-group col-md-5 col-md-offset-1">
    		        {!! Form::label('', 'Centro de Costo') !!}
                    {!! Form::text('', $employee->subCostCenter->nameWithCostCenterName, ['class' => 'form-control', 'disabled']) !!}
    		    </div>
    		    
    		    <div class="form-group col-md-5">
    		        {!! Form::label('', 'Cargo') !!}
                    {!! Form::text('', $employee->position->name, ['class' => 'form-control', 'disabled']) !!}
    		    </div>
    		    
    		    <div class="form-group col-md-5 col-md-offset-1">
    		        {!! Form::label('', 'Ciudad') !!}
                    {!! Form::text('', $employee->city, ['class' => 'form-control', 'disabled']) !!}
    		    </div>
    		    
    		    <div class="form-group col-md-5">
    		        {!! Form::label('', 'Dirección') !!}
                    {!! Form::text('', $employee->address, ['class' => 'form-control', 'disabled']) !!}
    		    </div>
    		    
    		    <div class="form-group col-md-5 col-md-offset-1">
    		        {!! Form::label('', 'Teléfono') !!}
                    {!! Form::text('', $employee->phone, ['class' => 'form-control', 'disabled']) !!}
    		    </div>
    		    
    		    <div class="form-group col-md-5">
    		        {!! Form::label('', 'Correo Electrónico') !!}
                    {!! Form::text('', $employee->email, ['class' => 'form-control', 'disabled']) !!}
    		    </div>
                
                <div class="col-md-10 col-md-offset-1">
                    
                    {{-- Si el registro está eliminado (softdeleted) no se muestran los botones/link para editar o mover a la papelera --}}
                    @if(!$employee->trashed())
                        <a href="{{ route('employee.edit', $employee->id) }}" class="btn btn-warning">
                            <span class="glyphicon glyphicon-pencil"></span>
                            Editar
                        </a>
                    
                        {{-- This button triggers the confirmation modal window --}}
                        <button class="btn btn-danger" data-toggle="modal" data-target="#modal_confirm">
                            <span class="glyphicon glyphicon-trash"></span>
                            Mover a Papelera
                        </button>

                    @else
                        <a href="{{ route('employee.restore', $employee->id) }}" class="btn btn-success">
                            <span class="fa fa-mail-reply"></span>
                            Restablecer
                        </a>
                    @endif

				</div>
            </div>
        </div>
    </div>
    
    {{-- Modal Window --}}
    <div class="modal fade" id="modal_confirm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Está Seguro?</h4>
            </div>
            <div class="modal-body">
                Toda la información de <strong>{{$employee->fullname}}</strong> será movida a la papelera!!
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                {!! Form::open(['route' => ['employee.destroy', $employee->id], 'method' => 'DELETE', 'class' => 'display-inline']) !!}
                    <button type="submit" class="btn btn-danger">Confirmar</button>
                {!! Form::close() !!}
            </div>
          </div>
        </div>
    </div>
    
@endsection

@section('scripts')

@stop()