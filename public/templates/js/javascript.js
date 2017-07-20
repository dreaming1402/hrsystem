$(document).ready(function() {
// Helper

// Hỗ trợ replace tất cả text có trong chuỗi
String.prototype.ReplaceAll = function(search, replacement) { var target = this; return target.split(search).join(replacement); };

// Chuyển dữ liệu row của bảng thành array
function TrToArray(_tr) { var result = []; $(_tr).find('td').each(function() { result.push($(this).html()); }); return result; }

// Hiển tị tin nhắn và console log
function ShowMessage(_message, _showAlert = true) { console.log(_message); if(_showAlert) alert(_message); }

// Lấy thông tin file
function GetFileName(_file_path) { return _file_path.split('\\').pop().split('/').pop(); }
function GetFileExtension(_file_path) { return _file_path.split('.').pop(); }
function GetFileId(_file_path) { return GetFileName(_file_path).ReplaceAll('.'+GetFileExtension(_file_path), ''); }

// Makeup json
function SyntaxHighlight(_json) {
    _json = JSON.stringify(_json, null, '\t'),
        style = $('#syntaxHighlight');
    if (style.length <= 0)
      $('body').prepend('<style id="syntaxHighlight">.string { color: green; }.number { color: darkorange; }.boolean { color: blue; }.null { color: magenta; }.key { color: red; }</style>');
    _json = _json.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
    return _json.replace(/("(\\u[a-zA-Z0-9]{4}|\\[^u]|[^\\"])*"(\s*:)?|\b(true|false|null)\b|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?)/g, function (match) {
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

// Định dạng số điện thoại
function PhoneInputFn(_value) {
  var _value = parseInt(_value.replace(/\-/g, ''));

  if (isNaN(_value)) {
    _value = '';
  } else {
    _value = String(_value);
  }

  switch (_value.length) {
    case 0:
    case 1:
    case 2:
      break;
    case 4:
    case 5:
    case 6:
      _value = _value.replace(/^(\d{3})/, "$1-");
      break;
    case 7:
    case 8:
    case 9:
      _value = _value.replace(/^(\d{3})(\d{3})/, "$1-$2-");
      break;
    case 10:
      _value = _value.replace(/(\d{3})(\d{3})(\d{4})/, "$1-$2-$3");
      break;
    default:
      _value = _value.substr(0, 10);
      _value = _value.replace(/(\d{3})(\d{3})(\d{4})/, "$1-$2-$3");
  }

  return _value;
}

// Định dạng tiền
function SalaryInputFn(_value, _currency = '$') {
  // Loại bỏ các ký tự đặc biệt
  _value = _value.toString().replace(_currency, '').replace(/\,/g, '').replace('-', '').replace('.', '');

  if (_value.length === 0) {
    _value = '';
  } else if (_value.length > 6) {
    _value = _value.substr(0, 6);
    _value = _currency + _value.replace(/\B(?=(\d{3})+(?!\d))/g, ',');
  } else {
    _value = _currency + _value.replace(/\B(?=(\d{3})+(?!\d))/g, ',');
  }

  return value;
}

// Lọc Fancygrid by fancyfrom field
var StringFilterFn = function(field, value, oldValue){
    if(field.interval){
        clearInterval(field.interval);
    }

    field.interval = setTimeout(function(){
        var grid = FancyGrid.get('grid_MyCard-management');

        grid.addFilter(field.name, value);
    }, 500);
};
var StringByKeyFilterFn = function(field, value, oldValue){
    if(field.interval){
        clearInterval(field.interval);
    }

    field.interval = setTimeout(function(){
        var grid = FancyGrid.get('grid_MyCard-management');

        grid.addFilter(field.name+'_by_key', value);
    }, 500);
};
var DateFromFilterFn = function(field, value, oldValue){
    if(field.interval){
        clearInterval(field.interval);
    }

    field.interval = setTimeout(function(){
        var grid = FancyGrid.get('grid_MyCard-management');

        grid.addFilter(field.name, Fancy.Date.parse(value, 'Y-m-d'), '>=');
    }, 500);
};
var DateTillFilterFn = function(field, value, oldValue){
    if(field.interval){
        clearInterval(field.interval);
    }

    field.interval = setTimeout(function(){
        var grid = FancyGrid.get('grid_MyCard-management');

        grid.addFilter(field.name, Fancy.Date.parse(value, 'Y-m-d'), '<=');
    }, 500);
};
var NumberFilterFn = function(field, value, oldValue){
    if(field.interval){
        clearInterval(field.interval);
    }

    field.interval = setTimeout(function(){
        var grid = FancyGrid.get('grid_MyCard-management');

        grid.addFilter(field.name, '');
        if (field.value == '')
            grid.clearFilter(field.name);
        else if (field.value == 'No') {
            //grid.clearFilter(field.name);
            grid.addFilter(field.name,'0', '=');
        }
        else {            
            //grid.clearFilter(field.name);
            grid.addFilter(field.name, value, '=');
        }

    }, 500);
};
var NumberFromFilterFn = function(field, value, oldValue){
    if(field.interval){
        clearInterval(field.interval);
    }

    field.interval = setTimeout(function(){
        var grid = FancyGrid.get('grid_MyCard-management');

        grid.addFilter(field.name, '');
        if (field.value == '')
            grid.clearFilter(field.name);
        else if (field.value == '0') {
            grid.clearFilter(field.name);
            grid.addFilter(field.name,'0', '=');
        }
        else{            
            grid.clearFilter(field.name);
            grid.addFilter(field.name, value, '>=');
        }
    }, 500);
};
var NumberTillFilterFn = function(field, value, oldValue){
    if(field.interval){
        clearInterval(field.interval);
    }

    field.interval = setTimeout(function(){
        var grid = FancyGrid.get('grid_MyCard-management');

        grid.addFilter(field.name, '');
        if (field.value == '')
            grid.clearFilter(field.name);
        else if (field.value == '0') {            
            grid.clearFilter(field.name);
            grid.addFilter(field.name, '0', '=');
        }
        else {
            grid.clearFilter(field.name);
            grid.addFilter(field.name, value, '<=');
        }
    }, 500);
};

// Định dạng bytes
function FormatBytes(_bytes, _decimals = 0) {
  if(_bytes == 0) return '0 Byte';
  var k = 1000,
      dm = _decimals + 1 || 3,
      sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'],
      i = Math.floor(Math.log(_bytes) / Math.log(k));
  return parseFloat((_bytes / Math.pow(k, i)).toFixed(dm)) + ' ' + sizes[i];
}

// Định dạng của vn = ngày/tháng/năm
function FormatDateVnToEn(_dateString) {
  var date = _dateString.split('-');// date = 0, month = 1, year = 2
  return date[2]+'-'+date[1]+'-'+date[0];
}

// Định dạng ngày Y-m-d H:M:s thành int
function FormatDate(_dateString) {
  var date = new Date(_dateString);
  return new Date(date.getFullYear(), date.getMonth(), date.getDate(), 0,0,0,0).getTime().toString();
}


// Lấy nội dung trang web
function GetLinkContent(_link) {
  var response = '',
      ajax = $.ajax({
          url: _link,
          async: false,
          success: function(data) { response = data; },
      });
  return response;
}

function upload_file(_form, _callback = null) {
  var server = _form.attr('action'),
      files = _form.find('input:file')[0].files;
  if (!files.length)
    return false;

  var processing = _form.find('.processing'),
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
          _form.append('<i class="processing fa fa-refresh fa-spin"></i>');
        else
          _form.find('.processing').removeClass('hidden');
      },
      success: function(data, textStatus, jqXHR) {
        if (_callback != null)
          window[_callback](data);
      },
      error: function(jqXHR, textStatus, errorThrown) {
          console.log(errorThrown);
      },
      complete: function(jqXHR, textStatus) {
        _form.find('.processing').addClass('hidden');
        console.log({action:'upload_file: '+server, file: files[0].name, status: textStatus});
      }
  });
}
function delete_file(_server, _file, _callback = null) {
  $.ajax({
      url: _server,
      method: 'POST',
      data: {id:'"'+_file+'"'},
      cache: false,
      contentType: false,
      processData: false,
      beforeSend: function(jqXHR, settings) {
        $('.processing').attr('class', 'processing fa fa-refresh fa-spin');
      },
      success: function(data, textStatus, jqXHR) {
        if (_callback != null)
          window[_callback](data);
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

// Tạo file CSV từ Fancygrid
function FancygridToCsv(_gridId, _fileName) {
  var header_length = $(_gridId).find('.fancy-grid-header-cell-text').length,
      col_length = $(_gridId).find('.fancy-grid-column').length,
      cel_length = $(_gridId).find('.fancy-grid-cell').length,
      header = $(_gridId).find('.fancy-grid-header-cell-text'),
      data = $(_gridId).find('.fancy-grid-cell'),
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

  //$('#down').attr('href', CreateTextFile(tmp.join('\n')));

  CreateTextFile(tmp.join('\n'), true)

  function CreateTextFile(_text, _autoDownload = false) {
    var text_file = null,
        data = new Blob([_text], {type: 'text/csv;charset=utf-8;'});

    // If we are replacing a previously generated file we need to
    // manually revoke the object URL to avoid memory leaks.
    if (text_file !== null) {
      window.URL.revokeObjectURL(text_file);
    }

    text_file = window.URL.createObjectURL(data);

    if (_autoDownload)
      saveAs(data, _fileName);

    return text_file;
  };
}

// Fix fancygird datetime filter by fancyform field
/*Fancy.Grid.prototype.addFilter = function(index, value, sign){
  var me = this,
    filter = me.filter.filters[index],
    sign = sign || '';

  if(filter === undefined){
    filter = {};
  }

  if(Fancy.isDate(value)){
    filter['type'] = 'date';
    filter['format'] = this.lang.date;
    filter['format']['read'] = "Y-m-d";
    filter['format']['write'] = "d-m-Y";
    filter['format']['edit'] = "Y-m-d";
    value = Number(value);
  }

  if(value === ''){
    delete filter[sign];
  }
  else{
    filter[sign] = value;
  }

  me.filter.filters[index] = filter;
  me.filter.updateStoreFilters();
};*/
// Fix data list for combo col
/*Fancy.form.field.Combo.prototype.loadListData = function() {
  var a = this;
  if (Fancy.isObject(a.data)) {
    var b = a.data.proxy;
    if (!b || !b.url)
      throw new Error("[FancyGrid Error]: combo data url is not defined");
    Fancy.Ajax({
      url: b.url,
      params: b.params || {},
      method: b.method || "GET",
      getJSON: !0,
      success: function(b) {
        a.data = a.configData(b.data),
        a.renderList(),
        a.onsList()
      }
    })
  }
};*/

// Hàm gọi event bằng function name
function CallFunctionByName(_function_name, _context, _args = []) {
  var args = _args.slice.call(arguments).splice(2),
      namespaces = _function_name.split("."),
      function_name = namespaces.pop(), // function name là cái cuối cùng của chuỗi
      namespace = namespaces.pop(); // namespace của function là cái cuối cùng của chuỗi đã cắt bằng function_name

  if (typeof _context[namespace][function_name] === 'function')
    return _context[namespace][function_name].apply(_context[namespace][function_name], args);
  else {
    console.log('"'+_function_name+'" function is not available');
    return false;
  }
};

$(document).ready(function() {
  // Gọi event bằng class .control-action
  // Ex: <a class="control-action" action="file.upload" data="url">Click me</a>
  $('body').on('click', '.control-action', function() {
    var action = $(this).attr('action'),
        data = $(this).attr('action-data'); //option1
          //data = [{sender: $(this), data: $(this).attr('action-data')}]; //option2
          
    //option1
    if (data == null)
      data = [];
    else
      data = data.split('|');

    if (!Array.isArray(data))
      data = [data];

      CallFunctionByName(action, window, data);
  })
})





//document.ready()
});