<?php $c = $_GET['c'] ?? 'dashboard'; ?>
<div class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <div class="sidebar-logo">
            <i class="bi bi-wrench-adjustable"></i>
        </div>
        <span class="sidebar-title"><?= APP_NAME ?></span>
        <button class="btn-close-sidebar d-lg-none" id="closeSidebar">
            <i class="bi bi-x-lg"></i>
        </button>
    </div>

    <nav class="sidebar-nav">
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link <?= $c === 'dashboard' ? 'active' : '' ?>" href="index.php?c=dashboard">
                    <i class="bi bi-speedometer2"></i>
                    <span>Dashboard</span>
                </a>
            </li>

            <li class="nav-header">GESTION</li>
            <li class="nav-item">
                <a class="nav-link <?= $c === 'cliente' ? 'active' : '' ?>" href="index.php?c=cliente">
                    <i class="bi bi-people"></i>
                    <span>Clientes</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= $c === 'vehiculo' ? 'active' : '' ?>" href="index.php?c=vehiculo">
                    <i class="bi bi-car-front"></i>
                    <span>Vehiculos</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= $c === 'operario' ? 'active' : '' ?>" href="index.php?c=operario">
                    <i class="bi bi-person-badge"></i>
                    <span>Operarios</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= $c === 'tarea' ? 'active' : '' ?>" href="index.php?c=tarea">
                    <i class="bi bi-list-task"></i>
                    <span>Catalogo Tareas</span>
                </a>
            </li>

            <li class="nav-header">AGENDA</li>
            <li class="nav-item">
                <a class="nav-link <?= $c === 'agenda' ? 'active' : '' ?>" href="index.php?c=agenda">
                    <i class="bi bi-calendar3"></i>
                    <span>Calendario</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= $c === 'cita' ? 'active' : '' ?>" href="index.php?c=cita">
                    <i class="bi bi-calendar-check"></i>
                    <span>Citas</span>
                </a>
            </li>

            <li class="nav-header">DOCUMENTOS</li>
            <li class="nav-item">
                <a class="nav-link <?= $c === 'deposito' ? 'active' : '' ?>" href="index.php?c=deposito">
                    <i class="bi bi-inbox"></i>
                    <span>Depositos</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= $c === 'presupuesto' ? 'active' : '' ?>" href="index.php?c=presupuesto">
                    <i class="bi bi-calculator"></i>
                    <span>Presupuestos</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= $c === 'albaran' ? 'active' : '' ?>" href="index.php?c=albaran">
                    <i class="bi bi-clipboard-check"></i>
                    <span>Albaranes</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= $c === 'factura' ? 'active' : '' ?>" href="index.php?c=factura">
                    <i class="bi bi-receipt"></i>
                    <span>Facturas</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= $c === 'proyecto' ? 'active' : '' ?>" href="index.php?c=proyecto">
                    <i class="bi bi-kanban"></i>
                    <span>Proyectos</span>
                </a>
            </li>

            <li class="nav-header">SISTEMA</li>
            <li class="nav-item">
                <a class="nav-link <?= $c === 'estadistica' ? 'active' : '' ?>" href="index.php?c=estadistica">
                    <i class="bi bi-graph-up"></i>
                    <span>Estadisticas</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= $c === 'config' ? 'active' : '' ?>" href="index.php?c=config">
                    <i class="bi bi-gear"></i>
                    <span>Configuracion</span>
                </a>
            </li>
        </ul>
    </nav>
</div>
<div class="sidebar-overlay" id="sidebarOverlay"></div>
