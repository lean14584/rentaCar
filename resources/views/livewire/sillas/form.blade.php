@include('common.modalHead')
<div class="row">
	
	<div class="col-sm-4">
	<label>Cadena Nombre</label>
		<div class="input-group">
			<div class="input-group-prepend">
				<span class="input-group-text">
					<span class="fas fa-edit">
						
					</span>
				</span>
			</div>
			<input type="text" wire:model.lazy="nombre" class="form-control" placeholder="" maxlength="255">
		</div>
		@error('nombre') <span class="text-danger er">{{ $message }}</span> @enderror
	</div>
	<div class="col-sm-4">
	<label>Estado</label>
		<div class="form-group">
			<select wire:model.lazy='estado' class="form-control">
				<option value="Elegir">Selecciones</option>
				<option value="Libre">Libre</option>
				<option value="Alquilada">Alquilada</option>
			</select>
			</select>
		@error('estado') <span class="text-danger er">{{ $message }}</span> @enderror
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