/**
 * Created by Arash on 9/15/2017.
 */

var R = 0;
$('#add_to_list').click(function () {

    var title = $('#title').val();
    var count = $('#count').val();

    if(title == '' || title == null)
    {
        $('#title').focus();
        $('#title').css('border-color','red');
        return false;
    }

    else if(count == '' || count == null)
    {
        $('#count').focus();
        $('#count').css('border-color','red');
        return false;
    }
    else if(title != '' && title!= null && count!= '' && count!= null) {



        R++;
        $('#tbody').append
        (
            "<tr>" +
            "<th scope='row'>" + R + "</th>" +
            "<td><input style='padding-right:5px;' class='form-control required' type='text' id='title' name='title[]'  value=' " + $('#title').val() + " '></td>" +
            "<td><input style='padding-right:5px;' class='form-control required' type='text' id='count' name='count[]'  value=' " + $('#count').val() + " '></td>" +
            "<td><input id='description' name='description[]' class='form-control col-md-7 col-xs-12' placeholder=''    value='"+$('#description').val()+ "'  required='required' type='text' style='width: 400px;' /></td>"+
            "<td><a   type='button' class='btn remove_row' data-toggle='tooltip' title='حذف'><span class='fa fa-trash-o'></span></a></td>" +
            "</tr>"
        );

        //two lines code below is to make title's text box and count's text box empty if needed....
       $('#title').val('');
       $('#count').val('');
       $('#description').val('');

        $(document).on('click', '.remove_row', function () {
            $(this).closest('tr').remove();
        });

    }

});


$('#send').click(function(){

   // alert('hello');

    var title1 = $('#title1').val();
    var count1 = $('#count1').val();

    var formData = $('#service').serialize();


    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
        }
    });

    $.ajax
    ({

        url: "sendService",
        type: "POST",
        //dataType:'JSON',
        data:formData,
        beforeSend:function () {
            var counter = 0;
            $(".required").each(function() {
                if ($(this).val() === "") {
                    $(this).css("border-color" , "red");
                    counter++;
                }
            });
            if(counter > 0){
                swal
                ({
                    title: '',
                    text: 'تعدادی از فیلدهای فرم خالی است.لطفا فیلدها را پر نمایید سپس ثبت نهایی را بزنید',
                    type:'warning',
                    confirmButtonText: "بستن"
                });
                return false;
            }
    },
        success:function(response)
        {
            swal
            ({
                title: '',
                text: response,
                type:'info',
                confirmButtonText: "بستن"
            });
            console.log(response);
        },error:function (error)
        {
            if(error.status === 500)
            {
                swal
                ({
                    title: '',
                    text: 'خطایی رخ داده است.لطفا با بخش پشتیبانی تماس بگیرید',
                    type:'info',
                    confirmButtonText: 'بستن'
                });
                console.log(error);
            }
             if(error.status === 422)
            {
                console.log(error);
                var errors = error.responseJSON; //this will get the errors response data.
                //show them somewhere in the markup
                //e.g
                var  errorsHtml = '';

                $.each(errors, function( key, value ) {
                    errorsHtml +=  value[0] + '\n'; //showing only the first error.
                });
                swal({
                    title: "",
                    text: errorsHtml,
                    type: "info",
                    confirmButtonText: "بستن"
                });
            }

        }

    })

});