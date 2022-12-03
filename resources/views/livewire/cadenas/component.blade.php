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
				@if(count($cadenas) > 0)
					<table class="table table-bordered table striped mt-1">
						<thead class="text-white" style="background: #3B3F5C">
							<tr>
								<th class="table-th text-white">NOMBRE</th>
								<th class="table-th text-white text-center">ESTADO</th>
								<th class="table-th text-white text-center">VALOR</th>
								<th class="table-th text-white text-center">ACTIONS</th>
							</tr>
			
						</thead>
						<tbody>
							@foreach($cadenas as $cadena)
							<tr>
								<td>
									<h6>{{$cadena->nombre}}</h6>
								</td>
								<td class="text-center">
                                    <span class="badge {{ $cadena->estado == 'Libre' ? 'badge-success' : 'badge-danger' }} text-uppercase">{{$cadena->estado}}</span>
                                </td>
								<td class="text-center">
									<h6>{{$cadena->valor}}</h6>
								</td>
								<td class="text-center">
									<a href="javascript:void(0)" 
									wire:click.prevent="Edit({{$cadena->id}})"
									class="btn btn-dark mtmobile" title="Editar Cadena">
										<i class="fas fa-edit"></i>
									</a>
									<a href="javascript:void(0)" 
									onclick="Confirm('{{$cadena->id}}')"
									class="btn btn-dark mtmobile" title="Eliminar Cadena">
										<i class="fas fa-trash"></i>
									</a>
								</td>
							</tr>
							@endforeach
						</tbody>
					</table>
					@else
						<h5 class="text-center text-muted">No Hay Cadenas Cargadas</h5>
					@endif
					{{$cadenas->links()}}
				</div>
			</div>
		</div>
	</div>
	@include('livewire.cadenas.form')
</div>

<script>
	document.addEventListener('DOMContentLoaded', function() {

		window.livewire.on('show-modal', msg => {
			$('#theModal').modal('show')
		});
		window.livewire.on('cadena-added', msg => {
			$('#theModal').modal('hide')
			 noty(msg)
		});
		window.livewire.on('cadena-updated', msg => {
			$('#theModal').modal('hide')
			 noty(msg)
		});
    	window.livewire.on('cadena-deleted', msg =>{
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