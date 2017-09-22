/**
 * Created by Arash on 9/16/2017.
 */

$(document).on('click','#acceptRequest',function(){
   var id= $(this).attr('content');
    //alert(id);
     var rate  = $('#rate').val();
     var price = $('#price').val();
     var token = $('#token').val();
    // if(rate == '' || rate == null)
    // {
    //     $('#rate').focus();
    //     $('#rate').css('border-color','red');
    //     return false;
    // }
    //
    // else if(price == '' || price == null)
    // {
    //     $('#price').focus();
    //     $('#price').css('border-color','red');
    //     return false;
    // }else
    //     {

            // $.ajaxSetup({
            //     headers: {
            //         'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            //     }
            // });

            //var formData = new formData('#serviceDetailForm').serialize();
            $.ajax
            ({
                url: "acceptServiceRequest",
                type:"POST",
                data:{'rate':rate,'price':price,'id':id,'_token':token},
                success:function(response)
                {

                },
                error:function (error) {
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
    //    }
});

$(document).on('click','#refuseRequest',function(){
    var id = $(this).attr('content');
    //alert(id);
    $('#commentModal').modal('show');
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
        }
    });
    // $.ajax
    // ({
    //    url:'refuseRequestRecord',
    //    type:'POST',
    //    data:id,
    //     success:function () {
    //         alert('success');
    //     },error:function(error)
    //     {
    //         console.log(error);
    //         alert('error');
    //     }
    // });
});

