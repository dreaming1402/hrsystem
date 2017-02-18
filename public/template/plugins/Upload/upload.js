function Upload(option) {
// using
	/*upload = new Upload({
	    namespace: 'upload',
		renderTo: '#employee-uploadImage',
		server: {
			upload: '?c=file&a=upload&t=employeeImage',
			delete: '?c=file&a=delete&t=employeeImage',
		},
	    hint: '<button action="upload.browers" class="control-action btn btn-default">Chọn</button> hoặc kéo thả ảnh vào đây',
		extensions: ['png'],
		autoSend: true,
		maxSize: 2, //mb
		multiple: true,
	    thumb: false,
	    callback: {
	        onDragover: false,
	        onDragleave: false,
	        onDrop: false,

	        onChange: false,
	        onAddItem: false,
	        onRemoveItem: false,

	        onSendFile: false,
	        onSendFileSuccess: false,
	        onSendFileError: false,
	        onSendFileComplete: false,

	        onDeleteFile: false,
	        onDeleteFileSuccess: false,
	        onDeleteFileError: false,
	        onDeleteFileComplete: false,
	    },

	});*/

// setup
	var dropzone = $(option.renderTo).find('.drop-zone'),
		file_handler = dropzone.find('input:file.file-handler');
		hint = dropzone.find('.hint'),
		item_demo = dropzone.find('.item.demo'),
		item_lists = [],
		upload_busy = false,

		maxSize = option.maxSize*1024*1024 || 2*1024*1024, //default max = 2mb

		template = $('<div class="drop-zone"><input class="file-handler" type="file" name="file" id="file" title=" " multiple accept=""><span class="hint">'
						+'<button action="'+option.namespace+'.browers" class="control-action btn btn-default">Chọn</button> hoặc kéo thả file vào đây</span><div class="item demo"><div class="thumb"><div class="centered"><span class="file-extension">EXT</span></div><!--centered--></div><!--thumb--><div class="info"><span class="name">Name</span><span class="size">size</span></div><!--info--><div class="overlay"><i class="loading fa fa-refresh fa-spin text-blue"></i><div class="centered"><div class="control">'
                    	+'<span action="'+option.namespace+'.sendFile" role="run" class="control-action text-blue"><i class="fa fa-upload"></i></span>'
                    	+'<span action="'+option.namespace+'.sendFile" role="trying" class="control-action text-orange"><i class="fa fa-refresh"></i></span>'
                    	+'<span action="'+option.namespace+'.removeItem" role="remove" class="control-action text-red"><i class="fa fa-times"></i></span>'
                    	+'<span action="'+option.namespace+'.deleteFile" role="delete" class="control-action bg-red"><i class="fa fa-trash"></i></span>'
                	+'</div><!--control--></div><!--centered--></div><!--overlay--></div><!--item--></div><!--drop-zone-->');

	if (!dropzone.length) {
		$(option.renderTo).prepend(template);
		dropzone = $(option.renderTo).find('.drop-zone');
	};

	if (!file_handler.length) {
		dropzone.prepend(template.find('input:file.file-handler'));
		file_handler = dropzone.find('input:file.file-handler');
	}

	if (!hint.length) {
		if (option.hint)
			template.find('.hint').html(option.hint);

		dropzone.append(template.find('.hint'));
		hint = dropzone.find('.hint');
	};

	if (!item_demo.length) {
		dropzone.append(template.find('.item.demo'));
		item_demo = dropzone.find('.item.demo');
	};

	if (!option.multiple)
		dropzone.addClass('single-file');

	if (option.extensions.length) { file_handler.attr('accept', "." + option.extensions.join(',.')); }

	if (option.placeholder)
		add_placeholder(option.placeholder);

// event
	dropzone.on("dragover", dropzone, function(event) {
	    event.preventDefault();  
	    event.stopPropagation();
	    $(this).addClass('dragging');

		if (option.callback && option.callback.onDragover)
			window[option.callback.onDragover](event);
	});
	dropzone.on("dragleave", dropzone, function(event) {
	    event.preventDefault();  
	    event.stopPropagation();
	    $(this).removeClass('dragging');

		if (option.callback && option.callback.onDragleave)
			window[option.callback.onDragleave](event);
	});
	dropzone.on("drop", dropzone, function(event) {
	    event.preventDefault();  
	    event.stopPropagation();

	    if(!event.originalEvent.dataTransfer) return;

	    var files = event.originalEvent.dataTransfer.files;
	    if (option.multiple) {
		    $(files).each(function(index, file) {
		    	add_item(file);
		    })	    	
	    } else {
	    	if (files.length)
	    		add_item(files[0]);
	    }

		if (option.callback && option.callback.onDrop)
			window[option.callback.onDrop](event);
	});
	file_handler.on('change', function(e) {
		var files = $(this)[0].files;
		if (option.multiple) {
		    $(files).each(function(index, file) {
		    	add_item(file);
		    })	    	
	    } else {
	    	if (files.length)
	    		add_item(files[0]);
	    }
	});

// function
	function clear() { dropzone.find('.item').remove(); }
	function browers() { file_handler.trigger('click'); }

	function show_hint() {
		var items = dropzone.find('.item:not(.item.demo)');
		if (items.length)
			hint.addClass('hidden');
		else
			hint.removeClass('hidden');

		if (option.callback && option.callback.onChange)
			window[option.callback.onChange]('changed');
	}

	function add_placeholder(placeholder) {
		// college items
		var item_id = item_lists.push(placeholder.thumb) - 1,
			item = item_demo.clone(true, true);

		item.removeClass('demo');
		item.attr('id', item_id);
		item.attr('file_id', placeholder.file_id);
		item.find('.name').text(file.name);
		item.find('.size').text(format_bytes(file.size));
		item.find('.file-extension').text(file_extension(file.name));
		item.find('.control-action').attr('action-data', item_id);

		// add item to dropzone
		dropzone.find('.item:not(.item.demo)').remove();
		dropzone.append(item);
		
		item.find('.thumb').attr('class', 'thumb loaded');
		item.find('.thumb').css('background-image', 'url("'+placeholder.thumb+'")');

    	show_hint();

		if (option.callback && option.callback.onAddItem)
			window[option.callback.onAddItem]({file: file, item_id: item_id});

		return item_id;
	}

	function add_item(file) {
		// check file accept
		if ($.inArray(file_extension(file.name).toLowerCase(), option.extensions) < 0
			|| file.size > maxSize
			|| upload_busy) return;

		// college items
		var item_id = item_lists.push(file) - 1,
			item = item_demo.clone(true, true);

		item.removeClass('demo');
		item.attr('id', item_id);
		item.find('.name').text(file.name);
		item.find('.size').text(format_bytes(file.size));
		item.find('.file-extension').text(file_extension(file.name));
		item.find('.control-action').attr('action-data', item_id);

		// add item to dropzone
		if (!option.multiple) 
			dropzone.find('.item:not(.item.demo)').remove();
		dropzone.append(item);
		
		if (option.thumb) {
			item.find('.thumb').attr('class', 'thumb loaded');
			item.find('.thumb').css('background-image', 'url("'+option.thumb+'")');
		}

		if (option.autoSend) // upload file
			send_file(item_id);		

    	show_hint();

		if (option.callback && option.callback.onAddItem)
			window[option.callback.onAddItem]({file: file, item_id: item_id});

		return item_id;
	}

	function remove_item(item_id) {
		dropzone.find('.item#'+item_id).remove();

    	show_hint();

		if (option.callback && option.callback.onRemoveItem)
			window[option.callback.onRemoveItem]({file: item_lists[item_id], item_id: item_id});
	}

	function send_file(item_id) {
		if (upload_busy)
			return;

		var item = dropzone.find('.item#'+item_id),
			file = item_lists[item_id],
			formData = new FormData();
		
		formData.append('file', file);
		var ajax = $.ajax({
			url: option.server.upload,
			method: 'POST',
			data: formData,
			cache: false,
			contentType: false,
			processData: false,
			beforeSend: function(jqXHR, settings) {
				item.attr('class', 'item processing');

				if (!option.multiple)
					upload_busy = true;

				if (option.callback && option.callback.onSendFile)
					window[option.callback.onSendFile]({file: file, item_id: item_id});
			},
			success: function(data, textStatus, jqXHR) {
				if (data.data.file
					&& $.inArray(file_extension(data.data.file).toLowerCase(), ['png', 'jpg', 'jpeg']) >= 0) {
					item.find('.thumb').attr('class', 'thumb loaded');
					item.find('.thumb').css('background-image', 'url("'+data.data.file+'")');
				} else {
					item.find('.thumb').attr('class', 'thumb');
				}

				if (data.success) {
					item.attr('class', 'item succeed');
					item.attr('file_id', file_id(data.data.file));
				} else {
					item.attr('class', 'item failed');
				}

				if (option.callback && option.callback.onSendFileSuccess)
					window[option.callback.onSendFileSuccess]({data: data, textStatus: textStatus, jqXHR: jqXHR});
			},
			error: function(jqXHR, textStatus, errorThrown) {
				item.attr('class', 'item error');
				item.find('.loading').attr('class', 'loading fa fa-ban text-orange');
				console.log(errorThrown);

				if (option.callback && option.callback.onSendFileError)
					window[option.callback.onSendFileError]({jqXHR: jqXHR, textStatus: textStatus, errorThrown: errorThrown});
			},
			complete: function(jqXHR, textStatus) {
				console.log({action:'upload_file: '+ option.server.upload, file: file.name, status: textStatus});

				upload_busy = false;

				if (option.callback && option.callback.onSendFileComplete)
					window[option.callback.onSendFileComplete]({jqXHR: jqXHR, textStatus: textStatus});
			}
		});
	}

	function delete_file(item_id) {
		var item = dropzone.find('.item#'+item_id),
			file_id = item.attr('file_id'),
			param = '?&id='+file_id;

		if (option.server.delete.indexOf('?') >= 0)
			param = '&id='+file_id;

		var ajax = $.ajax({
			url: option.server.delete+param,
			method: 'DELETE',
			beforeSend: function(jqXHR, settings) {
				item.attr('class', 'item processing');

				if (option.callback && option.callback.onDeleteFile)
					window[option.callback.onDeleteFile]({file: file, item_id: item_id});
			},
			success: function(data, textStatus, jqXHR) {
				if (data.success) {
					item.remove();
				} else {
					item.attr('class', 'item failed');
				}

				show_hint();

				if (option.callback && option.callback.onDeleteFileSuccess)
					window[option.callback.onDeleteFileSuccess]({data: data, textStatus: textStatus, jqXHR: jqXHR});
			},
			error: function(jqXHR, textStatus, errorThrown) {
				item.attr('class', 'item error');
				item.find('.loading').attr('class', 'loading fa fa-ban text-orange');
				console.log(errorThrown);

				if (option.callback && option.callback.onDeleteFileError)
					window[option.callback.onDeleteFileError]({jqXHR: jqXHR, textStatus: textStatus, errorThrown: errorThrown});
			},
			complete: function(jqXHR, textStatus) {
				console.log({action:'delete_file: '+ option.server.delete, file_id: file_id, status: textStatus});

				if (option.callback && option.callback.onDeleteFileComplete)
					window[option.callback.onDeleteFileComplete]({jqXHR: jqXHR, textStatus: textStatus});
			}
		});
	}

	function format_bytes(bytes, decimals = 0) {
		if(bytes == 0) return '0 Byte';
		var k = 1000,
			dm = decimals + 1 || 3,
			sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'],
			i = Math.floor(Math.log(bytes) / Math.log(k));
		return parseFloat((bytes / Math.pow(k, i)).toFixed(dm)) + ' ' + sizes[i];
	}

	function file_extension(file_path) { return file_path.split('.').pop(); }
	function file_name(file_path) { return file_path.split('\\').pop().split('/').pop(); }
	function file_id(file_path) { return file_name(file_path).replace('.'+file_extension(file_path), ''); }

	this.addItem = function(file) { return add_item(file); }
	this.removeItem = function(item_id) { remove_item(item_id); }

	this.sendFile = function(item_id) { send_file(item_id); }
	this.deleteFile = function(item_id) { delete_file(item_id); }

	this.clear = function() { clear(); }
	this.browers = function() { browers(); }
}