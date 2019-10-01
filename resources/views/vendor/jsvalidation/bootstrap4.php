<script>
    jQuery(document).ready(function(){

        $("<?= $validator['selector']; ?>").each(function() {
            $(this).validate({
                errorElement: 'label',
                errorClass: 'error',

                errorPlacement: function (error, element) {
                    if (element.parent('.input-group').length ||
                        element.prop('type') === 'checkbox' || element.prop('type') === 'radio') {
                        error.insertAfter(element.parent());
                        // else just place the validation message immediately after the input
                    } else {
                        error.insertAfter(element);
                    }
                },
                highlight: function (element) {
                    $(element).closest('.form-control').addClass('error'); // add the Bootstrap error class to the control group
                },

                <?php if (isset($validator['ignore']) && is_string($validator['ignore'])): ?>

                ignore: "<?= $validator['ignore']; ?>",
                <?php endif; ?>

                
                unhighlight: function(element) {
                    $(element).closest('.form-control').removeClass('error');
                },
                
                success: function (element) {
                    $(element).closest('.form-control').removeClass('error'); // remove the Boostrap error class from the control group
                },

                focusInvalid: false, // do not focus the last invalid input
                <?php if (Config::get('jsvalidation.focus_on_error')): ?>
                invalidHandler: function (form, validator) {

                    if (!validator.numberOfInvalids())
                        return;

                    $('html, body').animate({
                        scrollTop: $(validator.errorList[0].element).offset().top
                    }, <?= Config::get('jsvalidation.duration_animate') ?>);
                    $(validator.errorList[0].element).focus();

                },
                <?php endif; ?>

                rules: <?= json_encode($validator['rules']); ?>
            });
        });
    });
</script>
