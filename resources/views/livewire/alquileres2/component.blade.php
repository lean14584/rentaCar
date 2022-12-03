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
				@if(count($alquileres) > 0)
					<table class="table table-bordered table striped mt-1">
						<thead class="text-white" style="background: #3B3F5C">
							<tr>
								<th class="table-th text-white">CLIENTE</th>
								<th class="table-th text-white text-center">AUTO</th>
								<th class="table-th text-white text-center">FECHA INICIO</th>
								<th class="table-th text-white text-center">FECHA FIN</th>
								<th class="table-th text-white text-center">DIAS ALQUILER</th>
								<th class="table-th text-white text-center">TOTAL</th>
								<th class="table-th text-white text-center">ENTREGA</th>
								<th class="table-th text-white text-center">SALDO</th>
								<th class="table-th text-white text-center">ESTADO</th>
								<th class="table-th text-white text-center">ACTIONS</th>
							</tr>
			
						</thead>
						<tbody>
							@foreach($alquileres as $alquiler)
							<tr>
								<td>
									<h6>{{$alquiler->nombre}}</h6>
								</td>
								<td class="text-center">
									<h6>{{$alquiler->patente}}</h6>
								</td>
								<td class="text-center">
									<h6>{{$alquiler->fechaInicio}}</h6>
								</td>
								<td class="text-center">
									<h6>{{$alquiler->fechaFin}}</h6>
								</td>
								<td class="text-center">
									<h6>{{$alquiler->diasAlquiler}}</h6>
								</td>
								<td class="text-center">
									<h6>{{$alquiler->total}}</h6>
								</td>
								<td class="text-center">
									<h6>{{$alquiler->entrega}}</h6>
								</td>
								<td class="text-center">
									<h6>{{$alquiler->saldo}}</h6>
								</td>
								<td class="text-center">
                                    <span class="badge {{ $alquiler->estado == 'Cargada' ? 'badge-success' : 'badge-danger' }} text-uppercase">{{$alquiler->estado}}</span>
                                </td>
								<td class="text-center">
									<a href="javascript:void(0)" 
									wire:click.prevent="Edit({{$alquiler->id}})"
									class="btn btn-dark mtmobile" title="Editar Mantenimiento">
										<i class="fas fa-edit"></i>
									</a>
									<a href="javascript:void(0)" 
									onclick="Confirm('{{$alquiler->id}}')"
									class="btn btn-dark mtmobile" title="Eliminar Mantenimiento">
										<i class="fas fa-trash"></i>
									</a>
								</td>
							</tr>
							@endforeach
						</tbody>
					</table>
					@else
						<h5 class="text-center text-muted">No Hay Gastos Alquileres De Autos Cargados</h5>
					@endif
					{{$alquileres->links()}}
				</div>
			</div>
		</div>
	</div>
	@include('livewire.alquileres2.form')
</div>

<script>
	document.addEventListener('DOMContentLoaded', function() {

		window.livewire.on('show-modal', msg => {
			$('#theModal').modal('show')
		});
		window.livewire.on('alquiler-added', msg => {
			$('#theModal').modal('hide')
			 noty(msg)
		});
		window.livewire.on('alquiler-updated', msg => {
			$('#theModal').modal('hide')
			 noty(msg)
		});
    	window.livewire.on('alquiler-deleted', msg =>{
			//notificacion
			noty(msg)
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