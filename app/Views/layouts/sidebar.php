<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
  <!-- Brand Logo -->
  <a href="<?= base_url('/') ?>" class="brand-link">
    <i class="fas fa-boxes ml-3 mr-2"></i>
    <span class="brand-text font-weight-light">EstoquePro</span>
  </a>

  <!-- Sidebar -->
  <div class="sidebar">

    <!-- Sidebar user panel (opcional) -->
    <div class="user-panel mt-3 pb-3 mb-3 d-flex">
      <div class="image">
        <img src="<?= base_url('adminlte/dist/img/user2-160x160.jpg') ?>" class="img-circle elevation-2" alt="Usuário">
      </div>
      <div class="info">
        <a href="#" class="d-block">Olá, <?= auth()->user()->username; ?></a>
      </div>
    </div>

    <!-- Sidebar Menu -->
    <nav class="mt-2">
      <!-- <ul class="nav nav-pills nav-sidebar flex-column" role="menu"> -->
      <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

        <li class="nav-item">
          <a href="<?= base_url('/') ?>" class="nav-link">
            <i class="nav-icon fas fa-tachometer-alt"></i>
            <p>Dashboard</p>
          </a>
        </li>

        <li class="nav-item">
          <a href="<?= base_url('usuarios/create') ?>" class="nav-link">
            <i class="nav-icon fas fa-users-cog"></i>
            <p>Gerenciamento de Usuários</p>
          </a>
        </li>

        <li class="nav-item">
          <a href="<?= base_url('clientes') ?>" class="nav-link">
            <i class="nav-icon fas fa-users-cog"></i>
            <p>Clientes</p>
          </a>
        </li>

        <li class="nav-item">
          <a href="<?= base_url('fornecedores') ?>" class="nav-link">
            <i class="nav-icon fas fa-users-cog"></i>
            <p>Fornecedores</p>
          </a>
        </li>

        <li class="nav-item">
          <a href="<?= base_url('notas-fiscais') ?>" class="nav-link">
            <i class="nav-icon fas fa-users-cog"></i>
            <p>Notas Fiscais</p>
          </a>
        </li>

        <li class="nav-item">
          <a href="#" class="nav-link">
            <i class="nav-icon fas fa-copy"></i>
            <p>
              Gerenciamento de Estoque
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="#" class="nav-link">
                <i class="nav-icon fas fa-copy"></i>
                <p>
                  Entrada de Estoque
                </p>
              </a>
              <ul class="nav nav-treeview">
                <li class="nav-item">
                  <a href="<?= base_url('estoque/entrada-manual-estoque') ?>" class="nav-link">
                    <i class="far fa-dot-circle nav-icon"></i>
                    <p>Entrada Manual</p>
                  </a>
                </li>
              </ul>
            </li>
            <li class="nav-item">
              <a href="#" class="nav-link">
                <i class="nav-icon fas fa-copy"></i>
                <p>
                  Saída de Estoque
                </p>
              </a>
              <ul class="nav nav-treeview">
                <li class="nav-item">
                  <a href="<?= base_url('estoque/saida-estoque') ?>" class="nav-link">
                    <i class="far fa-dot-circle nav-icon"></i>
                    <p>Ajuste de Saldo</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="<?= base_url('estoque/saida-estoque-cliente') ?>" class="nav-link">
                    <i class="far fa-dot-circle nav-icon"></i>
                    <p>Saída para Cliente</p>
                  </a>
                </li>
              </ul>
            </li>
            <li class="nav-item">
              <a href="<?= base_url('estoque') ?>" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Painel de Estoque</p>
              </a>
            </li>

          </ul>
        </li>
        <li class="nav-item">
          <a href="#" class="nav-link">
            <i class="nav-icon fas fa-copy"></i>
            <p>
              Gerenciamento de Produtos
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="<?= base_url('produtos') ?>" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Produtos</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?= base_url('grupo-produtos') ?>" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Grupos de Produtos</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?= base_url('subgrupo-produtos') ?>" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Subgrupos de Produtos</p>
              </a>
            </li>
          </ul>
        </li>

      </ul>
    </nav>
    <!-- /.sidebar-menu -->
  </div>
  <!-- /.sidebar -->
</aside>