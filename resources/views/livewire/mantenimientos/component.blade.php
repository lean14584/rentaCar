<div class="row sales layout-top-spacing">

	<div class="col-sm-12">
		<div class="widget widget-chart-one">
			<div class="widget-heading">
				<h4 class="card-title">
					<b>{{$componentName}} | {{$pageTitle}}</b>
				</h4>
				<ul class="tabs tab-pills">
					<li>
						<a href="{{ url('pantalla') }}" class="tabmenu bg-dark" >Volver</a>
					</li>
					<li>
						<a href="javascript:void(0)" title="Agregar Nueva Categoria" class="tabmenu bg-dark" data-toggle="modal" data-target="#theModal">Agregar</a>
					</li>
				</ul>
			</div>
				@include('common.searchbox')
			<div class="widget-content">
				<div class="table-responsive">
				@if(count($mantenimietos) > 0)
					<table class="table table-bordered table striped mt-1">
						<thead class="text-white" style="background: #3B3F5C">
							<tr>
								<th class="table-th text-white">NOMBRE</th>
								<th class="table-th text-white text-center">AUTO</th>
								<th class="table-th text-white text-center">PROVEEDOR</th>
								<th class="table-th text-white text-center">VALOR</th>
								<th class="table-th text-white text-center">FECHA INICIO</th>
								<th class="table-th text-white text-center">FECHA FIN</th>
								<th class="table-th text-white text-center">DIAS MANTENIMIENTO</th>
								<th class="table-th text-white text-center">ESTADO</th>
								<th class="table-th text-white text-center">ACTIONS</th>
							</tr>
			
						</thead>
						<tbody>
							@foreach($mantenimietos as $mantenimieto)
							<tr>
								<td>
									<h6>{{$mantenimieto->nombre}}</h6>
								</td>
								<td class="text-center">
									<h6>{{$mantenimieto->patente}}</h6>
								</td>
								<td class="text-center">
									<h6>{{$mantenimieto->idProveedor}}</h6>
								</td>
								<td class="text-center">
									<h6>{{$mantenimieto->valor}}</h6>
								</td>
								<td class="text-center">
									<h6>{{$mantenimieto->fechaInicio}}</h6>
								</td>
								<td class="text-center">
									<h6>{{$mantenimieto->fechaFin}}</h6>
								</td>
								<td class="text-center">
									<h6>{{$mantenimieto->diasArreglo}}</h6>
								</td>
								<td class="text-center">
                                    <span class="badge {{ $mantenimieto->estado == 'Finalizada' ? 'badge-success' : 'badge-danger' }} text-uppercase">{{$mantenimieto->estado}}</span>
                                </td>
								<td class="text-center">
									<a href="javascript:void(0)" 
									wire:click.prevent="Habilitar({{$mantenimieto->id}})"
									class="btn btn-dark mtmobile" title="Habilitar Auto">
										<i class="fas fa-check-circle"></i>
									</a>
									<a href="javascript:void(0)" 
									wire:click.prevent="Edit({{$mantenimieto->id}})"
									class="btn btn-dark mtmobile" title="Editar Mantenimiento">
										<i class="fas fa-edit"></i>
									</a>
									<a href="javascript:void(0)" 
									onclick="Confirm('{{$mantenimieto->id}}')"
									class="btn btn-dark mtmobile" title="Eliminar Mantenimiento">
										<i class="fas fa-trash"></i>
									</a>
								</td>
							</tr>
							@endforeach
						</tbody>
					</table>
					@else
						<h5 class="text-center text-muted">No Hay Gastos Cargados</h5>
					@endif
					{{$mantenimietos->links()}}
				</div>
			</div>
		</div>
	</div>
	@include('livewire.mantenimientos.form')
</div>

<script>
	document.addEventListener('DOMContentLoaded', function() {

		window.livewire.on('show-modal', msg => {
			$('#theModal').modal('show')
		});
		window.livewire.on('mantenimieto-added', msg => {
			$('#theModal').modal('hide')
			 noty(msg)
		});
		window.livewire.on('mantenimieto-fin', msg => {
			$('#theModal').modal('hide')
			 noty(msg)
		});
		window.livewire.on('mantenimieto-updated', msg => {
			$('#theModal').modal('hide')
			 noty(msg)
		});
    	window.livewire.on('mantenimieto-deleted', msg =>{
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