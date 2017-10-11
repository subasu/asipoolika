$(document).ready(function () {
    $("#carType").change(function() {
        var $this = $(this);
        var id = $this.val();
        $.ajax({
            url: "{{ url('brands') }}",
            type: 'GET',
            dataType: 'json',
            data: {car_type: id},
            success: function(response) {
                var html;
                $.each(response.car_types, function(index, value) {
                    html += '<option value="' + index + '">' +  value + '</option>';
                });
                $("#brand").html(html);
            },
            error: function(error) {
                var errors = error.responseJSON;
                console.log(errors);
            }
        });
    });
});


$(document).ready(function () {
    $("#brand").change(function() {
        var $this = $(this);
        var id = $this.val();
        $.ajax({
            url: "{{ url('models') }}",
            type: 'GET',
            dataType: 'json',
            data: {brand_id: id},
            success: function(response) {
                var html;
                $.each(response.models, function(index, value) {
                    html += '<option value="' + index + '">' +  value + '</option>';
                });
                $("#models").html(html);
            },
            error: function(error) {
                var errors = error.responseJSON;
                console.log(errors);
            }
        });
    });
});


