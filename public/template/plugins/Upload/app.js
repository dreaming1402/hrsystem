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