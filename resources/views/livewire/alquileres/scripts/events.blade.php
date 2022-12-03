<script>
	document.addEventListener("DOMContentLoaded", function(){
		
		window.livewire.on('cargar-cliente', Cliente => {			
			//noty(Cliente, 2)
			window.livewire.emit('cargar-cliente-pos', Cliente)
		})
		
		window.livewire.on('cerrar-modal-clientes', Msg => {			
			$('#modalClientes').modal('hide')
		})
		
		window.livewire.on('modal-cliente', Msg => {			
			$('#modalClientes').modal('show')
			
		})
		
		window.livewire.on('cerrar-modal', Msg => {	
			noty(Msg)		
			$('#modalSearchProduct').modal('hide')
			
		})
		
		window.livewire.on('cerrar-modal-cliente', Msg => {	
			noty(Msg)		
			$('#modalCliente').modal('hide')
			
		})
		
		window.livewire.on('cerrar-modal-cadena', Msg => {	
			noty(Msg)		
			$('#modalCadena').modal('hide')
			
		})
		
		window.livewire.on('alquiler-added', msg => {
			//$('#theModal').modal('hide')
			 noty(msg)
		});
	
		window.livewire.on('cerrar-modal-matafuego', Msg => {	
			noty(Msg)		
			$('#modalMatafuego').modal('hide')
			
		})
		
		window.livewire.on('cerrar-modal-silla', Msg => {	
			noty(Msg)		
			$('#modalSilla').modal('hide')
			
		})
	
		window.livewire.on('alquiler-ok', Msg => {	
		console.log('alquiler-ok')	
		//@this.printTicket(Msg)		
		noty(Msg)			
	    })

		window.livewire.on('alquiler-error', Msg => {			
			noty(Msg, 2)
		})
		
		window.livewire.on('cambiar', valor => {
			var idSeleccionado = valor.split("-");
		document.getElementById("p"+idSeleccionado[0]).value = idSeleccionado[1];			
		})
		
		window.livewire.on('print-ticket', info => {		
			console.log(info);
			window.open("print://" + info,  '_self').close()
		})
		
	})
</script>