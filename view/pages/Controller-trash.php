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
Fancy.defineController('grid_controller', {
	onSelect: function(grid) {
		var selection = grid.getSelection(),
		restoreButton = grid.tbar[0];
		permanentlyDeleteButton = grid.tbar[1];

		if (selection.length >= 1) {
			restoreButton.enable();
			permanentlyDeleteButton.enable();
		} else {
			restoreButton.disable();
			permanentlyDeleteButton.disable();
		}
	},
	onClearSelect: function(grid) {
		grid.tbar[0].disable();
		grid.tbar[1].disable();
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
	renderTo: 'grid_<?php echo $page_id; ?>',
	title: '<?php echo $page_title; ?>',
	height: 600,
	trackOver: true,
	selModel: 'rows',
	columnLines: false,
	columnClickData: true,
	paging: {
		pageSize: 50,
		pageSizeData: [50,100,150,200]
	},
	tbar: [{
		text: 'Khôi phục',
		tip: 'Chọn 1 hoặc nhiều hàng để khôi phục',
		handler: function() {
			var me = this,
			selection = me.getSelection();

			$.each(selection, function() {
				me.restoreFromTrash(this);
			})
		}
	}, {
		text: 'Xóa hoàn toàn',
		tip: 'Chọn 1 hoặc nhiều hàng để xóa hoàn toàn',
		handler: function() {
			var me = this,
			selection = me.getSelection();

			$.each(selection, function() {
				me.permanentlyDelete(this);
			})
		}
	}, {
		text: 'Xuất ra Excel',
		handler: function() {
			fancygrid_2_csv('#grid_<?php echo $page_id; ?>', '<?php echo $page_title; ?>.csv');
		}
	}],
	events: [{
		select: 'onSelect'
	}, {
		clearselect: 'onClearSelect'
	}],
	controllers: ['grid_controller'],
	defaults: {
		type: 'string',
		sortable: true,
		resizable: true,
		editable: false,
		vtype: 'notempty',
		ellipsis: true,
		filter: {
			header: true,
			emptyText: 'Tìm kiếm'
		},
		width: 130,
		menu: true,
	},
	data: {
		proxy: {
			api: {
				read: 	'?c=<?php echo $controller; ?>&a=getData&trash',
			}
		}
	},
<?php echo fancygrid_columns($fancygrid['columns']); ?>
});
</script>