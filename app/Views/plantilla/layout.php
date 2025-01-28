<?php
if(!session('idusuario')){
    header('location: '.base_url().'');
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title><?=$title?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="title" content="Sistema ANDAR">
    <meta name="author" content="ColorlibHQ">
    <meta name="description" content="">
    <meta name="keywords" content="">

    <link rel="apple-touch-icon" sizes="57x57" href="<?=base_url('public')?>/favicon/apple-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="60x60" href="<?=base_url('public')?>/favicon/apple-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="72x72" href="<?=base_url('public')?>/favicon/apple-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76" href="<?=base_url('public')?>/favicon/apple-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="114x114" href="<?=base_url('public')?>/favicon/apple-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="120x120" href="<?=base_url('public')?>/favicon/apple-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="144x144" href="<?=base_url('public')?>/favicon/apple-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="152x152" href="<?=base_url('public')?>/favicon/apple-icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="<?=base_url('public')?>/favicon/apple-icon-180x180.png">
    <link rel="icon" type="image/png" sizes="192x192"  href="<?=base_url('public')?>/favicon/android-icon-192x192.png">
    <link rel="icon" type="image/png" sizes="32x32" href="<?=base_url('public')?>/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="96x96" href="<?=base_url('public')?>/favicon/favicon-96x96.png">
    <link rel="icon" type="image/png" sizes="16x16" href="<?=base_url('public')?>/favicon/favicon-16x16.png">
    <link rel="manifest" href="<?=base_url('public')?>/favicon/manifest.json">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="<?=base_url('public')?>/favicon/ms-icon-144x144.png">
    <meta name="theme-color" content="#ffffff">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro:ital,wght@0,300;0,400;0,700;1,400&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.1.0/styles/overlayscrollbars.min.css" integrity="sha256-LWLZPJ7X1jJLI5OG5695qDemW1qQ7lNdbTfQ64ylbUY=" crossorigin="anonymous">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.3.0/css/all.min.css" integrity="sha256-/4UQcSmErDzPCMAiuOiWPVVsNN2s3ZY/NsmXNcj0IFc=" crossorigin="anonymous">

    <link rel="stylesheet" href="<?=base_url('public/adminlte')?>/dist/css/adminlte.css">

    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.5/dist/sweetalert2.min.css" rel="stylesheet">

    <link rel="stylesheet" href="<?=base_url('public/css/styles.css')?>">

</head>

<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
    <!--begin::App Wrapper-->
    <div class="app-wrapper">
        <!--begin::Header-->
        <nav class="app-header navbar navbar-expand bg-body">
            <!--begin::Container-->
            <div class="container-fluid">
                <!--begin::Start Navbar Links-->
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" data-lte-toggle="sidebar" href="#" role="button">
                            <i class="fa-solid fa-bars"></i> <img src="<?=base_url()?>/public/logo/logo-andar-sm.jpeg" class="img-fluid" style="width:80px"/>
                        </a>
                    </li>
                </ul>
                <!--end::Start Navbar Links-->

                <!--begin::End Navbar Links-->
                <ul class="navbar-nav ms-auto">
                    <!--begin::User Menu Dropdown-->
                    <li class="nav-item dropdown user-menu">
                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                            <img src="<?=base_url('public/images/nav/user.jpg')?>" class="user-image rounded-circle shadow" alt="User Image">
                            <span class="d-none d-md-inline"><?=session('usuario')?></span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
                            <li class="text-bg-white text-center pt-2">
                                <p>
                                    <?=session('nombres')?><br>
                                    <small class="fw-semibold"><?=session('tipousu')?></small>
                                </p>
                            </li>
                            <li class="user-footer">
                                <a href="<?=base_url('mis-datos')?>" class="btn btn-sm btn-outline-secondary">Mis datos</a>
                                <a href="<?=base_url('salir')?>" class="btn btn-sm btn-outline-secondary float-end">Salir</a>
                            </li>
                            <!--end::Menu Footer-->
                        </ul>
                    </li>
                    <!--end::User Menu Dropdown-->
                </ul>
                <!--end::End Navbar Links-->
            </div>
            <!--end::Container-->
        </nav>
        <!--end::Header-->
        <!--begin::Sidebar-->
        <aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
            <!--begin::Sidebar Brand-->
            <div class="sidebar-brand">
                <!--begin::Brand Link-->
                <a href="./" class="brand-link">                    
                    <span class="brand-text fw-light"><?=help_nombreWeb()?></span>
                </a>
                <!--end::Brand Link-->
            </div>
            <!--end::Sidebar Brand-->
            <!--begin::Sidebar Wrapper-->
            <div class="sidebar-wrapper">
                <nav class="mt-2">
                    <!--begin::Sidebar Menu-->
                    <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="menu" data-accordion="false">
                        <li class="nav-item">
                            <a href="<?=base_url('sistema')?>" class="nav-link <?php echo isset($dashLinkActive) ? 'active': ''?>">
                                <i class="nav-icon fa-solid fa-gauge-high"></i>
                                <p>DASHBOARD</p>
                            </a>
                        </li>
                        <li class="nav-item menu-open">
                            <a href="javascript:;" class="nav-link">
                                <i class="nav-icon fa-solid fa-gear"></i>
                                <p>
                                    MANTENIMIENTOS
                                    <i class="nav-arrow fa-solid fa-angle-right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <?php
                                if( session('idtipousuario') == 1 ){
                                ?>
                                <li class="nav-item">
                                    <a href="<?=base_url('parametros')?>" class="nav-link ps-4 <?php echo isset($paramLinkActive) ? 'active': ''?>">
                                        <i class="nav-icon fa-solid fa-gears"></i>
                                        <p>Parámetros General</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?=base_url('perfiles')?>" class="nav-link ps-4 <?php echo isset($perfilLinkActive) ? 'active': ''?>">
                                        <i class="nav-icon fa-regular fa-user"></i>
                                        <p>Perfiles</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?=base_url('usuarios')?>" class="nav-link ps-4 <?php echo isset($usersLinkActive) ? 'active': ''?>">
                                        <i class="nav-icon fa-solid fa-user"></i>
                                        <p>Usuarios</p>
                                    </a>
                                </li>
                                <?php
                                }
                                ?>
                                <li class="nav-item">
                                    <a href="<?=base_url('transportistas')?>" class="nav-link ps-4 <?php echo isset($transLinkActive) ? 'active': ''?>">
                                        <i class="nav-icon fa-solid fa-truck"></i>
                                        <p>Transportistas</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?=base_url('clientes')?>" class="nav-link ps-4 <?php echo isset($clientesLinkActive) ? 'active': ''?>">
                                        <i class="nav-icon fa-solid fa-users"></i>
                                        <p>Clientes</p>
                                    </a>
                                </li><li class="nav-item">
                                    <a href="<?=base_url('piezas')?>" class="nav-link ps-4 <?php echo isset($piezasLinkActive) ? 'active': ''?>">
                                        <i class="nav-icon fa-solid fa-wrench"></i>
                                        <p>Piezas</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item menu-open">
                            <a href="javascript:;" class="nav-link">
                                <i class="nav-icon fa-solid fa-box-open"></i>
                                <p>
                                    MÓDULOS
                                    <i class="nav-arrow fa-solid fa-angle-right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="<?=base_url('torres')?>" class="nav-link ps-4 <?php echo isset($torresLinkActive) ? 'active': ''?>">
                                        <i class="fa-solid fa-tower-observation"></i>
                                        <p>Torres</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="./" class="nav-link ps-4 <?php echo isset($presuLinkActive) ? 'active': ''?>">
                                        <i class="nav-icon fa-solid fa-calculator"></i>
                                        <p>Presupuesto</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="./" class="nav-link ps-4 <?php echo isset($guiaLinkActive) ? 'active': ''?>">
                                        <i class="nav-icon fa-solid fa-file"></i>
                                        <p>Guía de Remisión</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="./" class="nav-link ps-4 <?php echo isset($factLinkActive) ? 'active': ''?>">
                                        <i class="nav-icon fa-solid fa-book"></i>
                                        <p>Facturación</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="./" class="nav-link ps-4 <?php echo isset($devolLinkActive) ? 'active': ''?>">
                                        <i class="nav-icon fa-solid fa-right-left"></i>
                                        <p>Devolución</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a href="<?=base_url('salir')?>" class="nav-link">
                                <i class="nav-icon fa-solid fa-arrow-right-from-bracket"></i>
                                <p>SALIR</p>
                            </a>
                        </li>
                    </ul>
                    <!--end::Sidebar Menu-->
                </nav>
            </div>
            <!--end::Sidebar Wrapper-->
        </aside>
        <!--end::Sidebar-->
        <!--begin::App Main-->
        <main class="app-main">
            <?php echo $this->renderSection("contenido");?>
        </main>
        <!--end::App Main-->
        <!--begin::Footer-->
        <footer class="app-footer">
            <!--begin::To the end-->
            <div class="float-end d-none d-sm-inline">Desarrollado por: Luis A. Calderón Sánchez</div>
            <!--end::To the end-->
            <!--begin::Copyright-->
            <strong>
                Copyright &copy; <?php echo date('Y')?>
                <a href="./"><?php echo help_nombreWeb()?></a>.
            </strong>
            Todos los derechos reservados.
            <!--end::Copyright-->
        </footer>
        <!--end::Footer-->
    </div>

    <script
    src="https://code.jquery.com/jquery-3.7.1.min.js"
    integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo="
    crossorigin="anonymous"></script>

    <script src="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.1.0/browser/overlayscrollbars.browser.es6.min.js" integrity="sha256-NRZchBuHZWSXldqrtAOeCZpucH/1n1ToJ3C8mSK95NU=" crossorigin="anonymous"></script>
    <!--end::Third Party Plugin(OverlayScrollbars)--><!--begin::Required Plugin(popperjs for Bootstrap 5)-->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.7/dist/umd/popper.min.js" integrity="sha384-zYPOMqeu1DAVkHiLqWBUTcbYfZ8osu1Nd6Z89ify25QV9guujx43ITvfi12/QExE" crossorigin="anonymous"></script>
    <!--end::Required Plugin(popperjs for Bootstrap 5)--><!--begin::Required Plugin(Bootstrap 5)-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.min.js" integrity="sha384-Y4oOpwW3duJdCWv5ly8SCFYWqFDsfob/3GkgExXKV4idmbt98QcxXYs9UoXAB7BZ" crossorigin="anonymous"></script>
    <!--end::Required Plugin(Bootstrap 5)--><!--begin::Required Plugin(AdminLTE)-->
    <script src="<?=base_url('public/adminlte')?>/dist/js/adminlte.js"></script>
    <!--end::Required Plugin(AdminLTE)--><!--begin::OverlayScrollbars Configure-->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.5/dist/sweetalert2.all.min.js"></script>



    <script>
        const SELECTOR_SIDEBAR_WRAPPER = ".sidebar-wrapper";
        const Default = {
            scrollbarTheme: "os-theme-light",
            scrollbarAutoHide: "leave",
            scrollbarClickScroll: true,
        };

        document.addEventListener("DOMContentLoaded", function() {
            const sidebarWrapper = document.querySelector(SELECTOR_SIDEBAR_WRAPPER);
            if (
                sidebarWrapper &&
                typeof OverlayScrollbarsGlobal?.OverlayScrollbars !== "undefined"
            ) {
                OverlayScrollbarsGlobal.OverlayScrollbars(sidebarWrapper, {
                    scrollbars: {
                        theme: Default.scrollbarTheme,
                        autoHide: Default.scrollbarAutoHide,
                        clickScroll: Default.scrollbarClickScroll,
                    },
                });
            }
        });
    </script>

    <?php echo $this->renderSection("scripts");?>
    
</body>

</html>