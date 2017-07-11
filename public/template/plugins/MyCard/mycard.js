/*
    Version: 1.0.0.3
    Author: Shin Lee
    Release: 2017-07-11
    News:
    1.0.0.2
    - Hỗ trợ nhiều template 
    - Cải thiện tốc độ tạo ảnh
    - Hỗ trợ tùy biến fields, class
    - Thêm tính năng debug xem trong console.log
    - Hỗ trợ dữ liệu từ fancygrid
    1.0.0.3
    - Thêm tính năng hiển thị thao tác .status
*/

function MyCard(_options) {
    var defaults = {
            appName: 'MyCard',
            namespace: _options.namespace || 'Demo',

            renderTo: _options.renderTo || '#MyCard',
            autoDownload: _options.autoDownload || false,

            debug: _options.debug || false,

            viewerCls: _options.viewerCls || 'viewer',
            cardCls: _options.cardCls || 'mycard',
            hintCls: _options.hintCls || 'hint',
            cardTemplates: _options.cardTemplates || false,
            fields: _options.fields || [
                'employee_id',
                'employee_name',
                'employee_position',
                'employee_department',
                'employeeImage',
                'print_count',
            ],

            // callback
            afterCreate: _options.afterCreate || false,
            afterAdd: _options.afterAdd || false,
            afterRemove: _options.afterRemove || false,
            afterClear: _options.afterClear || false,
            afterChange: _options.afterChange || false,
            afterDownload: _options.afterDownload || false,
            afterRending: _options.afterRending || false,
        },
        debug = defaults.debug || false,
        cardList = [], // Lưu html obj
        viewerTemplate = '<div class="'+defaults.viewerCls+'"><p class="'+defaults.hintCls+'">Empty Card</p><p class="status"></p></div></div>',
        cardTemplate = '<div class="'+defaults.cardCls+'" id="[employee_id]">[employee_id]</div>',
        cardControl = $('<div class="card-control overlay"><div class="centered"><i class="loading fa fa-refresh fa-spin text-blue"></i>'
                        +'<div class="control"><span action="'+defaults.namespace+'.RemoveCard" role="remove" class="control-action text-red"><i class="fa fa-times"></i></span></div><!--control--></div><!--centered--></div><!--overlay-->'),

        myCard = $(defaults.renderTo),
        viewer = myCard.find('.'+defaults.viewerCls),
        hint = myCard.find('.'+defaults.hintCls),
        status = myCard.find('.status'),
        renderCount = 0,
        zipCount = 0;
    
    // Note:
    // Các hàm đều giao tiếp bằng card_id
    // AddCard + CreateCard lấy object từ fancygrid

    // Setup
    this.Run = function() {
        if (!myCard.length) {
            console.log('[MyCard Error] - "renderTo" not set');
            return;
        }

        if (!viewer.length) {
            myCard.append(viewerTemplate);
            viewer = myCard.find('.'+defaults.viewerCls);
            hint = myCard.find('.'+defaults.hintCls);
            status = myCard.find('.status');
        }

        if (!hint.length) {
            viewer.append($(viewerTemplate).find('.'+defaults.hintCls));
            hint = myCard.find('.'+defaults.hintCls);
        }

        if (!status.length) {
            viewer.append($(viewerTemplate).find('.status'));
            status = myCard.find('.status');
        }

        if (!defaults.cardTemplates)
            defaults.cardTemplates.default = cardTemplate;

        if (debug) console.log('[MyCard Error] - Setup completed');
    };
    this.Run();

    // Thêm Card mới bằng Obj vào danh sách
    this.AddCard = function(_obj, _template_name = 'default') {
        // Thêm vào cardList
        var card = this.CreateCard(_obj, _template_name),
            //card_id = cardList.push(card) - 1;
            card_id = _obj.employee_id;

        // Thêm id cho card
        //card.attr('id', card_id);
        if (!card.find('.card-control').length)
            card.append(cardControl);
        card.find('.control-action').attr('action-data', card_id);

        // thêm vào cardList
        cardList.push(card);

        // Thêm vào viewer
        viewer.append(card);

        // Cập nhập status
        status.html('Added: '+card_id);

        if (debug) console.log('[MyCard Error] - Đã thêm card('+card_id+')');

        // calback event
        if (defaults.afterAdd)
            window[defaults.afterAdd](cardList, card);

        onChange();
    };

    // Xóa card bằng card_id
    this.RemoveCard = function(_card_id) {
        var index = this.GetCardIndex(_card_id);

        if (index > -1) {
            // Xóa khỏi danh sách
            cardList.splice(index, 1);

            // Xóa khỏi viewer
            viewer.find('.'+defaults.cardCls+'#'+_card_id).parent().remove();

            // Cập nhập status
            status.html('Removed: '+_card_id);

            if (debug) console.log('[MyCard Error] - Đã xóa card('+_card_id+')');
        } else {
            // Cập nhập status
            status.html('Remove failed: '+_card_id);

            if (debug) console.log('[MyCard Error] - Không tìm thấy card('+_card_id+')');
        }

        // calback event
        if (defaults.afterRemove)
            window[defaults.afterRemove](cardList);

        onChange();
    };

    // Xóa tất cả card
    this.Clear = function(_card_id) {
        cardList = [];
        viewer.find('.'+defaults.cardCls+'-container').remove();

        // Cập nhập status
        status.html('Clear completed');

        if (debug) console.log('[MyCard Error] - Đã xóa tất cả card');

        // calback event
        if (defaults.afterClear)
            window[defaults.afterClear](cardList);
    };

    // Tìm vị trí trong cardList bằng card_id
    // Kết quả trả về là index
    // -1 = không tìm thấy
    this.GetCardIndex = function(_card_id) { // done
        // Trả về -1 nếu không tìm thấy
        var index = -1;
        cardList.forEach(function(card, i) {
            if (card.find('.'+defaults.cardCls).attr('id') == _card_id) {
                // Trả về giá trị index
                index = i;
                return;
            };
        });

        if (debug) console.log('[MyCard Error] - Find index card('+_card_id+')='+index);
        return index;
    };

    // Tạo card theo cardTemplate
    // Tự động thay thế các field = value
    // Ex: [employee_id] thay bằng 16050095
    this.CreateCard = function(_obj, _template_name = 'default') {
        var card = '<div class="'+defaults.cardCls+'-container">'+defaults.cardTemplates[_template_name]+'</div>';

        if (!defaults.fields) {
            // Nếu không có fields thì loop hết tất cả các field
            $.each(_obj, function(key, value) {
                card = card.ReplaceAll('['+key+']', value);
            });
        } else {            
            $.each(defaults.fields, function(key, value) {
                card = card.ReplaceAll('['+value+']', _obj[value]);
            });
        };

        // set template
        card = card.ReplaceAll('[template_name]', _template_name);

        if (debug) console.log('[MyCard Error] - Tạo card thành công');

        card = $(card);

        // calback event
        if (defaults.afterCreate)
            window[defaults.afterCreate](cardList, card);

        return card;
    };

    // Tạo ảnh từ card_id
    this.CreateImage = function(_card_id, _auto_download = false) {
        var card = viewer.find('.'+defaults.cardCls+'#'+_card_id),
            card_outer = card.parent(),
            canvas = document.createElement('canvas'),
            context = canvas.getContext('2d'),
            MC = this;

        if (!card.length) {
            if (debug) console.log('[MyCard Error] - Không tìm thấy card('+_card_id+')');
            return;
        }

        // Xóa ảnh đã render
        card_outer.find('canvas.'+defaults.cardCls+'-render').remove();
        // Thêm class rending để biết đang quá trình đang diễn ra
        card_outer.attr('class', defaults.cardCls+'-container rending');

        // Set id, class, chiều cao + chiều rộng của hình
        canvas.id = _card_id;
        canvas.setAttribute('class', defaults.cardCls+'-render');
        canvas.width = card.width()*2;
        canvas.height = card.height()*2;

        console.log(card.outerHTML);

        // Chạy api tạo hình ảnh đổ vào canvas
        rasterizeHTML.drawHTML(card_outer.html(), canvas, {zoom:2})
        .then(function success(renderResult) {
            // Chèn ảnh vừa tạo vào card
            card_outer.append(canvas);
            // Đánh dấu đã thành công bằng
            card_outer.attr('class', defaults.cardCls+'-container success');

            if (debug) console.log('[MyCard Error] - Tạo ảnh thành công: card('+_card_id+')');
            
            // Cập nhập status
            status.html('Rending completed: '+_card_id);

            // Tự động download
            if (_auto_download)
                MC.DownloadCard(_card_id);
        }, function error(e) {
            // Đánh dấu tạo ảnh không thành công
            card_outer.attr('class', defaults.cardCls+'-container error');

            // Cập nhập status
            status.html('Rending failed: '+_card_id);

            if (debug) {
                console.log('[MyCard Error] - Tạo ảnh thất bại: card('+_card_id+')');                
                console.log(e);
            };
        });

        // calback event
        if (defaults.afterRending)
            window[defaults.afterRending](cardList, _card_id);
    };

    // Tạo nhiều ảnh
    this.CreateImageAll = function(_clear_before = true, _auto_download = false) {
        if (_clear_before) { // đặt class trở về mặc định
            renderCount = 0;
            zipCount = 0;
            viewer.find('.'+defaults.cardCls+'-container').attr('class', defaults.cardCls+'-container');
        }

        // Lần lượt tạo ảnh nếu class chưa có đánh dấu
        var card_outers = viewer.find('.'+defaults.cardCls+'-container:not(.'+defaults.cardCls+'-container.rending):not(.'+defaults.cardCls+'-container.success):not(.'+defaults.cardCls+'-container.error)');

        if (!card_outers.length) {
            if (_auto_download)
                zipAll();

            return;
        }

        var card = $(card_outers.find('.'+defaults.cardCls)[0]),
            card_outer = card.parent(),
            card_id = card.attr('id'),
            canvas = document.createElement('canvas'),
            context = canvas.getContext('2d'),
            MC = this;

        if (!card.length) {
            if (debug) console.log('[MyCard Error] - Không tìm thấy card('+card_id+')');
            return;
        }

        // Xóa ảnh đã render
        card_outer.find('canvas.'+defaults.cardCls+'-render').remove();
        // Thêm class rending để biết đang quá trình đang diễn ra
        card_outer.attr('class', defaults.cardCls+'-container rending');

        // Set id, class, chiều cao + chiều rộng của hình
        canvas.id = card_id;
        canvas.setAttribute('class', defaults.cardCls+'-render');
        canvas.width = card.width()*2;
        canvas.height = card.height()*2;

        // Chạy api tạo hình ảnh đổ vào canvas
        rasterizeHTML.drawHTML(card_outer.html(), canvas, {zoom:2})
        .then(function success(renderResult) {
            // Chèn ảnh vừa tạo vào card
            card_outer.append(canvas);
            // Đánh dấu đã thành công bằng
            card_outer.attr('class', defaults.cardCls+'-container success');

            if (debug) console.log('[MyCard Error] - Tạo ảnh thành công: card('+card_id+')');

            // Cập nhập status
            renderCount++;
            status.html('Rending completed: '+renderCount+'/'+cardList.length);

            // Next
            MC.CreateImageAll(false, _auto_download);
        }, function error(e) {
            // Đánh dấu tạo ảnh không thành công
            card_outer.attr('class', defaults.cardCls+'-container error');

            if (debug) {
                console.log('[MyCard Error] - Tạo ảnh thất bại: card('+card_id+')');
                console.log(e);
            };
        });

        // calback event
        if (defaults.afterRending)
            window[defaults.afterRending](cardList, card_id);
    };

    // Tải về card với định dạng zip bằng card_id
    this.DownloadCard = function(_card_id) {
        var renders = viewer.find('canvas.'+defaults.cardCls+'-render#'+_card_id);

        // Kiểm tra nếu đã tồn tại image thì mới download
        // Nếu không tồn tại thì gọi hàm tạo ảnh và tự động down về
        if (!renders.length) {
            this.CreateImage(_card_id, true);
            return;
        }

        // Nếu đã tồn tại render thì tiếp tục download
        var zip = new JSZip(),
            zip_name = defaults.appName+'-'+defaults.namespace,
            zip_folder = zip.folder(zip_name),
            img_name = _card_id + '.png',
            img_data = renders[0].toDataURL(); // chỉ lấy hình đầu tiên

        // Thêm file vào zip
        zip_folder.file(img_name, img_data.split('base64,')[1], {base64: true});

        // Đóng gói và tải xuống
        zip.generateAsync({type:'blob'}).then(function(content) {
            saveAs(content, zip_name+'_'+_card_id+'.zip');
            if (debug) console.log('[MyCard Error] - Tải về thành công '+zip_name+'_'+_card_id+'.zip');
        });

        // Cập nhập status
        status.html('Zip completed: '+_card_id);

        // calback event
        if (defaults.afterDownload)
            window[defaults.afterDownload](cardList, _card_id);
    };

    // Tải tất cả ảnh (gọi hàm CreateImageAll(true, true));
    this.DownloadCardAll = function() {
        this.CreateImageAll(true, true);
    };

    // Hàm add tất cả ảnh vào 1 file
    function zipAll() {
        var renders = viewer.find('canvas.'+defaults.cardCls+'-render');

        // Kiểm tra xem có ảnh hay không
        // Nếu không thì thoát
        if (!renders.length) {
            var mess = 'Không tìm thấy ảnh để tải về';
            alert(mess);
            if (debug) console.log('[MyCard Error] - '+mess);
            return;
        }

        // Nếu có thì tiếp tục
        var zip = new JSZip(),
            zip_name = defaults.appName+'-'+defaults.namespace,
            zip_folder = zip.folder(zip_name),
            card_id = 0;
        
        $.each(renders, function() {
            var render = $(this),
                render_id = render.attr('id'),
                img_name = render_id + '.png',               
                img_data = render[0].toDataURL(); // chỉ lấy ảnh đầu tiên

            card_id = render_id;

            // Thêm file vào zip
            zip_folder.file(img_name, img_data.split('base64,')[1], {base64: true});

            // Cập nhập status
            zipCount++;
            status.html('Zip completed: '+zipCount+'/'+cardList.length);

            // calback event
            if (defaults.afterDownload)
                window[defaults.afterDownload](cardList, card_id);
        });

        // Đóng gói và tải xuống
        zip.generateAsync({type:'blob'}).then(function(content) {
            if (renders.length == 1) {
                saveAs(content, zip_name+'_'+card_id+'.zip');
                if (debug) console.log('[MyCard Error] - Tải về thành công '+zip_name+'_'+card_id+'.zip');
            } else {
                saveAs(content, zip_name+'.zip');
                if (debug) console.log('[MyCard Error] - Tải về thành công '+zip_name+'.zip');
            }
        });
    };

    // Update gui
    function onChange() {
        var card = viewer.find('.'+defaults.cardCls+'-container:not(.'+defaults.cardCls+'-container.demo)');

        if (card.length)
            hint.addClass('hidden');
        else
            hint.removeClass('hidden');
        
        /*if (card.length == 1) {
            card.css('zoom', '100%');
            card.find('.overlay .loading').css('zoom', '100%');
            card.find('.overlay span').css('zoom', '100%');
        } else {*/
            card.css('zoom', '50%');
            card.find('.overlay .loading').css('zoom', '200%');
            card.find('.overlay span').css('zoom', '200%');
        //}


        // calback event
        if (defaults.afterChange)
            window[defaults.afterChange](cardList);
    };
}