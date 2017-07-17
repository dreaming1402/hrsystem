<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title><?php echo $page_title . ' - ' . APP_NAME; ?></title>

        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
<?php ApplyScript(); ?>
	</head>
	<body id="<?php echo $page_id; ?>" class="sidebar-mini skin-green sidebar-collapse">
		<div class="wrapper">
			<header class="main-header">
                <!-- Logo -->
                <a href="#" class="logo">
                    <!-- mini logo for sidebar mini 50x50 pixels -->
                    <span class="logo-mini"><b>HR</b>S</span>
                    <!-- logo for regular state and mobile devices -->
                    <span class="logo-lg"><b>HR</b>System</span>
                </a>

                <!-- Header Navbar: style can be found in header.less -->
                <nav class="navbar navbar-static-top">
                    <!-- Sidebar toggle button-->
                    <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
                        <span class="sr-only">Toggle navigation</span>
                    </a>

                    <div class="navbar-custom-menu">
                        <ul class="nav navbar-nav">
                            <!-- top-right menu-->
                            <li>
                                <a href="#">
                                  <i class="fa fa-sign-out"></i>
                                  Sign out
                                </a>
                            </li>
                        </ul>
                    </div>
                </nav>
            </header>