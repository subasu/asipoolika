var working = false;
$('.login').on('submit', function(e) {
  e.preventDefault();
  if (working) return;
  working = true;
  var $this = $(this),
    $state = $this.find('button > .state');
  $this.addClass('loading');
  $state.html('در حال اهراز هویت');
  setTimeout(function() {
    $this.addClass('ok');
    $state.html('خوش آمدید!');
    setTimeout(function() {
      $state.html('ورود');
      $this.removeClass('ok loading');
      working = false;
    }, 4000);
  }, 3000);
});