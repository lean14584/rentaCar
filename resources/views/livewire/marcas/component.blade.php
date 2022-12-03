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
				@if(count($marcas) > 0)
					<table class="table table-bordered table striped mt-1">
						<thead class="text-white" style="background: #3B3F5C">
							<tr>
								<th class="table-th text-white">DESCRIPCIÓN</th>
								<th class="table-th text-white text-center">IMAGEN</th>
								<th class="table-th text-white text-center">ACTIONS</th>
							</tr>
						</thead>
						<tbody>
							@foreach($marcas as $marca)
							<tr>
								<td>
									<h6>{{$marca->nombre}}</h6>
								</td>
								<td class="text-center">
									<span>
										<img src="{{ asset('storage/marcas/' . $marca->imagen) }}" alt="imagen de ejemplo" height="70" width="80" class="rounded">
									</span>
								</td>
								<td class="text-center">
									<a href="javascript:void(0)" 
									wire:click.prevent="Edit({{$marca->id}})"
									class="btn btn-dark mtmobile" title="Editar Marca">
										<i class="fas fa-edit"></i>
									</a>
									<a href="javascript:void(0)" 
									onclick="Confirm('{{$marca->id}}','{{$marca->nombre}}')"
									class="btn btn-dark mtmobile" title="Eliminar Marca">
										<i class="fas fa-trash"></i>
									</a>
								</td>
							</tr>
							@endforeach
						</tbody>
					</table>
					@else
						<h5 class="text-center text-muted">No Hay Marcas De Autos Cargadas</h5>
					@endif
					{{$marcas->links()}}
				</div>
			</div>
		</div>
	</div>
	@include('livewire.marcas.form')
</div>

<script>
	document.addEventListener('DOMContentLoaded', function() {

		window.livewire.on('show-modal', msg => {
			$('#theModal').modal('show')
		});
		window.livewire.on('marca-added', msg => {
			$('#theModal').modal('hide')
			 noty(msg)
		});
		window.livewire.on('marca-updated', msg => {
			$('#theModal').modal('hide')
			 noty(msg)
		});
    	window.livewire.on('marca-deleted', msg =>{
			//notificacion
			noty(msg)
		});
		

	});

	function Confirm(id, nombre) {

		swal({
			title: 'CONFIRMAR',
			text: '¿CONFIRMAS ELIMINAR EL REGISTRO '+nombre+'?',
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