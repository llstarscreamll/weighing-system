@extends('app')

@section('title') Registrar Peso @stop()

@section('content')
<div class="col-sm-10 col-sm-offset-1">
        
    <h1>Registrar Peso</h1>

    @include('partials.notifications')

	{!!Form::open(['method' => 'POST', 'route' => 'weighing.store', 'id' => 'register-weighing'])!!}
	<div class="row">
		
		<div class="form-group col-sm-6">
			{!!Form::label('employee_id', 'Operario')!!}
			{!!Form::select('employee_id', ['' => 'Seleccione']+$employees, null, ['class' => 'form-control'])!!}
			@if ($errors->has('employee_id'))<div class="text-danger">{!! $errors->first('employee_id') !!}</div>@endif
		</div>

		<div class="form-group col-sm-6">
			{!!Form::label('weight', 'Peso')!!}
			<div class="input-group">
				{!!Form::text('weight', $weight, ['class' => 'form-control'])!!}
				<span class="input-group-btn">
				    <a href="{{route('setting.edit', $portSetting->id)}}" class="btn btn-default" data-toggle="tooltip" data-placement="top" title="Configurar Puerto">
				    	<span class="glyphicon glyphicon-scale"></span>
				    </a>
				</span>
			</div>
			@if ($errors->has('weight'))<div class="text-danger">{!! $errors->first('weight') !!}</div>@endif
		</div>

		<div class="clearfix"></div>

		<div class="form-group col-sm-6">
			{!!Form::label('machine_id', 'Máquina')!!}
			{!!Form::select('machine_id', ['' => 'Seleccione', 'Máquina 1' => 'Máquina 1', 'Máquina 2' => 'Máquina 2'], null, ['class' => 'form-control'])!!}
			@if ($errors->has('machine_id'))<div class="text-danger">{!! $errors->first('machine_id') !!}</div>@endif
		</div>

		<div class="form-group col-sm-6">
			{!!Form::label('product_id', 'Producto')!!}
			{!!Form::select('product_id', ['' => 'Seleccione', 'Producto 1' => 'Producto 1', 'Producto 2' => 'Producto 2', 'Producto Test' => 'Producto Test',], null, ['class' => 'form-control'])!!}
			@if ($errors->has('product_id'))<div class="text-danger">{!! $errors->first('product_id') !!}</div>@endif
		</div>

		<div class="clearfix"></div>

		<div class="form-group col-sm-6">
			<button class="btn btn-default">
				Registrar
			</button>
		</div>

	</div>

	{!!Form::close()!!}

	{{--Zona donde muestro los últimos registros creados--}}
	<div class="row">	
		<div class="table-responsive">
			<table class="table table-condensed">
				<thead>
					<tr>
						<th>#</th>
						<th>Operario</th>
						<th>Máquina</th>
						<th>Producto</th>
						<th>Peso</th>
						<th>Fecha</th>
					</tr>
				</thead>
				<tbody>
				@foreach($weighings as $weighing)
					<tr>
						<td><a href="{{route('weighing.printTicket', $weighing->id)}}">{{$weighing->id}}</a></td>
						<td>{{$weighing->employee->name}}</td>
						<td>{{$weighing->machine_id}}</td>
						<td>{{$weighing->product_id}}</td>
						<td>{{$weighing->weight}}</td>
						<td>{{$weighing->created_at}}</td>
					</tr>
				@endforeach
				</tbody>
				<tfoot></tfoot>
			</table>
		</div>
	</div>

</div>
@stop()

@section('scripts')
<script>

	$(function () {
  		$('[data-toggle="tooltip"]').tooltip()
	});

</script>
@stop()