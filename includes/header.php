<!--Login Modal-->
    <div class="modal fade" id="loginModal" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button>
            <h2>Login or <a href="register.php">Register</a></h2>
            <p class="large">Use social accounts</p>
            <div class="social-login">
            	<a class="facebook" href="#"><i class="fa fa-facebook-square"></i></a>
              <div class="g-signin2" data-onsuccess="onGoogleSignIn" data-onerror="onGoogleSignInError" ></div>
            	<a class="twitter" href="#"><i class="fa fa-twitter-square"></i></a>
            </div>
          </div>
          <div class="modal-body">
          <form class="login-form" method="POST" action="process_login.php">
            <div class="form-group group">
            	<label for="log-email">Email</label>
              <input type="email" class="form-control" name="email" id="log-email" placeholder="Enter your email" required>
              <a class="help-link" href="#">Forgot email?</a>
            </div>
            <div class="form-group group">
            	<label for="log-password">Password</label>
              <input type="text" class="form-control" name="pass" id="log-password" placeholder="Enter your password" required>
              <a class="help-link" href="#">Forgot password?</a>
            </div>
            <div class="checkbox">
              <label><input type="checkbox" name="remember"> Remember me</label>
            </div>
            <input class="btn btn-success" type="submit" value="Login">
          </form>
          </div>
        </div><!-- /.modal-content -->
      </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

    <!--Header-->
    <header data-offset-top="150" data-stuck="250"><!--data-offset-top is when header converts to small variant and data-stuck when it becomes visible. Values in px represent position of scroll from top. Make sure there is at least 100px between those two values for smooth animation-->
    
      <!--Search Form-->
      <form class="search-form closed" method="get" action="search.php" role="form" autocomplete="off">
      	<div class="container">
          <div class="close-search"><i class="icon-delete"></i></div>
            <div class="form-group">
              <label class="sr-only" for="search-hd">Search for procuct</label>
              <input type="text" class="form-control" name="q" id="search-hd" placeholder="Search for procuct">
              <button type="submit"><i class="icon-magnifier"></i></button>
          </div>
        </div>
      </form>
      
    	<!--Split Background-->
    	<div class="left-bg"></div>
    	<div class="right-bg"></div>
      
    	<div class="container">
      	<a class="logo" href="index.php"><img src="img/logo.png" alt="Bushido"/></a>
        
        
      
      	<!--Mobile Menu Toggle-->
        <div class="menu-toggle"><i class="fa fa-list"></i></div>
        <div class="mobile-border"><span></span></div>
        
        <!--Main Menu-->
        <nav class="menu">
          <ul class="main">
            <li class="hide-sm"><a href="index.php">Home</a><!--Class "has-submenu" for proper highlighting and dropdown-->
            	<!-- <ul class="submenu">
                    <li><a href="index.html">Home - Slideshow</a></li>
                </ul> -->
            </li>
            <li class="has-submenu"><a href="#">Admin<span class="label">NEW</span><i class="fa fa-chevron-down"></i></a>
            	<ul class="submenu">
                    <li><a href="my_stuff.php">My Products</a></li>
                    <li><a href="company_info.php">Account: Company Info<span class="label">NEW</span></a></li>
                </ul>
            </li>
            <li class="hide-sm"><a href="#contact">Contact</a></li>
          </ul>
            
          <!-- Submenu for a catalog if I need this as it develops
          <ul class="catalog">
          	<li class="has-submenu"><a href="shop-filters-left-3cols.html">Phones<i class="fa fa-chevron-down"></i></a>
            	<ul class="submenu">
              	<li><a href="#">Nokia</a></li>
              	<li class="has-submenu"><a href="#">iPhone</a>
                	<ul class="sub-submenu">
                    <li><a href="#">iPhone 4</a></li>
                    <li><a href="#">iPhone 4s</a></li>
                    <li><a href="#">iPhone 5c</a></li>
                    <li><a href="#">iPhone 5s</a></li>
                  </ul>
                </li>
              	<li><a href="#">HTC</a></li>
              	<li class="has-submenu"><a href="#">Samsung</a>
                	<ul class="sub-submenu">
                    <li><a href="#">Galaxy Note 3</a></li>
                    <li><a href="#">Galaxy S5</a></li>
                    <li><a href="#">Galaxy S3 Neo</a></li>
                    <li><a href="#">Galaxy Gear</a></li>
                    <li><a href="#">Galaxy S Duos 2</a></li>
                  </ul>
                </li>
              	<li><a href="#">BlackBerry</a></li>
                <li class="offer">
                	<div class="col-1">
                  	<p class="p-style2">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
                  </div>
                  <div class="col-2">
                  	<img src="img/offers/menu-drodown-offer.jpg" alt="Special Offer"/>
                  	<a class="btn btn-block" href="#"><span>584$</span>Special offer</a>
                  </div>
                </li>
              </ul>
            </li>
          	<li><a href="shop-filters-left-3cols.html">Cameras</a></li>
          	<li><a href="shop-filters-left-3cols.html">Personal computers</a></li>
          	<li><a href="shop-filters-left-3cols.html">Gaming consoles</a></li>
          	<li><a href="shop-filters-left-3cols.html">TV sets</a></li>
          </ul>
          -->
          
        </nav>
        
        <!--Toolbar-->
        <div class="toolbar group">
          <button class="search-btn btn-outlined-invert"><i class="icon-magnifier"></i></button>
          <div class="middle-btns">
            <a class="btn-outlined-invert" href="#"><i class="icon-heart"></i> <span>Wishlist</span></a>
            <?php
		if (!isset($_SESSION['uid']) && empty($_SESSION['uid'])) { //If not logged in post login link, else post signup link
                    echo '<a class="login-btn btn-outlined-invert" href="#" data-toggle="modal" data-target="#loginModal"><i class="icon-profile"></i><span>Login</span></a>';
		} else {
                    echo '<a class="login-btn btn-outlined-invert" href="logout.php"><i class="icon-profile-remove"></i><span>Logout</span></a>';
		}
            ?>            
          </div>
          <div class="cart-btn">
          	<a class="btn btn-outlined-invert" href="product.php"><i class="icon-upload"></i><span></span></a>          </div>
        </div><!--Toolbar Close-->
      </div>
    </header><!--Header Close-->