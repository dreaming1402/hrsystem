<div class="row">

    <section id="alert-area" class="col-md-12">
    </section><!--alert-area-->

    <section class="col-md-12">
        <div class="box box-default">
            <div class="box-header">
                <i class="fa fa-upload"></i>
                <h3 class="box-title">Tải lên ảnh thẻ</h3>
            </div><!--box-header-->

        	<div class="box-body">
            	<div id="employee_uploadImage">
				    <p class="help-block">Chỉ đăng tải file hình ảnh (*.png)</p>
            	</div><!--uploadImage-->
            </div><!--box-body-->
        </div><!--box-body-->
    </section><!--col-md-12-->

</div><!--row-->
<script>
var upload_eui = new Upload({
	renderTo: '#employee_uploadImage',
	server: {
        upload: '?c=File&a=upload&t=employeeImage',
        delete: '?c=File&a=delete&t=employeeImage',
    },
    hint: '<button action="upload_eui.browers" class="control-action btn btn-default">Chọn</button> hoặc kéo thả ảnh (.png) vào đây',
	extensions: ['png'],
	autoSend: true,
	maxSize: 5, //mb
	multiple: true,
    namespace: 'upload_eui',
});
</script>