if ($("#form-create-module").length) {
  var FormWizard = (function() {
    var handleFormWizard = function() {
      var form = $("#form-create-module");
      form.steps({
        headerTag: "h3",
        bodyTag: "section",
        transitionEffect: "slideLeft",
        autoFocus: true,
        onStepChanging: function(event, currentIndex, newIndex) {
          form.validate().settings.ignore = ":disabled,:hidden";
          return form.valid();
        },
        onFinishing: function(event, currentIndex) {
          form.validate().settings.ignore = ":disabled";
          return form.valid();
        },
        onFinished: function(event, currentIndex) {
          form.submit();
        }
      });
    };

    return {
      // main function to initiate the module
      init: function() {
        handleFormWizard();
      }
    };
  })();

  $.Deferred(function() {
    FormWizard.init();
    $(document).on("change", "select.default", function() {
      $(this)
        .next("input")
        .val($(this).val());
    });

    $(document).on("click", "input[name='table_schema']", function() {
      if ($(this).val() == 1) {
        $("#crud_migration").hide();
        $("#crud_existing_table").show();
      } else {
        $("#crud_existing_table").hide();
        $("#crud_migration").show();
      }
    });
  });
}
