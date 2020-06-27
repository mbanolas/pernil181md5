<body class="hidden-sn mdb-skin">

<!--Double navigation-->
<header>
    <!-- Sidebar navigation -->
    <div id="slide-out" class="side-nav sn-bg-4">
        <ul class="custom-scrollbar">
            <!-- Logo -->
            <li>
                <div class="logo-wrapper waves-light">
                    <a href="#"><img src="<?php echo base_url() ?>/images/pernil181.jpeg" class="img-fluid flex-center"></a>
                </div>
            </li>
            <!--/. Logo -->

            <!--Search Form-->
            <li>
                <form class="search-form" role="search">
                    <div class="form-group md-form mt-0 pt-1 waves-light">
                        <input type="text" class="form-control" placeholder="Search">
                    </div>
                </form>
            </li>
            <!--/.Search Form-->
            <!-- Side navigation links -->
            <li>
                <ul class="collapsible collapsible-accordion">
                    <li><a class="collapsible-header waves-effect arrow-r"><i class="fas fa-chevron-right"></i> Subir archivos<i class="fas fa-angle-down rotate-icon"></i></a>
                        <div class="collapsible-body">
                            <ul>
                                <li><a href="<?php echo $base_url_anterior ?>/upload/do_upload" class="waves-effect">Boka</a></li>
                                <li><a href="<?php echo $base_url_anterior ?>/upload/do_upload" class="waves-effect">Prestashop</a></li>
                                <li class="dropdown-divider"></li>
                                <li><a href="<?php echo $base_url_anterior ?>/upload/do_upload" class="waves-effect">Tracking</a></li>
                                <li class="dropdown-divider"></li>
                                <li><a href="<?php echo $base_url_anterior ?>/upload/do_upload_costes_transportes" class="waves-effect">Costes transportes</a></li>
                            </ul>
                        </div>
                    </li>
                    <li><a class="collapsible-header waves-effect arrow-r"><i class="fas fa-address-book"></i> Directorios<i class="fas fa-angle-down rotate-icon"></i></a>
                        <div class="collapsible-body">
                            <ul>
                                <li><a href="<?php echo $base_url_anterior ?>/gestionTablas/proveedores" class="waves-effect">Proveedores</a>
                                <li><a href="<?php echo $base_url_anterior ?>/gestionTablas/acreedores" class="waves-effect">Acreedores</a>
                                <li><a href="<?php echo $base_url_anterior ?>/gestionTablas/clientes" class="waves-effect">Clientes</a>
                            </ul>
                        </div>
                    </li>
                    <li><a class="collapsible-header waves-effect arrow-r"><i class="fab fa-product-hunt"></i> Productos<i class="fas fa-angle-down rotate-icon"></i></a>
                        <div class="collapsible-body">
                            <ul>
                            <li><a href="<?php echo base_url() ?>/productos/productos" class="waves-effect">Productos</a></li>
                            <li><a href="<?php echo $base_url_anterior ?>/upload/do_upload" class="waves-effect">Packs</a></li>
                            <li><a href="<?php echo $base_url_anterior ?>/upload/do_upload" class="waves-effect">Envases y embalajes</a></li>
                            <li><a href="<?php echo $base_url_anterior ?>/upload/do_upload" class="waves-effect">Familias</a></li>
                            <li><a href="<?php echo $base_url_anterior ?>/upload/do_upload" class="waves-effect">Grupos</a></li>
                            <li><a href="<?php echo $base_url_anterior ?>/upload/do_upload" class="waves-effect">Relacionar grupos y familias</a></li>
                            </ul>
                        </div>
                    </li>
                    <li><a class="collapsible-header waves-effect arrow-r"><i class="fas fa-shopping-basket"></i> Tienda<i class="fas fa-angle-down rotate-icon"></i></a>
                        <div class="collapsible-body">
                            <ul>
                                <li><a href="<?php echo $base_url_anterior ?>/upload/do_upload" class="waves-effect">Ventas tickets</a></li>
                                <li><a href="<?php echo $base_url_anterior ?>/upload/do_upload" class="waves-effect">Facturas tickets a clientes</a></li>
                                <li><a href="<?php echo $base_url_anterior ?>/upload/do_upload" class="waves-effect">Cambio forma pago ticket</a></li>
                                <li><a href="<?php echo $base_url_anterior ?>/upload/do_upload" class="waves-effect">Cambio nombre cliente</a></li>
                                <li><a href="<?php echo $base_url_anterior ?>/upload/do_upload" class="waves-effect">Ventas directas</a></li>
                            <li><a href="<?php echo $base_url_anterior ?>/upload/do_upload" class="waves-effect">Entrada transporte tienda</a></li>
                            <li><a href="<?php echo $base_url_anterior ?>/upload/do_upload" class="waves-effect">Conversiones</a></li>
                            <li><a href="<?php echo $base_url_anterior ?>/upload/do_upload" class="waves-effect">Cierre caja</a></li>
                            <li><a href="<?php echo $base_url_anterior ?>/upload/do_upload" class="waves-effect">Informe caja</a></li>
                            <li><a href="<?php echo $base_url_anterior ?>/upload/do_upload" class="waves-effect">Inicialización caja</a></li>
                            
                            </ul>
                        </div>
                    </li>
                    <li><a class="collapsible-header waves-effect arrow-r"><i class="fas fa-globe"></i></i>Online<i class="fas fa-angle-down rotate-icon"></i></a>
                        <div class="collapsible-body">
                            <ul>
                            <li><a href="<?php echo $base_url_anterior ?>/upload/do_upload" class="waves-effect">Ventas online</a></li>
                            <li><a href="<?php echo $base_url_anterior ?>/upload/do_upload" class="waves-effect">Envío tracking online</a></li>
                            <li><a href="<?php echo $base_url_anterior ?>/upload/do_upload" class="waves-effect">Registros tracking online</a></li>                           
                            </ul>
                        </div>
                    </li>
                    <li><a class="collapsible-header waves-effect arrow-r"><i class="far fa-money-bill-alt"></i> Compras<i class="fas fa-angle-down rotate-icon"></i></a>
                        <div class="collapsible-body">
                            <ul>
                            <li><a href="<?php echo $base_url_anterior ?>/upload/do_upload" class="waves-effect">Pedidos</a></li>
                            <li><a href="<?php echo $base_url_anterior ?>/upload/do_upload" class="waves-effect">Albaranes</a></li>
                            <li><a href="<?php echo $base_url_anterior ?>/upload/do_upload" class="waves-effect">Facturas proveedores</a></li>                           
                            <li><a href="<?php echo $base_url_anterior ?>/upload/do_upload" class="waves-effect">Facturas acreedores</a></li>                           
                            <li><a href="<?php echo $base_url_anterior ?>/upload/do_upload" class="waves-effect">Transformaciones</a></li>                           
                            </ul>
                        </div>
                    </li>
                    <li><a class="collapsible-header waves-effect arrow-r"><i class="fas fa-warehouse"></i> Stocks<i class="fas fa-angle-down rotate-icon"></i></a>
                        <div class="collapsible-body">
                            <ul>
                            <li><a href="<?php echo $base_url_anterior ?>/upload/do_upload" class="waves-effect">Stocks totales</a></li>
                            <li><a href="<?php echo $base_url_anterior ?>/upload/do_upload" class="waves-effect">Stocks fechas caducidad</a></li>
                            <li><a href="<?php echo $base_url_anterior ?>/upload/do_upload" class="waves-effect">Inventario entrada</a></li>
                            <li><a href="<?php echo $base_url_anterior ?>/upload/do_upload" class="waves-effect">Resumenes stocks grupos familias</a></li>                           
                            </ul>
                        </div>
                    </li>
                    <li><a class="collapsible-header waves-effect arrow-r"><i class="far fa-chart-bar"></i> Estadísticas<i class="fas fa-angle-down rotate-icon"></i></a>
                        <div class="collapsible-body">
                            <ul>
                            <li><a href="<?php echo $base_url_anterior ?>/upload/do_upload" class="waves-effect">Evolución PVP productos</a></li>
                            <li><a href="<?php echo $base_url_anterior ?>/upload/do_upload" class="waves-effect">Evolución ventas mensuales producto</a></li>
                            <li><a href="<?php echo $base_url_anterior ?>/upload/do_upload" class="waves-effect">Inventario entrada</a></li>
                            <li><a href="<?php echo $base_url_anterior ?>/upload/do_upload" class="waves-effect">Ventas último día</a></li>                           
                            </ul>
                        </div>
                    </li>
                    <li><a class="collapsible-header waves-effect arrow-r"><i class="fas fa-adjust"></i> Definiciones<i class="fas fa-angle-down rotate-icon"></i></a>
                        <div class="collapsible-body">
                            <ul>
                                <li><a href="<?php echo $base_url_anterior ?>/upload/do_upload" class="waves-effect">IVAs</a></li>
                                <li><a href="<?php echo $base_url_anterior ?>/upload/do_upload" class="waves-effect">Formas pagos clientes</a></li>
                                <li><a href="<?php echo $base_url_anterior ?>/upload/do_upload" class="waves-effect">Formas pagos acreedores</a></li>
                                <li><a href="<?php echo $base_url_anterior ?>/upload/do_upload" class="waves-effect">Conceptos acreedores</a></li>
                                <li><a href="<?php echo $base_url_anterior ?>/upload/do_upload" class="waves-effect">Empresas online</a></li>
                            </ul>
                        </div>
                    </li>
                </ul>
            </li>
            <!--/. Side navigation links -->
        </ul>
        <div class="sidenav-bg mask-strong"></div>
    </div>
    <!--/. Sidebar navigation -->
    <!-- Navbar -->
    <nav class="navbar fixed-top navbar-toggleable-md navbar-expand-lg scrolling-navbar double-nav">
        <!-- SideNav slide-out button -->
        <div class="float-left">
            <a href="#" data-activates="slide-out" class="button-collapse"><i class="fas fa-bars"></i></a>
        </div>
        <!-- Breadcrumb-->
        <div class="breadcrumb-dn mr-auto">
            <p>Programa de gestión Pernil 181</p>
        </div>
        <ul class="nav navbar-nav nav-flex-icons ml-auto">
            <li class="nav-item">
                <a href="<?php echo base_url() ?>index.php/productos/productos/1" class="nav-link"><i class="fab fa-product-hunt"></i> <span class="clearfix d-none d-sm-inline-block">Productos</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link"><i class="fas fa-cash-register"></i> <span class="clearfix d-none d-sm-inline-block">Cierre caja</span></a>
            </li>
            <li class="nav-item">
                <a href="<?php echo $base_url_anterior ?>/pernil181" class="nav-link"><i class="fas fa-hand-point-left"></i> <span class="clearfix d-none d-sm-inline-block">Programa anterior</span></a>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Otros
                </a>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownMenuLink">
                    <a class="dropdown-item" href="#">Subida ventas tiendaBoka</a>
                    <a class="dropdown-item" href="#">Subida ventas online</a>
                    <a class="dropdown-item" href="#">Pendiente 3</a>
                </div>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?php echo base_url() ?>index.php/pernil181" ><i class="fas fa-power-off mdb-gallery-view-icon"></i> <span class="clearfix d-none d-sm-inline-block">Salir</span></a>
            </li>
        </ul>
    </nav>
    <!-- /.Navbar -->
</header>
<!--/.Double navigation-->

<!--Main Layout-->
<!-- <main>
    <div class="container-fluid">
        <h2 class="pt-5 pl-5">Gestión tabla productos</h2>
        <br>
        <h2 class="pl-5">Hola <?php  ?></h2>
        <h5 class=" pl-5">El menú lateral está oculto. Pulsar el icono "hamburgesa" de la esquina superior izquierda para abrirlo</h5>
        <h5 class="pl-5">En el menú de la barra superior figuran accesos frecuentes</h5>
        <div style="height: 2000px"></div>
    </div>
</main> -->
<!--Main Layout-->
<script>
    $(document).ready(function() {
        // SideNav Initialization
        $(".button-collapse").sideNav();
    });
</script>

</body>