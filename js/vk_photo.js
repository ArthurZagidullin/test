function wallPost() {
  VK.api('photos.getWallUploadServer', {
  },function (data) {
    if (data) {
      //console.log(foo);
      $.post('/classes/wallpost.php', {
        action: 'upload',
        url: data.response.upload_url
      },
       function (json) {
        console.log(json);
      },"json");
    }
  });
}
$(document).ready( function(){
  wallPost();
})