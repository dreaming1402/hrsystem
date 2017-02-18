$(document).ready(function(){
    function view_card(array) {
        var employee_id = array[2],
            print_date = array[13],
            archive_dir = array[7].replaceAll(' ', '').toLowerCase(); 

        employee_card = '<div class="item" id="{id}"><img {img-src} alt="{id}"></div>';
        employee_card = employee_card.replaceAll('{img-src}', 'src="?c=employeeCard&a=view&id={id}&date={print_date}&archive={archive_dir}"');
        employee_card = employee_card.replaceAll('{id}', employee_id);
        employee_card = employee_card.replaceAll('{print_date}', print_date);
        employee_card = employee_card.replaceAll('{archive_dir}', archive_dir);

        return employee_card;
    }

	$table_employeeCard_viewHistory = $('#table-employeeCard-viewHistory').DataTable({
        //"ajax": "?c=employeeDatabase",
        "deferRender": true,
        "scrollX": true,
        "scrollY": '480px',
        select: true,
        rowReorder: true,
        dom: 'Bfrtip',
        buttons: [
            'copy', 'excel', 'print'//, 'pdf'
        ],
        "pageLength": 15,
    });

    $table_employeeCard_viewHistory.on( 'click', 'tbody tr.selected', function() {
        var html = '';
        $('#table-employeeCard-viewHistory').find('tbody tr.selected').each(function() {
            var employee = tr_to_array(this);
            html += view_card(employee);
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

    $('#table-employeeCard-viewHistory-filter .data-index').on('change', function(event) {
        $table_employeeCard_viewHistory.column(this.id).search(this.value).draw();
    });

    $('#table-employeeCard-viewHistory-filter #reset').on('click', function(event) {
        $('#table-employeeCard-viewHistory-filter .data-index').val(function () {
            return $(this).find('option').filter(function () {
                return $(this).prop('defaultSelected');
            }).val();
        });

        $table_employeeCard_viewHistory.search('').columns().search('').draw();
    });
});