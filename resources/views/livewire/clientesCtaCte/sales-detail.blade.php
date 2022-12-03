<div wire:ignore.self class="modal fade" id="modalDetailsCtaCte" tabindex="-1" role="dialog">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header bg-dark">
				<h5 class="modal-title text-white">
					<b>Detalle de Venta # {{$saleId}}</b>
						@foreach($clienteVer as $c)
							<b> Cliente {{$c->cliente}} </b>
						@endforeach
				</h5>
				<h6 class="text-center text-warning" wire:loading>POR FAVOR ESPERE</h6>
			</div>
			<div class="modal-body">
				<div class="table-responsive">
					<table class="table table-bordered table striped mt-1">
						<thead class="text-white" style="background: #3B3F5C">
							<tr>
							<th class="table-th text-white text-center">FOLIO</th>
							<th class="table-th text-white text-center">PRODUCTO</th>
							<th class="table-th text-white text-center">PRECIO</th>
							<th class="table-th text-white text-center">CANT</th>
							<th class="table-th text-white text-center">IMPORTE</th>
						  </tr>
						</thead>
						<tbody>
							@foreach($details as $d)
							<tr>
								<td class='text-center'><h6 style="color:{{$d->status==1 OR $d->status==2 ? 'black' : 'red' }}">{{$d->id}}</h6></td> 
								<td class='text-center'><h6 style="color:{{$d->status==1 OR $d->status==2 ? 'black' : 'red' }}">{{$d->product}}</h6></td>
								<td class='text-center'><h6 style="color:{{$d->status==1 OR $d->status==2 ? 'black' : 'red' }}">{{number_format($d->price,2)}}</h6></td>
								<td class='text-center'><h6 style="color:{{$d->status==1 OR $d->status==2 ? 'black' : 'red' }}">{{number_format($d->quantity,2)}}</h6></td>
								<td class='text-center'><h6 style="color:{{$d->status==1 OR $d->status==2 ? 'black' : 'red' }}">{{number_format($d->price * $d->quantity,2)}}</h6></td>               
							</tr>
							@endforeach
						</tbody>
						<tfoot>
						  <tr>
							<td colspan="3"><h5 class="text-center font-weight-bold">TOTALES</h5></td>
							<td><h5 class="text-center">{{number_format($countDetails,2)}}</h5></td>
							<td><h5 class="text-center">
							  ${{number_format($sumDetails,2)}}
							</h5></td>
						  </tr>
						</tfoot>
					</table>         
				</div>
				@if(count($descuentos) < 1)
					@else			
						<div class="table-responsive">
						  <table class="table table-bordered table striped mt-1">
							<thead class="text-white" style="background: #3B3F5C">
							  <tr>
								<th class="table-th text-white text-center">PRMOCION</th>
								<th class="table-th text-white text-center">CATIDAD</th>
								<th class="table-th text-white text-center">MONTO</th>
							  </tr>
							</thead>
							<tbody>
							  @foreach($descuentos as $p)
							  <tr>
								<td class='text-center'><h6>{{$p->nombre}}</h6></td> 
								<td class='text-center'><h6>{{$p->cantidad}}</h6></td>            
								  <td class='text-center'><h6>${{number_format($p->monto,2)}}</h6></td>     
							  </tr>
							  @endforeach
							</tbody>
							<tfoot class="text-white" style="background: #3B3F5C">
							  <tr>
								<td><h5 class="text-white font-weight-bold">DESCUENTOS</h5></td>
								<td><h5 class="text-white text-center">{{number_format($countDescuento,2)}}</h5></td>
								<td><h5 class="text-white text-center">
								  ${{number_format($sumDescuento,2)}}
								</h5></td>
							  </tr>
							  <tr>
								<td style="background: #FFFFFF"></td>
								<td style="background: #FFFFFF"></td>
								<td style="background: #FFFFFF"></td>
							  </tr>
							   <tr>
								<td><h5 class="text-white font-weight-bold">TOTAL</h5></td>
								<td></td>
								<td><h5 class="text-white text-center">
								  ${{number_format(($sumDetails - $sumDescuento),2)}}
								</h5></td>
							  </tr>
							</tfoot>
							</table>         
						</div>
					@endif		
				<div class="table-responsive">
					<table class="table table-bordered table striped mt-1">
						<thead class="text-white" style="background: #3B3F5C">
							<tr>
								<th class="table-th text-white text-center">MEDIO DE PAGO</th>
								<th class="table-th text-white text-center">MONTO</th>
							</tr>
						</thead>
						<tbody>
						  @foreach($mediosDePago as $r)
							<tr>
								<td class='text-center'><h6>{{$r->metodo}}</h6></td> 
								<td class='text-center'><h6>${{number_format($r->monto,2)}}</h6></td>            
							</tr>
						  @endforeach
						</tbody>
					</table>         
				</div>
			</div>
			<div class="modal-footer">        
				<button type="button" class="btn btn-dark close-btn text-info" data-dismiss="modal">CERRAR</button>
			</div>
		</div>
	</div>
</div>