<div class="row sales layout-top-spacing">

    <div class="col-sm-12">
        <div class="widget widget-chart-one">
            <div class="widget-heading">
                <h4 class="card-title">
                    <b>{{$componentName}}</b> <b style="color:red;">{{$cliente}} {{$data}}</b>
                </h4>
                <ul class="tabs tab-pills">
					<li>
						<a href="{{ url('clientes') }}" class="tabmenu bg-dark" >Volver</a>
					</li>
                    <li>
                        <a href="javascript:void(0)" class="tabmenu bg-dark" data-toggle="modal" data-target="#theModal">Agregar Movimiento</a>
                    </li>
                </ul>
            </div>
			<div class="widget-heading">
                <h4 class="card-title">
                    
                </h4>
				<div class="connect-sorting bg-muted">
					<h3 class="text-black">Saldo: ${{number_format($sumaVista - $restaVista,2)}}</h3>
                </div>
            </div>
            @include('common.searchbox')
            <div class="widget-content">
				<div class="table-responsive">
				@if(count($data) > 0)
                    <table class="table table-bordered table striped mt-1">
                        <thead class="text-white" style="background: #3B3F5C">
                            <tr>
                                <th class="table-th text-white text-center">DESCRIPCION</th>
                                <th class="table-th text-white text-center">NUMERO VENTA</th>
                                <th class="table-th text-white text-center">DEBE</th>
								<th class="table-th text-white text-center">HABER</th>
                                <th class="table-th text-white text-center">METODO DE PAGO</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($data as $r)
						    <tr>
                                <td class="text-center"><h6>{{$r->descripcion}}</h6></td>
                                <td class="text-center"><h6 wire:click.prevent="getDetailsCtaCte({{$r->id_venta}})">{{$r->id_venta}}</h6></td>
								@if($r->descripcion=="Venta En Cta Cte")
                                <td class="text-center"><h6 style="color:red;font-weight: bold;">{{number_format($r->importe,2)}}</h6></td>
								@else
								<td class="text-center"><h6>0</h6></td>
								@endif
								@if($r->descripcion=="Orden De Pago" OR $r->descripcion=="Notas de Crédito")
                                <td class="text-center"><h6 style="color:green;font-weight: bold;">{{number_format($r->importe,2)}}</h6></td>
								@else
								<td class="text-center"><h6>0</h6></td>
								@endif
								<td class="text-center"><h6>{{$r->metodo_pago}}</h6></td>
                            </tr>
							@endforeach
						</tbody>
					</table>
					@else
						<h5 class="text-center text-muted">No Hay Movimientos En La Cta Cte</h5>
					@endif
				{{$data->links()}}
				</div>
			</div>
		</div>
	</div>
@include('livewire.clientesCtaCte.form')
@include('livewire.clientesCtaCte.sales-detail')
</div>

<script>
    document.addEventListener('DOMContentLoaded', function(){
		window.livewire.on('ctacte-added', msg => {
			$('#theModal').modal('hide')
			 noty(msg)
		})
		//eventos
        window.livewire.on('detalle-compra', Msg =>{
            $('#modalDetailsCtaCte').modal('show')
        })
	
    });

</script>