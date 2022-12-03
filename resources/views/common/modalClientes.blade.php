 <div wire:ignore.self class="modal fade" data-backdrop="static" id="modalClientes" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header bg-dark">
        <h5 class="modal-title text-white">
			<b>Buscar Clientes</b>
		</h5>
        <h6 class="text-center text-warning" wire:loading>Por Favor Espere
		</h6>
      </div>
      <div class="modal-body">

		@livewire('clientes-buscar-controller')
		
	  </div>
      <div class="modal-footer">
       
      </div>
    </div>
  </div>
</div>

<script>
	document.addEventListener("DOMContentLoaded", function(){
		window.livewire.on('borrarModal', Msg =>{
			$('#modalClientes').modal('hide')
		})
		$("#modalClientes").on('shown.bs.modal', function(){
        $(this).find('#buscarCliente').focus();
		//document.getElementById("buscar").value='';
		});
		
	})
</script>




