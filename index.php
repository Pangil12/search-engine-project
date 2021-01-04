<!-- connects to the database server -->
<?php include "var/www/inc/dbinfo.inc"; ?>
<!-- header is now in the includes folder -->
<?php include "includes/header.php"; ?>

<!-- This div is the main search page with no results -->
<form class="myForm" action="results.php" method="post">
    <div id="searchPage" class="input-group m-auto">
        <input type="text" class="form-control" placeholder="Search for anything"
               name="searchBox" required
               oninvalid="this.setCustomValidity('Enter Item to Search')"
               oninput="this.setCustomValidity('')">
        <div class="input-group-append">
            <button class="btn btn-info" type="submit" name="form_submit">Search</button>
        </div>
    </div>
</form>

<!-- footer is now in the includes folder -->
<?php include "includes/footer.php"; ?>
