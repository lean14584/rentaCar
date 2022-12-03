 <div wire:ignore.self class="modal fade" data-backdrop="static" id="modalDetalleSeguro" tabindex="-1" role="dialog">
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
	   @if(count($datosSeguros) > 0)
	  <table class="table table-bordered table striped mt-1">
						<thead class="text-white" style="background: #3B3F5C">
							<tr>
								<th class="table-th text-white text-center">VIGENCIA SEGURO</th>
								<th class="table-th text-white text-center">DIAS SEGURO</th>
								<th class="table-th text-white text-center">VIGENCIA POLIZA</th>
								<th class="table-th text-white text-center">DIAS POLIZA</th>
								<th class="table-th text-white text-center">EMPRESA RTO</th>
								<th class="table-th text-white text-center">VIGENCIA RTO</th>
								</tr>
			
						</thead>
						<tbody>
							@foreach($datosSeguros as $datosSeguro)
							<tr>
								<td class="text-center">
									<h6>{{$datosSeguro->vigenciaSeguro}}</h6>
								</td>
								<td class="text-center">
									<h6>{{$diasSeguro}}</h6>
								</td>
								<td class="text-center">
									<h6>{{$datosSeguro->vigenciaPoliza}}</h6>
								</td>
								<td class="text-center">
									<h6>{{$diasPoliza}}</h6>
								</td>
								<td class="text-center">
									<h6>{{$datosSeguro->empresa}}</h6>
								</td>
								<td class="text-center">
									<h6>{{$datosSeguro->fechaFin}}</h6>
								</td>
							</tr>
							@endforeach
						</tbody>
					</table>
					@else
						<h5 class="text-center text-muted">No Hay Polizas Ni Seguros Cargados</h5>
					@endif
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




