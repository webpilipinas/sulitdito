<?php
session_start();
include_once "AppNimbus.php";
$appnimbus = new AppNimbus('sulitdito', '8eeceba8aca008cb9ec5e73d2b223e777de89606');
$page_item = $appnimbus->_restCall('object', 'get_by_id', array(
	'id' => $_GET['id']
));

$item_parent = $appnimbus->_restCall('object', 'get_by_id', array(
	'id' => $page_item['data']['parents']['item_of']
));

$comments = $appnimbus->_restCall('object', 'get_by_parent', array(
	'id' => $page_item['data']['id'],
	'relationship' => 'commented_on'
));

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
					<li><a href="#postitem" data-toggle="model">Post an Item</a></li>
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
					?>
						<?php if( $data['success'] && isset($data['data']) && count($data['data']) > 0 ): ?>
							<?php foreach($data['data'] as $item): ?>
							<li <?php if( $page_item['data']['id'] == $item['id'] ) { echo 'class="active"'; } ?>><a href="view_item.php?id=<?php echo $item['id']; ?>"><i class="icon-heart"></i><?php echo $item['properties']['name']; ?></a></li>
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
				</div><!--/.well -->
			</div><!--/span-->
			<div class="span8">
				<div class="row-fluid well">
					<div class="span3">
						<img width="300" src="<?php echo $page_item['data']['properties']['photo']; ?>" alt="<?php echo $page_item['data']['properties']['name']; ?>" />	
					</div>
					<div class="span8">
						<h1><?php echo $page_item['data']['properties']['name']; ?></h1>
						<h4>Price: <small style="color: #000000 !important;">PHP<?php echo $page_item['data']['properties']['price']; ?></small></h4>
						<h4>Description:</h4>
						<p><?php echo nl2br($page_item['data']['properties']['description']); ?></p>
						<h4>Contact:</h4>
						<a class="btn btn-primary" href="mailto:<?php echo $item_parent['data']['properties']['email']; ?>"><?php echo $item_parent['data']['properties']['email']; ?></a>
						
						<br /><br />
						<h4>Discuss</h4>
						<?php if( $comments['success'] == false || count($comments['data']) < 1 ): ?>
						<div class="alert alert-info">						
							<?php if( isset($_SESSION['logged_in']) ): ?>
							<strong>There are no comments yet for this item! Be the first one to comment!</strong>
							<?php else: ?>
							<strong>There are no comments yet for this item! <a href="#login" data-toggle="modal">Login to comment!</a></strong>
							<?php endif; ?>
						</div>
						<?php else: ?>
						<?php if( !isset($_SESSION['logged_in']) ): ?>
						<div class="alert alert-info">
							Want to join the conversation? <strong><a href="#login" data-toggle="modal">Login to leave a comment!</a></strong>
						</div>
						<?php endif; ?>
						<?php foreach($comments['data'] as $comment): ?>
						<?php $comment_user = $appnimbus->_restCall('object', 'get_by_id', array(
							'id' => $comment['parents']['comment_of']
						));
						//echo '<pre>'; var_dump($comment_user); echo '</pre>';
						?>
						<div class="row-fluid">
							<div class="span1" style="padding-top: 10px">
								<img src="<?php echo 'http://www.gravatar.com/avatar/' . md5($comment_user['data']['properties']['email']); ?>?d=retro&s=50" alt="<?php echo $comment_user['data']['properties']['email']; ?>" />
							</div>
							<div class="span11 well" style="padding: 7px !important; background-color: #FFF">
								<p><strong><?php echo $comment_user['data']['properties']['email']; ?></strong> <?php echo $comment['properties']['body']; ?></p>
								<div style="color: #666; font-size: 10px; text-align: right; font-weight: bold;">
									<?php echo date('M d Y, h:iA'); ?>
								</div>
							</div>
						</div>
						<?php endforeach; ?>
						<?php endif; ?>
						<?php if( isset($_SESSION['logged_in']) ): ?>
						<div class="row-fluid">
							<div class="span1">
								<img src="<?php echo 'http://www.gravatar.com/avatar/' . md5($_SESSION['username']); ?>?d=retro&s=50" alt="<?php echo $_SESSION['username']; ?>" />
							</div>
							<div class="span11">
								<form class="form-horizontal" method="POST" action="post_comment.php">
									<textarea placeholder="Ask the owner anything! E.g. Are you willing to ship?" name="body" id="body" class="span14"></textarea>
									<input type="hidden" name="item_id" value="<?php echo $page_item['data']['id']; ?>" />
									<div style="text-align: right; margin-top: 10px">
										<button type="submit" class="btn btn-primary" name="comment_submit">Comment</button>
									</div>
								</form>
							</div>
						</div>
						<?php endif; ?>
					</div>
				</div>
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
