 <div wire:ignore.self class="modal fade" data-backdrop="static" id="modalDetalleAuto" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header bg-dark">
        <h5 class="modal-title text-white">
			<b>Detalles Auto: {{$patenteDetalle}}</b>
		</h5>
        <h6 class="text-center text-warning" wire:loading>Por Favor Espere
		</h6>
      </div>
      <div class="modal-body">
	  
	  <table class="table table-bordered table striped mt-1">
						<thead class="text-white" style="background: #3B3F5C">
							<tr>
								<th class="table-th text-white text-center">KILOMETROS</th>
								<th class="table-th text-white text-center">COMBUSTIBLE</th>
								<th class="table-th text-white text-center">CAMBIO ACEITE</th>
								<th class="table-th text-white text-center">KM. CAMBIO DE ACEITE</th>
								<th class="table-th text-white text-center">MARCA</th>
								<th class="table-th text-white text-center">MODELO</th>
								<th class="table-th text-white text-center">COLOR</th>
								</tr>
			
						</thead>
						<tbody>
							@foreach($datosAutos as $datosAuto)
							<tr>
								<td class="text-center">
									<h6>{{$datosAuto->kilometros}}</h6>
								</td>
								<td class="text-center">
									<h6>{{$datosAuto->combustible}}</h6>
								</td>
								<td class="text-center">
									<h6>{{$datosAuto->cambioAceite}}</h6>
								</td>
								<td class="text-center">
									<h6>{{$datosAuto->cambioAceite - $datosAuto->kilometros}}</h6>
								</td>
								<td class="text-center">
									<h6>{{$datosAuto->nombreMarca}}</h6>
								</td>
								<td class="text-center">
									<h6>{{$datosAuto->modelo}}</h6>
								</td>
								<td class="text-center">
									<h6>{{$datosAuto->color}}</h6>
								</td>
							</tr>
							@endforeach
						</tbody>
					</table>
	  </div>
      <div class="modal-footer">
        
        <button type="button" wire:click.prevent="resetUI()" class="btn btn-dark close-btn text-info" data-dismiss="modal">CERRAR</button>

      </div>
    </div>
  </div>
</div>

<script>
	document.addEventListener("DOMContentLoaded", function(){
		window.livewire.on('borrarModal', Msg =>{
			$('#modalConsultar').modal('hide')
		})
		
	})
</script>




