<?php
if (isset($_GET['logout'])) {
unset($_SESSION['SBUser']);

header('Location: login.php');
}
?>
     <style>
    .nav{
      background-color: mediumpurple;;
    }
  </style>
    <nav class="navbar navbar-expand-md fixed-top navbar-dark nav">
      <a class="navbar-brand" href="/bidding-system/">OUR BIDDING SYSTEM</a>
      <button class="navbar-toggler p-0 border-0" type="button" data-toggle="offcanvas">
        <span class="navbar-toggler-icon"></span>
      </button>

    <div class="navbar-collapse offcanvas-collapse nav" id="navbarsExampleDefault">
        <ul class="navbar-nav mr-auto">
          <li class="nav-item active  navigation">
          <a class="nav-link" href="/bidding-system/">Items <span class="sr-only">(current)</span></a>
          <div class="dropdown-menu" aria-labelledby="dropdown01">
              <a class="dropdown-item" href="#">Log Out</a>
              <a class="dropdown-item" href="change_password.php">Change Password</a>
            </div>
          </li>
        <li class="nav-item">
            <a class="nav-link" href="winned.php">
              <span class="glyphicon glyphicon-shopping-cart"></span> Winned Bids <span class="badge badge-secondary badge-pill bg-danger"></span></a>
          </li>
           <li class="nav-item">
            <a class="nav-link" href="my_account.php">
               My Account <span class="badge badge-secondary badge-pill bg-danger"></span></a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="navigation.php?logout=1">
               Logout <span class="badge badge-secondary badge-pill bg-danger"></span></a>
          </li>
        </ul>
      </div>
    </nav>

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script>window.jQuery || document.write('<script src="/bidding-system/javascript/jquery-slim.min.js"><\/script>')</script>
    <script src="/bidding-system/javascript/popper.min.js"></script>
    <script src="/bidding-system/javascript/bootstrap.min.js"></script>
    <script src="/bidding-system/javascript/holder.min.js"></script>
    <script src="/bidding-system/accessories/css/offcanvas.js"></script>
