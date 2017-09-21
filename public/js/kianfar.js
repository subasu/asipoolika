

var count=0;
var record_count=0;
$('#add_to_list').click(function(){
    count++;
    var row_id='row'+count;
    var select_id='select'+count;
    //var row='<tr id="row'+count+'">'+
    var row='<tr id="'+row_id+'">'+
            '<th scope="row">'+count+'</th>'+
            '<td>'+'<input style="padding-right:5px;" class="form-control" type="text" name="product_title[]" value="'+$('#product_title').val()+'">'+'</td>'+
            '<td>'+'<input style="padding-right:5px;" class="form-control" type="number" name="product_count[]" value="'+$('#product_count').val()+'">'+'</td>'+
            '<td>'+$.trim($("#unit_count option:selected").text())+'</td>'+
            '<input type="hidden" name="unit_count[]" value="'+$.trim($("#unit_count option:selected").text())+'">'+
            //'<td>'+'<select id="'+select_id+'" class="form-control">'+
            //      unit_count_each_record(select_id)
            //+'</select>'+
            //'</td>'+
            '<td>'+'<input style="padding-right:5px;" class="form-control" type="text" name="product_details[]" value="'+$('#product_details').val()+'">'+'</td>'+
            '<td>'+
            '<a type="button" class="btn btn-danger remove_row" data-toggle="tooltip" title="حذف" style="font-size:18px;">'+
            '<span class="fa fa-trash"></span>'+
            '</a>'+
            '</td>'+
            '</tr>';
    $('#table-row').append(row);
    record_count++;
    $('#record_count').val(record_count);
});

$(document).on('click','.remove_row', function(){
    $(this).closest('tr').remove();
    record_count--;
    $('#record_count').val(record_count);
});

