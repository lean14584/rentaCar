<div class="row">
	<div class="col-sm-4">
		<div>
			<div class="connect-sorting">
			<h6 class="text-center mb-2">Cliente</h6>
					<div class="connect-sorting-content">
						<div class="card simple-title-task ui-sortaqble-handle">
							<div class="card-body">
								<div class="task-header">
									<div style="background: #3b3f5c; color:white;font-size:15px;text-align:center;padding: 6px;" wire:click="clienteCambio">
								{{$cliente}}</div>
								<input type="hidden" id="idCliente" value="{{$idCliente}}"/>
								</div>
							</div>
						</div>	
					</div>
			</div>
		</div>
	</div>
	<div class="col-sm-4">
		<div>
			<div class="connect-sorting">
				<h6 class="text-center mb-2">Cadena</h6>
				<div class="connect-sorting-content">
						<div class="card simple-title-task ui-sortaqble-handle">
							<div class="card-body">
								<div class="task-header">
									<div style="background: #3b3f5c; color:white;font-size:15px;text-align:center;padding: 6px;" wire:click="clienteCambio">
								{{$cadena}}</div>
								<input type="hidden" id="idCliente" value="{{$idCliente}}"/>
								</div>
							</div>
						</div>	
					</div>
			</div>
		</div>
	</div>
	<div class="col-sm-4">
		<div>
			<div class="connect-sorting">
				<h6 class="text-center mb-2">Matafuego</h6>	
				<div class="connect-sorting-content">
						<div class="card simple-title-task ui-sortaqble-handle">
							<div class="card-body">
								<div class="task-header">
									<div style="background: #3b3f5c; color:white;font-size:15px;text-align:center;padding: 6px;" wire:click="clienteCambio">
								{{$matafuego}}</div>
								<input type="hidden" id="idCliente" value="{{$idCliente}}"/>
								</div>
							</div>
						</div>	
					</div>
			</div>
		</div>
	</div>
	<div class="col-sm-4">
		<div>
			<div class="connect-sorting">
				<h6 class="text-center mb-2">Silla De Bebe</h6>
				<div class="connect-sorting-content">
						<div class="card simple-title-task ui-sortaqble-handle">
							<div class="card-body">
								<div class="task-header">
									<div style="background: #3b3f5c; color:white;font-size:15px;text-align:center;padding: 6px;" wire:click="clienteCambio">
								{{$silla}}</div>
								<input type="hidden" id="idCliente" value="{{$idCliente}}"/>
								</div>
							</div>
						</div>	
					</div>
			</div>
		</div>
	</div>
	<div class="col-sm-4">
		<div>
			<div class="connect-sorting">
				<h6 class="text-center mb-2">Fecha De Inicio</h6>
					<div class="connect-sorting-content">
						<div class="card simple-title-task ui-sortaqble-handle">
							<div class="card-body">
								<div class="task-header">
									<input type="text" wire:model="fechaInicio" class="form-control flatpickr" placeholder="Fecha De Inicio">
									<input type="hidden" wire:model.lazy="importePorDiaCadena" class="form-control" placeholder="" maxlength="255" readonly>
									<input type="hidden" wire:model.lazy="importePorDia" class="form-control" placeholder="" maxlength="255" readonly>
									<input type="hidden" wire:model.lazy="importePorDiaMatafuego" class="form-control" placeholder="" maxlength="255" readonly>
									<input type="hidden" wire:model.lazy="importePorDiaSilla" class="form-control" placeholder="" maxlength="255" readonly>
								</div>
							</div>
						</div>	
					</div>
			</div>
		</div>
	</div>
	<div class="col-sm-4">
		<div>
			<div class="connect-sorting">
				<h6 class="text-center mb-2">Fecha Fin</h6>
					<div class="connect-sorting-content">
						<div class="card simple-title-task ui-sortaqble-handle">
							<div class="card-body">
								<div class="task-header">
									<input type="text" wire:model="fechaFin" class="form-control flatpickr" placeholder="Fecha De Fin">
								</div>
							</div>
						</div>	
					</div>
			</div>
		</div>
	</div>
	<div class="col-sm-4">
		<div>
			<div class="connect-sorting">
				<h6 class="text-center mb-2">Lugar De Retiro</h6>
					<div class="input-group">
						<select wire:model.lazy='lugar_retiro' wire:change='cargarValorAlquilerTotal' class="form-control">
							<option value="Elegir">Selecciones</option>
							<option value="Aeropuerto">Aeropuerto</option>
							<option value="Terminal">Terminal</option>
							<option value="Otro">Otro</option>
						</select>
					</div>
			</div>
		</div>
	</div>
	<div class="col-sm-4">
		<div>
			<div class="connect-sorting">
				<h6 class="text-center mb-2">Lugar De Devolucion</h6>
					<div class="input-group">
					<select wire:model.lazy='lugar_devolucion' class="form-control">
						<option value="Elegir">Selecciones</option>
						<option value="Aeropuerto">Aeropuerto</option>
						<option value="Terminal">Terminal</option>
						<option value="Otro">Otro</option>
					</select>
				</div>
			</div>
		</div>
	</div>
	<div class="col-sm-4">
		<div>
			<div class="connect-sorting">
				<h6 class="text-center mb-2">Metodo De Pago</h6>
					<div class="input-group">
					<select wire:model.lazy='metodo_pago' class="form-control">
						<option value="Elegir">Selecciones</option>
						<option value="Efectivo">Efectivo</option>
						<option value="Debito">Debito</option>
						<option value="Credito">Credito</option>
						<option value="Banco">Banco</option>
						<option value="Cta Cte">Cta Cte</option>
					</select>
				</div>
			</div>
		</div>
	</div>
	<div class="col-sm-12">
		<div>
			<div class="connect-sorting">
				<div class="connect-sorting-content">
						<div class="card simple-title-task ui-sortaqble-handle">
							<div class="card-body">
								<div class="task-header">
									<div style="background: #3b3f5c; color:white;font-size:35px;text-align:center;padding: 6px;">
									TOTAL: ${{number_format($totalVer,2)}}</div>
									<input type="hidden" id="hiddenTotal" value="{{$totalVer}}"/>
								</div>
								<br>
						
							</div>
						</div>	
					</div>
			</div>
		</div>
	</div>
	<div class="col-sm-4">
		<div>
			<div class="connect-sorting">
				<h6 class="text-center mb-2">Entrega</h6>
					<div class="connect-sorting-content">
						<div class="card simple-title-task ui-sortaqble-handle">
							<div class="card-body">
								<div class="task-header">
									<input type="text" wire:model.lazy="entrega" wire:change='cargarValorSaldo' class="form-control" placeholder="" maxlength="255">
								</div>
							</div>
						</div>	
					</div>
			</div>
		</div>
	</div>
	<div class="col-sm-4">
		<div>
			<div class="connect-sorting">
				<h6 class="text-center mb-2">Saldo</h6>
					<div class="connect-sorting-content">
						<div class="card simple-title-task ui-sortaqble-handle">
							<div class="card-body">
								<div class="task-header">
									<input type="text" wire:model.lazy="saldo" class="form-control" placeholder="" maxlength="255" readonly>
								</div>
							</div>
						</div>	
					</div>
			</div>
		</div>
	</div>
	<div class="col-sm-4">
		<div>
			<div class="connect-sorting">
				<h5 class="text-center mb-2">Guardar</h5>
					<div class="connect-sorting-content">
						<div class="card simple-title-task ui-sortaqble-handle">
							<div class="card-body">
								<div class="task-header">
									<center><button wire:click.prevent="saveSale" wire:loading.attr="disabled" class="btn btn-dark btn-md btn-block">GUARDAR F9</button></center>
        						</div>
							</div>
						</div>	
					</div>
			</div>
		</div>
	</div>
</div>

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