 <!--sidebar wrapper -->

 <?php
     $app_name = app_config('AppName');
     $image = app_config('AppLogo');
 ?>

 <div class="sidebar-wrapper" data-simplebar="true">
     <div class="sidebar-header">
         
         <div>
             <h4 class="logo-text"><?php echo e($app_name); ?></h4>
         </div>
         <div class="toggle-icon ms-auto"><i class='bx bx-arrow-back'></i>
         </div>
     </div>
     <!--navigation-->
     <ul class="metismenu" id="menu">


         <li>
             <a href="<?php echo e(route('memberdashboard')); ?>">
                 <div class="parent-icon"><i class='bx bx-home-alt'></i>
                 </div>
                 <div class="menu-title">Dashboard</div>
             </a>
         </li>

         <li>
             <a href="<?php echo e(route('user.payment-request')); ?>">
                 <div class="parent-icon"><i class='bx bx-mobile'></i>
                 </div>
                 <div class="menu-title">Payment Request</div>
             </a>
         </li>
         <li>
             <a href="<?php echo e(route('member.serviceReq', 'all')); ?>">
                 <div class="parent-icon"><i class='bx bx-mobile'></i>
                 </div>
                 <div class="menu-title">MFS Request</div>
             </a>
         </li>


         <li>
             <a href="<?php echo e(route('member_modem_list')); ?>">
                 <div class="parent-icon"><i class='bx bx-mobile'></i>
                 </div>
                 <div class="menu-title">Modems</div>
             </a>
         </li>

         


         <?php if(auth()->user('web')->user_type != 'agent'): ?>
         <li>
             <a href="<?php echo e(route('member_list')); ?>">
                 <div class="parent-icon"><i class='bx bx-user'></i>
                 </div>
                 <div class="menu-title">
                     <?php if(auth()->user('web')->user_type == 'partner'): ?>
                         Agent
                     <?php endif; ?>
                 </div>
             </a>

         </li>
     <?php else: ?>

         <?php endif; ?>


         <li>
             <a href="<?php echo e(route('member_sms_inbox')); ?>">
                 <div class="parent-icon"><i class='bx bx-code-alt'></i>
                 </div>
                 <div class="menu-title">Sms Inbox</div>
             </a>
         </li>

         <li>
					<a href="<?php echo e(route('member_transaction')); ?>">
						<div class="parent-icon"><i class="bx bx-grid-alt"></i>
						</div>
						<div class="menu-title">Transaction</div>
					</a>

				</li>



         <!--<li>
     <a class="has-arrow" href="javascript:;">
      <div class="parent-icon"><i class="bx bx-line-chart"></i>
      </div>
      <div class="menu-title">Reports</div>
     </a>
     <ul>
      <li> <a href="charts-apex-chart.html"><i class='bx bx-radio-circle'></i>Apex</a>
      </li>
      <li> <a href="charts-chartjs.html"><i class='bx bx-radio-circle'></i>Chartjs</a>
      </li>
      <li> <a href="charts-highcharts.html"><i class='bx bx-radio-circle'></i>Highcharts</a>
      </li>
     </ul>
    </li>



    <li>
     <a href="#" target="_blank">
      <div class="parent-icon"><i class="bx bx-folder"></i>
      </div>
      <div class="menu-title">CS</div>
     </a>
    </li>-->

         <!-- 				<li>
     <a class="has-arrow" href="javascript:;">
      <div class="parent-icon"><i class="bx bx-menu"></i>
      </div>
      <div class="menu-title">Menu Levels</div>
     </a>
     <ul class="mm-collapse">
      <li> <a  href="javascript:;"><i class="bx bx-radio-circle"></i>Level One</a></li>
     </ul>
    </li>
    -->
         <li>
             <a href="<?php echo e(route('supportList')); ?>">
                 <div class="parent-icon"><i class="bx bx-support"></i>
                 </div>
                 <div class="menu-title">Support</div>
             </a>
         </li>
     </ul>
     <!--end navigation-->
 </div>
 <!--end sidebar wrapper -->
<?php /**PATH F:\Rahim Vai\Gateway\payment-gateway\resources\views/member/layout/member_sidebar.blade.php ENDPATH**/ ?>