<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title><?php echo $page_title . ' - ' . APP_NAME; ?></title>

        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

<?php echo get_templates(['css', 'js']); ?>
	</head>
	<body id="<?php echo $page_id; ?>" class="skin-blue sidebar-mini">
		<div class="wrapper">
			<header class="main-header">
                <!-- Logo -->
                <a href="#" class="logo">
                    <!-- mini logo for sidebar mini 50x50 pixels -->
                    <span class="logo-mini">
                        <b>HR</b>
                        S
                    </span>
                    <!-- logo for regular state and mobile devices -->
                    <span class="logo-lg">
                        <b>HR</b>
                        System
                    </span>
                </a>
                <!-- Header Navbar: style can be found in header.less -->
                <nav class="navbar navbar-static-top">
                    <!-- Sidebar toggle button-->
                    <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
                        <span class="sr-only">Toggle navigation</span>
                    </a>
                </nav>
            </header>