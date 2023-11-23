<style type="text/css">
  .logo {
    height: 10vh;
  }

  .navs, .footer {
    color: #cccccc;
    font-size: 16pt;
  }

  .footer {
    margin-top: 250px;
  }

  .navs a, .footer a {
    text-decoration: none;
    color: #cccccc;
  }

  .navs a:hover, .footer a:hover, #dashboard:hover, #profile:hover, #help:hover, #logout:hover {
    color: #ffffff;
    cursor: pointer;
  }

  .nav-item {
    margin-top: 15px;
  }
</style>
<div class="row logo">
  
</div> 
<div class="navs">
  <div class="nav-item"><i class="fa fa-chart-line"></i> <a href="/home">Dashboard</a></div>

  <div class="nav-item"><i class="fa fa-list"></i> <a href="/orders">Orders</a></div>
  <div class="nav-item"><i class="fa fa-cash-register"></i> <a href="/sales">Sales</a></div>
  <div class="nav-item"><i class="fa fa-box"></i> <a href="/inventories">Inventory</a></div>
  <div class="nav-item"><i class="fa fa-box-open"></i> <a href="/products">Products</a></div>
  <div class="nav-item"><i class="fa fa-user-check"></i> <a href="/services">Services</a></div>
</div>
<div class="footer">
  <div class="nav-item"><i class="fa fa-user"></i> <span id="profile">Profile</span></div>
  <div class="nav-item"><i class="fa fa-question"></i> <span id="help">Help</span></div>
  <div class="nav-item"><i class="fa fa-arrow-right"></i> <a href="/logout">Logout</a></div>
</div>