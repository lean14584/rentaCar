@include('common.modalHead')
<div class="row">
		
	<div class="col-sm-4">
	<div class="form-group">
			<label>Auto</label>
			<select wire:model='idAuto' class="form-control">
				<option value="Elegir" selected>Elegir</option>
					@foreach($autos as $auto)
						<option value="{{$auto->id}}">{{$auto->patente}}</option>
 					@endforeach
			</select>
			@error('idAuto')<span class="text-danger er">{{ $message}}</span>@enderror
		</div>
	</div>
	@if($idAuto!="Elegir")
	<div class="col-sm-4">
	<label>Propiedad Auto</label>
		<div class="form-group">
			<select wire:model.lazy='nombre' wire:change='cargarValores' class="form-control">
				<option value="Elegir">Selecciones</option>
				<option value="Cambio De Aceite">Cambio De Aceite</option>
				<option value="Cambio De Cubiertas">Cambio De Cubiertas</option>
				<option value="Combustible">Combustible</option>
				<option value="Multas">Multas</option>
				<option value="Mantenimiento">Mantenimiento</option>
				<option value="Arreglos">Arreglos</option>
			</select>
		@error('nombre') <span class="text-danger er">{{ $message }}</span> @enderror
		</div>
	</div>
	@endif
	<div class="col-sm-4">
	<div class="form-group">
			<label>Proveedor</label>
			<select wire:model='idProveedor' class="form-control">
				<option value="Elegir" selected>Elegir</option>
					@foreach($proveedores as $proveedor)
						<option value="{{$proveedor->id}}">{{$proveedor->nombre}}</option>
 					@endforeach
			</select>
			@error('idProveedor')<span class="text-danger er">{{ $message}}</span>@enderror
		</div>
	</div>
	<div class="col-sm-4">
	<label>Medio De Pago</label>
		<div class="form-group">
			<select wire:model.lazy='metodo_pago' class="form-control">
				<option value="Elegir">Selecciones</option>
				<option value="Efectivo">Efectivo</option>
				<option value="Debito">Debito</option>
				<option value="Credito">Credito</option>
				<option value="Banco">Banco</option>
				<option value="Cta Cte">Cta Cte</option>
			</select>
		@error('metodo_pago') <span class="text-danger er">{{ $message }}</span> @enderror
		</div>
	</div>
	<div class="col-sm-4">
	<label>Km Actuales</label>
		<div class="input-group">
			<div class="input-group-prepend">
				<span class="input-group-text">
					<span class="fas fa-edit">
						
					</span>
				</span>
			</div>
			<input type="text" wire:model.lazy="kmActual" class="form-control" placeholder="" maxlength="255">
		</div>
		@error('kmActual') <span class="text-danger er">{{ $message }}</span> @enderror
	</div>
	<div class="col-sm-4">
	<label>Km Proximo</label>
		<div class="input-group">
			<div class="input-group-prepend">
				<span class="input-group-text">
					<span class="fas fa-edit">
						
					</span>
				</span>
			</div>
			<input type="text" wire:model.lazy="kmProximo" class="form-control" placeholder="" maxlength="255">
		</div>
		@error('kmProximo') <span class="text-danger er">{{ $message }}</span> @enderror
	</div>
	<div class="col-sm-4">
        <label>Fecha Inicio</label>
			<div class="form-group">
				<input type="text" wire:model="fechaInicio" class="form-control flatpickr" placeholder="Click para elegir">
            </div>
    </div>
	<div class="col-sm-4">
        <label>Fecha Fin</label>
			<div class="form-group">
				<input type="text" wire:model="fechaFin" class="form-control flatpickr" placeholder="Click para elegir">
            </div>
    </div>
	<div class="col-sm-4">
	<label>Valor</label>
		<div class="input-group">
			<div class="input-group-prepend">
				<span class="input-group-text">
					<span class="fas fa-edit">
						
					</span>
				</span>
			</div>
			<input type="text" wire:model.lazy="valor" class="form-control" placeholder="" maxlength="255">
		</div>
		@error('valor') <span class="text-danger er">{{ $message }}</span> @enderror
	</div>
	
</div>

@include('common.modalFooter')
<script>
    document.addEventListener('DOMContentLoaded', function(){
flatpickr(document.getElementsByClassName('flatpickr'),{
            enableTime: false,
            dateFormat: 'Y-m-d',
            locale: {
                firstDayofWeek: 1,
                weekdays: {
                    shorthand: ["Dom", "Lun", "Mar", "Mié", "Jue", "Vie", "Sáb"],
                    longhand: [
                    "Domingo",
                    "Lunes",
                    "Martes",
                    "Miércoles",
                    "Jueves",
                    "Viernes",
                    "Sábado",
                    ],
                },
                months: {
                    shorthand: [
                    "Ene",
                    "Feb",
                    "Mar",
                    "Abr",
                    "May",
                    "Jun",
                    "Jul",
                    "Ago",
                    "Sep",
                    "Oct",
                    "Nov",
                    "Dic",
                    ],
                    longhand: [
                    "Enero",
                    "Febrero",
                    "Marzo",
                    "Abril",
                    "Mayo",
                    "Junio",
                    "Julio",
                    "Agosto",
                    "Septiembre",
                    "Octubre",
                    "Noviembre",
                    "Diciembre",
                    ],
                },

            }

        })
	})
</script>		