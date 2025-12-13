<!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-dark">
<!-- <nav class="main-header navbar navbar-expand navbar-white navbar-light"> -->
  <!-- Botão de toggle da sidebar -->
  <ul class="navbar-nav">
    <li class="nav-item">
      <a class="nav-link" data-widget="pushmenu" href="#" role="button">
        <i class="fas fa-bars"></i>
      </a>
    </li>
  </ul>

  <!-- Navbar do lado direito -->
  <ul class="navbar-nav ml-auto">

    <!-- Exemplo de menu suspenso para notificações (opcional) -->
    <!--
    <li class="nav-item dropdown">
      <a class="nav-link" data-toggle="dropdown" href="#">
        <i class="far fa-bell"></i>
        <span class="badge badge-warning navbar-badge">3</span>
      </a>
      <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
        <span class="dropdown-item dropdown-header">3 Notificações</span>
        <div class="dropdown-divider"></div>
        <a href="#" class="dropdown-item">
          <i class="fas fa-box mr-2"></i> Produto em baixo estoque
          <span class="float-right text-muted text-sm">2 min</span>
        </a>
      </div>
    </li>
    -->

    <!-- Menu do usuário -->
    <li class="nav-item dropdown">
      <a class="nav-link" data-toggle="dropdown" href="#" aria-expanded="false">
        <i class="far fa-user-circle"></i> <span class="ml-1"><?= auth()->user()->username; ?></span>
      </a>
      <div class="dropdown-menu dropdown-menu-right">
        <a href="<?= base_url('perfil') ?>" class="dropdown-item">
          <i class="fas fa-user mr-2"></i> Meu Perfil
        </a>
        <div class="dropdown-divider"></div>
        <a href="<?= base_url('logout') ?>" class="dropdown-item">
          <i class="fas fa-sign-out-alt mr-2"></i> Sair
        </a>
      </div>
    </li>
  </ul>
</nav>
<!-- /.navbar -->