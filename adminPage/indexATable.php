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

  <div id="loading">
    <img id="loading-image" src="img/indexingScreen.gif" alt="Loading..." />
  </div>

  <div class="container">
    <!-- Navigation buttons -->
    <?php include "includes/adminNav.php"; ?>

    <!-- Container & table for the `Index a Page` option -->
    <div class="table-responsive">
      <form id="addUrlForm">
      <div class="row my-2">
        <label for="URLFIeld" class="col-form-label ml-3">Add a page to list:</label>
        <div class="col">
          <input name="urlField" class="form-control" type="text" placeholder="Enter URL of webpage" id="URLFIeld">
          <div id="label" class="is-invalid text-danger">
            <small id="warningLabel" style="visibility: hidden;">Enter valid URL including scheme</small>
          </div>
        </div>
        <div class="col">
          <input class="btn btn-success" name="submit" type="submit" value="Add Page"/>
        </div>
      </div>
      </form>
      <div class="table-container">
        <?php include "includes/indexAPage.php"; ?>
      </div>
      <button id='submitButton' type='submit' class='btn btn-info float-right m-2'>Index Pages</button>
    </div>
  </div>

<?php include "includes/footer.php"; ?>
<link rel="stylesheet" href="css/adminPage.css">
