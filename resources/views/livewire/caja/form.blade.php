@include('common.modalHead')
<div class="row">
	<div class="col-sm-12 col-md-6">
		<div class="form-group">
		<label>Tipo</label>
				<select wire:model='tipo' class="form-control">
					<option value="Elegir" selected>Elegir</option>
						<option value="Egreso De Dinero Manual Desde Caja">Egreso</option>
						<option value="Ingreso De Dinero Manual Desde Caja">Ingreso</option>
				</select>
				@error('tipo')<span class="text-danger er">{{ $message}}</span>@enderror
		</div>
	</div>
	<div class="col-sm-12 col-md-6">
		<div class="form-group">
			<label>Monto</label>
			<input type="text" wire:model.lazy="monto" 
			class="form-control"   >
			@error('monto') <span class="text-danger er">{{ $message}}</span>@enderror
		</div>
	</div>
	
	<div class="col-sm-12 col-md-12">
		<div class="form-group">
		<label>Medio De Pago</label>
				<select wire:model='medio_pago' class="form-control">
					<option value="Elegir" selected>Elegir</option>
						<option value="Efectivo">Efectivo</option>
						<option value="Debito">Debito</option>
						<option value="Credito">Credito</option>
				</select>
				@error('medio_pago')<span class="text-danger er">{{ $message}}</span>@enderror
		</div>
	</div>

@include('common.modalFooter')
</div>

