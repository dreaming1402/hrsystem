<div class="row">

    <section class="col-md-8">
        <div class="box box-default">
            <div id="form_<?php echo $page_id; ?>"></div>
        </div><!--box-->
    </section><!--col-md-8-->

    <section id="alert-area" class="col-md-4">
    </section><!--alert-area-->

    <section class="col-md-4">
        <div class="box box-default">
            <div class="box-header with-border">
                <h3 class="box-title">Tạo hồ sơ</h3>
                <div class="box-tools pull-right"><button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button></div>
            </div><!--box-header-->
            <div class="box-body">
                <p><i class="fa fa-key"></i> Trạng thái: <strong>Bản nháp</strong> (<a href="#">chỉnh sửa</a>)</p>
                <p><i class="fa fa-calendar"></i> Ngày tạo: <strong>Ngay lập tức</strong> (<a href="#">chỉnh sửa</a>)</p>
            </div><!--box-body-->
            <div class="box-footer">
                <div class="pull-right">
                    <span class="btn disabled"><i class="processing fa fa-refresh fa-spin hidden"></i></span>
                    <button action="new_data" class="control-action btn btn-primary">Tạo hồ sơ</button>
                </div>
            </div><!--box-footer-->
        </div><!--box-->
    </section><!--col-md-4-->

    <section class="col-md-4">
        <div class="box box-default">
            <div class="box-header">
                <i class="fa fa-paperclip"></i>
                <h3 class="box-title">Đính kèm</h3>
                <div class="box-tools pull-right"><button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button></div>
            </div><!--box-header-->
            <div class="box-body">
                <div class="row">
                    <div class="col-xs-5">
                        <div id="employeeImage">                     
                        </div><!--employeeImage-->
                    </div><!--col-xs-5-->
                    <div class="col-xs-7">
                        <label>Ảnh 3x4</label>
                        <p class="help-block">Image file (*.png)</p>
                        <div class="form-group">
                            <button action="upload_ei.deleteFile" action-data="0" class="control-action delete btn btn-default">Xóa ảnh</button>
                        </div><!--form-group-->
                        <span class="message text-green hidden"></span>
                    </div><!--col-xs-7-->
                </div><!--row-->
            </div><!--box-body-->
        </div><!--box-->
    </section><!--col-md-4-->

</div><!--row-->

<style>
#employeeImage .drop-zone {
    min-height: 100px;
}
#employeeImage .hint {
    margin: 20px 0px;
}
#employeeImage .thumb {
    width: 100%;
    height: 100px;
    border: none;
}
#employeeImage .info {
    display: none;
}

#employeeImage .overlay { display: none !important; }
</style>

<script>
var form_<?php echo $page_id; ?> = new FancyForm({
    renderTo: 'form_<?php echo $page_id; ?>',
    title: '<?php echo $page_title; ?>',
    width: 'fit',
    height: 'fit',
    activeTab: <?php echo (isset($_GET['t'])) ? $_GET['t'] : 0;?>,

    <?php echo fancyform_items($fancyform); ?>    
})

function new_data() {
    console.log(form_<?php echo $page_id; ?>.get());
    $.ajax({
        method: 'POST',
        data:form_<?php echo $page_id; ?>.get(),
        beforeSend: function() {            
            $('.processing').removeClass('hidden');
        },
        success: function(data, textStatus, jqXHR) {
            $('.processing').addClass('hidden');
            if (data.success) {
                $('#alert-area').html('<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+data.message+'</div>');
                window.location.replace('?c=<?php echo $controller; ?>&a=edit&id='+data.data.id);
            } else {
                $('#alert-area').html('<div class="alert alert-warning alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+data.message+'</div>');
            }
        }
    });
}

var item_id = 0;
function store_upload_id(data) {
    item_id = data.item_id;
    $('.form-group .control-action[action="upload_<?php echo $page_id; ?>.deleteFile"]').attr('action-data', item_id)
}

function upload_success(data) {
    var response = data.data;
    $('.message').html(response.message);
    if (response.success) {
        $('.message').attr('class', 'message text-green');
    }
    else {
        $('.message').attr('class', 'message text-red');      
    }
}
function delete_success(data) {
    var response = data.data;
    $('.message').html(response.message)
    if (response.success) {
        $('.message').attr('class', 'message text-green');
        $('.form-group .control-action[action="upload_<?php echo $page_id; ?>.deleteFile"]').attr('action-data', '')
    } else {
        $('.message').attr('class', 'message text-red');
    }
}
var upload_<?php echo $page_id; ?> = new Upload({
    namespace: 'upload_<?php echo $page_id; ?>',
    renderTo: '#employeeImage',
    server: {
        upload: '?c=File&a=upload&t=employeeImage',
        delete: '?c=File&a=delete&t=employeeImage',
    },
    hint: '<button action="upload_<?php echo $page_id; ?>.browers" class="control-action btn btn-default">Chọn</button> hoặc kéo thả ảnh vào đây',
    extensions: ['png'],
    autoSend: true,
    maxSize: 5, //mb
    callback: {
        onAddItem: 'store_upload_id',
        onSendFileSuccess: 'upload_success',
        onDeleteFileSuccess: 'delete_success',
    },
    /*placeholder:  {
        thumb: '?c=<?php echo $controller; ?>Image&a=view&id=<?php echo $controller_data[strtolower($controller)."_id"]; ?>',
        file_id: '<?php echo $controller_data[strtolower($controller)."_id"]; ?>',
    }*/
});
</script>