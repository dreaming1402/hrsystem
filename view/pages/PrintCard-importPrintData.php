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
				    <p class="help-block">Chỉ đăng tải file excel (*.xls hoặc *.xlsx)</p>
            	</div><!--uploadImage-->

            	<div id="grid_<?php echo $page_id; ?>"></div>
            </div><!--box-body-->
        </div><!--box-body-->
    </section><!--col-md-12-->

</div><!--row-->
<script>
var uploader_<?php echo $page_id; ?> = new Uploader({
    renderTo: '#uploader_<?php echo $page_id; ?>',
    debug: true,
    autoUpload: true,
    multiFile: false,
    overwrite: true,
    maxSize: 5, //mb    
    server: {
        upload: '?c=File&a=upload&t=printData',
        delete: '?c=File&a=delete&t=printData',
    },
    namespace: 'uploader_<?php echo $page_id; ?>',
    hint: '<button action="uploader_<?php echo $page_id; ?>.Browers" class="control-action btn btn-default">Chọn</button> hoặc kéo thả file (*.xlsx hoặc *.xls) vào đây',
    extensions: ['xlsx', 'xls'],
    afterUploadSuccess: 'ImportExcel',
});

function ImportExcel(_response) {
    var file = _response.data.data;

    $('#grid_<?php echo $page_id; ?>').empty();

    new $.ajax({
        url: '?c=File&a=importExcel&t=<?php echo $table_name; ?>&f='+file,
        method: 'POST',
        success: function(data) {
            if (data.success) {
                $('#alert-area').html('<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+data.message+'</div>');

                /*var logs = [];
                $.each(data.data, function(index, row) {
                    var tmp = {};
                    $.each(row, function(key, value) {
                        tmp[key] = value;
                    });
                    logs.push(tmp);
                });*/

                var grid_<?php echo $page_id; ?> = new FancyGrid({
                    title: '<?php echo $page_title; ?>',
                    renderTo: 'grid_<?php echo $page_id; ?>',
                    theme: 'blue',
                    height: 580,
                    trackOver: true,
                    selModel: 'rows',
                    paging: {
                        pageSize: 50,
                        pageSizeData: [50,100,150,200]
                    },
                    data: data.data,
                    <?php echo $fancygrid; ?>
                });

            } else {
                $('#alert-area').html('<div class="alert alert-warning alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+data.message+'</div>');
            };            
        },
        error: function() {
            $('#alert-area').html('<div class="alert alert-warning alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>Export error</div>');
        },
    });
};
</script>