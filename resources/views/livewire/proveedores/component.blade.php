<div class="row sales layout-top-spacing">
	<div class="col-sm-12">
		<div class="widget widget-chart-one">
			<div class="widget-heading">
				<h4 class="card-title">
					<b>{{$componentName}} | {{$pageTitle}}</b>
				</h4>
				<ul class="tabs tab-pills">
					<li>
						<a href="javascript:void(0)" class="tabmenu bg-dark" title="Agregar Nuevo Proveedor" data-toggle="modal" data-target="#theModal">Agregar</a>
					</li>
				</ul>		
			</div>
			@include('common.searchbox')
			<div class="widget-content">
				<div class="table-responsive">
				@if(count($data) > 0)
					<table class="table table-bordered table stripped mt-1">
						<thead class="text-white" style="background: #3b3f5c">
							<tr>
								<th class="table-th text-white text-center">NOMBRE</th>
								<th class="table-th text-white text-center">LOCALIDAD</th>
								<th class="table-th text-white text-center">TELEFONO</th>
								<th class="table-th text-white text-center">DIRECCION</th>
								<th class="table-th text-white text-center">CORREO</th>
								<th class="table-th text-white text-center">IMAGEN</th>
								<th class="table-th text-white text-center">ACCIONES</th>
							</tr>	
						</thead>
						<tbody>
						@foreach($data as $dat)
							<tr>
								<td><h6>{{$dat->nombre}}</h6></td>
								<td><h6>{{$dat->localidad}}</h6></td>
								<td><h6>{{$dat->telefono}}</h6></td>
								<td><h6>{{$dat->direccion}}</h6></td>
								<td><h6>{{$dat->correo}}</h6></td>
								<td class="text-center">
									<span>
										<img src="{{ asset('storage/proveedores/' . $dat->imagen) }}" alt="imagen de ejemplo" height="70" width="80" class="rounded">
									</span>
								</td>
								<td class="text-center">
									<a href="javascript:void(0)"
											wire:click="Edit({{$dat->id}})"
											class="btn btn-dark mtmobile" title="Editar Proveedor">
											<i class="fas fa-edit"> Editar</i>
									</a>
									<a href="javascript:void(0)"
											onclick="Confirm('{{$dat->id}}','{{$dat->nombre}}')"
											class="btn btn-dark mtmobile" title="Borrar Proveedor">
										<i class="fas fa-trash"> Eliminar</i>
									</a>
										<a href="{{url('proveedoresCtaCte/'.$dat->id.'/'.$dat->nombre)}}"
											class="btn btn-dark mtmobile" title="Cta. Cte. Del Proveedor {{$dat->nombre}}">
										<i class="fas fa-book"> Cta. Cte</i>
									</a>
								</td>
		
							</tr>
						@endforeach	
						</tbody>
					</table>
					@else
						<h5 class="text-center text-muted">No Hay Proveedores Cargados</h5>
					@endif
					{{$data->links()}}
				</div>
			</div>
		</div>
	</div>
@include('livewire.proveedores.form')
<script src="{{ asset('js/keypress.js')}}"></script>
<script src="{{ asset('js/onscan.js')}}"></script>
</div>

<script>
	document.addEventListener("DOMContentLoaded", function(){
		
		window.livewire.on('modal-productos-proveedor', msg =>{
			$('#modalProductosProveedor').modal('show')
		});
		
		window.livewire.on('show-modal', msg =>{
			$('#theModal').modal('show')
		});	
		
		window.livewire.on('proveedor-added', msg =>{
			$('#theModal').modal('hide')
			noty(msg)
		});	
		
		window.livewire.on('proveedor-updated', msg =>{
			$('#theModal').modal('hide')
	    	noty(msg)
		});	
		
		window.livewire.on('proveedor-deleted', msg =>{
			//notificacion
			noty(msg)
		});
		
		$('#theModal').on('hidden.bs.modal', function(e) {
			$('.er').css('display', 'none')
		});	
		
	});
	
	function Confirm(id, proveedor)
	{

			swal({
				title: 'CONFIRMAR',
				text: '¿CONFIRMAR ELIMNAR EL PROVEEDOR '+proveedor+'?',
				type: 'warning',
				showCancelButton: true,
				cancelButtonText: 'Cerrar',
				cancelButtonColor: '#fff',
				confirmButtonText: 'Aceptar',
				confirmButtonColor: '#3b3f5c',
			}).then(function(result){
				if(result.value){
					window.livewire.emit('deleteRow', id)
					swal.close()
				}
			})
	}
</script>
