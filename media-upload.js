// Script from https://laurahoughcreative.co.uk/using-the-wordpress-media-uploader-in-your-plugin-options-page/
jQuery(document).ready(function($){
  var mediaUploader;
  $('#aspwa_upload_192_image_button').click(function(e) {
    e.preventDefault();
      if (mediaUploader) {
      mediaUploader.open();
      return;
    }
    mediaUploader = wp.media.frames.file_frame = wp.media({
      title: 'Choose Image',
      button: {
      text: 'Choose Image'
    }, multiple: false });
    mediaUploader.on('select', function() {
      var attachment = mediaUploader.state().get('selection').first().toJSON();
      $('#aspwa_the_192_image_url').val(attachment.url);
    });
    mediaUploader.open();
  });
  
    $('#aspwa_upload_512_image_button').click(function(e) {
    e.preventDefault();
      if (mediaUploader) {
      mediaUploader.open();
      return;
    }
    mediaUploader = wp.media.frames.file_frame = wp.media({
      title: 'Choose Image',
      button: {
      text: 'Choose Image'
    }, multiple: false });
    mediaUploader.on('select', function() {
      var attachment = mediaUploader.state().get('selection').first().toJSON();
      $('#aspwa_the_512_image_url').val(attachment.url);
    });
    mediaUploader.open();
  });
});