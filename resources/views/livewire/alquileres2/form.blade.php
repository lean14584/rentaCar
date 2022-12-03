@include('common.modalHead')
<div class="row">
		
	<div class="col-sm-4">
	<div class="form-group">
			<label>Auto</label>
			<select wire:model='idAuto' wire:change='cargarValorAlquiler' class="form-control">
				<option value="Elegir" selected>Elegir</option>
					@foreach($autos as $auto)
						<option value="{{$auto->id}}">{{$auto->patente}}</option>
 					@endforeach
			</select>
			@error('idAuto')<span class="text-danger er">{{ $message}}</span>@enderror
		</div>
	</div>
	<div class="col-sm-4">
	<div class="form-group">
			<label>Importe Por Dia</label>
			<div class="input-group">
			<input type="text" wire:model.lazy="importePorDia" class="form-control" placeholder="" maxlength="255" readonly>
			<input type="hidden" wire:model.lazy="importePorDiaCadena" class="form-control" placeholder="" maxlength="255" readonly>
			<input type="hidden" wire:model.lazy="importePorDiaMatafuego" class="form-control" placeholder="" maxlength="255" readonly>
			<input type="hidden" wire:model.lazy="importePorDiaSilla" class="form-control" placeholder="" maxlength="255" readonly>
		</div>
		@error('importePorDia')<span class="text-danger er">{{ $message}}</span>@enderror
	</div>
	</div>
	<div class="col-sm-4">
	<div class="form-group">
			<label>Cliente</label>
			<select wire:model='idCliente' class="form-control">
				<option value="Elegir" selected>Elegir</option>
					@foreach($clientes as $cliente)
						<option value="{{$cliente->id}}">{{$cliente->nombre}}</option>
 					@endforeach
			</select>
			@error('idCliente')<span class="text-danger er">{{ $message}}</span>@enderror
		</div>
	</div>
	<div class="col-sm-4">
	<div class="form-group">
			<label>Cadenas</label>
			<select wire:model='idCadena' wire:change='cargarValorCadenas' class="form-control">
				<option value="Elegir" selected>Elegir</option>
					@foreach($cadenas as $cadena)
						<option value="{{$cadena->id}}">{{$cadena->nombre}}</option>
 					@endforeach
			</select>
			@error('idCadena')<span class="text-danger er">{{ $message}}</span>@enderror
		</div>
	</div>
	<div class="col-sm-4">
	<div class="form-group">
			<label>Matafuegos</label>
			<select wire:model='idMatafuego' wire:change='cargarValorMatafuegos' class="form-control">
				<option value="Elegir" selected>Elegir</option>
					@foreach($matafuegos as $matafuego)
						<option value="{{$matafuego->id}}">{{$matafuego->nombre}}</option>
 					@endforeach
			</select>
			@error('idMatafuego')<span class="text-danger er">{{ $message}}</span>@enderror
		</div>
	</div>
	<div class="col-sm-4">
	<div class="form-group">
			<label>Silla De Bebe</label>
			<select wire:model='idSilla' wire:change='cargarValorSillas' class="form-control">
				<option value="Elegir" selected>Elegir</option>
					@foreach($sillas as $silla)
						<option value="{{$silla->id}}">{{$silla->nombre}}</option>
 					@endforeach
			</select>
			@error('idSilla')<span class="text-danger er">{{ $message}}</span>@enderror
		</div>
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
	<label>Lugar Retiro</label>
		<div class="input-group">
			<select wire:model.lazy='lugar_retiro' wire:change='cargarValorAlquilerTotal' class="form-control">
				<option value="Elegir">Selecciones</option>
				<option value="Aeropuerto">Aeropuerto</option>
				<option value="Terminal">Terminal</option>
				<option value="Otro">Otro</option>
			</select>
		</div>
		@error('lugar_retiro') <span class="text-danger er">{{ $message }}</span> @enderror
	</div>
	<div class="col-sm-4">
	<label>Lugar De Devolucion</label>
		<div class="input-group">
			<select wire:model.lazy='lugar_devolucion' class="form-control">
				<option value="Elegir">Selecciones</option>
				<option value="Aeropuerto">Aeropuerto</option>
				<option value="Terminal">Terminal</option>
				<option value="Otro">Otro</option>
			</select>
		</div>
		@error('lugar_devolucion') <span class="text-danger er">{{ $message }}</span> @enderror
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
	<label>Total</label>
		<div class="input-group">
			<div class="input-group-prepend">
				<span class="input-group-text">
					<span class="fas fa-edit">
						
					</span>
				</span>
			</div>
			<input type="text" wire:model.lazy="total" class="form-control" placeholder="" maxlength="255" readonly>
		</div>
		@error('total') <span class="text-danger er">{{ $message }}</span> @enderror
	</div>
	<div class="col-sm-4">
	<label>Entrega</label>
		<div class="input-group">
			<div class="input-group-prepend">
				<span class="input-group-text">
					<span class="fas fa-edit">
						
					</span>
				</span>
			</div>
			<input type="text" wire:model.lazy="entrega" wire:change='cargarValorSaldo' class="form-control" placeholder="" maxlength="255">
		</div>
		@error('entrega') <span class="text-danger er">{{ $message }}</span> @enderror
	</div>
	<div class="col-sm-4">
	<label>Saldo</label>
		<div class="input-group">
			<div class="input-group-prepend">
				<span class="input-group-text">
					<span class="fas fa-edit">
						
					</span>
				</span>
			</div>
			<input type="text" wire:model.lazy="saldo" class="form-control" placeholder="" maxlength="255" readonly>
		</div>
		@error('saldo') <span class="text-danger er">{{ $message }}</span> @enderror
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