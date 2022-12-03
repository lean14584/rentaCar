@include('common.modalHead')
<div class="row">
	<div class="col-sm-12 col-md-8">
		<div class="form-group">
			<label>Nombre</label>
			<input type="text" wire:model.lazy="nombre" class="form-control" placeholder="Ej: Leandro Rebolloso">
			@error('nombre')<span class="text-danger er">{{ $message}}</span>@enderror
		</div>
	</div>
	<div class="col-sm-12 col-md-4">
		<div class="form-group">
			<label>Inicio De Actividad</label>
			<input type="text" wire:model.lazy="inicio" class="form-control" placeholder="" maxlength="10">
			@error('inicio')<span class="text-danger er">{{ $message}}</span>@enderror
		</div>
	</div>
	<div class="col-sm-12 col-md-6">
		<div class="form-group">
			<label>Cuit</label>
			<input type="text" wire:model.lazy="cuit" class="form-control" placeholder="">
			@error('cuit')<span class="text-danger er">{{ $message}}</span>@enderror
		</div>
	</div>
	<div class="col-sm-12 col-md-6">
		<div class="form-group">
			<label>Ingresos Brutos</label>
			<input type="text" wire:model.lazy="brutos" class="form-control" placeholder="">
			@error('brutos')<span class="text-danger er">{{ $message}}</span>@enderror
		</div>
	</div>
	<div class="col-sm-12 col-md-6">
		<div class="form-group">
			<label>Localidad</label>
			<input type="text" wire:model.lazy="localidad" class="form-control" placeholder="Ej: San Rafael">
			@error('localidad')<span class="text-danger er">{{ $message}}</span>@enderror
		</div>
	</div>
	<div class="col-sm-12 col-md-6">
		<div class="form-group">
			<label>Telefono</label>
			<input type="text" wire:model.lazy="telefono" class="form-control" placeholder="Ej: 2604565656">
			@error('telefono')<span class="text-danger er">{{ $message}}</span>@enderror
		</div>
	</div>
	<div class="col-sm-12 col-md-6">
		<div class="form-group">
			<label>Direccion</label>
			<input type="text" wire:model.lazy="direccion" class="form-control" placeholder="Ej: El Libertador 123">
			@error('direccion')<span class="text-danger er">{{ $message}}</span>@enderror
		</div>
	</div>
	<div class="col-sm-12 col-md-6">
		<div class="form-group">
			<label>Correo</label>
			<input type="text" wire:model.lazy="correo" class="form-control" placeholder="Ej: nada@gmail.com">
			@error('correo')<span class="text-danger er">{{ $message}}</span>@enderror
		</div>
	</div>
	<div class="col-sm-12 col-md-12">
	<label>Imagen Proveedor</label>
		<div class="form-group custom-file">
			<input type="file" class="custom-file-input form-control" wire:model="image" accept="image/x-png, image/gif, image/jpeg" >
			<label  class="custom-file-label">Imágen {{$image}}</label>
			@error('image') <span class="text-danger er">{{ $message }}</span> @enderror
		</div>
	</div>
@include('common.modalFooter')
</div>