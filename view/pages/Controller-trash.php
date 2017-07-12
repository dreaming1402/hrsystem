<div class="row">

    <section id="alert-area" class="col-md-12">
    </section><!--alert-area-->

    <section class="col-md-12">
        <div class="box box-default">
            <div id="grid_<?php echo $page_id; ?>"></div>
        </div><!--box-->
    </section><!--col-md-12-->

</div><!--row-->

<script>
Fancy.defineController('controller_<?php echo $page_id; ?>', {
	onSelect: function(grid) {
		var selection = grid.getSelection(),
		restoreButton = grid.tbar[1];
		permanentlyDeleteButton = grid.tbar[2];

		if (selection.length >= 1) {
			restoreButton.enable();
			permanentlyDeleteButton.enable();
		} else {
			restoreButton.disable();
			permanentlyDeleteButton.disable();
		}
	},
	onClearSelect: function(grid) {
		grid.tbar[1].disable();
		grid.tbar[2].disable();
	},
	restoreFromTrash: function(item) {
		$.ajax({
        	url: '?c=<?php echo $controller; ?>&a=restoreFromTrash&id='+item.id,
        	method: 'PUT',
			success: function(data) {
	            if (data.success) {
	                $('#alert-area').html('<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+data.message+'</div>');
					grid_<?php echo $page_id; ?>.remove(item.id);
	            } else {
	                $('#alert-area').html('<div class="alert alert-warning alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+data.message+'</div>');
	            }
			},
		});
	},
	permanentlyDelete: function(item) {
		$.ajax({
        	url: '?c=<?php echo $controller; ?>&a=permanentlyDelete&id='+item.id,
        	method: 'DELETE',
			success: function(data) {
	            if (data.success) {
	                $('#alert-area').html('<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+data.message+'</div>');
					grid_<?php echo $page_id; ?>.remove(item.id);
	            } else {
	                $('#alert-area').html('<div class="alert alert-warning alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+data.message+'</div>');
	            }
			},
		});
	}
});

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
    controllers: ['controller_<?php echo $page_id; ?>'],
	tbar: [
		{
            type: 'search',
            width: 350,
            emptyText: 'Tìm kiếm thông tin bất kỳ',
            tip: 'Nhập thông tin',
            paramsMenu: true,
            paramsText: 'Cài đặt',
        },
		{
			text: 'Khôi phục',
			tip: 'Chọn 1 hoặc nhiều hàng để khôi phục',
			handler: function() {
				var me = this,
				selection = me.getSelection();

				$.each(selection, function() {
					me.restoreFromTrash(this);
				})
			}
		},
		{
			text: 'Xóa hoàn toàn',
			tip: 'Chọn 1 hoặc nhiều hàng để xóa hoàn toàn',
			handler: function() {
				var me = this,
				selection = me.getSelection();

				$.each(selection, function() {
					me.permanentlyDelete(this);
				})
			}
		},
		{
			text: 'Xuất ra Excel',
            tip: 'Tạm thời chỉ xuất được file .csv, upload mở trong Google Drive để đọc được chữ tiếng Việt',
			handler: function() {
				fancygrid_2_csv('#grid_<?php echo $page_id; ?>', '<?php echo $page_title; ?>.csv');
			}
		}
	],
	events: [{
		select: 'onSelect'
	}, {
		clearselect: 'onClearSelect'
	}],
<?php echo FancygridParse($fancygrid); ?>
});
</script>