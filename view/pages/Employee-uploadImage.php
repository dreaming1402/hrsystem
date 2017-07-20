<div class="row">

    <section id="alert-area" class="col-md-12">
    </section><!--alert-area-->

    <section class="col-md-12">
        <div class="box box-default">
            <div class="box-header">
                <i class="fa fa-upload"></i>
                <h3 class="box-title"><?php echo $page_title; ?></h3>
            </div><!--box-header-->

        	<div class="box-body">
            	<div id="uploader_<?php echo $page_id; ?>">
				    <p class="help-block">Chỉ đăng tải file hình ảnh (*.png)</p>
            	</div><!--uploadImage-->
            </div><!--box-body-->
        </div><!--box-body-->
    </section><!--col-md-12-->

</div><!--row-->
<script>
var uploader_<?php echo $page_id; ?> = new Uploader({
    renderTo: '#uploader_<?php echo $page_id; ?>',
    debug: true,
    autoUpload: true,
    multiFile: true,
    overwrite: true,
    maxSize: 5, //mb    
    server: {
        upload: '?c=File&a=upload&t=employeeImage',
        delete: '?c=File&a=delete&t=employeeImage',
    },
    namespace: 'uploader_<?php echo $page_id; ?>',
    hint: '<button action="uploader_<?php echo $page_id; ?>.Browers" class="control-action btn btn-default">Chọn</button> hoặc kéo thả ảnh (.png) vào đây',
    extensions: ['png'],
});
</script>