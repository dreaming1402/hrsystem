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

<div class="item" id="{id}">
	<div class="mycard back <?php echo $mycard_class; ?>">
		<div class="layer background"><img <?php echo $back_background_src; ?> ></div>
		<div class="layer context">
			<p class="text"><strong>www.poongin.co.kr</strong></p>
			<p class="text">Phải đeo thẻ khi làm việc tại công ty.
				<small>Employees must visibly display the ID card when on company premises.</small>
			</p>
			<p class="text">Không cho người khác mượn thẻ.
				<small>The ID card mustnot be used by others or for purposes others then those authorized by company.</small>
			</p>
			<p class="text">Trả lại thẻ khi thôi việc.
				<small>Employees must return the ID card upon resignation.</small>
			</p>
			<p class="text">Liên lạc bộ phận nhân sự khi mất thẻ.
				<small>Contact to HR.Dept when the ID card lost.</small>
			</p>
			<p class="text">Nhặt được xin liên hệ theo số điện thoại.
				<small>When found, please contact the telephone number below.</small>
			</p>
		</div>		
		<div class="layer company-logo"><img <?php echo $back_logo_src; ?> ></div>
		<div class="layer company-contact">
			<p class="text">
				Phòng nhân sự - HR Department
				<small>0511.3796.755 ~ 57</small>
				<small>Lô P2, Đường số 6, KCN Hòa Khánh, Phường Hòa Khánh Bắc, Quận Liên Chiểu,<br>Thành phố Đà Nẵng, Việt Nam.</small>
				<small>MST: 0401681606</small>
			</p>
		</div>
		<style type="text/css">
<?php
$tmp = get_template('mycard', ['html'], 'mycard');
$tmp .= get_template('mycard', ['html'], 'mycard-employee');
echo $tmp;
?>
		</style>
	</div>
</div>