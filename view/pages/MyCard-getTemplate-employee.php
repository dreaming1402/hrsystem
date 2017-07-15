<?php
if (!isset($mycard_class))
	$mycard_class = ['employee'];

$plugin_dir = TEMPLATE_DIR.'/plugins/MyCard';

// default
$background_src = 'src="'.$plugin_dir.'/img/bg-limit.png"';
$logo_src = 'src="'.$plugin_dir.'/img/logo.png"';

// Khu vực giới hạn
$layer_unlimit = '';
if (isset($_GET['unlimit']))
	$layer_unlimit = '<div class="layer unlimit"><img src="'.$plugin_dir.'/img/unlimit.png"></div>';

// Ngày hưởng chế độ
$layer_datelimit = '';

// Ngày in
$print_date = date('Y-m-d');

if (isset($_GET['pregnancy'])) {
	array_push($mycard_class, 'landscape pregnancy');
	$layer_datelimit = '<div class="layer datelimit"><p>Từ ngày: [maternity_begin]</p></div>';
	$background_src = 'src="'.$plugin_dir.'/img/bg-pregnancy.png"';
} else if (isset($_GET['hasbaby'])) {
	array_push($mycard_class, 'landscape hasbaby');
	$layer_datelimit = '<div class="layer datelimit"><p><span>Từ ngày:</span> [maternity_begin]<br><span>Đến ngày:</span> [maternity_end]</p></div>';
	$background_src = 'src="'.$plugin_dir.'/img/bg-hasbaby.png"';	
} else if (isset($_GET['staff'])) {
	array_push($mycard_class, 'landscape staff');
	$logo_src = 'src="'.$plugin_dir.'/img/logo-white.png"';
	$background_src = 'src="'.$plugin_dir.'/img/bg-staff.png"';
}
?>

<div class="mycard-card CR-80 <?php echo join($mycard_class, ' '); ?>" id="[employee_id]">
	<div class="layer background"><img <?php echo $background_src; ?> ></div>
	<div class="layer company-logo"><img <?php echo $logo_src; ?> ></div>
	<div class="layer employee-image"><img [employeeImage] ></div>
	<div class="layer employee-detail">
		<p class="employee-position">[employee_position]</p>
		<p class="employee-name">[employee_name]</p>
		<p class="employee-id">[employee_id]</p>
		<p class="employee-department">[employee_department]</p>
		<p class="print-date">[print_count]/<?php echo $print_date;?></p>
	</div>
	<div class="layer company-slogan"><p>Word Class Apparel Leader</p></div>
	<?php echo $layer_unlimit; ?>
	<?php echo $layer_datelimit; ?>
	<style type="text/css">
		<?php echo get_template('mycard', ['html'], 'mycard'); ?>
		<?php echo get_template('mycard', ['html'], 'mycard-employee'); ?>
	</style>
</div>