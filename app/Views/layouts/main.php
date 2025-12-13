<!DOCTYPE html>
<html lang="pt-br">

<head>
  <!-- <meta charset="UTF-8"> -->
  <meta name="viewport" content="width=device-width, initial-scale=1" charset="utf-8">
  <title><?= $this->renderSection('title') ?></title>

  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?= base_url('adminlte/plugins/fontawesome-free/css/all.min.css') ?>">

  <!-- Theme style -->
  <link rel="stylesheet" href="<?= base_url('adminlte/dist/css/adminlte.min.css') ?>">

  <!-- DataTables -->
  <link rel="stylesheet" href="<?= base_url('adminlte/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') ?>">
  <link rel="stylesheet" href="<?= base_url('adminlte/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') ?>">


  <?= $this->renderSection('styles') ?>
</head>

<body class="hold-transition dark-mode sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed">
  <!-- <body class="hold-transition sidebar-mini"> -->
  <div class="wrapper">

    <?= $this->include('layouts/navbar') ?>
    <?= $this->include('layouts/sidebar') ?>

    <div class="content-wrapper">
      <section class="content">
        <?= $this->renderSection('content') ?>
      </section>
    </div>

    <?= $this->include('layouts/footer') ?>
  </div>

  <!-- jQuery -->
  <script src="<?= base_url('adminlte/plugins/jquery/jquery.min.js') ?>"></script>

  <!-- Bootstrap 4 -->
  <script src="<?= base_url('adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>

  <!-- AdminLTE App -->
  <script src="<?= base_url('adminlte/dist/js/adminlte.min.js') ?>"></script>
  <!-- ChartJS -->
  <script src="<?= base_url('adminlte/plugins/chart.js/Chart.min.js') ?>"></script>

  <?= $this->renderSection('scripts') ?>
</body>

</html>