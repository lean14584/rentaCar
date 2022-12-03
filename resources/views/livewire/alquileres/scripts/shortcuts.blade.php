<script>

	var listener = new window.keypress.Listener();
		
	listener.simple_combo("f1", function(){
		console.log("f1")
		$('#modalSearchProduct').modal('show')
		
		$("#modalSearchProduct").on('shown.bs.modal', function(){
        $(this).find('#modal-search-input').focus();

		});
		
	})

	listener.simple_combo("f2", function(){
		console.log("f2")
		$('#modalCliente').modal('show')
		
		$("#modalCliente").on('shown.bs.modal', function(){
        $(this).find('#modal-search-input').focus();

		});
	})
	
	listener.simple_combo("f3", function(){
		console.log("f3")
		$('#modalCadena').modal('show')
		
		$("#modalCadena").on('shown.bs.modal', function(){
        $(this).find('#modal-search-input').focus();

		});
	})
	
	listener.simple_combo("f4", function(){
		console.log("f4")
		$('#modalMatafuego').modal('show')
		
		$("#modalMatafuego").on('shown.bs.modal', function(){
        $(this).find('#modal-search-input').focus();

		});
	})
	
	listener.simple_combo("f7", function() {
		console.log("f7")
		$('#modalSilla').modal('show')
		
		$("#modalSilla").on('shown.bs.modal', function(){
        $(this).find('#modal-search-input').focus();

		});
	})
	
	listener.simple_combo("f8", function(){
		console.log("f8")
		livewire.emit('saveAlquiler')
	})
		
</script>
