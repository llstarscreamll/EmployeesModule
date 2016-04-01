@extends('CoreModule::app')

@section('title')
    Empleados
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
                
                <div class="row tools">
					
					{{-- Action Buttons --}}
					<small class="col-md-6 action-buttons hidden-print">
						@if(\Request::get('show_only') != 'trashed')
							{{-- funciona con ventana modal de confirmación --}}
							<div class="display-inline-block" data-toggle="tooltip" data-placement="top" title="Activar Empleado(s)">
								<button
									type="button"
									class="btn btn-default btn-sm"
									id="btn-enable-employee"
									data-action="{{route('employee.status', 'enabled')}}"
									data-method="PUT"
									data-message="Desea activar a el(los) empleado(s) marcado(s)? Se verán disponibles sus datos para realizar operaciones en los respectivos módulos del sistema."
									data-toggle="modal"
									data-target="#confirm-modal"
								>
									<span class="glyphicon glyphicon-eye-open"></span>
									<span class="sr-only">Activar Empleado(s)</span>
								</button>
							</div>
							
							{{-- funciona con ventana modal de confirmación --}}
							<div class="display-inline-block" data-toggle="tooltip" data-placement="top" title="Desactivar Empleado(s)">
								<button 
									type="button"
									class="btn btn-default btn-sm"
									id="btn-disable-employee"
									data-action="{{route('employee.status', 'disabled')}}"
									data-method="PUT"
									data-message="Desea desactivar a el(los) empleado(s) marcado(s)? No estará disponible en los módulos del sistema, pero sus datos históricos no se verán afectados."
									data-toggle="modal"
									data-target="#confirm-modal"
								>
									<span class="glyphicon glyphicon-eye-close"></span>
									<span class="sr-only">Desactivar Empleado(s)</span>
								</button>
							</div>
							
							{{-- funciona con ventana modal de confirmación --}}
							<div class="display-inline-block" data-toggle="tooltip" data-placement="top" title="Mover Empleado(s) Papelera">
								<button
									type="button"
									class="btn btn-default btn-sm"
									id="btn-trash"
									data-action="{{route('employee.destroy')}}"
									data-method="DELETE"
									data-message="El(los) empleado(s) seleccionados serán movidos a la papelera, pero sus datos históricos no se verán afectados."
									data-toggle="modal"
									data-target="#confirm-modal"
								>
									<span class="glyphicon glyphicon-trash"></span>
									<span class="sr-only">Mover Empleados a Papelera</span>
								</button>
							</div>

						@endif
						
						<a id="create-user-link" class="btn btn-default btn-sm" href="{!! route('employee.create') !!}" role="button"  data-toggle="tooltip" data-placement="top" title="Crear Empleado">
							<span class="glyphicon glyphicon-plus"></span>
							<span class="sr-only">Crear Empleado</span>
						</a>
						
					</small>
					
					<div class="col-md-6 text-right">
					    
					    <a href="{{route('employee.export_to_excel')}}" id="btn-import" class="btn btn-success btn-sm" data-toggle="tooltip" data-placement="top" title="Descargar Datos de Empleados">
							<span class="fa fa-download"></span>
							<span class="sr-only">Descargar Datos de Empleados</span>
						</a>
					    
					    <a href="{{route('employee.prepare_import')}}" id="btn-import" class="btn btn-success btn-sm" data-toggle="tooltip" data-placement="top" title="Cargar Datos de Empleados">
							<span class="fa fa-upload"></span>
							<span class="sr-only">Cargar Datos de Empleados</span>
						</a>
					    
					</div>
					
					@include ('CoreModule::layout.notifications')
					
				</div>
				
            </div>

            <div class="panel-body">
				
				<div class="row">
    				<div class="col-md-6"></div>
    				@include ('EmployeesModule::employees.partials.searchForm')
				</div>
					
				@include ('EmployeesModule::employees.partials.index-table')
				
            </div>
        </div>
    </div>

    {{-- La ventana modal de confirmación --}}
    <div class="modal fade" id="confirm-modal" tabindex="-1" role="dialog" aria-labelledby="confirm-modal-label">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				
			    <div class="modal-header">
				    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				    <h4 class="modal-title" id="exampleModalLabel">Está seguro?</h4>
			    </div>
			    
			    <div class="modal-body">
				    <div class="modal-message"></div>
			    </div>
			    
			    <div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
					<button type="button" id="btn-confirm" class="btn btn-danger">Confirmar</button>
			    </div>
			    
			</div>
		</div>
	</div>
    
@endsection

@section('script')

    <script type="text/javascript">
        
        $(document).ready(function(){
        	
        	$('#btn-confirm').click(function(event){
        		var btn = $(event.target);
        		var form = $('form[name=table-form]');
        		var method = $('input[name=_method]');
        		
        		form.attr({
        			'action' : btn.attr('data-action')
        		});
        		
        		method.attr('value', btn.attr('data-method'));
        		form.submit();
        	});
        	
        	{{-- Configura el contenido de la ventana modal --}}
        	$('#confirm-modal').on('show.bs.modal', function (event) {
				var button = $(event.relatedTarget);
				var method = button.data('method');
				var action = button.data('action');
				var message = button.data('message');

				var modal = $(this)
				modal.find('.modal-body .modal-message').text(message)
				modal.find('.modal-footer button#btn-confirm').attr('data-method', method)
				modal.find('.modal-footer button#btn-confirm').attr('data-action', action)
			});
            
            {{-- searching if there are checkboxes checked to toggle enable action buttons --}}
            scanCheckedCheckboxes('.checkbox-table-item');
            
            {{-- toggle the checkbox checked state from row click --}}
            toggleCheckboxFromRowClick();
            
            {{-- toggle select all checkboxes --}}
            toggleCheckboxes();
            
            {{-- listen click on checkboxes to change row class and count the ones checked --}}
            $('.checkbox-table-item').click(function(event) {
                scanCheckedCheckboxes('.'+$(this).attr('class'));
                event.stopPropagation();
            });
            
            {{-- Initialize all tooltips --}}
            $('[data-toggle="tooltip"]').tooltip();
            
        });
        
    </script>

@stop()