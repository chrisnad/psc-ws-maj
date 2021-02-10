/******/ (() => { // webpackBootstrap
/*!****************************************!*\
  !*** ./resources/js/dropzoneOption.js ***!
  \****************************************/
window.onload = function () {
  Dropzone.options.dropzone = {
    autoProcessQueue: false,
    uploadMultiple: false,
    init: function init() {
      var myDropzone = this; // Update selector to match your button

      $("#button").click(function (e) {
        console.log("hello");
        e.preventDefault();
        myDropzone.processQueue();
      });
      this.on('sending', function (file, xhr, formData) {
        // Append all form inputs to the formData Dropzone will POST
        var data = $('#dropzone').serializeArray();
        $.each(data, function (key, el) {
          formData.append(el.name, el.value);
        });
      });
    }
  };
};
/******/ })()
;