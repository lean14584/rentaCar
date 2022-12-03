<div>
	<style></style>
	<div class="row layout-top-spacing">
		<div class="col-sm-12 col-md-6">
			<!-- DETALLES -->
			@include('livewire.alquileres.partials.detail')
		</div>
		<div class="col-sm-12 col-md-6">
			<!-- TOTAL -->
			@include('livewire.alquileres.partials.total')
		</div>
	</div>
	<livewire:modal-search/>
	<livewire:modal-cliente/>
	<livewire:modal-cadena/>
	<livewire:modal-matafuego/>
	<livewire:modal-silla/>
</div>

<script src="{{ asset('js/keypress.js') }}"></script>
<script src="{{ asset('js/onscan.js') }}"></script>

@include('livewire.alquileres.scripts.shortcuts')
@include('livewire.alquileres.scripts.events')
@include('livewire.alquileres.scripts.general')
