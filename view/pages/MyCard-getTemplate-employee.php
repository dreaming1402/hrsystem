<?php
if (!isset($mycard_class))
	$mycard_class = 'employee';

$plugin_dir = TEMPLATE_DIR.'/plugins/MyCard';

$unlimit = isset($_GET['unlimit']) ? '<div class="layer unlimit"><img src="'.$plugin_dir.'/img/unlimit.png"></div>' : '';
$datelimit = '';

$logo_src = 'src="'.$plugin_dir.'/img/logo.png"';
$background_src = 'src="'.$plugin_dir.'/img/bg-limit.png"';

$back_logo_src = 'src="'.$plugin_dir.'/img/logo-black.png"';
$back_background_src = 'src="'.$plugin_dir.'/img/bg-back.png"';


if (isset($_GET['pregnant'])) {
	$mycard_class .= ' pregnant';
	$datelimit = '<div class="layer datelimit"><p>Từ ngày: {start-date}</p></div>';

	$logo_src = 'src="'.$plugin_dir.'/img/logo.png"';
	$background_src = 'src="'.$plugin_dir.'/img/bg-pregnant.png"';
	
	$back_logo_src = 'src="'.$plugin_dir.'/img/logo.png"';
	$back_background_src = 'src="'.$plugin_dir.'/img/bg-pregnant.png"';

} else if (isset($_GET['hasbaby'])) {
	$mycard_class .= ' hasbaby';
	$datelimit = '<div class="layer datelimit"><p><span>Từ ngày:</span>{start-date}<br><span>Đến ngày:</span>{end-date}</p></div>';

	$logo_src = 'src="'.$plugin_dir.'/img/logo.png"';
	$background_src = 'src="'.$plugin_dir.'/img/bg-hasbaby.png"';
	
	$back_logo_src = 'src="'.$plugin_dir.'/img/logo.png"';
	$back_background_src = 'src="'.$plugin_dir.'/img/bg-hasbaby.png"';
	
} else if (isset($_GET['staff'])) {
	$mycard_class .= ' staff';

	$logo_src = 'src="'.$plugin_dir.'/img/logo-white.png"';
	$background_src = 'src="'.$plugin_dir.'/img/bg-staff.png"';

	$back_logo_src = 'src="'.$plugin_dir.'/img/logo-white.png"';
	$back_background_src = 'src="'.$plugin_dir.'/img/bg-back-staff.png"';

} 

?>

<div class="item">
	<div class="mycard <?php echo $mycard_class; ?>" 

		id="{print_card_id}" 
		date="<?php echo date('Y-m-d'); ?>" 
		description="{print_description}" 

		employee_department="{employee_department}" 
		employee_id="{employee_id}" 
		employee_full_name="{employee_full_name}"
		employee_position="{employee_position}" 
		employee_type="{employee_type}" 
		employee_contract_id="{employee_contract_id}"

		maternity_type="{maternity_type}" 
		maternity_begin="{maternity_begin}" 
		maternity_end="{maternity_end}">

		<div class="layer background"><img <?php echo $background_src; ?> ></div>
		<div class="layer company-logo"><img <?php echo $logo_src; ?> ></div>
		<div class="layer employee-image"><img {img-src} ></div>
		<div class="layer employee-detail">
			<p class="employee-position">{employee_position}</p>
			<p class="employee-name">{employee_full_name}</p>
			<p class="employee-id">{employee_id}</p>
			<p class="employee-department">{employee_department}</p>
		</div>
		<div class="layer company-slogan"><p>Word Class Apparel Leader</p></div>
<?php echo $unlimit; ?>
<?php if (isset($_GET['pregnant']) || isset($_GET['hasbaby'])) echo $datelimit;?>

		<style type="text/css">
<?php
$tmp = get_template('mycard', ['html'], 'mycard');
$tmp .= get_template('mycard', ['html'], 'mycard-employee');
echo $tmp;
?>
		</style>
	</div>
</div>