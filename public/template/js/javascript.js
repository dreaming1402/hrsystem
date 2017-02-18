$(document).ready(function(){

    $('.match').on('click', function() {
        $id = $(this).parent().attr('id');
        $.ajax({
            method: "GET",
            url: "copy.php",
            data: { id: $id }
        }).done(function( msg ) {
            $('.box#' + $id).html(msg);
        });
    });

    
});

String.prototype.replaceAll = function(search, replacement) { var target = this; return target.split(search).join(replacement); };
function tr_to_array(tr) { result = []; $(tr).find('td').each(function() { result.push($(this).html()); }); return result; }
function message(message, showAlert = true) { console.log(message); if(showAlert) alert(message); }

function get_file_name(file_path) { return file_path.split('\\').pop().split('/').pop(); }
function get_file_extension(file_path) { return file_path.split('.').pop(); }
function get_file_id(file_path) {
    /*var file_id = file_name.replaceAll('.' + get_file_extension(file_name), '');
    file_id = file_id.replaceAll(' ', '-'); // replace space by -
    file_id = file_id.replaceAll('.', '_'); // replace . by _
    return file_id;//.toLowerCase();*/
    return get_file_name(file_path).replaceAll('.'+get_file_extension(file_path), '');
}
/*function render_image(file, view) {
    var oFReader = new FileReader();
    oFReader.readAsDataURL(file);
    oFReader.onload = function (oFREvent) {
        $(view).attr('src', oFREvent.target.result).fadeIn();
    };
}*/

function syntaxHighlight(json) {
    var json = JSON.stringify(json, null, '\t'),
        style = $('#syntaxHighlight');
    if (style.length <= 0)
      $('body').prepend('<style id="syntaxHighlight">.string { color: green; }.number { color: darkorange; }.boolean { color: blue; }.null { color: magenta; }.key { color: red; }</style>');
    json = json.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
    return json.replace(/("(\\u[a-zA-Z0-9]{4}|\\[^u]|[^\\"])*"(\s*:)?|\b(true|false|null)\b|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?)/g, function (match) {
        var cls = 'number';
        if (/^"/.test(match)) {
            if (/:$/.test(match)) {
                cls = 'key';
            } else {
                cls = 'string';
            }
        } else if (/true|false/.test(match)) {
            cls = 'boolean';
        } else if (/null/.test(match)) {
            cls = 'null';
        }
        return '<span class="' + cls + '">' + match + '</span>';
    });
}
function phoneInputFn(value) {
  var value = parseInt(value.replace(/\-/g, ''));

  if (isNaN(value)) {
    value = '';
  } else {
    value = String(value);
  }

  switch (value.length) {
    case 0:
    case 1:
    case 2:
      break;
    case 4:
    case 5:
    case 6:
      value = value.replace(/^(\d{3})/, "$1-");
      break;
    case 7:
    case 8:
    case 9:
      value = value.replace(/^(\d{3})(\d{3})/, "$1-$2-");
      break;
    case 10:
      value = value.replace(/(\d{3})(\d{3})(\d{4})/, "$1-$2-$3");
      break;
    default:
      value = value.substr(0, 10);
      value = value.replace(/(\d{3})(\d{3})(\d{4})/, "$1-$2-$3");
  }

  return value;
}
function salaryInputFn(value) {
  var value = value.toString().replace('$', '').replace(/\,/g, '').replace('-', '').replace('.', '');

  if (value.length === 0) {
    value = '';
  } else if (value.length > 6) {
    value = value.substr(0, 6);
    value = '$' + value.replace(/\B(?=(\d{3})+(?!\d))/g, ',');
  } else {
    value = '$' + value.replace(/\B(?=(\d{3})+(?!\d))/g, ',');
  }

  return value;
}
function formatBytes(bytes, decimals = 0) {
  if(bytes == 0) return '0 Byte';
  var k = 1000,
      dm = decimals + 1 || 3,
      sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'],
      i = Math.floor(Math.log(bytes) / Math.log(k));
  return parseFloat((bytes / Math.pow(k, i)).toFixed(dm)) + ' ' + sizes[i];
}
function get_link_content(link) {
  var response = '',
      ajax = $.ajax({
          url: link,
          async: false,
          success: function(data) { response = data; },
      });
  return response;
}

function upload_file(form, callback = null) {
  var server = form.attr('action'),
      files = form.find('input:file')[0].files;
  if (!files.length)
    return false;

  var processing = form.find('.processing'),
      formData = new FormData();
  formData.append('file', files[0]);
 
  var ajax = $.ajax({
      url: server,
      method: 'POST',
      data: formData,
      cache: false,
      contentType: false,
      processData: false,
      beforeSend: function(jqXHR, settings) {
        if (processing.length)
          form.append('<i class="processing fa fa-refresh fa-spin"></i>');
        else
          form.find('.processing').removeClass('hidden');
      },
      success: function(data, textStatus, jqXHR) {
        if (callback != null)
          window[callback](data);
      },
      error: function(jqXHR, textStatus, errorThrown) {
          console.log(errorThrown);
      },
      complete: function(jqXHR, textStatus) {
        form.find('.processing').addClass('hidden');
        console.log({action:'upload_file: '+server, file: files[0].name, status: textStatus});
      }
  });
}

function delete_file(server, file, callback = null) {
  $.ajax({
      url: server,
      method: 'POST',
      data: {id:'"'+file+'"'},
      cache: false,
      contentType: false,
      processData: false,
      beforeSend: function(jqXHR, settings) {
        $('.processing').attr('class', 'processing fa fa-refresh fa-spin');
      },
      success: function(data, textStatus, jqXHR) {
        if (callback != null)
          window[callback](data);
      },
      error: function(jqXHR, textStatus, errorThrown) {
        $('.message').attr('class', 'message text-red');
        $('.message i').attr('class', 'icon fa fa-ban');
        $('.message span').html(errorThrown);
        console.log('Error: ' + errorThrown)
      },
      complete: function(jqXHR, textStatus) {
        $('.processing').attr('class', 'hidden');
        console.log('Complete: ' + textStatus);
      }
  });
}

function fancygrid_2_csv(grid_id, file_name) {
  var header_length = $(grid_id).find('.fancy-grid-header-cell-text').length,
      col_length = $(grid_id).find('.fancy-grid-column').length,
      cel_length = $(grid_id).find('.fancy-grid-cell').length,
      header = $(grid_id).find('.fancy-grid-header-cell-text'),
      data = $(grid_id).find('.fancy-grid-cell'),
      tmp = [],
      rows_total = cel_length/col_length,
      row_index = 1;

  $.each(header, function() {
    if (tmp[0] == null) tmp[0] = '';
    tmp[0] += '"'+this.innerText.trim()+'",';
  })

  $.each(data, function() {
    if (tmp[row_index] == null) tmp[row_index] = '';
    tmp[row_index] += '"'+this.innerText.trim()+'",';

    if (row_index >= rows_total) {
      row_index = 1; // 0 = header
    } else {
      row_index++;
    }
  })

  //$('#down').attr('href', make_text_file(tmp.join('\n')));

  make_text_file(tmp.join('\n'), true)

  function make_text_file(text, auto_download = false) {
    var text_file = null,
      data = new Blob([text], {type: 'text/csv;charset=utf-8;'});

    // If we are replacing a previously generated file we need to
    // manually revoke the object URL to avoid memory leaks.
    if (text_file !== null) {
      window.URL.revokeObjectURL(text_file);
    }

    text_file = window.URL.createObjectURL(data);

    if (auto_download)
      saveAs(data, file_name);

    return text_file;
  };
}


function execute_function_by_name(functionName, context, args = []) {
    var args = args.slice.call(arguments).splice(2),
        namespaces = functionName.split("."),
        func = namespaces.pop();

    for(var i = 0; i < namespaces.length; i++) {
        context = context[namespaces[i]];
    }

    return context[func].apply(context, args);
}

$(document).ready(function() {
  // helper
  $('body').on('click', '.control-action', function() {
      var action = $(this).attr('action'),
          data = $(this).attr('action-data'); //option1
          //data = [{sender: $(this), data: $(this).attr('action-data')}]; //option2

    //option1
      if (isNaN(data))
        data = [];
      else
        data = data.split('|');

      if (!Array.isArray(data))
        data = [data];

      execute_function_by_name(action, window, data);
  })
})