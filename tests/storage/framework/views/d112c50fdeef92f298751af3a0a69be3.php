<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo e(app_config('AppName')); ?></title>
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>"/>

    <link rel="icon" href="<?php echo e(asset('static/backend/images/favicon-32x32.png')); ?>" type="image/png"/>


    <link href="<?php echo e(asset('static/backend/plugins/simplebar/css/simplebar.css')); ?>" rel="stylesheet"/>

    <link href="<?php echo e(asset('static/backend/plugins/metismenu/css/metisMenu.min.css')); ?>" rel="stylesheet"/>

    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.css" />




	<?php echo $__env->yieldPushContent('css'); ?>
    <!-- Bootstrap CSS -->
    <link href="<?php echo e(asset('static/backend/css/bootstrap.min.css')); ?>" rel="stylesheet">
    <link href="<?php echo e(asset('static/backend/css/bootstrap-extended.css')); ?>" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <link href="<?php echo e(asset('static/backend/css/app.css')); ?>" rel="stylesheet">
    <link href="<?php echo e(asset('static/backend/css/icons.css')); ?>" rel="stylesheet">

    <!-- Theme Style CSS -->
    <link rel="stylesheet" href="<?php echo e(asset('static/backend/css/dark-theme.css')); ?>"/>
    <link rel="stylesheet" href="<?php echo e(asset('static/backend/css/semi-dark.css')); ?>"/>
    <link rel="stylesheet" href="<?php echo e(asset('static/backend/css/header-colors.css')); ?>"/>

    

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" integrity="sha512-example" crossorigin="anonymous" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />

    
    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.7/css/dataTables.dataTables.css" />




</head>

<body>
<div class="wrapper">
    <?php echo $__env->make('admin.layouts.admin_sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
	 <div class="page-wrapper">
        <div class="page-content">
    <?php echo $__env->yieldContent('content'); ?>
	</div>
	</div>
    <?php echo $__env->make('admin.layouts.admin_header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php echo $__env->make('admin.layouts.admin_footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

</div>




<script src="<?php echo e(asset('static/backend/js/bootstrap.bundle.min.js')); ?>"></script>

<script src="<?php echo e(asset('static/backend/js/jquery.min.js')); ?>"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script src="<?php echo e(asset('static/backend/plugins/simplebar/js/simplebar.min.js')); ?>"></script>
<script src="<?php echo e(asset('static/backend/plugins/metismenu/js/metisMenu.min.js')); ?>"></script>

<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>


<script src="https://cdn.datatables.net/2.1.7/js/dataTables.js"></script>


<?php echo $__env->yieldPushContent('js'); ?>
<script src="<?php echo e(asset('static/backend/js/app.js')); ?>"></script>
<script>

$("form").submit(function(){
$("button[type=submit]").attr("disabled", "disabled");
$("button[type=submit]").empty().html("Please wait...");
});
</script>



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
            } catch (oException) {
            }
        }

        $("form").submit(function () {
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


<!--<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>-->
</body>
</html>
<?php /**PATH F:\Rahim Vai\Gateway\payment-gateway\resources\views/admin/layouts/admin_app.blade.php ENDPATH**/ ?>