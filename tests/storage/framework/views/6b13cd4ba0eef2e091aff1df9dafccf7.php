<div class="row align-items-center mt-3 mb-3">




<?php if($paginator->hasPages()): ?>
    
    <div class="col-sm-12 col-md-5">Showing <?php echo e($paginator->firstItem()); ?> to <?php echo e($paginator->lastItem()); ?> of total <?php echo e($paginator->total()); ?> entries</div>

    <nav aria-label="Page navigation" class="col-sm-12 col-md-7">
        <ul class="pagination justify-content-end m-0">
            <?php if($paginator->onFirstPage()): ?>
                <li class="page-item disabled">
                    <a class="page-link" href="#" tabindex="-1">First</a>
                </li>
                <li class="page-item disabled">
                    <a class="page-link" href="#" tabindex="-1"><i class="bx bx-chevron-left"></i></a>
                </li>
            <?php else: ?>
                <li class="page-item">
                    <a class="page-link" href="<?php echo e(url()->current() . '?page=1'); ?>">First</a>
                </li>
                <li class="page-item">
                    <a class="page-link" href="<?php echo e($paginator->previousPageUrl()); ?>"><i class="bx bx-chevron-left fa-sm"></i></a>
                </li>
            <?php endif; ?>

            <?php $__currentLoopData = $elements; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $element): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php if(is_string($element)): ?>
                    <li class="page-item disabled"><?php echo e($element); ?></li>
                <?php endif; ?>

                <?php if(is_array($element)): ?>
                    <?php $__currentLoopData = $element; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $page => $url): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php if($page == $paginator->currentPage()): ?>
                            <li class="page-item active">
                                <a class="page-link"><?php echo e($page); ?></a>
                            </li>
                        <?php else: ?>
                            <li class="page-item">
                                <a class="page-link" href="<?php echo e($url); ?>"><?php echo e($page); ?></a>
                            </li>
                        <?php endif; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php endif; ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

            <?php if($paginator->hasMorePages()): ?>
                <li class="page-item">
                    <a class="page-link" href="<?php echo e($paginator->nextPageUrl()); ?>" rel="next"><i class="bx bx-chevron-right fa-sm"></i></a>
                </li>
                <li class="page-item">
                    <a class="page-link" href="<?php echo e(url()->current() . '?page='. $paginator->lastPage()); ?>" rel="next">Last</a>
                </li>
            <?php else: ?>
                <li class="page-item disabled">
                    <a class="page-link" href="#"><i class="bx bx-chevron-right fa-sm"></i></a>
                </li>
            <?php endif; ?>
        </ul>
    </nav>
<?php endif; ?>
</div>
<?php /**PATH F:\Rahim Vai\Gateway\payment-gateway\resources\views/common/pagination-custom.blade.php ENDPATH**/ ?>