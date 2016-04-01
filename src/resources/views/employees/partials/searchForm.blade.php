<div class="col-md-6">

    {!! Form::model($input, ['route' => 'employee.index', 'method' => 'GET', 'name' => 'search-form']) !!}
        
        <div class="input-group">

            <div class="input-group-btn">

                {{-- Filtros por estado y registros en papelera --}}
                <div class="btn-group" role="group">
                    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Filtrar
                        <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu">
                        <li><a id="show-trashed" href="{{route('employee.index', ['show_only' => 'trashed'])}}"><span class="glyphicon glyphicon-trash"></span> Mostrar Registros en Papelera</a></li>
                        <li><a id="show-enabled" href="{{route('employee.index', ['show_only' => 'enabled'])}}"><span class="glyphicon glyphicon-eye-open"></span> Mostrar Activados</a></li>
                        <li><a id="show-disabled" href="{{route('employee.index', ['show_only' => 'disabled'])}}"><span class="glyphicon glyphicon-eye-close"></span> Mostrar Desactivados</a></li>
                    </ul>
                </div>

            </div>
            {{-- Busqueda por nombres, apellidos, cédila, código --}}
            {!! Form::text('find', null, ['placeholder' => 'Buscar por nombres, apellido, cédula o código...', 'class' => 'form-control']) !!}
            
            <span class="input-group-btn">
                {{-- El botón para realizar la búsqueda --}}
                <button class="btn btn-default" type="submit">
                    <span class="glyphicon glyphicon-search"></span>
                    <span class="sr-only">Buscar</span>
                </button>
                {{-- Borra los filtros, redirige al index --}}
                <a href="{{route('employee.index')}}" class="btn btn-default">
                    <span class="glyphicon glyphicon-remove"></span>
                    <span class="sr-only">Quitar Filtros</span>
                </a>
            </span>
            
        </div>
            
        @if ($errors->has('find'))
            <div class="text-danger">
                {!! $errors->first('find') !!}
            </div>
        @endif
    
    {!! Form::close() !!}

</div>