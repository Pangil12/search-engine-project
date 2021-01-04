<!-- header is now in the includes folder -->
<?php include "includes/header.php"; ?>

  <!-- Bootstrap nav bar aligned right -->
  <nav class="navbar navbar-expand-sm navbar-light bg-info font-weight-bold">
    <div class="container-fluid">
      <ul class="navbar-nav ml-auto mr-4">
        <li class="nav-item pr-3">
          <i class="fas fa-user-cog"></i> Admin
        </li>
        <li class="nav-item">
          <a href="../index.php"><i class="fas fa-sign-out-alt"></i>Logoff</a>
        </li>
      </ul>
    </div>
  </nav>

  <div class="container">

    <!-- Navigation buttons -->
    <?php include "includes/adminNav.php"; ?>

    <!-- Container & table for the Indexed Pages -->
    <div id="IndexedPagesTable" class="table-responsive">
      <?php include "includes/indexedTable.php"; ?>
    </div>

  </div>

<?php include "includes/footer.php"; ?>
