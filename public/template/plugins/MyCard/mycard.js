function MyCard(option) {
// using
	/* var mycard = new MyCard({
		renderTo: '#viewer',
	    autoRending: false,
	    toCard: 'to_employee_card',
	    namespace: 'employeeCard',
    	afterPrint: 'after_employee_card_print',
        afterDownload: 'after_employee_card_download',
        hint: false,
	}); */

// setup
    var viewer = $(option.renderTo).find('.viewer'),
        hint = viewer.find('.hint'),

        item_lists = [],

        overlay = $('<div class="overlay"><i class="loading fa fa-refresh fa-spin text-blue"></i><div class="centered">'
                    +'<div class="control">'
                        +'<span action="'+option.namespace+'.printItem" role="print" class="control-action text-blue"><i class="fa fa-print"></i></span>'
                        +'<span action="'+option.namespace+'.downloadImage" role="run" class="control-action text-blue"><i class="fa fa-download"></i></span>'
                        +'<span action="'+option.namespace+'.downloadImage" role="trying" class="control-action text-orange"><i class="fa fa-refresh"></i></span>'
                        +'<span action="'+option.namespace+'.removeItem" role="remove" class="control-action text-red"><i class="fa fa-times"></i></span></div><!--control--></div><!--centered--></div><!--overlay-->'),
        template = $('<div id="'+option.namespace+'" class="viewer"><p class="hint">Empty Card</p><!--hint--></div><!--viewer-->');

    if (!viewer.length) {
        $(option.renderTo).prepend(template);
        viewer = $(option.renderTo).find('.viewer');
    };

    if (!hint.length) {
        if (option.hint)
            template.find('.hint').html(option.hint);

        viewer.append(template.find('.hint'));
        hint = viewer.find('.hint');
    };

// function
    function show_hint() {
        var items = viewer.find('.item:not(.item.demo)');
        if (items.length)
            hint.addClass('hidden');
        else
            hint.removeClass('hidden');

        items.css('zoom', '50%');
        items.find('.overlay .loading').css('zoom', '200%');
        items.find('.overlay span').css('zoom', '200%');
    }

    function add_item(o) {
        // college items
        var item = $(to_card(o)),
            item_id = item_lists.push(item) - 1;

        item.attr('id', item_id);
        item.append(overlay.clone());
        item.find('.control-action').attr('action-data', item_id);

        // add item to viewer
        viewer.append(item);

        if (option.autoRending)
            rending_image(item_id);

        show_hint();

        return item_id;
    }

    function remove_item(item_id) {
        viewer.find('.item#'+item_id).remove();
        show_hint();
    }

    function clear() { viewer.find('.item').remove(); }

    function rending_image(item_id, auto_download = false) {
        var item = viewer.find('.item#'+item_id),

            canvas = document.createElement('canvas'),
            context = canvas.getContext('2d');

        item.find('canvas.render').remove();
        item.attr('class', 'item processing');

        canvas.id = item.attr('id');
        canvas.setAttribute('class', 'render');
        canvas.width = item.width()*2;
        canvas.height = item.height()*2;

        rasterizeHTML.drawHTML(item.html(), canvas, {zoom:2})
        .then(function success(renderResult) {
            //context.drawImage(renderResult.image, 0, 0);
            item.append(canvas);
            item.attr('class', 'item succeed');

            if (auto_download)
                download_image(item_id);

        }, function error(e) {
            console.log(e);
            item.attr('class', 'item error');
        });
    }

    function rending_all(clean_before = true, auto_download = false) {
        if (clean_before) // clear class name and set to default 'item'
            viewer.find('.item').attr('class', 'item');

        var items = viewer.find('.item:not(.item.processing):not(.item.succeed)');

        if (items.length) {
            var item = $(items[0]),

                canvas = document.createElement('canvas'),
                context = canvas.getContext('2d');

            item.find('canvas.render').remove();
            item.attr('class', 'item processing');

            canvas.id = item.attr('id');
            canvas.setAttribute('class', 'render');
            canvas.width = item.width()*2;
            canvas.height = item.height()*2;

            rasterizeHTML.drawHTML(item.html(), canvas, {zoom:2})
            .then(function success(renderResult) {
                //context.drawImage(renderResult.image, 0, 0);
                item.append(canvas);
                item.attr('class', 'item succeed');

                // next
                rending_all(false, auto_download);

            }, function error(e) {
                console.log(e);
                item.attr('class', 'item error');
            });
        } else {
            if (auto_download)
                download_all();
        }
    }

    function download_image(item_id) {
        // check render exist
        var item = viewer.find('.item#'+item_id),
            render = item.find('canvas.render');
        if (!render.length)
            return;

        var zip = new JSZip(),
            mycard = item.find('.mycard'),
            zip_folder = zip.folder('MyCard-'+option.namespace),
            file_name = mycard.attr('id') + '.png',
            imgData = render[0].toDataURL();
        
        zip_folder.file(file_name, imgData.split('base64,')[1], {base64: true});

        zip.generateAsync({type:'blob'}).then(function(content) {
            saveAs(content, 'MyCard-'+option.namespace + '_' + mycard.attr('id') + '.zip'); // mycard_16050095.zip
        });

        // after download session
        if (option.afterDownload)
            window[option.afterDownload](item);
    }

    function download_all() {
        // check items exist
        var items = viewer.find('.item'),
        	zip = new JSZip(),
            zip_folder = zip.folder('MyCard-'+option.namespace);

        $.each(items, function() {
            var item = $(this),
                render = item.find('canvas.render');
			if (!render.length)
            	return;

            var mycard = item.find('.mycard'),
                file_name = mycard.attr('id') + '.png';

            // check image exist
            if (render.length) {
                var imgData = render[0].toDataURL();
                zip_folder.file(file_name, imgData.split('base64,')[1], {base64: true});
            }

            // after download session
            if (option.afterDownload)
                window[option.afterDownload](item);
            })

        zip.generateAsync({type:'blob'}).then(function(content) {
            saveAs(content, 'MyCard-'+option.namespace+'.zip'); // mycard.zip
        });
    }

    function print_item(item_id) {
        viewer.find('.item#'+item_id+' canvas.render').remove();
        var item = document.getElementById(item_id),
            print_viewer = window.open();

        print_viewer.document.open();
        print_viewer.document.write('<body onload="window.print()">'+item.innerHTML+'<style>@page{size:CR-80;margin:0px;}</style></body>');
        print_viewer.document.close();

        setTimeout(function(){
            print_viewer.close();

            // after print session
            if (option.afterPrint)
            	window[option.afterPrint](item);
        },100);
    }

    function print_all() {
        viewer.find('.item canvas.render').remove();
        var items = document.getElementsByClassName('item'),
            print_viewer = window.open(),
            tmp = '';

        $.each(items, function(index) {
            tmp += items[index].innerHTML;
        })

        print_viewer.document.open();
        print_viewer.document.write('<body onload="window.print()">'+tmp+'<style>@page{size:CR-80;margin:0px;}</style></body>');
        print_viewer.document.close();
        
        setTimeout(function(){
            print_viewer.close();

            // after print session
            if (option.afterPrint) {
                $.each(items, function(index) {
                    window[option.afterPrint](items[index]);
                })
            }
        },100);
    }

    function to_card(o) {
    	if (option.toCard)
    	    return window[option.toCard](o);
    	else
    		alert('Error: option.toCard function need to set');
    }

    function get_card_template(url) {
        var response = '',
            ajax = $.ajax({
                url: url,
                async: false,
                success: function(data) { response = data; },
            });
        return response;
    }

// public function
    this.addItem = function(o) { return add_item(o); }
    this.removeItem = function(item_id) { remove_item(item_id); }
    this.clear = function() { clear(); }

    this.rendingImage = function(item_id) { rending_image(item_id); }
    this.rendingAll = function(item_id) { rending_all(); }

    this.downloadImage = function(item_id) { rending_image(item_id, true); }
    this.downloadAll = function(clear_before = true) { rending_all(clear_before, true); }

    this.printItem = function(item_id) { print_item(item_id); }
    this.printAll = function() { print_all(); }
}