@include('common.modalHead')
<div class="row">
	
	<div class="col-sm-12 col-md-12">
		<div class="form-group">
			<input type="hidden" wire:model.lazy="name" 
			class="form-control" placeholder="ej: Luis Fax"  />
			<input type="hidden" wire:model.lazy="param" 
			class="form-control" placeholder="ej: Luis Fax"  />
			@error('nombre') <span class="text-danger er">{{ $message}}</span>@enderror
		</div>
	</div>
	<div class="col-sm-12 col-md-6">
			<div class="form-group">
				<label>Tipo</label>
				<select wire:model='descripcion' class="form-control">
					<option value="Elegir" selected>Elegir</option>
						<option value="Orden De Pago">Orden De Pago</option>
					</select>
				@error('descripcion')<span class="text-danger er">{{ $message}}</span>@enderror
			</div>
	</div>
	<div class="col-sm-12 col-md-6">
		<div class="form-group">
			<label >Monto</label>
			<input type="text" wire:model.lazy="monto" 
			class="form-control"   >
			@error('monto') <span class="text-danger er">{{ $message}}</span>@enderror
		</div>
	</div>
	@if($descripcion=="Orden De Pago")
	<div class="col-sm-12 col-md-12">
		<div class="form-group">
				<select wire:model='metodo_pago' class="form-control">
					<option value="Elegir" selected>Elegir</option>
						<option value="Efectivo">Efectivo</option>
						<option value="Debito">Debito</option>
						<option value="Credito">Credito</option>
					</select>
				@error('metodo_pago')<span class="text-danger er">{{ $message}}</span>@enderror
		</div>
	</div>
	@endif

@include('common.modalFooter')
</div>

