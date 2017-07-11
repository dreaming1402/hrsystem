<div class="row">

    <section class="col-md-12">
        <div class="box box-default">
            <div id="grid_<?php echo $page_id;?>"></div>
        </div><!--box-->
    </section><!--col-md-12-->

    <section class="col-md-12">
        <div class="box box-default">
            <div class="box-header">
                <i class="fa fa-download"></i>
                <h3 class="box-title">Tải về <a href="<?php echo UPLOAD_DIR.'/forms/FORM IN THE GIAY - ORI.docm'; ?>">Mẫu in thẻ giấy</a> và <a href="<?php echo TEMPLATE_DIR.'/fonts/roboto.zip'; ?>">Font chữ</a></h3>
            </div><!--box-header-->
        </div><!--box-->
    </section><!--col-md-4-->
    
</div><!--row-->
<script>
var employeeCard,
    // get template
    default_card_template = GetLinkContent('?c=MyCard&a=gettemplate&t=employee'),
    staff_card_template = GetLinkContent('?c=MyCard&a=gettemplate&t=employee&staff'),
    pregnancy_card_template = GetLinkContent('?c=MyCard&a=gettemplate&t=employee&pregnancy'),
    hasbaby_card_template = GetLinkContent('?c=MyCard&a=gettemplate&t=employee&hasbaby'),

    default_unlimit_card_template = GetLinkContent('?c=MyCard&a=gettemplate&t=employee&unlimit'),
    staff_unlimit_card_template = GetLinkContent('?c=MyCard&a=gettemplate&t=employee&staff&unlimit'),
    pregnancy_unlimit_card_template = GetLinkContent('?c=MyCard&a=gettemplate&t=employee&pregnancy&unlimit'),
    hasbaby_unlimit_card_template = GetLinkContent('?c=MyCard&a=gettemplate&t=employee&hasbaby&unlimit');

Fancy.defineController('conrol_<?php echo $page_id;?>', {
    onSelect: function(grid) { // done
        var selection = grid.getSelection(),
            print_button = grid.tbar[1];

        if (selection.length > 0) {
            print_button.enable();
        } else {
            print_button.disable();
        };
    },
    onClearSelect: function(grid) { // done
        grid.tbar[1].disable();
    },
    onCellClick: function(grid, o) { // done
        if (o.column.title != 'Tải') return;
        grid.printCard(o.data);
    },
    printCard: function(item) { // done
        var me = this,
            printCardForm = me.printCardForm;

        // Set template_name
        var template_name = [],
            contract_type = item.contract_type.toLowerCase(),
            employee_type = item.employee_type.toLowerCase(),
            employee_department = item.employee_department.toLowerCase(),
            maternity_type = item.maternity_type.toLowerCase(),            
            unlimit = ['packing 1', 'packing 2'];

        if (employee_type == 'worker')
            template_name.push('default');
        else
            template_name.push('staff');

        // thẻ bầu và thẻ có con nhỏ không phân biệt employee_type
        if (maternity_type != 'none')
            template_name = [maternity_type.ReplaceAll(' ', '')];

        // Kiểm tra quyền truy cập của nhân viên bằng tên bộ phận có trong unlimit[]
        if ($.inArray(employee_department, unlimit) >= 0)
            template_name.push('unlimit');

        // Nối lại thành tên hoàn chỉnh có dạng type_unlimit
        template_name = template_name.join('_');

        // Kiểm tra đã tạo form in thì bỏ qua bước tạo obj
        if (printCardForm) {
            // Xóa thẻ nếu có
            employeeCard.Clear();

            // Thêm mới thẻ được chọn
            employeeCard.AddCard(item, template_name);

            // Cập nhập form
            printCardForm.set('print_card_id', item.employee_id);
            printCardForm.set(item);

            // Hiển thị form
            printCardForm.show();
        } else {
            // Tạo form in thẻ mới nếu được kick hoạt lần đầu
            printCardForm = new FancyForm({
                title: {
                    text: 'In thẻ nhân viên',
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
                            if (this.get('print_card_type') == '') {
                                alert('Chọn loại thẻ cần in');
                                return;
                            };
                            if (this.get('print_description') == '') {
                                alert('Cần ghi chú lại lần in này với lý do gì');
                                return;
                            };

                            employeeCard.DownloadCardAll();
                            this.submit();
                            this.hide();
                        }
                    }
                ],
                events: [{
                    init: function() {
                        // Fill all fields
                        this.set(item);

                        // Hiển thị form
                        this.show();
                    }
                }],

                <?php echo FancyformParse($fancyform); ?>
            });

            me.printCardForm = printCardForm;

            // Chèn vào form in thẻ
            $('.print-viewer .fancy-field-set-items').prepend('<div id="MyCard"></div>');

            // Khởi tạo plugin MyCard
            employeeCard = new MyCard({
                debug: false,
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

            employeeCard.AddCard(item, template_name);
        };

        // Clear những field cần nhập
        me.printCardForm.set('print_description', '');

        // Nếu đang thử việc lựa chọn mặc định thẻ giấy
        if (contract_type == 'probation')
            me.printCardForm.set('print_card_type', 'Thẻ giấy');
        else if (maternity_type == 'pregnancy')
            me.printCardForm.set('print_card_type', 'Thẻ bầu');
        else if (maternity_type == 'has baby')
            me.printCardForm.set('print_card_type', 'Thẻ con nhỏ');
        else
            me.printCardForm.set('print_card_type', '');
    },
});
var gird_<?php echo $page_id;?> = new FancyGrid({
    title: 'Danh sách in thẻ',
	renderTo: 'grid_<?php echo $page_id;?>',
	theme: 'blue',
	height: 600,
	trackOver: true,
	selModel: 'rows',
	paging: {
        pageSize: 50,
        pageSizeData: [50,100,150,200]
	},
    controllers: ['conrol_<?php echo $page_id;?>'],    
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
            text: 'Tải xuống thẻ được chọn',
            tip: 'Bôi đen các hàng để in nhiều người',
            disabled: true,
            handler: function(){
                this.clearFilter();
                console.log(this.get('employee_gender'));
            }
        },
        {
            text: 'Xóa bộ lọc',
            handler: function(){
                this.clearFilter();
                console.log(this.get('employee_gender'));
            }
        },
        {
            text: 'Xuất ra Excel',
            tip: 'Tạm thời chỉ xuất được file .csv, upload mở trong Google Drive để đọc được chữ tiếng Việt',
            handler: function() {
                FancygridToCsv('#grid_<?php echo $page_id;?>', '<?php echo $page_title; ?>.csv');
            }
        }
    ],
	events: [
        { select: 'onSelect' },
        { clearselect: 'onClearSelect' },
        { cellclick: 'onCellClick' },
    ],
	data: {
		proxy: {
			api: {
				read: 	'?c=Employee&a=getPrintList',
			}
		}
	},   
<?php echo FancygridParse($fancygrid); ?>
});
</script>