<!-- Merchant Notification Dropdown with Sound -->
<div x-data="merchantNotifications()" x-init="init()" class="relative">
    <button @click="toggleDropdown()" class="flex items-center justify-center w-10 h-10 rounded-lg text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors relative">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
        </svg>
        <span x-show="unreadCount > 0" x-text="unreadCount" class="absolute -top-1 -right-1 flex items-center justify-center min-w-[18px] h-[18px] bg-red-500 text-white text-[10px] font-bold rounded-full border-2 border-white dark:border-slate-900 px-1"></span>
        <span x-show="isNew" class="absolute top-0 right-0 flex h-3 w-3">
            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
            <span class="relative inline-flex rounded-full h-3 w-3 bg-red-500"></span>
        </span>
    </button>

    <!-- Dropdown Panel -->
    <div x-show="open" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         @click.away="open = false"
         class="absolute right-0 mt-2 w-80 sm:w-96 bg-white dark:bg-slate-800 rounded-xl shadow-2xl border border-slate-200 dark:border-slate-700 z-50 max-h-[80vh] overflow-hidden flex flex-col"
         style="display: none;">
        
        <!-- Header -->
        <div class="flex items-center justify-between px-4 py-3 border-b border-slate-200 dark:border-slate-700 bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-slate-800 dark:to-slate-700">
            <div class="flex items-center space-x-2">
                <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                </svg>
                <h3 class="text-sm font-bold text-slate-800 dark:text-slate-100">Notifications</h3>
                <span x-show="unreadCount > 0" x-text="'(' + unreadCount + ')'" class="text-xs font-semibold text-blue-600 dark:text-blue-400"></span>
            </div>
            <button @click="markAllAsRead()" x-show="unreadCount > 0" class="text-xs text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 font-medium transition-colors">
                Mark all read
            </button>
        </div>

        <!-- Notifications List -->
        <div class="flex-1 overflow-y-auto" style="max-height: 400px;">
            <template x-if="loading">
                <div class="flex items-center justify-center py-12">
                    <svg class="animate-spin h-8 w-8 text-blue-600" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </div>
            </template>

            <template x-if="!loading && notifications.length === 0">
                <div class="flex flex-col items-center justify-center py-12 px-4">
                    <svg class="w-16 h-16 text-slate-300 dark:text-slate-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                    </svg>
                    <p class="text-sm text-slate-500 dark:text-slate-400 font-medium">No notifications yet</p>
                    <p class="text-xs text-slate-400 dark:text-slate-500 mt-1">We'll notify you when something arrives</p>
                </div>
            </template>

            <template x-if="!loading && notifications.length > 0">
                <div>
                    <template x-for="notification in notifications" :key="notification.id">
                        <div @click="handleNotificationClick(notification)" 
                             :class="{'bg-blue-50 dark:bg-blue-900/20': !notification.is_read, 'hover:bg-slate-50 dark:hover:bg-slate-700/50': true}"
                             class="px-4 py-3 border-b border-slate-100 dark:border-slate-700 cursor-pointer transition-colors">
                            <div class="flex items-start space-x-3">
                                <div class="flex-shrink-0">
                                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center">
                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z" />
                                        </svg>
                                    </div>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-start justify-between">
                                        <p class="text-sm font-semibold text-slate-800 dark:text-slate-100" x-text="notification.title"></p>
                                        <span x-show="!notification.is_read" class="flex-shrink-0 w-2 h-2 bg-blue-600 rounded-full ml-2 mt-1.5"></span>
                                    </div>
                                    <p class="text-xs text-slate-600 dark:text-slate-400 mt-1 line-clamp-2" x-text="notification.message"></p>
                                    <div class="flex items-center mt-2 text-xs text-slate-500 dark:text-slate-500">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <span x-text="formatTime(notification.created_at)"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
            </template>
        </div>

        <!-- Footer -->
        <div class="px-4 py-3 border-t border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/50">
            <a href="{{ route('merchant.support_list_view') }}" class="block text-center text-sm text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 font-medium transition-colors">
                View all tickets →
            </a>
        </div>
    </div>
</div>

<!-- Audio element for notification sound -->
<audio id="notificationSound" preload="auto">
    <source src="data:audio/wav;base64,UklGRnoGAABXQVZFZm10IBAAAAABAAEAQB8AAEAfAAABAAgAZGF0YQoGAACBhYqFbF1fdJivrJBhNjVgodDbq2EcBj+a2/LDciUFLIHO8tiJNwgZaLvt559NEAxQp+PwtmMcBjiR1/LMeSwFJHfH8N2QQAoUXrTp66hVFApGn+DyvmwhBSmI0fPTgjMGHm7A7+OZUQ0PVKfn77BeFQxMoN/xwHIiBCqIzvLThzQHH27A7+OZUQ0PVKfn77BeFQxMoN/xwHIiBCqIzvLThzQHH27A7+OZUQ0PVKfn77BeFQxMoN/xwHIiBCqIzvLThzQHH27A7+OZUQ0PVKfn77BeFQxMoN/xwHIiBCqIzvLThzQHH27A7+OZUQ0PVKfn77BeFQxMoN/xwHIiBCqIzvLThzQHH27A7+OZUQ0PVKfn77BeFQxMoN/xwHIiBCqIzvLThzQHH27A7+OZUQ0PVKfn77BeFQxMoN/xwHIiBCqIzvLThzQHH27A7+OZUQ0PVKfn77BeFQxMoN/xwHIiBCqIzvLThzQHH27A7+OZUQ0PVKfn77BeFQxMoN/xwHIiBCqIzvLThzQHH27A7+OZUQ0PVKfn77BeFQxMoN/xwHIiBCqIzvLThzQHH27A7+OZUQ0PVKfn77BeFQxMoN/xwHIiBCqIzvLThzQHH27A7+OZUQ0PVKfn77BeFQxMoN/xwHIiBCqIzvLThzQHH27A7+OZUQ0PVKfn77BeFQxMoN/xwHIiBCqIzvLThzQHH27A7+OZUQ0PVKfn77BeFQxMoN/xwHIiBCqIzvLThzQHH27A7+OZUQ0PVKfn77BeFQxMoN/xwHIiBCqIzvLThzQHH27A7+OZUQ0PVKfn77BeFQxMoN/xwHIiBCqIzvLThzQHH27A7+OZUQ0PVKfn77BeFQxMoN/xwHIiBCqIzvLThzQHH27A7+OZUQwT" type="audio/wav">
</audio>

<script>
function merchantNotifications() {
    return {
        open: false,
        loading: false,
        notifications: [],
        unreadCount: 0,
        isNew: false,
        lastCheckTime: Date.now(),
        
        init() {
            this.fetchNotifications();
            // Check for new notifications every 10 seconds
            setInterval(() => {
                this.fetchNotifications();
            }, 10000);
        },
        
        toggleDropdown() {
            this.open = !this.open;
            if (this.open && this.isNew) {
                this.isNew = false;
            }
        },
        
        async fetchNotifications() {
            try {
                const response = await fetch('{{ route("merchant.notifications") }}');
                const data = await response.json();
                
                const oldUnreadCount = this.unreadCount;
                this.notifications = data.notifications;
                this.unreadCount = data.unread_count;
                
                // Play sound if there's a new notification
                if (this.unreadCount > oldUnreadCount && oldUnreadCount >= 0) {
                    this.playNotificationSound();
                    this.isNew = true;
                }
            } catch (error) {
                console.error('Error fetching notifications:', error);
            }
        },
        
        async handleNotificationClick(notification) {
            // Mark as read
            if (!notification.is_read) {
                try {
                    await fetch(`/merchant/notifications/${notification.id}/read`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        }
                    });
                    
                    notification.is_read = true;
                    this.unreadCount = Math.max(0, this.unreadCount - 1);
                } catch (error) {
                    console.error('Error marking notification as read:', error);
                }
            }
            
            // Navigate to ticket
            if (notification.ticket_number) {
                window.location.href = `/merchant/support/reply/${notification.ticket_number}`;
            }
        },
        
        async markAllAsRead() {
            try {
                await fetch('{{ route("merchant.notifications.readAll") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });
                
                this.notifications.forEach(n => n.is_read = true);
                this.unreadCount = 0;
            } catch (error) {
                console.error('Error marking all as read:', error);
            }
        },
        
        playNotificationSound() {
            const audio = document.getElementById('notificationSound');
            if (audio) {
                audio.play().catch(e => console.log('Could not play notification sound:', e));
            }
        },
        
        formatTime(timestamp) {
            const date = new Date(timestamp);
            const now = new Date();
            const diffMs = now - date;
            const diffMins = Math.floor(diffMs / 60000);
            
            if (diffMins < 1) return 'Just now';
            if (diffMins < 60) return `${diffMins}m ago`;
            
            const diffHours = Math.floor(diffMins / 60);
            if (diffHours < 24) return `${diffHours}h ago`;
            
            const diffDays = Math.floor(diffHours / 24);
            if (diffDays < 7) return `${diffDays}d ago`;
            
            return date.toLocaleDateString();
        }
    };
}
</script>
