<style type="text/css">
  .logo {
    height: 15vh;
  }

  .navs, .footer {
    color: #cccccc;
    font-size: 14pt;
    position: fixed;
  }

  .footer {
    bottom: 3em;
  }

  .navs a, .footer a {
    text-decoration: none;
    color: #cccccc;
  }

  .navs a:hover, .footer a:hover, #dashboard:hover, #profile:hover, #help:hover, #logout:hover {
    color: #ffffff;
    cursor: pointer;
  }

  .navs {
    height: 70vh;
  }

  .nav-item {
    margin-top: 5px;
  }

  .footer {
    height: 14.3vh;
  }
</style>
<div class="row logo">
  
</div> 
<div class="navs">
  <div class="nav-item"><i class="fa fa-chart-line"></i> <a href="/home">Dashboard</a></div>

  <div class="nav-item"><i class="fa fa-list"></i> <a href="/orders">Orders</a></div>
  <div class="nav-item"><i class="fa fa-cash-register"></i> <a href="/sales">Sales</a></div>
  <div class="nav-item"><i class="fa fa-box"></i> <a href="/inventories">Inventory</a></div>
</div>
<div class="footer">
  <div><i class="fa fa-user"></i> <span id="profile">Profile</span></div>
  <div><i class="fa fa-question"></i> <span id="help">Help</span></div>
  <div><i class="fa fa-arrow-right"></i> <a href="/logout">Logout</a></div>
</div>