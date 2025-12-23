 <!--sidebar wrapper -->

 @php
     $app_name = app_config('AppName');
     $image = app_config('AppLogo');
 @endphp

 <div class="sidebar-wrapper" data-simplebar="true">
     <div class="sidebar-header">
         {{-- <div>
					<img src="{{ asset('static/backend/images/logo-icon.png') }}" class="logo-icon" alt="logo icon">
				</div> --}}
         <div>
             <h4 class="logo-text">{{ $app_name }}</h4>
         </div>
         <div class="toggle-icon ms-auto"><i class='bx bx-arrow-back'></i>
         </div>
     </div>
     <!--navigation-->
     <ul class="metismenu" id="menu">


         <li>
             <a href="{{ route('memberdashboard') }}">
                 <div class="parent-icon"><i class='bx bx-home-alt'></i>
                 </div>
                 <div class="menu-title">Dashboard</div>
             </a>
         </li>

         <li>
             <a href="{{ route('user.payment-request') }}">
                 <div class="parent-icon"><i class='bx bx-mobile'></i>
                 </div>
                 <div class="menu-title">Payment Request</div>
             </a>
         </li>
         <li>
             <a href="{{ route('member.serviceReq', 'all') }}">
                 <div class="parent-icon"><i class='bx bx-mobile'></i>
                 </div>
                 <div class="menu-title">MFS Request</div>
             </a>
         </li>


         <li>
             <a href="{{ route('member_modem_list') }}">
                 <div class="parent-icon"><i class='bx bx-mobile'></i>
                 </div>
                 <div class="menu-title">Modems</div>
             </a>
         </li>

         {{-- @if (auth()->user('web')->user_type != 'agent')
             <li>
                 <a href="{{ route('member_list') }}">
                     <div class="parent-icon"><i class='bx bx-user'></i>
                     </div>
                     <div class="menu-title">
                         @if (auth()->user('web')->user_type == 'partner')
                             DSO
                         @else
                             Agent
                         @endif
                     </div>
                 </a>

             </li>
         @else --}}


         @if (auth()->user('web')->user_type != 'agent')
         <li>
             <a href="{{ route('member_list') }}">
                 <div class="parent-icon"><i class='bx bx-user'></i>
                 </div>
                 <div class="menu-title">
                     @if (auth()->user('web')->user_type == 'partner')
                         Agent
                     @endif
                 </div>
             </a>

         </li>
     @else

         @endif


         <li>
             <a href="{{ route('member_sms_inbox') }}">
                 <div class="parent-icon"><i class='bx bx-code-alt'></i>
                 </div>
                 <div class="menu-title">Sms Inbox</div>
             </a>
         </li>

         <li>
					<a href="{{ route('member_transaction') }}">
						<div class="parent-icon"><i class="bx bx-grid-alt"></i>
						</div>
						<div class="menu-title">Transaction</div>
					</a>

				</li>



       <li>
     <a href="{{ route('api_method_list') }}">
      <div class="parent-icon"><i class="bx bx-folder"></i>
      </div>
      <div class="menu-title">Merchant API</div>
     </a>
    </li>



    <!--<li>
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
             <a href="{{ route('supportList') }}">
                 <div class="parent-icon"><i class="bx bx-support"></i>
                 </div>
                 <div class="menu-title">Support</div>
             </a>
         </li>


         <li>
            <a class="has-arrow" href="javascript:;">
                <div class="parent-icon"><i class="bx bx-code-block"></i>
                </div>
                <div class="menu-title">Report</div>
            </a>
            <ul>
                <li><a href="{{ route('report.member.service_report') }}"><i class='bx bx-key'></i>Service Report</a></li>

            </ul>
        </li>


     </ul>
     <!--end navigation-->
 </div>
 <!--end sidebar wrapper -->
