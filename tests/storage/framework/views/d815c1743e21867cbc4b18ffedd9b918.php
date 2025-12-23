<!DOCTYPE html>
<html lang="en">

<head>
	<?php echo $__env->make('partial.customer_head', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
</head>

<body class="container-page send-money request-page">
    <div id="scroll-top-area">
        <a href="index.html#top-header"><i class="ti-angle-double-up" aria-hidden="true"></i></a>
    </div>
	
	<?php echo $__env->make('partial.customer_navbar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
	
	
	

        <?php echo $__env->yieldContent('customer'); ?>

	



	



<?php echo $__env->make('partial.customer_footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php echo $__env->make('partial.customer_script', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>


</body>

</html>

<?php /**PATH F:\Rahim Vai\Gateway\payment-gateway\resources\views/welcome.blade.php ENDPATH**/ ?>