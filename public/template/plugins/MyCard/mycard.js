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
    - Thêm tính năng hiển thị thao tác .class
    1.0.0.4
    - Sửa .status thành .output
    - Thêm biến me đại diện cho this(root)
*/

function MyCard(_options) {
    var defaults = {
            appName: 'MyCard',
            namespace: _options.namespace || 'Demo',

            renderTo: _options.renderTo || '#MyCard',
            autoDownload: _options.autoDownload || false,

            debug: _options.debug || false,

            viewerCls: _options.viewerCls || 'mycard-viewer',
            cardCls: _options.cardCls || 'mycard-card',
            hintCls: _options.hintCls || 'mycard-hint',
            outputCls: _options.outputCls || 'mycard-output',
            cardTemplates: _options.cardTemplates || {
                default: false,
            },
            fields: _options.fields || [
                'employee_id',
                'employee_name',
                'employee_position',
                'employee_department',
                'employeeImage',
                'print_count',
                'maternity_begin',
                'maternity_end',
            ],
            hint: _options.hint || false,

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
        outputTemplate = '<p class="'+defaults.outputCls+'"></p>',
        viewerTemplate = '<div class="'+defaults.viewerCls+'"><p class="'+defaults.hintCls+'">Empty Card</p></div></div>',
        cardTemplate = '<div class="'+defaults.cardCls+'" id="[employee_id]">[employee_id]</div>',
        cardControl = $('<div class="card-control overlay"><div class="centered"><i class="loading fa fa-refresh fa-spin text-blue"></i>'
                        +'<div class="control"><span action="'+defaults.namespace+'.RemoveCard" role="remove" class="control-action text-red"><i class="fa fa-times"></i></span></div><!--control--></div><!--centered--></div><!--overlay-->'),

        myCard = $(defaults.renderTo),
        output = myCard.find('.'+defaults.outputCls),
        viewer = myCard.find('.'+defaults.viewerCls),
        hint = myCard.find('.'+defaults.hintCls),
        renderCount = 0,
        zipCount = 0,
        me = this;
    
    // Note:
    // Các hàm đều giao tiếp bằng card_id
    // AddCard + CreateCard lấy object từ fancygrid

    // Setup
    this.Run = function() {
        if (!myCard.length) {
            // Hiển thị thông báo
            error('"renderTo" not set', true);
            return;
        };
        myCard.addClass('mycard');

        if (!viewer.length) {
            myCard.append(viewerTemplate);
            viewer = myCard.find('.'+defaults.viewerCls);
            hint = myCard.find('.'+defaults.hintCls);
        };

        if (!hint.length) {
            viewer.append($(viewerTemplate).find('.'+defaults.hintCls));
            hint = myCard.find('.'+defaults.hintCls);
        } else {
            if (defaults.hint) {                
                hint.html(defaults.hint);
                hint = myCard.find('.'+defaults.hintCls);
            };
        };

        if (!output.length) {
            myCard.append(outputTemplate);
            output = myCard.find('.'+defaults.outputCls);
        };

        if (typeof _options.cardTemplates == 'undefined') {
            defaults.cardTemplates['default'] = cardTemplate;
        } else {
            defaults.cardTemplates.default = _options.cardTemplates.default || cardTemplate;
        };

        // Cập nhập output
        error('Setup completed');
    };
    this.Run();

    // Thêm Card mới bằng Obj vào danh sách
    this.AddCard = function(_obj, _template_name = 'default') {
        // Thêm vào cardList
        var card = me.CreateCard(_obj, _template_name),
            //card_id = cardList.push(card) - 1;
            card_id = _obj.employee_id;

        // Thêm id cho card
        card.attr('id', card_id);
        if (!card.find('.card-control').length)
            card.append(cardControl);
        card.find('.control-action').attr('action-data', card_id);

        // thêm vào cardList
        cardList.push(card);

        // Thêm vào viewer
        viewer.append(card);

        // Cập nhập output
        error('Đã thêm card('+card_id+')');

        // calback event
        if (defaults.afterAdd)
            window[defaults.afterAdd](cardList, card);

        onChange();

        return card_id;
    };

    // Xóa card bằng card_id
    this.RemoveCard = function(_card_id) {
        var index = me.GetCardIndex(_card_id);

        if (index > -1) {
            // Xóa khỏi danh sách
            cardList.splice(index, 1);

            // Xóa khỏi viewer
            viewer.find('.'+defaults.cardCls+'#'+_card_id).parent().remove();

            // Cập nhập output
            error('Đã xóa card('+_card_id+')');
        } else {
            // Cập nhập output          
            error('Không tìm thấy card('+_card_id+')');
        };

        // calback event
        if (defaults.afterRemove)
            window[defaults.afterRemove](cardList);

        onChange();
    };

    // Xóa tất cả card
    this.Clear = function() {
        cardList = [];
        viewer.find('.'+defaults.cardCls+'-container').remove();

        // Cập nhập output
        error('Đã xóa tất cả card');

        // calback event
        if (defaults.afterClear)
            window[defaults.afterClear](cardList);

        onChange();
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
                return index;
            };
        });
        
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
        card = $(card);

        // Cập nhập output
        error('Tạo card thành công');

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
            // Hiện thông báo
            error('Không tìm thấy card('+_card_id+')', true);
            return;
        };

        // Xóa ảnh đã render
        card_outer.find('canvas.'+defaults.cardCls+'-render').remove();
        // Thêm class rending để biết đang quá trình đang diễn ra
        card_outer.attr('class', defaults.cardCls+'-container rending');

        // Set id, class, chiều cao + chiều rộng của hình
        canvas.id = _card_id;
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

            // Cập nhập output
            error('Tạo ảnh thành công: card('+_card_id+')');

            // Tự động download
            if (_auto_download)
                MC.DownloadCard(_card_id);
        }, function error(e) {
            // Đánh dấu tạo ảnh không thành công
            card_outer.attr('class', defaults.cardCls+'-container error');

            // Cập nhập output
            error('Tạo ảnh thất bại: card('+_card_id+')');
            if (debug) {                
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
                me.zipAll();

            return;
        };

        var card = $(card_outers.find('.'+defaults.cardCls)[0]),
            card_outer = card.parent(),
            card_id = card.attr('id'),
            canvas = document.createElement('canvas'),
            context = canvas.getContext('2d'),
            MC = this;

        if (!card.length) {
            error('Không tìm thấy card('+card_id+')');
            return;
        };

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

            // Cập nhập output
            renderCount++;
            error('Rending completed: '+renderCount+'/'+cardList.length);

            // Next
            MC.CreateImageAll(false, _auto_download);
        }, function error(e) {
            // Đánh dấu tạo ảnh không thành công
            card_outer.attr('class', defaults.cardCls+'-container error');

            if (debug) {
                error('Tạo ảnh thất bại: card('+card_id+')');
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
            me.CreateImage(_card_id, true);
            return;
        };

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
            error('Tải về thành công '+zip_name+'_'+_card_id+'.zip');
        });

        // calback event
        if (defaults.afterDownload)
            window[defaults.afterDownload](cardList, _card_id);
    };

    // Tải tất cả ảnh (gọi hàm CreateImageAll(true, true));
    this.DownloadCardAll = function() {
        me.CreateImageAll(true, true);
    };

    // Hàm add tất cả ảnh vào 1 file
    function zipAll() {
        var renders = viewer.find('canvas.'+defaults.cardCls+'-render');

        // Kiểm tra xem có ảnh hay không
        // Nếu không thì thoát
        if (!renders.length) {
            error('Không tìm thấy ảnh để tải về', true);
            return;
        };

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

            // Cập nhập output
            zipCount++;
            error('Zip completed: '+zipCount+'/'+cardList.length);

            // calback event
            if (defaults.afterDownload)
                window[defaults.afterDownload](cardList, card_id);
        });

        // Đóng gói và tải xuống
        zip.generateAsync({type:'blob'}).then(function(content) {
            if (renders.length == 1) {
                saveAs(content, zip_name+'_'+card_id+'.zip');
                error('Tải về thành công '+zip_name+'_'+card_id+'.zip');
            } else {
                saveAs(content, zip_name+'.zip');
                error('Tải về thành công '+zip_name+'.zip');
            };
        });
    };

    // Update GUI
    function onChange() { // done
        var cards = viewer.find('.'+defaults.cardCls+'-container:not(.'+defaults.cardCls+'-container.demo)');

        if (cards.length)
            hint.addClass('hidden');
        else
            hint.removeClass('hidden');
        
        /*if (card.length == 1) {
            card.css('zoom', '100%');
            card.find('.overlay .loading').css('zoom', '100%');
            card.find('.overlay span').css('zoom', '100%');
        } else {*/
            cards.css('zoom', '50%');
            cards.find('.overlay .loading').css('zoom', '200%');
            cards.find('.overlay span').css('zoom', '200%');
        //}


        // calback event
        if (defaults.afterChange)
            window[defaults.afterChange](cardList);
    };

    // Error handler
    function error(_message, _show_alert = false) { // done
        if (_show_alert) alert(_message);
        if (debug) console.log('[Upload Error] - ' + _message);
        output.text(_message);
    };
};