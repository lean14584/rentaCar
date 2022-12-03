<div class="row sales layout-top-spacing">

	<div class="col-sm-12">
		<div class="widget widget-chart-one">
			<div class="widget-heading">
				<h4 class="card-title">
					<b>{{$componentName}} | {{$pageTitle}}</b>
				</h4>
				<ul class="tabs tab-pills">
					<li>
						<a href="javascript:void(0)" title="Agregar Nueva Categoria" class="tabmenu bg-dark" data-toggle="modal" data-target="#theModal">Agregar</a>
					</li>
				</ul>
			</div>
				@include('common.searchbox')
			<div class="widget-content">
				<div class="table-responsive">
				@if(count($autos) > 0)
					<table class="table table-bordered table striped mt-1">
						<thead class="text-white" style="background: #3B3F5C">
							<tr>
								<th class="table-th text-white text-center">PATENTE</th>
								<th class="table-th text-white text-center">TITULAR</th>
								<th class="table-th text-white text-center">SEGURO/POLIZA/RTO</th>
								<th class="table-th text-white text-center">ESTADO</th>
								<th class="table-th text-white text-center">GASTOS</th>
								<th class="table-th text-white text-center">VALOR ALQUILER POR DIA</th>
								<th class="table-th text-white text-center">IMAGEN</th>
								<th class="table-th text-white text-center">ACTIONS</th>
							</tr>
			
						</thead>
						<tbody>
							@foreach($autos as $auto)
							<tr>
								<td class="text-center">
									<h6 wire:click="detalleAuto('{{$auto->id}}','{{$auto->patente}}')" title="Detalles Del Auto Al Dar Clic">{{$auto->patente}}</h6>
								</td>
								<td class="text-center">
									<h6>{{$auto->titular}}</h6>
								</td>
								<td class="text-center">
									<h6 wire:click="delalleSeguro('{{$auto->id}}','{{$auto->patente}}','{{$auto->vigenciaSeguro}}','{{$auto->vigenciaPoliza}}')" title="Detalles Del Seguro Y La Poliza Al Dar Clic">Ver Estado</h6>
								</td>
								<td class="text-center">
                                    <span class="badge {{ $auto->estado == 'Disponible' ? 'badge-success' : 'badge-danger' }} text-uppercase">{{$auto->estado}}</span>
                                </td>
								<td class="text-center">
									<h6 wire:click="delalleMantenimientos('{{$auto->id}}','{{$auto->patente}}')" title="Detalles De Los Mantenimientos Cargados Al Dar Clic">Ver Gastos</h6>
								</td>
								<td class="text-center">
									<h6>{{$auto->importe}}</h6>
								</td>
								<td class="text-center">
									<span>
										<img src="{{ asset('storage/autos/' . $auto->imagen) }}" alt="imagen de ejemplo" height="70" width="80" class="rounded">
									</span>
								</td>
								<td class="text-center">
									<a href="javascript:void(0)" 
									wire:click.prevent="Edit({{$auto->id}})"
									class="btn btn-dark mtmobile" title="Editar Auto">
										<i class="fas fa-edit"></i>
									</a>
									<a href="javascript:void(0)" 
									onclick="Confirm('{{$auto->id}}')"
									class="btn btn-dark mtmobile" title="Eliminar Auto">
										<i class="fas fa-trash"></i>
									</a>
								</td>
							</tr>
							@endforeach
						</tbody>
					</table>
					@else
						<h5 class="text-center text-muted">No Hay Autos Cargados</h5>
					@endif
					{{$autos->links()}}
				</div>
			</div>
		</div>
	</div>
	@include('livewire.autos.form')
	@include('common.modalDetalleAuto')
	@include('common.modalDetalleSeguro')
	@include('common.modalDetalleMantenimiento')
</div>

<script>
	document.addEventListener('DOMContentLoaded', function() {

		window.livewire.on('show-modal', msg => {
			$('#theModal').modal('show')
		});
		window.livewire.on('auto-added', msg => {
			$('#theModal').modal('hide')
			 noty(msg)
		});
		window.livewire.on('auto-updated', msg => {
			$('#theModal').modal('hide')
			 noty(msg)
		});
    	window.livewire.on('auto-deleted', msg =>{
			//notificacion
			noty(msg)
		});
		
		window.livewire.on('modal-detalle-auto', msg => {
			$('#modalDetalleAuto').modal('show')
			 //noty(msg)
		});
		window.livewire.on('modal-detalle-seguro', msg => {
			$('#modalDetalleSeguro').modal('show')
			 //noty(msg)
		});
		window.livewire.on('modal-detalle-mantenimiento', msg => {
			$('#modalDetalleMantenimiento').modal('show')
			 //noty(msg)
		});
		
	});

	function Confirm(id) {

		swal({
			title: 'CONFIRMAR',
			text: '¿CONFIRMAS ELIMINAR EL REGISTRO?',
			type: 'warning',
			showCancelButton: true,
			cancelButtonText: 'Cerrar',
			cancelButtonColor: '#fff',
			confirmButtonColor: '#3B3F5C',
			confirmButtonText: 'Aceptar'
		}).then(function(result) {
			if (result.value) {
				window.livewire.emit('deletedRow', id)
				swal.close()
			}

		})
	}
</script>