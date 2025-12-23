 <header>
     <div class="topbar d-flex align-items-center">
         <nav class="navbar navbar-expand gap-3">
             <div class="mobile-toggle-menu"><i class='bx bx-menu'></i>
             </div>



             <div class="top-menu ms-auto">
                 <ul class="navbar-nav align-items-center gap-1">


                     <li class="nav-item dark-mode d-none d-sm-flex">
                         <a class="nav-link dark-mode-icon" href="javascript:;"><i class='bx bx-moon'></i>
                         </a>
                     </li>


                     <li class="nav-item dropdown dropdown-large">
                         <a class="nav-link dropdown-toggle dropdown-toggle-nocaret position-relative" href="#"
                             data-bs-toggle="dropdown"><span class="alert-count">7</span>
                             <i class='bx bx-bell'></i>
                         </a>

                     </li>



                 </ul>
             </div>
             <div class="user-box dropdown px-3">
                 <a class="d-flex align-items-center nav-link dropdown-toggle gap-3 dropdown-toggle-nocaret"
                     href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                     <img src="<?php echo e(asset('static/backend/images/avatars/avatar-2.png')); ?>" class="user-img"
                         alt="user avatar">
                     <div class="user-info">
                         <?php
                            $myId = Auth::guard('merchant')->user()->id;
                             $balance = getMerchantBalance($myId);
                             $balance = getMerchantBalance($myId);
                         ?>

                         <p class="user-name mb-0"><?php echo e(auth('merchant')->user()->username); ?></p>
                         <p class="designattion mb-0"><?php echo e($balance['balance']); ?> BDT</p>
                     </div>
                 </a>
                 <ul class="dropdown-menu dropdown-menu-end">
                     <li><a href="<?php echo e(route('merchant.profile')); ?>" class="dropdown-item d-flex align-items-center"
                             href="javascript:;"><i class="bx bx-user fs-5"></i><span>Profile</span></a>
                     </li>

                     <li>
                         <div class="dropdown-divider mb-0"></div>
                     </li>
                     <li><a class="dropdown-item d-flex align-items-center" href="<?php echo e(route('merchantlogout')); ?>"><i
                                 class="bx bx-log-out-circle"></i><span>Logout</span></a>
                     </li>
                 </ul>
             </div>
         </nav>
     </div>
 </header>
 <!--end header -->
<?php /**PATH F:\Rahim Vai\Gateway\payment-gateway\resources\views/merchant/mrc_head.blade.php ENDPATH**/ ?>