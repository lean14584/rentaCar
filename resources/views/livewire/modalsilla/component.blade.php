<div wire:ignore.self class="modal fade" id="modalSilla" tabindex="-1">
    <div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<div class="input-group">
					<input type="text" class="form-control" wire:model="searchSilla" id="modal-search-input" placeholder="Puedes buscar por patente" />
						<div class="input-group-prepend">
							<span class="input-group-text imput-gp">
								<i class="fas fa-search">
								</i>
							</span>
						</div>
				</div>
			</div>
			<div class="modal-body">
				<div class="row p-2">
					<div class="table-responsive">
						<table class="table table-bordered table-striped mt-1">
							<thead class="text-white" style="background: #3B3F5C">
								<tr>	
									<th width="4%">FOTO</th>
									<th class="table-th text-center text-white">NOMBRE</th>
									<th class="table-th text-center text-white">ACCIONES</th>	
								</tr>
							</thead>
							<tbody>
								@forelse($sillas as $silla)
								<tr>	
									<td>
										<span>
											<img src="{{ asset('storage/sillas/' . $silla->image)}}" alt="img" height="50" width="50" class="rounded">
										</span>
									</td>
									<td>
										<div class="text-center">
											<h6><b>{{$silla->nombre}}</b></h6>
										</div>	
									</td>
									<td>
										<div class="text-center">
											<button wire:click.prevent="$emit('cargarSilla',{{$silla->id}})" class="btn btn-dark">
												<i class="fas fa-cart-arrow-down mr-1"></i>
												AGREGAR
											</button>
										</div>	
									</td>
								</tr>
								@empty
								<tr>
									<td colspan="5">SIN RESULTADOS</td>
								</tr>
								@endforelse
							</tbody>
						</table>
					</div>
				</div>
			</div>
		<div class="modal-footer">
			<button type="button" class="btn btn-outline-dark" data-dismiss="modal">CERRAR VENTANA</button>
		</div>
		</div>
	</div>
</div>

