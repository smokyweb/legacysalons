window.document.onkeydown = function(e) {
  if (!e) {
    e = event;
  }
  if (e.keyCode == 27) {
    lightbox_close();
  }
}

/*HTML MP4 Video*/
function mp4_lightbox_open(postid, autoplayhtm) {
  document.getElementById('pfv_vvideo_lightbox_'+postid).style.display = 'block';
  document.getElementById('pfv_video_fadelayout_'+postid).style.display = 'block';
  if(autoplayhtm == 1){
    var contro = document.getElementById('pfv_htmlvideo_'+postid);
    contro.controls = true;
    contro.play();
  }
}
function mp4_lightbox_close(postid) {
  document.getElementById('pfv_vvideo_lightbox_'+postid).style.display = 'none';
  document.getElementById('pfv_video_fadelayout_'+postid).style.display = 'none';
  var cotrlpause = document.getElementById('pfv_htmlvideo_'+postid);
    cotrlpause.controls = true;
    cotrlpause.pause();
}

/*Youtube Lightbox open*/

function ytube_lightbox_open(postid, ytubautoplay) {
  var lightBoxVideo = document.getElementById("pfviframeVideo_"+postid);
  //window.scrollTo(0, 0);
  document.getElementById('pfv_vvideo_lightbox_'+postid).style.display = 'block';
  document.getElementById('pfv_video_fadelayout_'+postid).style.display = 'block';
  if(ytubautoplay == 1){
    var symbol = jQuery("#pfviframeVideo_"+postid)[0].src.indexOf("?") > -1 ? "&" : "?";
    jQuery("#pfviframeVideo_"+postid)[0].src += symbol + "autoplay=1";
  }
  // Remove hash from URL
  setTimeout(function() {
    var url = window.location.href;
    if (url.indexOf('#') > -1) {
      var cleanUrl = url.split('#')[0];
      history.replaceState(null, null, cleanUrl);
    }
  }, 100); // Delay to ensure the popup has time to open
}

function ytube_lightbox_close(postid) {
  document.getElementById('pfv_vvideo_lightbox_'+postid).style.display = 'none';
  document.getElementById('pfv_video_fadelayout_'+postid).style.display = 'none';
  var testvar = jQuery("#pfviframeVideo_"+postid).attr('src');
  var arr=testvar.split('?');
  jQuery("#pfviframeVideo_"+postid).attr('src',arr[0]);
}

/*Vimeo Video*/

function vmio_lightbox_open(postid, vmioautoplay) {
  var vimlightBoxVideo = document.getElementById("pfviframeVideo_"+postid);
  //window.scrollTo(0, 0);
  document.getElementById('pfv_vvideo_lightbox_'+postid).style.display = 'block';
  document.getElementById('pfv_video_fadelayout_'+postid).style.display = 'block';  
  
  if(vmioautoplay == 1){
    var symbol = jQuery("#pfviframeVideo_"+postid)[0].src.indexOf("?") > -1 ? "&" : "?";
    jQuery("#pfviframeVideo_"+postid)[0].src += symbol + "autoplay=1";
  }
  // Remove hash from URL
  setTimeout(function() {
    var url = window.location.href;
    if (url.indexOf('#') > -1) {
      var cleanUrl = url.split('#')[0];
      history.replaceState(null, null, cleanUrl);
    }
  }, 100); // Delay to ensure the popup has time to open
}
function vmio_lightbox_close(postid) {
  document.getElementById('pfv_vvideo_lightbox_'+postid).style.display = 'none';
  document.getElementById('pfv_video_fadelayout_'+postid).style.display = 'none';
  var testvar = jQuery("#pfviframeVideo_"+postid).attr('src');
  var arr=testvar.split('?');
  jQuery("#pfviframeVideo_"+postid).attr('src',arr[0]);
}


jQuery(document).ready(function(){
    jQuery('.blog').find('.modelpup > a').attr('href', 'javascript:void(0)');
    jQuery('.blog').find('.pfv_vidBox').parent().attr('href', 'javascript:void(0)');
});