$(function(){
  $('.drag-and-drop').each(function() {
    const dropZone = $('#dropZone', this);
    const fileInput = $('#fileInput', this);

    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
      dropZone.on(eventName, preventDefaults);
    });

    ['dragenter', 'dragover'].forEach(eventName => {
      dropZone.on(eventName, highlight);
    });

    ['dragleave', 'drop'].forEach(eventName => {
      dropZone.on(eventName, unhighlight);
    });

    dropZone.on('drop', handleDrop);

    function preventDefaults(e) {
      e.preventDefault();
      e.stopPropagation();
    }

    function highlight() {
      dropZone.addClass('highlight');
    }

    function unhighlight() {
      dropZone.removeClass('highlight');
    }

    function handleDrop(e) {
      const files = e.target.files || e.originalEvent.dataTransfer.files;

      console.log(files);

      if (!files || files.length === 0) {
        alert('No files were dropped');
        return;
      }

      if (files.length > 1) {
        alert('Please only upload one image');
        return;
      }

      handleFiles(files);
    }

    function handleFiles(files) {
      const file = files[0];
      const reader = new FileReader();

      reader.onload = function(e) {
        const dataURL = e.target.result;
        appendImage(dataURL);
      };

      reader.readAsDataURL(file);
    }

    function appendImage(dataURL) {
      let img = dropZone.find('#img-preview');

      

      if (img.length === 0) {
         img = $('<img>');
        dropZone.append(img);
      }

      img.attr({
        src: dataURL,
        id: 'img-preview'
      });

      
      const hiddenInput = $('<input>').attr({
        type: 'hidden',
        name: 'image',
        value: dataURL
      });

      $('input', dropZone).remove();
      dropZone.append(hiddenInput);
    }

    // Click event handler for file input
    dropZone.on('click', function() {
      fileInput.click();
    });

    // Change event handler for file input
    fileInput.on('change', function() {
      const files = this.files;
      if (!files || files.length === 0) {
        return;
      }
      handleFiles(files);
    });
  });
});