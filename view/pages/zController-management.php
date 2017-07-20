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
		editButton = grid.tbar[1],
		moveToTrashButton = grid.tbar[2];

		if (selection.length === 1) {
			editButton.enable();
		} else {
			editButton.disable();
		}

		if (selection.length >= 1) {
			moveToTrashButton.enable();			
		} else {
			moveToTrashButton.disable();			
		}
	},
	onClearSelect: function(grid) {
		grid.tbar[1].disable();
		grid.tbar[2].disable();
	},
	onRowDBLClick: function(grid, o) {
		grid.editRow(o.data);
	},
	editRow: function(item) {
		window.location.replace('?c=<?php echo $controller; ?>&a=edit&id='+item.id);
	},
	addRow: function() {
		window.location.replace('?c=<?php echo $controller; ?>&a=new');
	},
	moveToTrash: function(item) {
		$.ajax({
        	url: '?c=<?php echo $controller; ?>&a=moveToTrash&id='+item.id,
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
		text: 'Thêm mới',
		handler: function() {
			this.addRow();
		}
	}, {
		text: 'Sửa thông tin',
		disabled: true,
		tip: 'Chọn hoặc double-click vào hàng muốn sửa',
		handler: function() {
			var me = this,
			selection = me.getSelection(),
			item = selection[0];

			this.editRow(item);
		}
	}, {
		text: 'Chuyển đến thùng rác',
		tip: 'Chọn 1 hoặc nhiều hàng để chuyển đến thùng rác',
		handler: function() {
			var me = this,
			selection = me.getSelection();

			$.each(selection, function() {
				me.moveToTrash(this);
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
	}, {
		rowdblclick: 'onRowDBLClick'
	},],
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
				read: 	'?c=<?php echo $controller; ?>&a=getData',
			}
		}
	},
<?php echo fancygrid_columns($fancygrid['columns']); ?>
});
</script>