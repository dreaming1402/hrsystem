<div class="row">

    <section id="alert-area" class="col-md-12">
    </section><!--alert-area-->

    <section class="col-md-12">
        <div class="box box-default">
            <div class="box-header">
                <i class="fa fa-upload"></i>
                <h3 class="box-title">Tải lên danh sách</h3>
            </div><!--box-header-->

            <div class="box-body">
            	<div id="employeeDatabase"></div>
            	<p class="help-block">Chỉ đăng tải file excel (.xlsx)</p>
                <p class="message hidden"><i class="icon fa hidden"></i> <span></span></p>
            </div><!--box-body-->

            <div class="box-footer">
            	<div id="logs"></div>
            </div><!--box-footer-->
        </div><!--box-->
    </section><!--col-md-12-->

</div><!--row-->

<script>
var upload_eidb = new Upload({
    namespace: 'upload_eidb',
	renderTo: '#employeeDatabase',
    server: {
        upload: '?c=File&a=upload&t=employeeDatabase',
        delete: '?c=File&a=delete&t=employeeDatabase',
    },
    hint: '<button action="upload_eidb.browers" class="control-action btn btn-default">Chọn</button> hoặc kéo thả file (.xlsx) vào đây',
    extensions: ['xlsx'],
    autoSend: true,
    maxSize: 5, //mb
    callback: {
        onSendFile: 'upload_begin',
        onSendFileSuccess: 'upload_success',
    },
});

function upload_begin(data) {
	$('.message .icon').addClass('hidden');
	$('.message').attr('class', 'message text-blue');
	$('.message span').html('Đang tải lên ...');

    $('#logs').empty();
}

function upload_success(data) {
	var response = data.data;
	if (response.success) {
        $('.message').attr('class', 'message text-green');
		$('.message .icon').attr('class', 'icon fa fa-check');
    }
    else {
        $('.message').attr('class', 'message text-yellow');
		$('.message .icon').attr('class', 'icon fa fa-warning');
    }
    $('.message span').html(response.message);

    var logs = [];
    $.each(response.data.logs, function(table_name, querys) {
    	prefix = table_name.replaceAll('db_', ''),
		$.each(querys, function(query_status, query_data) {
			$.each(query_data, function(index, row_data) {
	    		logs.push({key: row_data[prefix+'_id'], table_name: table_name, query_status: query_status});
			});			
		});
    });

    grid_logs = new FancyGrid({
		renderTo: 'logs',
		title: 'Logs',
		height: 600,
		trackOver: true,
		selModel: 'rows',
		clicksToEdit: 1,
		columnLines: false,
		columnClickData: true,
		paging: {
			pageSize: 20,
			pageSizeData: [5,10,20,50]
		},
		defaults: {
			type: 'string',
			sortable: true,
			resizable: true,
			//editable: true,
			vtype: 'notempty',
			ellipsis: true,
			filter: {
				header: true,
				emptyText: 'Tìm kiếm'
			},
			flex: 1,
		},
		data: logs,
		columns: [
			{
				index: 'table_name',
				title: 'Table name'
			}, {
				index: 'key',
				title: 'Key'
			}, {
				index: 'query_status',
				title: 'Query status'
			},
		],
		grouping: {
		    by: 'table_name'
		},
	});
}
</script>