<?php
session_start();
include_once "AppNimbus.php";
$appnimbus = new AppNimbus('sulitdito', '8eeceba8aca008cb9ec5e73d2b223e777de89606');
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Sulit Dito! Gusto niyo?</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="">
	<meta name="author" content="">

	<!-- Le styles -->
	<link href="/css/bootstrap.css" rel="stylesheet">
	<style type="text/css">
		body {
		padding-top: 60px;
		padding-bottom: 40px;
		}
		.sidebar-nav {
		padding: 9px 0;
		}
	</style>
</head>

<body>
	<div class="navbar navbar-fixed-top">
		<div class="navbar-inner">
		<div class="container-fluid">
			<a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
			</a>
			<a class="brand" href="/">Sulit Dito!</a>
			<div class="btn-group pull-right">
				<?php if( isset($_SESSION['logged_in']) ): ?>
				<a class="btn dropdown-toggle" data-toggle="dropdown" href="#">
					<i class="icon-user"></i> Logged in as: <?php echo $_SESSION['username']; ?>
					<span class="caret"></span>
				</a>
				<ul class="dropdown-menu">
					<li><a href="#postitem" data-toggle="modal" data-target="#postitem">Post an Item</a></li>
					<li class="divider"></li>
					<li><a href="/sign_out.php">Sign Out</a></li>
				</ul>
				<?php else: ?>
				<a class="btn" href="#">
					<i class="icon-shopping-cart"></i> <span data-toggle="modal" data-target="#login">Sell your stuff!</span>
				</a>
				<?php endif; ?>
			</div>
		</div>
		</div>
	</div>
	<div class="container-fluid">
		<div class="row-fluid">
		<div class="span3">
			<div class="well sidebar-nav">
			<ul class="nav nav-list">
				<?php if( isset($_SESSION['logged_in']) ): ?>
				<li class="nav-header">My Items</li>
				<?php 
				$data = $appnimbus->_restCall('object', 'get_by_parent', array(
					'id' => $_SESSION['user_id'],
					'relationship' => 'item_of'
				));
				//echo '<pre>'; var_dump($data); echo '</pre>';
				?>
				<?php if( $data['success'] && isset($data['data']) && count($data['data']) > 0 ): ?>
				<?php foreach($data['data'] as $item): ?>
				<li><a href="view_item.php?id=<?php echo $item['id']; ?>"><i class="icon-heart"></i><?php echo $item['properties']['name']; ?></a></li>
				<?php endforeach; ?>
				<li><a href="#postitem" data-toggle="modal" data-target="#postitem"><i class="icon-upload"></i>Post an Item</a></li>
				<?php else: ?>
				<div class="alert alert-warning">
				<p>You don't have any items yet! Start posting items by <a href="#postitem" data-toggle="modal">clicking here</a></p>
				</div>
				<?php endif; ?>
				<?php else: ?>
				<h2>What is <strong>SulitDito?</strong></h2>
				<p>SulitDito provides anyone with an easy way to sell anything online!</p>
				<?php endif; ?>
			</ul>
			<div class="alert alert-info">
				<p>Download <h4>Sulit Dito</h4>'s source code! <a href="https://github.com/webpilipinas/sulitdito" target="_blank">https://github.com/webpilipinas/sulitdito</a></p>
			</div>
			</div><!--/.well -->
		</div><!--/span-->
		<div class="span9">
			<div class="hero-unit">
			<h1>May gusto ka bang <a href="http://pabenta.com" target="_blank" style="color: #983B3F">PABENTA</a>?</h1>
			<?php if( !isset($_SESSION['logged_in']) ): ?>
			<p>Sell your extra stuff here for FREE! Just sign up for a <strong>Sulit Dito!</strong> account, describe the item you want with a few photos and you're all set! Quickly sell things like that camera lense you don't use anymore, the home furniture you need to get rid off, that Boracay ticket you won't get to use, and more!</p>
			<p><a data-toggle="modal" href="#login" class="btn btn-primary btn-large"><i class="icon-shopping-cart icon-white"></i>Sell your items &raquo;</a></p>
			<?php else: ?>
			<p>Click on the button below to sell your item for FREE!</p>
			<p><a data-toggle="modal" href="#postitem" class="btn btn-primary btn-large"><i class="icon-shopping-cart icon-white"></i>Post your item &raquo;</a></p>
			<?php endif; ?>
			</div>
			<div class="row-fluid">
			<h1>Latest Items</h1>
			<?php $all_items = $appnimbus->_restCall('object', 'get_all', array(
				'name' => 'Item' 
			));
			//echo '<pre>'; var_dump($all_items); echo '</pre>';
			?>
			<?php if( count($all_items['data']) > 0 ): ?>
			<?php foreach($all_items['data'] as $postitem): ?>
			<div class="span4 well" style="padding: 12px !important; width: 290px; height: 200px; overflow: hidden;">
				<h3><?php echo $postitem['properties']['name']; ?></h3>
				<div style="width: 100px; margin-right: 10px; float: left">
					<img src="<?php echo $postitem['properties']['photo']; ?>" width="100" />
				</div>
				<div style="width: 150px; float: left">
					<p><?php echo $postitem['properties']['description']; ?></p>
					<div style="text-align: right; margin-top: 10px">
						<a class="btn btn-inverse" href="view_item.php?id=<?php echo $postitem['id']; ?>">View &raquo;</a>
					</div>
				</div>
				<div style="clear: both"></div>
			</div><!--/span-->
			<?php endforeach; ?>
			<?php else: ?>
			<div class="alert alert-info">
				<?php if( !isset($_SESSION['logged_in']) ): ?>
				<p>There are no items for sale! Be the first to post by <a href="#login" data-toggle="modal">clicking here</a></p>
				<?php else: ?>
				<p>There are no items for sale! Be the first to post by <a href="#postitem" data-toggle="modal">clicking here</a></p>
				<?php endif; ?>
			</div>
			<?php endif; ?>
			</div><!--/row-->
		</div><!--/span-->
		</div><!--/row-->

		<hr>

		<footer>
		<p>&copy; Powered by <a href="http://appnimbus.com" target="_blank">AppNimbus</a></p>
		</footer>

	</div><!--/.fluid-container-->
	
	<script src="/js/jquery.js"></script>
    <script src="/js/bootstrap-transition.js"></script>
    <script src="/js/bootstrap-alert.js"></script>
    <script src="/js/bootstrap-modal.js"></script>
    <script src="/js/bootstrap-dropdown.js"></script>
    <script src="/js/bootstrap-scrollspy.js"></script>
    <script src="/js/bootstrap-tab.js"></script>
    <script src="/js/bootstrap-tooltip.js"></script>
    <script src="/js/bootstrap-popover.js"></script>
    <script src="/js/bootstrap-button.js"></script>
    <script src="/js/bootstrap-collapse.js"></script>
    <script src="/js/bootstrap-carousel.js"></script>
    <script src="/js/bootstrap-typeahead.js"></script>
    
    <?php if( !isset($_SESSION['logged_in']) ): ?>
	<div class="modal hide fade" id="login">
		<form method="POST" action="create_login.php">
			<div class="modal-header">
				<button class="close" data-dismiss="modal">&times;</button>
				<h3>Login or Create an account to start selling!</h3>
			</div>
			<div class="modal-body">
				<label for="email">Email:</label>
				<input type="text" placeholder="yourname@youremail.com" name="email" id="email" />
				<label for="password">Password:</label>
				<input type="password" placeholder="Your password" name="password" id="password" />
			</div>
			<div class="modal-footer">
				<a href="#" class="btn" data-dismiss="modal">Cancel</a>
				<button type="submit" class="btn btn-primary" name="login_submit">Login or Create an account!</a>
			</div>
		</form>
	</div>
	<?php else: ?>
	<div class="modal hide fade" id="postitem">
		<form method="POST" action="post_item.php" class="form-horizontal" enctype="multipart/form-data">
			<div class="modal-header">
				<button class="close" data-dismiss="modal">&times;</button>
				<h3>Create a new item!</h3>
			</div>
			<div class="modal-body">
				<label for="name">Item Name:</label>
				<input type="text" placeholder="E.g. Boracay Tickets" name="name" id="name" class="span6" />
				<br /><br />
				<label for="description">Description: <small>(optional)</small></label>
				<textarea placeholder="E.g. I wasn't able to use these tickets, Cebu Pacific flight C102" name="description" id="description" class="span6" rows="7"></textarea>
				<br /><br />
				<label for="price">Price</label>
				<div class="input-prepend input-append">
					<span class="add-on">PHP</span><input name="price" id="price" class="span2" id="appendedPrependedInput" size="8" type="text">
				</div>
				<br /><br />
				<label for="itemphoto">Photo</label>
				<input type="file" name="itemphoto" id="itemphoto" class="span6" />
			</div>
			<div class="modal-footer">
				<a href="#" class="btn" data-dismiss="modal">Cancel</a>
				<button type="submit" class="btn btn-primary" name="post_submit">Post Item!</a>
			</div>
		</form>
	</div>
	<?php endif; ?>
</body>
</html>
