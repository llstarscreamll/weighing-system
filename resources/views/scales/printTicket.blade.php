@extends('app')

@section('title') Imprimir Ticket de Pesaje @stop()

<style type="text/css">
	.ticket-table{
		font-size: 12px;
	}

	.company-logo{
		font-size: 50px;
	}

	.company-name{
		font-size: 12px;
	}

	.company-name-small{
		font-size: 10px;
	}

</style>

@section('content')
	<div class="col-sm-10 col-sm-offset-1">
        
    <h1 class="hidden-print">Imprimir Ticket de Pesaje</h1>

    @include('partials.notifications')

    <div style="width: 350px;">

    	<table class="table table-condensed ticket-table">
    		<thead>
    			<tr>
    				<th colspan="2" class="text-center">
    					<span class="glyphicon glyphicon-tower company-logo" aria-hidden="true"></span>
			  			<br>
			  			<small class="company-name">Weisy</small>
			  			<br>
			  			<small class="company-name-small">Sistema de Pesaje</small>
    				</th>
    			</tr>
    		</thead>
    		<tbody>
    			<tr>
    				<td><strong>Producto: </strong>{{$weighing->product_id}}</td>
    				<td><strong>XXXXX: </strong>xxx</td>
    			</tr>
    			<tr>
    				<td><strong>Máquina: </strong>{{$weighing->machine_id}}</td>
    				<td><strong>XXXXX: </strong>xxx</td>
    			</tr>
    			<tr>
    				<td><strong>Peso(kg): </strong>{{$weighing->weight}}</td>
    				<td><strong>XXXXX: </strong>xxx</td>
    			</tr>
    			<tr>
    				<td><strong>Código de Operario: </strong>{{$weighing->employee->internal_code}}</td>
    				<td><strong>XXXXX: </strong>xxx</td>
    			</tr>
    			<tr>
    				<td colspan="2" class="text-center">
    					<span>{!!DNS1D::getBarcodeSVG($weighing->getBarcode(), "EAN13", 2, 60)!!}</span>
				  		<br>
				  		<span class="sr-only"><strong>Código de Barras: </strong></span>{{$weighing->getBarcode()}}
    				</td>
    			</tr>
    		</tbody>
    		<tfoot>
    			<tr class="text-center">
    				<td colspan="2">
    					<small>Lorem Ipsum is simply dummy text of the printing and typesetting industry.</small>
    					<br>
    					<small>Standard dummy text ever since the 1500.</small>
    					<br>
    					<small>Making this the first true generator on the Internet. It uses a dictionary of over 200 Latin words.</small>
    				</td>
    			</tr>
    		</tfoot>
    	</table>

	</div>

    </div>
@stop()
