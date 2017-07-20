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
    onSelect: function(_grid) {
        var selection = _grid.getSelection(),
        moveToTrashButton = _grid.tbar[1];

        if (selection.length >= 1) {
            moveToTrashButton.enable();
        } else {
            moveToTrashButton.disable();
        }
    },
    onClearSelect: function(_grid) {
        _grid.tbar[1].disable();
    },
    moveToTrash: function(_item) {
        new $.ajax({
            url: '?c=<?php echo $controller; ?>&a=remove&id='+_item.id,
            method: 'PUT',
            success: function(data) {
                if (data.success) {
                    $('#alert-area').html('<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+data.message+'</div>');
                    grid_<?php echo $page_id; ?>.remove(_item.id);
                } else {
                    $('#alert-area').html('<div class="alert alert-warning alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+data.message+'</div>');
                }
            },
        });
    },
    exportExcel: function(_grid) {
        var rows = _grid.store.filteredData || _grid.getData(),
            col_names = [];

        if (rows.length) {
            if (_grid.store.filteredData) {
                $.each(rows[0].data, function(key, value) {
                    col_names.push(key);
                });
                var tmp = [];
                $.each(rows, function(key, value) {
                    tmp.push(value.data);
                });
                rows = tmp;
            } else {                
                $.each(rows[0], function(key, value) {
                    col_names.push(key);
                });
            }
        };

        new $.ajax({
            url: '?c=File&a=exportExcel&t=<?php echo $page_id; ?>',
            data: {data: rows, header: [col_names]},
            method: 'POST',
            success: function(data) {
                if (data.success) {
                    $('#alert-area').html('<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+data.message+' '+data.data+'</div>');
                    window.location = this.url+'&f='+data.data;
                } else {
                    $('#alert-area').html('<div class="alert alert-warning alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+data.message+'</div>');
                };
            },
            error: function() {
                $('#alert-area').html('<div class="alert alert-warning alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>Export error</div>');
            },
        });
    },
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
            text: 'Chuyển đến thùng rác',
            tip: 'Chọn 1 hoặc nhiều hàng để chuyển đến thùng rác',
            handler: function() {
                var me = this,
                selection = me.getSelection();

                $.each(selection, function() {
                    me.moveToTrash(this);
                });
            }
        },
        {
            text: 'Xóa bộ lọc',
            handler: function(){
                this.clearFilter();
            }
        },
        {
            text: 'Tải lại bảng',
            tip: 'Refresh',
            handler: function(){
                this.clearFilter();
                this.load();
            }
        },
        {
            text: 'Xuất ra Excel',
            handler: function() {
                this.exportExcel(this);
            }
        }
    ],
    events: [{
        select: 'onSelect'
    }, {
        clearselect: 'onClearSelect'
    },],
<?php echo $fancygrid; ?>
});
</script>