jQuery.fn.form = function(options) {

    s = jQuery.extend({
        form_id: '',
        key: '',
        result: '',
        url: './form.php',
    }, options);

    var elems = $(this);

    $.ajax({
        type: "GET",
        url: s.url,
        data: 'form_id=' + options.form_id + "&key=" + options.key,
        success: function(data) {
            elems.each(function() {
                obj = $(this);
                s.result = data;
                return this.innerHTML = s.result;
            });
        },
        error: function(data) {
//            alert("Error loading data.");
        }
    });

    return $(this);
}