<!doctype html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title>
        <?php if(!isset($pageTitle)): ?>
            <?php echo e(config('app.name', 'Laravel')); ?>

        <?php else: ?>
            <?php echo e('Merchant ' . $pageTitle . ' - ' . config('app.name')); ?>

        <?php endif; ?>
    </title>
    <link href="<?php echo e(asset('static/backend/plugins/simplebar/css/simplebar.css')); ?>" rel="stylesheet" />
    <link href="<?php echo e(asset('static/backend/plugins/metismenu/css/metisMenu.min.css')); ?>" rel="stylesheet" />
    <?php echo $__env->yieldPushContent('css'); ?>

    <!-- Bootstrap CSS -->
    <link href="<?php echo e(asset('static/backend/css/bootstrap.min.css')); ?>" rel="stylesheet">
    <link href="<?php echo e(asset('static/backend/css/bootstrap-extended.css')); ?>" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <link href="<?php echo e(asset('static/backend/css/app.css')); ?>" rel="stylesheet">
    <link href="<?php echo e(asset('static/backend/css/icons.css')); ?>" rel="stylesheet">
    <link href="<?php echo e(asset('noteStyle/script.js')); ?>" rel="stylesheet">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css"
        integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"
        integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js"
        integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js"
        integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous">
    </script>


    <!-- Theme Style CSS -->
    <link rel="stylesheet" href="<?php echo e(asset('static/backend/css/dark-theme.css')); ?>" />
    <link rel="stylesheet" href="<?php echo e(asset('static/backend/css/semi-dark.css')); ?>" />
    <link rel="stylesheet" href="<?php echo e(asset('static/backend/css/header-colors.css')); ?>" />
</head>

<body>
    <div class="wrapper">
        <?php echo $__env->make('merchant.mrc_sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <?php echo $__env->make('merchant.mrc_head', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <div class="page-wrapper">
            <div class="page-content">
                <?php echo $__env->yieldContent('mrc_content'); ?>

            </div>
        </div>

        <!--start overlay-->
        <div class="overlay toggle-icon"></div>
        <!--end overlay-->
        <!--Start Back To Top Button-->
        <a href="javaScript:;" class="back-to-top"><i class='bx bxs-up-arrow-alt'></i></a>
        <!--End Back To Top Button-->
        <footer class="page-footer">
            <p class="mb-0">Copyright © <?php echo e(date('Y')); ?>. All right reserved.</p>
        </footer>
    </div>
    <!--end wrapper-->

    </div>


    <!-- Bootstrap JS -->
    <script src="<?php echo e(asset('static/backend/js/bootstrap.bundle.min.js')); ?>"></script>
    <!--plugins-->
    <script src="<?php echo e(asset('static/backend/js/jquery.min.js')); ?>"></script>
    <script src="<?php echo e(asset('static/backend/plugins/simplebar/js/simplebar.min.js')); ?>"></script>
    <script src="<?php echo e(asset('static/backend/plugins/metismenu/js/metisMenu.min.js')); ?>"></script>
    <script src="<?php echo e(asset('noteStyle/script.js')); ?>"></script>
    <?php echo $__env->yieldPushContent('js'); ?>

    <!--app JS-->
    <script src="<?php echo e(asset('static/backend/js/app.js')); ?>"></script>


    <script>
        function onlyNumbers(evt) {
            var e = event || evt; // for trans-browser compatibility
            var charCode = e.which || e.keyCode;
            if (charCode > 31 && (charCode < 48 || charCode > 57))
                return false;
            return true;

        }

        function PopUp(itarget, width, height) {
            try {
                width = (width != null) ? width : 400;
                height = (height != null) ? height : 400;
                var top, left;
                top = (screen.height / 2) - (height / 2);
                left = (screen.width / 2) - (width / 2);
                var lwin = window.open(itarget, '', 'location=no,height=' + height + ',width=' + width +
                    ',status=no,resizable=yes,scrollbars=yes,top=' + top + ',left=' + left);
                lwin.focus();
            } catch (oException) {}
        }

        $("form").submit(function() {
            $("button[type=submit]").attr("disabled", "disabled");
            $("button[type=submit]").empty().html("Please wait...");
        });

        function checkallbox(iobj) {
            inputArray = document.getElementsByTagName("input");
            for (i = 0; i < inputArray.length; i++) {
                if (inputArray[i].type.toLowerCase() == "checkbox" && inputArray[i] != iobj) {
                    tempCheckbox = inputArray[i];
                    if (tempCheckbox.checked) {
                        tempCheckbox.checked = false;
                    } else {
                        tempCheckbox.checked = true;
                    }
                }
            }
        }


        function checkallper(iobj) {
            inputArray = document.getElementsByName("per[]");
            for (i = 0; i < inputArray.length; i++) {
                if (inputArray[i].type.toLowerCase() == "checkbox" && inputArray[i] != iobj) {
                    tempCheckbox = inputArray[i];
                    if (tempCheckbox.checked) {
                        tempCheckbox.checked = false;
                    } else {
                        tempCheckbox.checked = true;
                    }
                }
            }
        }
    </script>


</body>

</html>
<?php /**PATH F:\Rahim Vai\Gateway\payment-gateway\resources\views/merchant/mrc_app.blade.php ENDPATH**/ ?>