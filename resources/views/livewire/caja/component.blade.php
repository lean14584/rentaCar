<div class="row sales layout-top-spacing">

    <div class="col-sm-12">
        <div class="widget widget-chart-one">
            <div class="widget-heading">
                <h4 class="card-title">
                    <b>{{$componentName}}</b> <b style="color:red;"></b>
                </h4>
				<ul class="tabs tab-pills">
					<li>
                        <a href="javascript:void(0)" title="Agregar Movimiento De Caja" class="tabmenu bg-dark" data-toggle="modal" data-target="#theModal">Agregar Movimiento</a>
                    </li>
                </ul>
            </div>
			<div class="widget-heading">
                <h4 class="card-title">
                    
                </h4>
				<div class="connect-sorting bg-muted">
					<h3 class="text-black">Total En Caja: ${{number_format($sumaVista - $restaVista,2)}}</h3>
                </div>
            </div>
			@include('common.searchbox')
			<div class="widget-content">
				<div class="table-responsive">
				@if(count($data) > 0)
                    <table class="table table-bordered table striped mt-1">
                        <thead class="text-white" style="background: #3B3F5C">
                            <tr>
                                <th class="table-th text-white text-center">FECHA</th>
                                <th class="table-th text-white text-center">AUTO</th>
								<th class="table-th text-white text-center">USUARIO</th>
                                <th class="table-th text-white text-center">DETALLE</th>
								<th class="table-th text-white text-center">MEDIO DE PAGO</th>
                                <th class="table-th text-white text-center">ENTRADA</th>
								<th class="table-th text-white text-center">SALIDA</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($data as $r)
                            <tr>
                                <td class="text-center"><h6>{{$r->created_at}}</h6></td>
                                <td class="text-center"><h6>{{$r->patenteVer}}</h6></td>
								<td class="text-center"><h6>{{$r->nombre}}</h6></td>
								<td class="text-center"><h6>{{$r->descripcion}}</h6></td>
								<td class="text-center"><h6>{{$r->metodo_pago}}</h6></td>
								<td class="text-center"><h6 style="color:green;font-weight: bold;">${{number_format($r->entrada,2)}}</h6></td>
								<td class="text-center"><h6 style="color:red;font-weight: bold;">${{number_format($r->salida,2)}}</h6></td>
							</tr>
							@endforeach
						</tbody>
						<tfoot class="text-white" style="background: #3B3F5C">
                            
		                </tfoot>
					</table>
					@else
						<h5 class="text-center text-muted">No Hay Movimientos De Caja Cargados</h5>
					@endif
				{{$data->links()}}
				</div>
			</div>
		</div>
	</div>
@include('livewire.caja.form')
</div>

<script>
    document.addEventListener('DOMContentLoaded', function(){
		window.livewire.on('ctacte-added', msg => {
			$('#theModal').modal('hide')
			 noty(msg)
		})
		//eventos
       // window.livewire.on('detalle-compra', Msg =>{
        //    $('#modalDetailsCtaCte').modal('show')
        //})
	
    });

</script>