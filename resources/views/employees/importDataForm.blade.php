@extends('app')

@section('title') Importar Datos de Empleados @stop()

@section('content')
<div class="col-md-10 col-md-offset-1">
		
		<h1>Importar Datos de Empleados</h1>

		@include('partials.notifications')

		{{--Área donde imprimo los errores de validación--}}
		@if (count($errors) > 0)
		    <div class="alert alert-danger">
		    	<strong>¡Error!</strong>
		        <ul>
		            @foreach ($errors->all() as $error)
		                <li>{{ $error }}</li>
		            @endforeach
		        </ul>
		    </div>
		@endif

		{{--Área donde imprimo la info de los datos procesados--}}
		@if(Session::has('processedData'))
			<div class="col-sm-6">

				<ul class="list-group">
				@foreach(Session::get('processedData') as $key => $data)
					<li id="{{$key}}" class="list-group-item">
						{{trans('formCommons.'.$key)}}
						<div class="pull-right">
							<span class="label label-danger">{{$data['invalid']}}</span>
							<span class="label label-success">{{$data['processed']}}</span>
							<span class="label label-warning">{{$data['ignored']}}</span>
						</div>
					</li>
				@endforeach
				@foreach(Session::get('statics') as $key => $static)
				<li id="{{$key}}" class="list-group-item">
					{{trans('formCommons.'.$key)}}
					<div class="pull-right">
						<span class="label label-info">{{$static}}</span>
					</div>
				</li>
				@endforeach
				</ul>

				<ul>
					<li>Registros ***** <span class="label label-danger">#</span> = <strong>con errores de validación</strong></li>
					<li>Registros ***** <span class="label label-success">#</span> = <strong>correctos</strong></li>
					<li>Registros ***** <span class="label label-warning">#</span> = <strong>ignorados</strong></li>
					<li>Los registros con errores de validación no se intentan crear, ni actualizar, ni eliminar.</li>
				</ul>

			</div>
			<div class="clearfix"></div>
		@endif

		{{--Formulario de Importación de datos--}}
		{!!Form::open(['route' => 'employee.postImportDataForm', 'method' => 'POST', 'id' => 'import-employees-data'])!!}
		<div class="row">
			{{--Campo de URL de donde se quiere extraer los datos a importar, los datos que devuelve la url deben estar en formato Json--}}
			<div class="form-group col-sm-6">
				{!!Form::label('url')!!}
				{!!Form::url('url', null, ['class' => 'form-control'])!!}
			</div>

			<div class="clearfix"></div>

			{{--El botón que envía el formulario--}}
			<div class="col-sm-6">
				<button type="submit" class="btn btn-primary">
					Importar
				</button>
			</div>

		</div>
		{!!Form::close()!!}

</div>
@stop()