<?php include_once 'header.php'; ?>
<?php include_once 'sidebar.php'; ?>

<div class="content-wrapper">

    <section class="content-header">
        <h1>
            <?php echo ucwords($_GET['c']); ?>
            <small><?php echo ucwords($_GET['a']); ?></small>
        </h1>
        <ol class="breadcrumb">
            <li>
                <?php the_homepage(); ?>
            </li>
            <li class="active"><?php echo ucwords($_GET['c']); ?></li>
        </ol><!--breadcrumb-->
    </section><!--content-header-->

    <section class="content">
	   <?php include_once isset($page) ? $page : 'pages/'.$_GET['c'].'-'.$_GET['a'].'.php'; ?>
    </section><!--content-->

</div><!--content-wrapper-->
<?php include_once 'footer.php'; ?>