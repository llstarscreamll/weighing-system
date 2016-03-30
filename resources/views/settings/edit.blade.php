@extends('app')

@section('title') Editar Configuración @stop()

@section('content')
	<div class="col-sm-10 col-sm-offset-1">

		<h1 class="hidden-print">Configuración <small>Editar</small></h1>

    	@include('partials.notifications')

		{!!Form::open(['route' => ['setting.update', $setting->id], 'method' => 'PUT'])!!}

			<div class="form-group col-md-5">
				{!!Form::label('value', $setting->name)!!}
				{!!Form::text('value', $setting->value, ['class' => 'form-control'])!!}
				<span class="help-block">
					Si estás en Linux o Mac -> "/dev/ttyS*", <small>debes tener los permisos necesarios para conectarte al puerto, depende de tu versión o distribución.</small>
				</span>
				<span class="help-block">
					Si estas en Windows -> "com*:", no olvidar los dos puntos ":" final.
				</span>
			</div>

			<div class="form-group col-md-5">
				<button class="btn btn-default">
					Guardar
				</button>
			</div>

		{!!Form::close()!!}
	</div>
@stop()
