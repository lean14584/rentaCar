@include('common.modalHead')
<div class="row">
	
	<div class="col-sm-3">
	<label>Patente Auto</label>
		<div class="input-group">
			<div class="input-group-prepend">
				<span class="input-group-text">
					<span class="fas fa-edit">
						
					</span>
				</span>
			</div>
			<input type="text" wire:model.lazy="patente" class="form-control" placeholder="" maxlength="255">
		</div>
		@error('patente') <span class="text-danger er">{{ $message }}</span> @enderror
	</div>
	<div class="col-sm-3">
	<label>Propiedad Auto</label>
		<div class="form-group">
			<select wire:model.lazy='titular' class="form-control">
				<option value="Elegir">Selecciones</option>
				<option value="Dueño">Dueño</option>
				<option value="Tercero">Tercero</option>
			</select>
		@error('titular') <span class="text-danger er">{{ $message }}</span> @enderror
		</div>
	</div>
	<div class="col-sm-3">
	<label>Kilometros Auto</label>
		<div class="input-group">
			<div class="input-group-prepend">
				<span class="input-group-text">
					<span class="fas fa-edit">
						
					</span>
				</span>
			</div>
			<input type="text" wire:model.lazy="kilometros" class="form-control" placeholder="" maxlength="255">
		</div>
		@error('kilometros') <span class="text-danger er">{{ $message }}</span> @enderror
	</div>
	<div class="col-sm-3">
	<label>Km C. De Aceite</label>
		<div class="input-group">
			<div class="input-group-prepend">
				<span class="input-group-text">
					<span class="fas fa-edit">
						
					</span>
				</span>
			</div>
			<input type="text" wire:model.lazy="cambioAceite" class="form-control" placeholder="" maxlength="255">
		</div>
		@error('cambioAceite') <span class="text-danger er">{{ $message }}</span> @enderror
	</div>
	<div class="col-sm-3">
	<div class="form-group">
			<label>Marca</label>
			<select wire:model='marca_id' class="form-control">
				<option value="Elegir" selected>Elegir</option>
					@foreach($marcas as $marca)
						<option value="{{$marca->id}}">{{$marca->nombre}}</option>
 					@endforeach
			</select>
			@error('marca_id')<span class="text-danger er">{{ $message}}</span>@enderror
		</div>
	</div>
	<div class="col-sm-3">
	<label>Modelo Auto</label>
		<div class="input-group">
			<div class="input-group-prepend">
				<span class="input-group-text">
					<span class="fas fa-edit">
						
					</span>
				</span>
			</div>
			<input type="text" wire:model.lazy="modelo" class="form-control" placeholder="" maxlength="255">
		</div>
		@error('modelo') <span class="text-danger er">{{ $message }}</span> @enderror
	</div>
	<div class="col-sm-3">
	<label>Color Auto</label>
		<div class="input-group">
			<div class="input-group-prepend">
				<span class="input-group-text">
					<span class="fas fa-edit">
						
					</span>
				</span>
			</div>
			<input type="text" wire:model.lazy="color" class="form-control" placeholder="" maxlength="255">
		</div>
		@error('color') <span class="text-danger er">{{ $message }}</span> @enderror
	</div>
	<div class="col-sm-3">
	<label>Combustible</label>
		<div class="form-group">
			<select wire:model.lazy='combustible' class="form-control">
				<option value="Elegir">Selecciones</option>
				<option value="Nafta">Nafta</option>
				<option value="Diesel">Diesel</option>
				<option value="GNC">GNC</option>
			</select>
		@error('combustible') <span class="text-danger er">{{ $message }}</span> @enderror
		</div>
	</div>	
	<div class="col-sm-4">
	<label>Estado Disponible/Alquilado</label>
		<div class="form-group">
			<select wire:model.lazy='estado' class="form-control">
				<option value="Elegir">Selecciones</option>
				<option value="Disponible">Disponible</option>
				<option value="Alquilado">Alquilado</option>
				<option value="No Disponible">No Disponible</option>
			</select>
		@error('estado') <span class="text-danger er">{{ $message }}</span> @enderror
		</div>
	</div>
	<div class="col-sm-4">
	<label>Importe Alquiler</label>
		<div class="input-group">
			<div class="input-group-prepend">
				<span class="input-group-text">
					<span class="fas fa-edit">
						
					</span>
				</span>
			</div>
			<input type="text" wire:model.lazy="importe" class="form-control" placeholder="" maxlength="255">
		</div>
		@error('importe') <span class="text-danger er">{{ $message }}</span> @enderror
	</div>
	<div class="col-sm-4">
	<label>Imagen Auto</label>
		<div class="form-group custom-file">
			<input type="file" class="custom-file-input form-control" wire:model="image" accept="image/x-png, image/gif, image/jpeg" >
			<label  class="custom-file-label">Imágen {{$image}}</label>
			@error('image') <span class="text-danger er">{{ $message }}</span> @enderror
		</div>
	</div>
</div>

@include('common.modalFooter')