@include('common.modalHead')
<div class="row">

	<div class="col-sm-12 col-md-4">
		<div class="form-group">
			<label>Nombre</label>
			<input type="text" wire:model.lazy="nombre" class="form-control" placeholder="Ej: Juan Perez">
			@error('nombre')<span class="text-danger er">{{ $message}}</span>@enderror
		</div>
	</div>
	<div class="col-sm-12 col-md-4">
		<div class="form-group">
			<label>Direccion</label>
			<input type="text" wire:model.lazy="direccion" class="form-control" placeholder="Ej: El Libertador 123">
			@error('direccion')<span class="text-danger er">{{ $message}}</span>@enderror
		</div>
	</div>
	<div class="col-sm-12 col-md-4">
		<div class="form-group">
			<label>Provincia</label>
			<input type="text" wire:model.lazy="provincia" class="form-control" placeholder="Ej: Mendoza">
			@error('provincia')<span class="text-danger er">{{ $message}}</span>@enderror
		</div>
	</div>
	<div class="col-sm-12 col-md-4">
		<div class="form-group">
			<label>Ciudad</label>
			<input type="text" wire:model.lazy="ciudad" class="form-control" placeholder="Ej: San Rafael">
			@error('ciudad')<span class="text-danger er">{{ $message}}</span>@enderror
		</div>
	</div>
	<div class="col-sm-12 col-md-4">
		<div class="form-group">
			<label>Tipo De Documento</label>
			<select wire:model='tipo_documento' class="form-control">
				  <option value="0">Seleccionar tipo documento</option>
                  <option value="96">DNI</option>
                  <option value="80">CUIT</option>
                  <option value="86">CUIL</option>
                  <option value="87">CDI</option>
                  <option value="89">LE</option>
                  <option value="90">LC</option>
                  <option value="92">En trámite</option>
                  <option value="93">Acta nacimiento</option>
                  <option value="94">Pasaporte</option>
                  <option value="91">CI extranjera</option>
                  <option value="99">Otros</option>	
			</select>
			@error('tipo_documento')<span class="text-danger er">{{ $message}}</span>@enderror
		</div>
	</div>
	<div class="col-sm-12 col-md-4">
		<div class="form-group">
			<label>Documento</label>
			<input type="text" wire:model.lazy="documento" class="form-control" placeholder="xxxxxxxx">
			@error('documento')<span class="text-danger er">{{ $message}}</span>@enderror
		</div>
	</div>
	<div class="col-sm-12 col-md-4">
		<div class="form-group">
			<label>Telefono</label>
			<input type="text" wire:model.lazy="telefono" class="form-control" placeholder="Ej: 2604565656">
			@error('telefono')<span class="text-danger er">{{ $message}}</span>@enderror
		</div>
	</div>
	<div class="col-sm-12 col-md-4">
		<div class="form-group">
			<label>Correo</label>
			<input type="text" wire:model.lazy="correo" class="form-control" placeholder="Ej: nada@gmail.com">
			@error('correo')<span class="text-danger er">{{ $message}}</span>@enderror
		</div>
	</div>
	<div class="col-sm-12 col-md-4">
		<div class="form-group">
			<label>Licencia</label>
			<input type="text" wire:model.lazy="licencia" class="form-control" placeholder="Ej: xxxxxxxx">
			@error('licencia')<span class="text-danger er">{{ $message}}</span>@enderror
		</div>
	</div>
	<div class="col-sm-12 col-md-4">
		<div class="form-group">
			<label>Vencimiento Licencia</label>
			<input type="text" wire:model.lazy="vencimiento_licencia" class="form-control" placeholder="Ej: 2022-12-12">
			@error('vencimiento_licencia')<span class="text-danger er">{{ $message}}</span>@enderror
		</div>
	</div>
	<div class="col-sm-12 col-md-8">
	<label>Imagen Cliente</label>
		<div class="form-group custom-file">
			<input type="file" class="custom-file-input form-control" wire:model="image" accept="image/x-png, image/gif, image/jpeg" >
			<label  class="custom-file-label">Imágen {{$image}}</label>
			@error('image') <span class="text-danger er">{{ $message }}</span> @enderror
		</div>
	</div>
@include('common.modalFooter')
</div>