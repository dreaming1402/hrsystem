<div class="row">

    <section id="alert-area" class="col-md-12">
    </section><!--alert-area-->

    <section class="col-md-8">
        <div class="box box-default">
            <div id="grid_MyCard-management"></div>
        </div><!--box-->
    </section><!--col-md-12-->

    <section class="col-md-4">
        <div class="box box-default">
            <div class="box-header">
                <i class="fa fa-download"></i>
                <h3 class="box-title">Tải về <a href="<?php echo UPLOAD_DIR.'/forms/FORM IN THE GIAY - ORI.docm'; ?>">Mẫu in thẻ giấy</a> và <a href="<?php echo TEMPLATE_DIR.'/fonts/roboto.zip'; ?>">Font chữ</a></h3>
            </div><!--box-header-->
        </div><!--box-->
    </section><!--col-md-4-->

    <section class="col-md-4">
        <div class="box box-default">
            <div class="box-header">
                <i class="fa fa-check-square-o"></i>
                <h3 class="box-title">Thẻ đã chọn</h3>
            </div><!--box-header-->

            <div class="box-body">
                <div id="viewer"></div><!--viewer-->
            </div><!--box-body-->

            <div class="box-footer">                
                <div id="control">
                    <div action="employeeCard.downloadAll" class="control-action btn btn-default"><i class="fa fa-download"></i> Tải về</div>
                    <div class="pull-right">
                        <span class="btn disabled"><i class="processing fa fa-refresh fa-spin hidden"></i></span>
                        <div action="employeeCard.printAll" action-data="0" class="control-action btn btn-primary"><i class="fa fa-print"></i> In thẻ được chọn</div>
                    </div><!--pull-right-->
                </div><!--control-->
            </div><!--box-footer-->
        </div><!--box-->
    </section><!--col-md-4-->
    
</div><!--row-->
<script>
Fancy.defineTheme('staff', {
	config: {
		cellHeight: 60
	}
});

var gird_mcm = new FancyGrid({
	renderTo: 'grid_MyCard-management',
	title: 'Danh sách in thẻ',
	theme: 'staff',
	height: 600,
	trackOver: true,
	selModel: 'rows',
	columnLines: false,
	columnClickData: true,
	paging: {
        pageSize: 50,
        pageSizeData: [50,100,150,200]
	},
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
		width: 120,
        menu: true,
	},

    tbar: [{
        text: 'Xuất ra Excel',
        handler: function() {
            fancygrid_2_csv('#grid_MyCard-management', '<?php echo $page_title; ?>.csv');
        }
    }],

	events: [{
        rowclick: function(grid, e) {
            var selecteds = grid.getSelection();
            if (selecteds.length)
                employeeCard.clear();

            $.each(selecteds, function() {
                employeeCard.addItem(this);
            });
        }
    }],
	data: {
		proxy: {
			api: {
				read: 	'?c=Employee&a=getPrintList',
			}
		}
	},
<?php echo fancygrid_columns($fancygrid['columns']); ?>
});

var employee_card_template = get_link_content('?c=MyCard&a=gettemplate&t=employee'),
    employee_staff_card_template = get_link_content('?c=MyCard&a=gettemplate&t=employee&staff'),
    employee_hasbaby_card_template = get_link_content('?c=MyCard&a=gettemplate&t=employee&hasbaby'),
    employee_pregnant_card_template = get_link_content('?c=MyCard&a=gettemplate&t=employee&pregnant'),

    employee_unlimit_card_template = get_link_content('?c=MyCard&a=gettemplate&t=employee&unlimit'),
    employee_staff_unlimit_card_template = get_link_content('?c=MyCard&a=gettemplate&t=employee&staff&unlimit'),
    employee_hasbaby_unlimit_card_template = get_link_content('?c=MyCard&a=gettemplate&t=employee&hasbaby&unlimit'),
    employee_pregnant_unlimit_card_template = get_link_content('?c=MyCard&a=gettemplate&t=employee&pregnant&unlimit');

function to_employee_card(o) {
    var employee = o,

        employee_id = o.employee_id,
        employee_full_name = o.full_name,
        employee_position = o.position,
        employee_department = o.department,
        employee_type = o.employee_type,
        employee_contract_id = o.contract_id,

        maternity_type = o.db_maternity_maternity_type,
        maternity_begin = o.db_maternity_maternity_begin,
        maternity_end = o.db_maternity_maternity_end,

        template = '',
        unlimit = ['packing 1', 'packing 2'];

    if ($.inArray(employee_department.toLowerCase(), unlimit) >= 0) {
        // unlimit template
        if (maternity_type.toLowerCase() == 'pregnancy') {
            template = employee_pregnant_unlimit_card_template;
        } else if (maternity_type.toLowerCase() == 'has baby') {
            template = employee_hasbaby_unlimit_card_template;
        } else {
            if (employee_type.toLowerCase() == 'staff') {
                template = employee_staff_unlimit_card_template;
            } else {
                template = employee_unlimit_card_template;
            }
        }
    } else {
        if (maternity_type.toLowerCase() == 'pregnancy') {
            template = employee_pregnant_card_template;
        } else if (maternity_type.toLowerCase() == 'has baby') {
            template = employee_hasbaby_card_template;
        } else {
            if (employee_type.toLowerCase() == 'staff') {
                template = employee_staff_card_template;
            } else {
                template = employee_card_template;
            }
        }
    }


    employee_card = template;
    employee_card = employee_card.replaceAll('{print_card_id}', employee_id);

    employee_card = employee_card.replaceAll('{img-src}', 'src="?c=employeeImage&a=view&id={employee_id}"');
    employee_card = employee_card.replaceAll('{employee_department}', employee_department);
    employee_card = employee_card.replaceAll('{employee_id}', employee_id);
    employee_card = employee_card.replaceAll('{employee_full_name}', employee_full_name);
    employee_card = employee_card.replaceAll('{employee_type}', employee_type);
    employee_card = employee_card.replaceAll('{employee_position}', employee_position);

    employee_card = employee_card.replaceAll('{employee_contract_id}', employee_contract_id);

    employee_card = employee_card.replaceAll('{maternity_type}', maternity_type);
    employee_card = employee_card.replaceAll('{maternity_begin}', maternity_begin);
    employee_card = employee_card.replaceAll('{maternity_end}', maternity_end);
    //employee_card = employee_card.replaceAll('{slogan}', 'Word Class Apparel Leader');

    var print_description = prompt("Nhập giải thích cho lần in này ("+employee_id+" - "+employee_full_name+")", "");
    if (print_description == "" || print_description == null)
        return false;

    employee_card = employee_card.replaceAll('{print_description}', print_description);

    return employee_card;
}

function after_employee_card_print(item) {
    var mycard = $(item).find('.mycard'),

        print_card_id = mycard.attr('id'),
        print_description = mycard.attr('description'),
        print_date = mycard.attr('date'),

        employee_department = mycard.attr('employee_department'),
        employee_id = mycard.attr('employee_id'),
        employee_full_name = mycard.attr('employee_full_name'),
        employee_position = mycard.attr('employee_position'),
        employee_type = mycard.attr('employee_type'),
        employee_contract_id = mycard.attr('employee_contract_id'),

        maternity_type = mycard.attr('maternity_type'),
        maternity_begin = mycard.attr('maternity_begin'),
        maternity_end = mycard.attr('maternity_end'),

        ajax = $.ajax({
            method: 'POST',
            data: {
                print_card_id: print_card_id,
                print_description: print_description,
                print_date: print_date,

                employee_department: employee_department,
                employee_id: employee_id,
                employee_full_name: employee_full_name,
                employee_position: employee_position,
                employee_type: employee_type,
                employee_contract_id: employee_contract_id,

                maternity_type: maternity_type,
                maternity_begin: maternity_begin,
                maternity_end: maternity_end,
            },
        });
}

var employeeCard = new MyCard({
    renderTo: '#viewer',
    autoRending: false,
    toCard: 'to_employee_card',
    namespace: 'employeeCard',
    afterPrint: 'after_employee_card_print',
    afterDownload: 'after_employee_card_print',
    hint: 'Chưa có nhân viên nào được chọn',
});
</script>