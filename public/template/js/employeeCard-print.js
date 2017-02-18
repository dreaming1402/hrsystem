$(document).ready(function(){
	var $employee_card_template,
        $employee_staff_card_template,
        $employee_hasbaby_card_template,
        $employee_pregnant_card_template,

        $employee_unlimit_card_template,
        $employee_staff_unlimit_card_template,
        $employee_hasbaby_unlimit_card_template,
        $employee_pregnant_unlimit_card_template;

    function load_card_template() {
        $.when( $.ajax( "?c=card&a=getTemplate&t=employee" ) ).then(function( data, textStatus, jqXHR  ) {
            $employee_card_template = data;
        });
        $.when( $.ajax( "?c=card&a=getTemplate&t=employee&staff" ) ).then(function( data, textStatus, jqXHR  ) {
            $employee_staff_card_template = data;
        });
        $.when( $.ajax( "?c=card&a=getTemplate&t=employee&hasbaby" ) ).then(function( data, textStatus, jqXHR  ) {
            $employee_hasbaby_card_template = data;
        });
        $.when( $.ajax( "?c=card&a=getTemplate&t=employee&pregnant" ) ).then(function( data, textStatus, jqXHR  ) {
            $employee_pregnant_card_template = data;
        });

        $.when( $.ajax( "?c=card&a=getTemplate&t=employee&unlimit" ) ).then(function( data, textStatus, jqXHR  ) {
            $employee_unlimit_card_template = data;
        });
        $.when( $.ajax( "?c=card&a=getTemplate&t=employee&staff&unlimit" ) ).then(function( data, textStatus, jqXHR  ) {
            $employee_staff_unlimit_card_template = data;
        });
        $.when( $.ajax( "?c=card&a=getTemplate&t=employee&hasbaby&unlimit" ) ).then(function( data, textStatus, jqXHR  ) {
            $employee_hasbaby_unlimit_card_template = data;
        });
        $.when( $.ajax( "?c=card&a=getTemplate&t=employee&pregnant&unlimit" ) ).then(function( data, textStatus, jqXHR  ) {
            $employee_pregnant_unlimit_card_template = data;
        });
    } load_card_template();

    function to_employee_card(array) {
        var employee = array,
            employee_id = employee[2],
            employee_name = employee[4],
            employee_position = employee[6],
            employee_department = employee[1],
            employee_maternity = employee[10].toLowerCase(),
            employee_type = employee[8].toLowerCase(),
            start_datelimit = employee[11],
            end_datelimit = employee[12],
            contract_type = employee[7].replaceAll(' ', '').toLowerCase(),

            template = '';

        if (employee[1].toLowerCase() == 'packing 1' || employee[1].toLowerCase() == 'packing 2') {
            // unlimit template
            if (employee_maternity == 'pregnancy') {
                template = $employee_pregnant_unlimit_card_template;
            } else if (employee_maternity == 'has baby') {
                template = $employee_hasbaby_unlimit_card_template;
            } else {
                if (employee_type == 'staff') {
                    template = $employee_staff_unlimit_card_template;
                } else {
                    template = $employee_unlimit_card_template;
                }
            }
        } else {
            if (employee_maternity == 'pregnancy') {
                template = $employee_pregnant_card_template;
            } else if (employee_maternity == 'has baby') {
                template = $employee_hasbaby_card_template;
            } else {
                if (employee_type == 'staff') {
                    template = $employee_staff_card_template;
                } else {
                    template = $employee_card_template;
                }
            }
        }


        employee_card = template
        employee_card = employee_card.replaceAll('{img-src}', 'src="?c=employeeImage&a=view&id={id}"');
        employee_card = employee_card.replaceAll('{id}', employee_id);
        employee_card = employee_card.replaceAll('{name}', employee_name);
        employee_card = employee_card.replaceAll('{position}', employee_position);
        employee_card = employee_card.replaceAll('{department}', employee_department);
        employee_card = employee_card.replaceAll('{start-date}', start_datelimit);
        employee_card = employee_card.replaceAll('{end-date}', end_datelimit);
        employee_card = employee_card.replaceAll('{contract-type}', contract_type);
        //employee_card = employee_card.replaceAll('{slogan}', 'Word Class Apparel Leader');

        return employee_card;
    }

    function capture(selector) {
        var canvas = document.createElement('canvas'),
            context = canvas.getContext('2d'),
            html_container = $(selector).parent(),
            html = html_container.html();

        html_container.find('canvas.render').remove();
        html_container.append('<div class="process"><div class="middle center"><div class="loading"></div><p class="message">Loading...</p></div></div>');

        canvas.id = html_container.attr('id');
        canvas.setAttribute('class', 'render');
        canvas.width = $(selector).width();
        canvas.height = $(selector).height();

        rasterizeHTML.drawHTML(html)
        .then(function success(renderResult) {
            context.drawImage(renderResult.image, 0, 0);
            html_container.append(canvas);
            html_container.find('.process').fadeOut(400);
            html_container.find('.process').remove();
        }, function error(e) {
            html_container.find('.loading').remove();
            html_container.find('.message').addClass('error');
            html_container.find('.message').text(e);
        });
    };

    $('#getImages').on('click', function () {  $('#card-view .mycard').each(function() { capture(this); }); });

	$('#downloadAll').on('click', function () {
        var zip = new JSZip(),
            img = zip.folder('mycard'),
            render = $('#card-view canvas.render');

        render.each(function() {
            var imgData = $(this)[0].toDataURL(),
                archive = $(this).parent().attr('archive');
                console.log(archive);
            img.file(archive + '_' + $(this).attr('id') + '.png', imgData.split('base64,')[1], {base64: true});
        });
        zip.generateAsync({type:'blob'}).then(function(content) {
            saveAs(content, 'mycard' + '.zip');
        });
    });

	$table_employeeCard = $('#table-employeeCard-print').DataTable({
        //"ajax": "?c=employeeDatabase",
        "deferRender": true,
        "scrollX": true,
        "scrollY": '480px',
        searching: true,
        ordering:  true,
        fixedColumns: false,
        fixedHeader: false,
        select: true,
        rowReorder: true,
        responsive: false,
        keys: false,
        autoFill: false,
        dom: 'Bfrtip',
        buttons: [
            'copy', 'excel', 'print'//, 'pdf'
        ],
        "pageLength": 15,
    });

    $table_employeeCard.on( 'click', 'tbody tr.selected', function() {
        var html = '';
        $('#table-employeeCard-print').find('tbody tr.selected').each(function() {
            var employee = tr_to_array(this);
            html += to_employee_card(employee);
        })
        $('#card-view').html(html);

        if($('#card-view .item').length > 1) {
            $('#card-view .item').css('zoom', '25%');
        } else {
            $('#card-view .item').css('zoom', '50%');
        }

    }).on( 'deselect', function ( e, dt, type, indexes ) {
        if (indexes.length <= 1) {
            var employee_id = dt.data()[2];
            $('#card-view').find('#'+employee_id).remove();
        } else {
            var employees = dt.data();
            employees.each(function(index) {
                var employee_id = index[2];
                $('#card-view').find('#'+employee_id).remove();
            });
        }
    });

    $('#table-employeeCard-print-filter .data-index').on('change', function(event) {
        $table_employeeCard.column(this.id).search(this.value).draw();
    });

    $('#table-employeeCard-print-filter #reset').on('click', function(event) {
        $('#table-employeeCard-print-filter .data-index').val(function () {
            return $(this).find('option').filter(function () {
                return $(this).prop('defaultSelected');
            }).val();
        });

        $table_employeeCard.search('').columns().search('').draw();
    });
});