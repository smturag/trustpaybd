<!--start header -->
<header>
    <div class="topbar d-flex align-items-center">
        <nav class="navbar navbar-expand gap-3">
            <div class="mobile-toggle-menu"><i class='bx bx-menu'></i></div>

                  <div class="top-menu ms-auto">
                <ul class="navbar-nav align-items-center gap-1">
                    <li class="nav-item dark-mode d-sm-flex">
                        <a class="nav-link dark-mode-icon" href="javascript:;"><i class='bx bx-moon'></i>
                        </a>
                    </li>
                </ul>
            </div>
            <div class="user-box dropdown px-3">
                <a class="d-flex align-items-center nav-link dropdown-toggle gap-3 dropdown-toggle-nocaret" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <img src="<?php echo e(asset('static/backend/images/avatars/avatar-2.png')); ?>" class="user-img" alt="user avatar" />
                    <div class="user-info">
                        <p class="user-name mb-0"><?php echo e(ucwords(Auth::guard('admin')->user()->admin_name)); ?></p>
                        <p class="designation mb-0"><?php echo e(ucwords(Auth::guard('admin')->user()->type)); ?></p>
                    </div>
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item d-flex align-items-center" href="<?php echo e(route('admin.profile')); ?>"><i class="bx bx-user fs-5"></i><span>Profile</span></a></li>
                    <li><a class="dropdown-item d-flex align-items-center" href="javascript:;"><i class="bx bx-cog fs-5"></i><span>Settings</span></a>
                    </li>
                    
                    
                    <li>
                        <div class="dropdown-divider mb-0"></div>
                    </li>
                    <li><a class="dropdown-item d-flex align-items-center" href="<?php echo e(route('adminlogout')); ?>"><i class="bx bx-log-out-circle"></i><span>Logout</span></a>
                    </li>
                </ul>
            </div>
        </nav>
    </div>
</header>

 <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="script.js"></script>

<script>

$(document).ready(function() {
    // Check if dark mode preference exists in local storage
    var isDarkMode = localStorage.getItem('darkModePreference') === 'true';

    // Get the dark mode icon element
    var darkModeIcon = $(".dark-mode-icon");

    // Set the initial class and icon based on the dark mode state
    if (isDarkMode) {
        darkModeIcon.find("i").attr("class", "bx bx-sun");
        $("html").addClass("dark-theme");
    } else {
        darkModeIcon.find("i").attr("class", "bx bx-moon");
        $("html").addClass("light-theme");
    }

    // Add a click event listener to the dark mode icon
    darkModeIcon.on("click", function() {
        // Toggle the dark mode state
        isDarkMode = !isDarkMode;

        // Toggle the icon and theme classes
        if (isDarkMode) {
            darkModeIcon.find("i").attr("class", "bx bx-sun");
            $("html").removeClass("light-theme").addClass("dark-theme");
        } else {
            darkModeIcon.find("i").attr("class", "bx bx-moon");
            $("html").removeClass("dark-theme").addClass("light-theme");
        }

        // Store the dark mode preference in local storage
        localStorage.setItem('darkModePreference', isDarkMode);
    });
});


</script>

<style>
    body.light-theme {
    background-color: #fff;
    color: #000;
}

/* Dark mode styles */
body.dark-theme {
    background-color: #333;
    color: #fff;
}
</style>
<?php /**PATH F:\Rahim Vai\Gateway\payment-gateway\resources\views/admin/layouts/admin_header.blade.php ENDPATH**/ ?>