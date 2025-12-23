<!--sidebar wrapper -->
<div class="sidebar-wrapper" data-simplebar="true">
    <div class="sidebar-header">

        <?php
            $app_name = app_config('AppName');
            $image = app_config('AppLogo');

        ?>

        
        <div>
            <h4 class="logo-text"><?php echo e($app_name); ?></h4>
        </div>
        <div class="toggle-icon ms-auto"><i class='bx bx-arrow-back'></i>
        </div>
    </div>
    <!--navigation-->
    <ul class="metismenu" id="menu">
        <li>
            <a href="<?php echo e(route('merchant_dashboard')); ?>">
                <div class="parent-icon"><i class='bx bx-home-alt'></i>
                </div>
                <div class="menu-title">Dashboard</div>
            </a>
        </li>

        <li>
            <a href="<?php echo e(route('merchant.payment-request')); ?>">
                <div class="parent-icon"><i class="lni lni-wallet"></i>
                </div>
                <div class="menu-title">Payment List</div>
            </a>
        </li>

        <li>
            <a href="<?php echo e(route('merchant.service-request')); ?>">
                <div class="parent-icon"><i class="lni lni-wallet"></i>
                </div>
                <div class="menu-title">MFS Request</div>
            </a>
        </li>



        <li>
            <a class="has-arrow" href="javascript:;">
                <div class="parent-icon"><i class="lni lni-display-alt"></i></div>
                <div class="menu-title">Withdraw</div>
            </a>
            <ul>
                <li><a href="<?php echo e(route('merchant.withdraw')); ?>"><i class='bx bx-radio-circle'></i> Request Withdraw</a>
                </li>
                <li><a href="<?php echo e(route('merchant.withdraw-list')); ?>"><i class='bx bx-radio-circle'></i>Withdraw List</a>
                </li>
            </ul>
        </li>

        

        <?php if(auth()->guard('merchant')->user()->merchant_type == 'general'): ?>
            <li>
                <a href="<?php echo e(route('sub_merchant.list')); ?>">
                    <div class="parent-icon"><i class="lni lni-cog"></i></div>
                    <div class="menu-title">Sub Merchant List</div>
                </a>
            </li>
        <?php endif; ?>


        <!--<li>-->
        <!--	<a href="<?php echo e(route('merchant.developer-index')); ?>">-->
        <!--		<div class="parent-icon"><i class="lni lni-cog"></i>-->
        <!--		</div>-->
        <!--		<div class="menu-title">Developer</div>-->
        <!--	</a>-->
        <!--</li>-->
        <li>
            <a class="has-arrow" href="javascript:;">
                <div class="parent-icon"><i class="bx bx-cog"></i>
                </div>
                <div class="menu-title">Developer</div>
            </a>
            <ul>
                <li><a href="<?php echo e(route('merchant.developer-index')); ?>"><i class='bx bx-radio-circle'></i>Settings</a>
                </li>
                
            </ul>
            <!--    </li>-->
            <!--    	<li>-->
            <!--	<a class="has-arrow" href="javascript:;">-->
            <!--		<div class="parent-icon"><i class="bx bx-menu"></i>-->
            <!--		</div>-->
            <!--		<div class="menu-title">Reports</div>-->
            <!--	</a>-->
            <!--	<ul class="mm-collapse">-->
            <!--		<li> <a  href="javascript:;"><i class="bx bx-radio-circle"></i>Report 1</a></li>-->
            <!--	</ul>-->
            <!--</li>-->

        <li>
            <a href="<?php echo e(route('merchant.support_list_view')); ?>">
                <div class="parent-icon"><i class="bx bx-support"></i></div>
                <div class="menu-title">Support</div>
            </a>
        </li>
        <li>
            <a href="<?php echo e(route('merchantlogout')); ?>">
                <div class="parent-icon"><i class="bx bx-exit text-red"></i></div>
                <div class="menu-title">Log Out</div>
            </a>
        </li>
    </ul>
</div>
<?php /**PATH F:\Rahim Vai\Gateway\payment-gateway\resources\views/merchant/mrc_sidebar.blade.php ENDPATH**/ ?>