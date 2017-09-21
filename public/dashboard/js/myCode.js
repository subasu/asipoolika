$("#thisYearShow").click(function(){
   $("#thisYear").toggle(500);
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


$('#submitUser').click(function(){
   if(!$('#lat').val())
   {
      alert ('مکان مشاوراملاکی را روی نقشه انتخاب کنید.');
      var divPosition = $('#chooseMapJump').offset();
      $('html, body').animate({scrollTop: divPosition.top}, "slow");
   }
   if(!$('#areas').val())
      alert('انتخاب منطقه اجباری است');
});

$(document).ready(function(){
   //alert($('#dealTypeName').val());
   priceFileds();
   if($('#dealTypeName').val()=='rent')
       var rent=
          '<label style="font-size: 15px;" class="control-label pull-right col-md-12 col-sm-12 col-xs-12 form-group"  for="" name=""> مدت قرارداد :'+
          '</label>'+
          '<input type="text" name="durationCount" class="col-md-2 col-sm-2 col-xs-2 pull-right" placeholder="عدد">'+
          '<select name="durationYM" class="pull-right" style="margin-right: 5px;">'+
          '<option value="سال">سال</option>'+
          '<option value="ماه">ماه</option>'+
          '</select>';
   $('#onlyRent').append(rent);
});

function priceFileds()
{
   var dealType=$('#dealTypeEn').val();
   var title;
   switch (dealType)
   {
      case 'sell':
          title='قیمت کل';
           break;
      case 'exchange':
         title='قیمت کل';
           break;
      case 'preSell':
         title='پیش پرداخت';
           break;
   }
   var priceField;
   if(dealType=='sell' || dealType=='exchange' || dealType=='preSell')
   {
      priceField=
          '<div class="col-md-5 col-sm-6 col-xs-12 pull-right">'+
          '<div class="input-group">'+
          '<span class="input-group-addon">تومان</span>'+
          '<input id="holePriceSell" type="number" placeholder="" min="0" class="form-control" name="holePriceSell" style="color:black">'+
          '<span class="input-group-addon">'+title+'<span class="required" style="font-size: 20px;color: red;">*</span></span>'+
          '</div>'+
          '</div>';
   }
   else if(dealType=='trust')
   {
      priceField=
          '<div class="col-md-5 col-sm-6 col-xs-12 pull-right">'+
          '<div class="input-group">'+
          '<span class="input-group-addon">تومان</span>'+
          '<input id="trustPrice" type="number" min="0" class="form-control" name="trustPrice" style="color:black">'+
          '<span class="input-group-addon"> مبلغ رهن<span class="required" style="font-size: 20px;color: red;">*</span></span>'+
          '</div>'+
          '</div>'+
          '<div class="col-md-5 col-sm-6 col-xs-12 pull-right" style="display: none">'+
          '<div class="input-group">'+
          '<span class="input-group-addon">تومان</span>'+
          '<input id="rentPrice" type="hidden" min="0" class="form-control" value="0" name="rentPrice" style="color:black">'+
          '<span class="input-group-addon"> مبلغ اجاره<span class="required" style="font-size: 20px;color: red;">*</span></span>'+
          '</div>'+
          '</div>';
   }
   else if(dealType=='rent')
   {
      priceField=
          '<div class="col-md-5 col-sm-6 col-xs-12 pull-right">'+
          '<div class="input-group">'+
          '<span class="input-group-addon">تومان</span>'+
          '<input id="trustPrice" type="number" min="0" class="form-control" name="trustPrice" style="color:black">'+
          '<span class="input-group-addon"> مبلغ رهن<span class="required" style="font-size: 20px;color: red;">*</span></span>'+
          '</div>'+
          '</div>'+
          '<div class="col-md-5 col-sm-6 col-xs-12 pull-right">'+
          '<div class="input-group">'+
          '<span class="input-group-addon">تومان</span>'+
          '<input id="rentPrice" type="number" min="0" class="form-control" name="rentPrice" style="color:black">'+
          '<span class="input-group-addon"> مبلغ اجاره<span class="required" style="font-size: 20px;color: red;">*</span></span>'+
          '</div>'+
          '</div>';
   }
   $('#price').append(priceField);
}

