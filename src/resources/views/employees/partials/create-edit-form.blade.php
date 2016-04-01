<div class="form-group col-md-5 col-md-offset-1">
	{!! Form::label('name', 'Nombre *') !!}
    {!! Form::text('name', null, ['class' => 'form-control'])  !!}
    @if ($errors->has('name'))
        <div class="text-danger">
            {!! $errors->first('name') !!}
        </div>
    @endif
</div>

<div class="form-group col-md-5">
	{!! Form::label('lastname', 'Apellido *') !!}
    {!! Form::text('lastname', null, ['class' => 'form-control'])  !!}
    @if ($errors->has('lastname'))
        <div class="text-danger">
            {!! $errors->first('lastname') !!}
        </div>
    @endif
</div>

<div class="clearfix"></div>

<div class="form-group col-md-5 col-md-offset-1">
	{!! Form::label('identification_number', 'No. Identificación *') !!}
    {!! Form::text('identification_number', null, ['class' => 'form-control'])  !!}
    @if ($errors->has('identification_number'))
        <div class="text-danger">
            {!! $errors->first('identification_number') !!}
        </div>
    @endif
</div>

<div class="form-group col-md-5">
	{!! Form::label('internal_code', 'Código *') !!}
    {!! Form::text('internal_code', null, ['class' => 'form-control'])  !!}
    @if ($errors->has('internal_code'))
        <div class="text-danger">
            {!! $errors->first('internal_code') !!}
        </div>
    @endif
</div>

<div class="clearfix"></div>

<div class="form-group col-md-5 col-md-offset-1">
	{!! Form::label('sub_cost_center_id', 'Centro de Costo *') !!}
    {!! Form::select('sub_cost_center_id', ['' => 'Selecciona centro de costo']+$cost_centers, null, ['class' => 'form-control selectpicker show-tick'])  !!}
    @if ($errors->has('sub_cost_center_id'))
        <div class="text-danger">
            {!! $errors->first('sub_cost_center_id') !!}
        </div>
    @endif
</div>

<div class="form-group col-md-5">
	{!! Form::label('position_id', 'Cargo *') !!}
    {!! Form::select('position_id', ['' => 'Selecciona cargo']+$positions, null, ['class' => 'form-control selectpicker show-tick'])  !!}
    @if ($errors->has('position_id'))
        <div class="text-danger">
            {!! $errors->first('position_id') !!}
        </div>
    @endif
</div>

<div class="clearfix"></div>

<div class="form-group col-md-5 col-md-offset-1">
	{!! Form::label('city', 'Ciudad') !!}
    {!! Form::text('city', null, ['class' => 'form-control'])  !!}
    @if ($errors->has('city'))
        <div class="text-danger">
            {!! $errors->first('city') !!}
        </div>
    @endif
</div>

<div class="form-group col-md-5">
	{!! Form::label('address', 'Dirección') !!}
    {!! Form::text('address', null, ['class' => 'form-control'])  !!}
    @if ($errors->has('address'))
        <div class="text-danger">
            {!! $errors->first('address') !!}
        </div>
    @endif
</div>

<div class="clearfix"></div>

<div class="form-group col-md-5 col-md-offset-1">
	{!! Form::label('phone', 'Teléfono') !!}
    {!! Form::text('phone', null, ['class' => 'form-control'])  !!}
    @if ($errors->has('phone'))
        <div class="text-danger">
            {!! $errors->first('phone') !!}
        </div>
    @endif
</div>

<div class="form-group col-md-5">
	{!! Form::label('email', 'Correo Electrónico') !!}
    {!! Form::email('email', null, ['class' => 'form-control'])  !!}
    @if ($errors->has('email'))
        <div class="text-danger">
            {!! $errors->first('email') !!}
        </div>
    @endif
</div>

<div class="clearfix"></div>