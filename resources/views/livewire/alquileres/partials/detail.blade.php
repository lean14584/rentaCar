<div class="connect-sorting">
	<div class="connect-sorting-content">
		<h6 class="text-center mb-2">DETALLE DEL AUTO</h6>
			<div class="card simple-title-task ui-sortable-handle">
				<div class="card-body">
					@if($total > 0)
						<div class="table-responsive tblscroll" style="max-height: 650px; overflow: hidden">
							<table class="table table-bordered table-striped mt-1">
								<thead class="text-white" style="background: #3B3F5C">
									<tr>
									<th width="10%">IMAGEN</th>
									<th class="table-th text-left text-white">DESCRIPCIÓN</th>
									<th class="table-th text-center text-white">PRECIO</th>
									<th class="table-th text-center text-white">ACTIONS</th>
									</tr>
								</thead>
								<tbody>
								@foreach($cart as $item)
									<tr>
										<td class="text-center table-th">
											@if(count($item->attributes) > 0)
											<span>
												<img src="{{ asset('storage/autos/' . $item->attributes[0]) }}" alt="imágen de producto" height="90" width="90" class="rounded">
											</span>
											@endif
										</td>
										<td><h6>{{$item->name}}</h6></td>
										<td class="text-center">${{number_format($item->price,2)}}</td>
										<td class="text-center">
											<button onclick="Confirm('{{$item->id}}', 'removeItem', '¿CONFIRMAS ELIMINAR EL REGISTRO?')" class="btn btn-dark mbmobile">
												<i class="fas fa-trash-alt"></i>
											</button>
										</td>
									</tr>
								@endforeach
								</tbody>
							</table>
						</div>
					@else
						<h5 class="text-center text-muted">Agrega Autos Al Alquiler</h5>
					@endif
					<div wire:offline>
						<h5 class="text-center text-muted">You are now offline.</h5>
					</div>
				</div>
			</div>
	</div>
</div>	
