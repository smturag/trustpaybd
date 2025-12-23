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

                    <li class="nav-item d-none d-lg-flex">

    <div class="d-flex align-items-center px-3 py-1 rounded-pill bg-light-success"
         title="Your main balance">
        <i class='bx bx-wallet text-success me-1'></i>
        <span class="fw-bold text-success">
            ৳{{ auth()->user('web')->balance }}
            {{ auth()->user('web')->user_type == 'partner' ? auth()->user('web')->available_balance : null }}
        </span>
    </div>

    @if (auth()->user('web')->user_type == 'partner')
        <div class="d-flex align-items-center px-3 py-1 rounded-pill bg-light-success ms-2"
             title="Your available (withdrawable) balance">
            <i class='bx bx-wallet text-success me-1'></i>
            <span class="fw-bold text-success">
                ৳{{ auth()->user('web')->available_balance }}
            </span>
        </div>
    @endif

</li>



                     <!--<li class="nav-item dropdown dropdown-large">
        <a class="nav-link dropdown-toggle dropdown-toggle-nocaret position-relative" href="#" data-bs-toggle="dropdown"><span class="alert-count">7</span>
         <i class='bx bx-bell'></i>
        </a>

       </li>-->



                 </ul>
             </div>



             <div class="user-box dropdown px-3">
                 <a class="d-flex align-items-center nav-link dropdown-toggle gap-3 dropdown-toggle-nocaret"
                     href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                     <img src="{{ asset('static/backend/images/avatars/avatar-2.png') }}" class="user-img"
                         alt="user avatar">
                     <div class="user-info">
                         <p class="user-name mb-0">{{ auth()->user('web')->member_code }}</p>
                         <p class="designattion mb-0"> {{ auth()->user('web')->user_type }}</p>
                     </div>
                 </a>
                 <ul class="dropdown-menu dropdown-menu-end">
                     <li><a class="dropdown-item d-flex align-items-center" href="{{ route('member.profile_view') }}"><i
                                 class="bx bx-user fs-5"></i><span>Profile</span></a>
                     </li>
                     <li><a class="dropdown-item d-flex align-items-center" href="javascript:;"><i
                                 class="bx bx-cog fs-5"></i><span>Settings</span></a>
                     </li>
                     <li><a class="dropdown-item d-flex align-items-center" href="javascript:;"><i
                                 class="bx bx-home-circle fs-5"></i><span>Dashboard</span></a>
                     </li>
                     <li><a class="dropdown-item d-flex align-items-center" href="javascript:;"><i
                                 class="bx bx-dollar-circle fs-5"></i><span>Earnings</span></a>
                     </li>
                     <li><a class="dropdown-item d-flex align-items-center" href="javascript:;"><i
                                 class="bx bx-download fs-5"></i><span>Downloads</span></a>
                     </li>
                     <li>
                         <div class="dropdown-divider mb-0"></div>
                     </li>
                     <li><a class="dropdown-item d-flex align-items-center" href="{{ route('userlogout') }}"><i
                                 class="bx bx-log-out-circle"></i><span>Logout</span></a>
                     </li>
                 </ul>
             </div>
         </nav>
     </div>
 </header>
 <!--end header -->
