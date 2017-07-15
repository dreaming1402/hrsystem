<?php
function the_template($key, $types = []) {
	$result = '';
	foreach ($types as $type) {
		switch (strtolower($type)) {
			case 'css':
				$result .= '<link href="'.TEMPLATE_DIR.'/css/'.$key.'.css" rel="stylesheet" type="text/css">';
				break;
			case 'js':
				$result .= '<script src="'.TEMPLATE_DIR.'/js/'.$key.'.js" type="text/javascript"></script>';
				break;
		}
	}
	echo $result;
}

function get_template_source($key, $type) {
	if (isset($GLOBALS['app_configs']['template'][$key])) {
		return $GLOBALS['app_configs']['template'][$key][$type][0];
	}
}

function get_template($key, $types = [], $child_key = '') {
	$result = '';
	if (isset($GLOBALS['app_configs']['template'][$key])) {
		foreach ($GLOBALS['app_configs']['template'][$key] as $type => $value) {
			foreach ($value as $child => $url) {
				if ($child_key != '' && $child != $child_key) continue;
				if (!in_array($type, $types)) continue;

				if ($type == 'css') {
					$result .= '<link href="' . $url . '" rel="stylesheet" type="text/css">';
				} elseif ($type == 'js') {
					$result .= '<script src="' . $url . '" type="text/javascript"></script>';
				} elseif ($type == 'html') {
					$result .= file_get_contents($url);
				}
			}
		}
	}
	return $result;
}

function set_template($key, $value) {
	$GLOBALS['app_configs']['template'][$key] = $value;
	return $GLOBALS['app_configs']['template'][$key];
}

function get_templates($types = []) {
	$result = '';
	foreach ($GLOBALS['app_configs']['template'] as $key => $value) {
		$result .= get_template($key, $types);
	}
	return $result;
}


// Chuyển hướng trang
function redirect_to($page = 'index.php') {
    header('Location: '. BASE_URL . $page);
    exit();
}

function add_field($field, $collapse_item = false) {
	if (sizeof($field) <= 0)
		return false;

	$html = '';

	foreach ($field as $group_name => $group_content) {
		$num = rand(0,100);
		$element = '<fieldset>
					<a data-toggle="collapse" href="#group-'.$num.'"><legend>'.$group_name.'</legend></a><div class="collapse '.($collapse_item == $group_name ? 'in' : '').'" id="group-'.$num.'">';
		foreach ($group_content as $id => $content) {
			$element .= '<div class="form-group"><label class="col-md-5 control-label" for="'.$id.'">'.$content['label'].'</label><div class="col-md-7">'; 
			switch ($content['type']) {
				case 'checkbox':
					# code...
					break;

				case 'radio':
					# code...
					break;

				case 'textarea':
					# code...
					break;

				case 'button':
					foreach ($content['group'] as $btn_id => $btn_content) {
						$element .= '<button type="'.$btn_content['type'].'" class="btn '.$btn_content['class'].'">'.(isset($btn_content['icon']) ? '<span class="glyphicon '.$btn_content['icon'].'" aria-hidden="true"></span> ' : '').$btn_content['label'].'</button>';
					}
					break;

				case 'file':
					$element .= '<input id="'.$id.'" name="'.$id.'" type="'.$content['type'].'" class="file-loading">';
					$element .= '<script>$("#'.$id.'").fileinput({
								    overwriteInitial: true,
								    maxFileSize: 1500,
								    showClose: false,
								    showCaption: false,
								    browseLabel: "",
								    removeLabel: "",
								    browseIcon: "<i class=\'glyphicon glyphicon-folder-open\'></i>",
								    removeIcon: "<i class=\'glyphicon glyphicon-remove\'></i>",
								    removeTitle: "Cancel or reset changes",
								    elErrorContainer: \'#kv-avatar-errors-1\',
								    msgErrorClass: \'alert alert-block alert-danger\',
								    defaultPreviewContent: \'<img src="'.TEMPLATE_DIR.'/plugins/bootstrap-fileinput/v4.3.6/img/default_avatar_male.jpg" alt="Your Avatar" style="width:160px">\',
								    layoutTemplates: {main2: \'{preview} {remove} {browse}\'},
								    allowedFileExtensions: '.$content['extension'].'
								});</script>';
					break;

				case 'date':
					$element .= '<input id="'.$id.'" name="'.$id.'" type="'.$content['type'].'" placeholder="'.$content['placeholder'].'" value="'.(isset($content['value']) ? $content['value'] : '').'" class="form-control datepicker" '.($content['required'] ? 'required' : '').'>';
					//$element .= '<script>$("#'.$id.'").datepicker({dateFormat: "dd-mm-yyyy", autoclose: true}).datepicker("setDate", new Date());</script>';
					break;

				case 'select':
					$element .= '<select id="'.$id.'" name="'.$id.'" class="form-control select2" style="width: 100%;">';
					$element .= $id;
					foreach ($content['value']['option'] as $key => $value) {
						$element .= '<option value="'.$key.'"'.($content['value']['selected'] == $key ? 'selected' : '').'>'.$value.'</option>';
					}
					$element .= '<select>';
					$element .= '<script>$(".select2").select2();</script>';
					break;

				default:
					$element .= '<input id="'.$id.'" name="'.$id.'" type="'.$content['type'].'" placeholder="'.$content['placeholder'].'" value="'.(isset($content['value']) ? $content['value'] : '').'" class="form-control" '.($content['required'] ? 'required' : '').'>';
					break;
			}
			$element .= '</div></div>';
		}
		$element.= '</fieldset>';

		$html .= $element;
	}

	echo $html;
}

function show_message($type = 'success', $message, $element_id = 'body ') {
	$show_alert = '<script>$("'.$element_id.'.alert").removeClass("hidden");</script>';
    $hide_alert = '<script>$("'.$element_id.'.alert").addClass("hidden");</script>';
    $type_alert = '<script>$("'.$element_id.'.alert").attr("class", "alert alert-'.$type.'");</script>';
	$message_alert = '<script>$("'.$element_id.'.alert").html("'.$message.'")</script>';
	echo $message_alert.$type_alert.$show_alert;
}
