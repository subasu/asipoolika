$(document).on('click', '#close-preview', function(){
    $('.image-preview').popover('hide');
    // Hover befor close the preview
    $('.image-preview').hover(
        function () {
            $('.image-preview').popover('show');
        },
        function () {
            $('.image-preview').popover('hide');
        }
    );
});

$(function() {
    // Create the close button
    var closebtn = $('<button/>', {
        type:"button",
        text: ' X ',
        id: 'close-preview',
        style: 'font-size: initial;',
    });
    closebtn.attr("class","close pull-right");
    // Set the popover default content
    $('.image-preview').popover({
        trigger:'manual',
        html:true,
        title: "<strong>پیش نمایش</strong>"+$(closebtn)[0].outerHTML,
        content: "هیچ تصویری نیست",
        placement:'right'
    });
    // Clear event
    $('.image-preview-clear').click(function(){
        $('.image-preview').attr("data-content","").popover('hide');
        $('.image-preview-filename').val("");
        $('.image-preview-clear').hide();
        $('.image-preview-input input:file').val("");
        $(".image-preview-input-title").text("انتخاب تصویر امضاء");
        $(".image-preview-input-title2").text("انتخاب کارت کارگری");
    });
    // Create the preview image
    $(".image-preview-input input:file").change(function (){
        var img = $('<img/>', {
            id: 'dynamic',
            width:150,
            height:150
        });
        var file = this.files[0];
        var reader = new FileReader();
        // Set preview image into the popover data-content
        reader.onload = function (e) {
            $(".image-preview-input-title").text("تغییر تصویر");
            $(".image-preview-clear").show();
            $(".image-preview-filename").val(file.name);
            img.attr('src', e.target.result);
            $(".image-preview").attr("data-content",$(img)[0].outerHTML).popover("show");
        }
        reader.readAsDataURL(file);
    });
});
//Custom Required Input Message
var msg="";
var elements = document.getElementsByTagName("INPUT");

for (var i = 0; i < elements.length; i++) {
   elements[i].oninvalid =function(e) {
      if (!e.target.validity.valid) {
         switch(e.target.id){
            case 'username' :
               e.target.setCustomValidity("Username cannot be blank");break;
            default : e.target.setCustomValidity("این فیلد را پر کنید");break;

         }
      }
   };
   elements[i].oninput = function(e) {
      e.target.setCustomValidity(msg);
   };
}
function showGoAdminPage()
{
   $('#goAdminPage').show(500);
}
$('#showImageUploadDiv').click(function(){
   $('#uploadUserImageDiv').show(300);
});


