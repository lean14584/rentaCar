 <div class="sidebar-wrapper sidebar-theme">
    <nav id="compactSidebar">
		<ul class="menu-categories">
			{{-- @can('Marcas_Menu') --}}
            <li class="active">
                <a href="{{url('marcas')}}" class="menu-toggle" data-active="true">
                    <div class="base-menu">
                        <div class="base-icons">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-tag"><path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"></path><line x1="7" y1="7" x2="7.01" y2="7"></line></svg>
                        </div>
                        <span>MARCAS</span>
                    </div>
                </a>
            </li>
			{{-- @endcan --}}
			{{-- @can('Autos_Menu') --}}
			<li class="">
                <a href="{{ url('autos') }}" class="menu-toggle" data-active="false">
                    <div class="base-menu">
                        <div class="base-icons">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-tag"><path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"></path><line x1="7" y1="7" x2="7.01" y2="7"></line></svg>
                        </div>
                        <span>AUTOS</span>
                    </div>
                </a>
            </li>
			{{-- @endcan --}}
			{{-- @can('Alquiler_Menu') --}}
			<li class="">
                <a href="{{ url('alquilerListado') }}" class="menu-toggle" data-active="false">
                    <div class="base-menu">
                        <div class="base-icons">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-tag"><path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"></path><line x1="7" y1="7" x2="7.01" y2="7"></line></svg>
                        </div>
                        <span>ALQUILER LISTADO</span>
                    </div>
                </a>
            </li>
			{{-- @endcan --}}
			{{-- @can('Alquiler_Menu') --}}
			<li class="">
                <a href="{{ url('alquiler') }}" class="menu-toggle" data-active="false">
                    <div class="base-menu">
                        <div class="base-icons">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-tag"><path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"></path><line x1="7" y1="7" x2="7.01" y2="7"></line></svg>
                        </div>
                        <span>ALQUILER AUTOS</span>
                    </div>
                </a>
            </li>
			{{-- @endcan
			@can('Proveedores_Menu') --}}
			<li class="">
                <a href="{{ url('proveedores') }}" class="menu-toggle" data-active="false">
                    <div class="base-menu">
                        <div class="base-icons">
                           <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-activity"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="8.5" cy="7" r="4"></circle><polyline points="17 11 19 13 23 9"></polyline></svg>
                       </div>
                       <span>PROVEEDORES</span>
                   </div>
               </a>
           </li> 
		   {{-- @endcan
		   @can('Clientes_Menu') --}}
			<li class="">
                <a href="{{url('clientes')}}" class="menu-toggle" data-active="true">
                    <div class="base-menu">
                        <div class="base-icons">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-users"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                        </div>
                        <span>CLIENTES</span>
                    </div>
                </a>
            </li>
			{{-- @endcan
			@can('Seguros_Menu') --}}
			<li class="">
                <a href="{{ url('pantalla') }}" class="menu-toggle" data-active="false">
                    <div class="base-menu">
                        <div class="base-icons">
                             <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-key"><path d="M21 2l-2 2m-7.61 7.61a5.5 5.5 0 1 1-7.778 7.778 5.5 5.5 0 0 1 7.777-7.777zm0 0L15.5 7.5m0 0l3 3L22 7l-3-3m-3.5 3.5L19 4"></path></svg>
                        </div>
                        <span>CARGAR GASTOS</span>
                    </div>
                </a>
            </li>
			{{-- @endcan
			@can('Usuarios_Menu') --}}
			<li class="">
				<a href="{{ url('usuarios') }}" class="menu-toggle" data-active="false">
					<div class="base-menu">
						<div class="base-icons">
						   <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-users"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
					   </div>
					   <span>USUARIOS</span>
				   </div>
			   </a>
			</li>
			{{-- @endcan
			@can('PermisosRoles_Menu') --}}
			<li class="">
                <a href="{{ url('pantallaRoles') }}" class="menu-toggle" data-active="false">
                    <div class="base-menu">
                        <div class="base-icons">
                             <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-key"><path d="M21 2l-2 2m-7.61 7.61a5.5 5.5 0 1 1-7.778 7.778 5.5 5.5 0 0 1 7.777-7.777zm0 0L15.5 7.5m0 0l3 3L22 7l-3-3m-3.5 3.5L19 4"></path></svg>
                        </div>
                        <span>ROLES/PERMISOS</span>
                    </div>
                </a>
            </li>
			{{-- @endcan
			@can('Caja_Menu') --}}
			<li class="">
				<a href="{{url('caja')}}" class="menu-toggle" data-active="false">
					<div class="base-menu">
						<div class="base-icons">
							<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-dollar-sign"><line x1="12" y1="1" x2="12" y2="23"></line><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>
						</div>
						<span>CAJA</span>
					</div>
				</a>
			</li>
			{{-- @endcan
			@can('Reportes_Menu') --}}
			<li class="">
				<a href="{{url('reportes')}}" class="menu-toggle" data-active="false">
					<div class="base-menu">
						<div class="base-icons">
							<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-grid"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect></svg>
						</div>
						<span>REPORTES</span>
					</div>
				</a>
			</li>
			{{-- @endcan --}}
		</ul>
	</nav>
</div>

<div id="compact_submenuSidebar" class="submenu-sidebar" style="display: none!important">
</div>