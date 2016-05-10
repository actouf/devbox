$('.delete-button').click(function(){
  return confirm('Confirmez cette suppression ?');
});

setTimeout(function(){
  $('.alert-layout').fadeOut(500);
}, 2000);


reloadPlugins();

$('body').on('click', '.photoPreview img', function(){
  var parent = $(this).parents('.photoSpace');
  parent.find('.photoInput').click();
});

$('body').on('change', '.photoInput', function(){
  var reader = new FileReader();
  var parent = $(this).parents('.photoSpace');
  reader.onload = function (e) {
    console.log(parent.find('.photoPreview img').length);
    parent.find('.photoPreview img').attr('src', e.target.result);
  };
  reader.readAsDataURL(this.files[0]);
});

$("body").on('change', '.urlinput', function() {
  if ($(this).val().indexOf("http://") !== 0 && $(this).val().indexOf("https://") !== 0) {
    $(this).val("http://" + $(this).val());
  }
});

$("body").on('change', '.gifinput', function() {
  if ($(this).val().indexOf("http://") !== 0 && $(this).val().indexOf("https://") !== 0) {
    $(this).val("http://" + $(this).val());
  }
});

function reloadPlugins()
{
  // Single select
  $(".select2-single").select2({
    allowClear: true
  });

  $('.switcher').switcher({
    theme: 'circle',
    on_state_content: '<span class="fa fa-check"></span>',
    off_state_content: '<span class="fa fa-times"></span>'
  });


  $('.date').datepicker({
    format: 'dd/mm/yyyy'
  });
}

function imageExists(image_url)
{
  var http = new XMLHttpRequest();
  http.open('HEAD', image_url, false);
  http.send();
  return http.status != 404;

}