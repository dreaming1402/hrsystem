<div class="row">

    <section id="alert-area" class="col-md-12">
    </section><!--alert-area-->

    <section class="col-md-12">
        <div class="box box-default">
            <div class="box-header">
                <i class="fa fa-download"></i>
                <h3 class="box-title">Tải về <a href="<?php echo UPLOAD_DIR.'/forms/FORM IN THE GIAY - ORI.docm'; ?>">Mẫu in thẻ giấy</a> và <a href="<?php echo TEMPLATE_DIR.'/fonts/roboto.zip'; ?>">Font chữ</a></h3>
            </div><!--box-header-->
        </div><!--box-->
    </section><!--col-md-4-->

    <section class="col-md-12">
        <div class="box box-default">
            <div id="grid_<?php echo $page_id;  ?>"></div>
        </div><!--box-->
    </section><!--col-md-12-->
    
</div><!--row-->
<script>
var employeeCard,
    // get template
    default_card_template = GetLinkContent('?c=PrintCard&a=gettemplate&t=employee'),
    staff_card_template = GetLinkContent('?c=PrintCard&a=gettemplate&t=employee&staff'),
    pregnancy_card_template = GetLinkContent('?c=PrintCard&a=gettemplate&t=employee&pregnancy'),
    hasbaby_card_template = GetLinkContent('?c=PrintCard&a=gettemplate&t=employee&hasbaby'),

    default_unlimit_card_template = GetLinkContent('?c=PrintCard&a=gettemplate&t=employee&unlimit'),
    staff_unlimit_card_template = GetLinkContent('?c=PrintCard&a=gettemplate&t=employee&staff&unlimit'),
    pregnancy_unlimit_card_template = GetLinkContent('?c=PrintCard&a=gettemplate&t=employee&pregnancy&unlimit'),
    hasbaby_unlimit_card_template = GetLinkContent('?c=PrintCard&a=gettemplate&t=employee&hasbaby&unlimit');

Fancy.defineController('controller_<?php echo $page_id; ?>', {
    onSelect: function(_grid) { // done
        var selection = _grid.getSelection(),
            viewDetail_button = _grid.tbar[1],
            print_button = _grid.tbar[2];

        if (selection.length > 0) {
            viewDetail_button.enable();
            print_button.enable();
        } else {
            viewDetail_button.disable();
            print_button.disable();
        };
    },
    onClearSelect: function(_grid) { // done
        _grid.tbar[1].disable();
        _grid.tbar[2].disable();
    },
    onCellClick: function(_grid, o) { // done
        if (o.column.title == 'Tải') {
            if(employeeCard) employeeCard.Clear();
            _grid.printCard(o.data);
        } else if (o.column.title == 'Xem') {
            _grid.viewDetail(o.data);
        }
    },
    onRowDBLClick: function(_grid, o) {
        _grid.viewDetail(o.data);
    },
    printCardAll: function(_grid) {
        var items = _grid.getSelection(),
            me = this;
        if(employeeCard) employeeCard.Clear();
        $.each(items, function(index, item) {
            me.printCard(item);
        });
    },
    printCard: function(_item) { // done
        var me = this,
            printCardForm = me.printCardForm;

        // Set template_name
        var template_name = [],
            contract_type = _item.contract_type.toLowerCase(),
            employee_type = _item.employee_type.toLowerCase(),
            employee_department = _item.employee_department.toLowerCase(),
            maternity_type = _item.maternity_type.toLowerCase(),            
            unlimit = ['packing 1', 'packing 2'];

        if (employee_type == 'worker')
            template_name.push('default');
        else
            template_name.push('staff');

        // thẻ bầu và thẻ có con nhỏ không phân biệt employee_type
        if (maternity_type != 'none' && maternity_type != '')
            template_name = [maternity_type.ReplaceAll(' ', '')];

        // Kiểm tra quyền truy cập của nhân viên bằng tên bộ phận có trong unlimit[]
        if ($.inArray(employee_department, unlimit) >= 0)
            template_name.push('unlimit');

        // Nối lại thành tên hoàn chỉnh có dạng type_unlimit
        template_name = template_name.join('_');

        // Kiểm tra đã tạo form in thì bỏ qua bước tạo obj
        if (printCardForm) {
            // Thêm mới thẻ được chọn
            employeeCard.AddCard(_item, template_name);

            // Cập nhập form
            printCardForm.set(_item);
            printCardForm.set('db_print_card_id', _item.employee_id);

            // Hiển thị form
            printCardForm.show();
        } else {
            // Tạo form in thẻ mới nếu được kick hoạt lần đầu
            printCardForm = new FancyForm({
                title: {
                    text: '<?php echo $page_title; ?>',
                    tools: [
                        {
                            text: 'Đóng',
                            handler: function() {
                                this.hide();
                            }
                        }
                    ]
                },
                theme: 'blue',
                window: true,
                modal: true,
                draggable: true,
                width: 500,
                height: 'fit',
                method: 'POST',
                buttons: ['side',
                    {
                        text: 'Đóng',
                        handler: function() {
                            this.hide();
                        }
                    },
                    {
                        text: 'Tải về',
                        handler: function() {
                            if (this.get('db_print_card_name') == '') {
                                alert('Chọn loại thẻ cần in');
                                return;
                            };
                            if (this.get('db_print_card_desc') == '') {
                                alert('Cần ghi chú lại lần in này với lý do gì');
                                return;
                            };

                            employeeCard.DownloadCardAll();
                            
                            var print_form = this,
                                db_print_card_name = this.get('db_print_card_name'),
                                db_print_card_desc = this.get('db_print_card_desc');
                            $.each(new $('.mycard-card-container'), function(key, value) {
                                var employee_id = $(value).attr('id'),
                                    item = grid_<?php echo $page_id; ?>.findItem('employee_id', employee_id);

                                    SubmitForm(item[0].data, print_form, db_print_card_name, db_print_card_desc);
                            });

                            this.hide();

                            $('#alert-area').html('<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>Đã tải về thành công</div>');
                        }
                    }
                ],
                events: [{
                    init: function() {
                        // Fill all fields
                        this.set(_item);
                        this.set('db_print_card_id', _item.employee_id);

                        // Hiển thị form
                        this.show();
                    }
                }],

                <?php echo $fancyform; ?>
            });

            me.printCardForm = printCardForm;

            // Chèn vào form in thẻ
            $('.print-viewer .fancy-field-set-items').prepend('<div id="MyCard"></div>');

            // Khởi tạo plugin MyCard
            employeeCard = new MyCard({
                debug: true,
                namespace: 'employeeCard',
                cardTemplates: {
                    default: default_card_template,
                    staff: staff_card_template,
                    pregnancy: pregnancy_card_template,
                    hasbaby: hasbaby_card_template,

                    default_unlimit: default_unlimit_card_template,
                    staff_unlimit: staff_unlimit_card_template,
                    pregnancy_unlimit: pregnancy_unlimit_card_template,
                    hasbaby_unlimit: hasbaby_unlimit_card_template,
                },
            });

            employeeCard.AddCard(_item, template_name);
        };

        // Clear những field cần nhập
        me.printCardForm.set('db_print_card_desc', '');

        // Nếu đang thử việc lựa chọn mặc định thẻ giấy
        if (contract_type == 'probation')
            me.printCardForm.set('db_print_card_name', 'Thẻ giấy');
        else if (maternity_type == 'pregnancy')
            me.printCardForm.set('db_print_card_name', 'Thẻ bầu');
        else if (maternity_type == 'has baby')
            me.printCardForm.set('db_print_card_name', 'Thẻ con nhỏ');
        else
            me.printCardForm.set('db_print_card_name', '');

        // Mod submit
        function SubmitForm(_item, _form, _db_print_card_name, _db_print_card_desc) {
            _form.set(_item);
            _form.set('db_print_card_id', _item.employee_id);
            _form.set('db_print_card_name', _db_print_card_name);
            _form.set('db_print_card_desc', _db_print_card_desc);
            _form.submit();
        };
    },
    viewDetail: function(_item) {
        var me = this,
            viewDetailGrid = me.viewDetailGrid;

        // Nếu đã tồn tại grid
        if (viewDetailGrid) {
            viewDetailGrid.data.proxy.api.read = '?c=PrintCard&a=getPrintData&employee_id='+_item.employee_id;

            viewDetailGrid.setSubTitle(_item.employee_id+' - '+_item.employee_name);
            viewDetailGrid.load();
            viewDetailGrid.show();
        } else {
            // Nếu chưa thì tạo mới grid
            viewDetailGrid = new FancyGrid({
                theme: 'blue',
                width: 580,
                trackOver: true,
                selModel: 'rows',
                paging: {
                    pageSize: 50,
                    pageSizeData: [50,100,150,200]
                },
                events: [{
                    init: function() {
                        // Hiển thị form
                        this.show();
                    }
                }],

                window: true,
                modal: true,
                draggable: true,
                height: 400,
                title: {
                    text: 'Chi tiết lịch sử in thẻ',
                    tools: [
                        {
                            text: 'Đóng',
                            handler: function() {
                                this.hide();
                            }
                        }
                    ]
                },
                subTitle: {
                    text: _item.employee_id+' - '+_item.employee_name,
                },
                buttons: ['side',
                    {
                        text: 'Đóng',
                        handler: function() {
                            this.hide();
                        }
                    },
                ],
                data: {
                    proxy: {
                        api: {
                            read: '?c=PrintCard&a=getPrintData&employee_id='+_item.employee_id,
                        },
                    },
                },
                <?php echo $childgrid; ?>
            });
        }

        me.viewDetailGrid = viewDetailGrid;
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
            paramsText: 'Cài đặt'
        },
        {
            text: 'Xem chi tiết',
            tip: 'Click chuột 2 lần vào hàng muốn xem',
            disabled: true,
            handler: function(){
                this.viewDetail(this.getSelection()[0]);
            }
        },
        {
            text: 'Tải xuống thẻ được chọn',
            tip: 'Bôi đen nhiều hàng để in nhiều người',
            disabled: true,
            handler: function(){
                this.printCardAll(this);
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
	events: [
        { select: 'onSelect' },
        { clearselect: 'onClearSelect' },
        { cellclick: 'onCellClick' },
        { rowdblclick: 'onRowDBLClick' },
    ],
    <?php echo $fancygrid; ?>
});
</script>