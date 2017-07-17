<?php include_once 'header.php'; ?>
<?php include_once 'sidebar.php'; ?>

<div class="content-wrapper">

    <section class="content-header">
        <h1>
            <?php echo ucwords($controller); ?>
            <small><?php echo ucwords($action); ?></small>
        </h1>
        <ol class="breadcrumb">
            <li>
                <?php TheHomePage(); ?>
            </li>
            <li class="active"><?php echo ucwords($controller); ?></li>
        </ol><!--breadcrumb-->
    </section><!--content-header-->

    <section class="content">
	   <?php include_once isset($page) ? $page : 'pages/'.$controller.'-'.$action.'.php'; ?>
    </section><!--content-->

</div><!--content-wrapper-->
<?php include_once 'footer.php'; ?>