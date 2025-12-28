<div x-data="{ open: false }" class="relative">
    <button @click="open = !open" class="relative p-2.5 rounded-xl hover:bg-gray-100 dark:hover:bg-gray-800 transition-all duration-200 group">
        <svg class="w-5 h-5 text-gray-600 dark:text-gray-300 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
        </svg>
        <span id="notificationBadge" class="absolute top-1.5 right-1.5 w-2 h-2 bg-red-500 rounded-full ring-2 ring-white dark:ring-gray-900 animate-pulse" style="display: none;"></span>
    </button>
    
    <div x-show="open" @click.away="open = false" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95 translate-y-1" x-transition:enter-end="opacity-100 scale-100 translate-y-0" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" class="absolute right-0 mt-3 w-80 sm:w-96 bg-white dark:bg-gray-800 rounded-2xl shadow-2xl border border-gray-200 dark:border-gray-700 overflow-hidden z-50" style="display: none; max-height: 500px;">
        <div class="px-5 py-4 bg-gradient-to-r from-blue-500 to-purple-600 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
            <div>
                <h3 class="font-bold text-white text-base">Support Notifications</h3>
                <p class="text-xs text-blue-100 mt-0.5">New support tickets & replies</p>
            </div>
            <button id="markAllRead" class="text-xs text-white hover:text-blue-100 underline transition-colors">
                Mark all read
            </button>
        </div>
        <div id="notificationList" class="overflow-y-auto" style="max-height: 400px;">
            <div class="p-4 text-center text-gray-500 dark:text-gray-400">
                <svg class="w-12 h-12 mx-auto mb-3 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                </svg>
                <p class="text-sm font-medium">No notifications</p>
                <p class="text-xs mt-1">You're all caught up!</p>
            </div>
        </div>
    </div>
</div>

<!-- Audio element for notification sound -->
<audio id="adminNotificationSound" preload="auto">
    <source src="data:audio/wav;base64,UklGRnoGAABXQVZFZm10IBAAAAABAAEAQB8AAEAfAAABAAgAZGF0YQoGAACBhYqFbF1fdJivrJBhNjVgodDbq2EcBj+a2/LDciUFLIHO8tiJNwgZaLvt559NEAxQp+PwtmMcBjiR1/LMeSwFJHfH8N2QQAoUXrTp66hVFApGn+DyvmwhBSmI0fPTgjMGHm7A7+OZUQ0PVKfn77BeFQxMoN/xwHIiBCqIzvLThzQHH27A7+OZUQ0PVKfn77BeFQxMoN/xwHIiBCqIzvLThzQHH27A7+OZUQ0PVKfn77BeFQxMoN/xwHIiBCqIzvLThzQHH27A7+OZUQ0PVKfn77BeFQxMoN/xwHIiBCqIzvLThzQHH27A7+OZUQ0PVKfn77BeFQxMoN/xwHIiBCqIzvLThzQHH27A7+OZUQ0PVKfn77BeFQxMoN/xwHIiBCqIzvLThzQHH27A7+OZUQ0PVKfn77BeFQxMoN/xwHIiBCqIzvLThzQHH27A7+OZUQ0PVKfn77BeFQxMoN/xwHIiBCqIzvLThzQHH27A7+OZUQ0PVKfn77BeFQxMoN/xwHIiBCqIzvLThzQHH27A7+OZUQ0PVKfn77BeFQxMoN/xwHIiBCqIzvLThzQHH27A7+OZUQ0PVKfn77BeFQxMoN/xwHIiBCqIzvLThzQHH27A7+OZUQ0PVKfn77BeFQxMoN/xwHIiBCqIzvLThzQHH27A7+OZUQ0PVKfn77BeFQxMoN/xwHIiBCqIzvLThzQHH27A7+OZUQ0PVKfn77BeFQxMoN/xwHIiBCqIzvLThzQHH27A7+OZUQ0PVKfn77BeFQxMoN/xwHIiBCqIzvLThzQHH27A7+OZUQ0PVKfn77BeFQxMoN/xwHIiBCqIzvLThzQHH27A7+OZUQ0PVKfn77BeFQxMoN/xwHIiBCqIzvLThzQHH27A7+OZUQwT" type="audio/wav">
</audio>

<style>
    .notification-item {
        padding: 12px 16px;
        border-bottom: 1px solid #f0f0f0;
        cursor: pointer;
        transition: background-color 0.2s;
    }
    
    .dark .notification-item {
        border-bottom-color: #374151;
    }
    
    .notification-item:hover {
        background-color: #f8f9fa;
    }
    
    .dark .notification-item:hover {
        background-color: #1f2937;
    }
    
    .notification-item.unread {
        background-color: #e7f3ff;
    }
    
    .dark .notification-item.unread {
        background-color: #1e3a5f;
    }
    
    .notification-item.unread:hover {
        background-color: #d0e8ff;
    }
    
    .dark .notification-item.unread:hover {
        background-color: #2d4a7c;
    }
    
    .notification-title {
        font-weight: 600;
        font-size: 14px;
        margin-bottom: 4px;
        color: #1f2937;
    }
    
    .dark .notification-title {
        color: #f3f4f6;
    }
    
    .notification-message {
        font-size: 13px;
        color: #6b7280;
        margin-bottom: 4px;
        line-height: 1.4;
    }
    
    .dark .notification-message {
        color: #9ca3af;
    }
    
    .notification-time {
        font-size: 11px;
        color: #9ca3af;
    }
    
    .dark .notification-time {
        color: #6b7280;
    }
</style>

<script>
    let notificationInterval;
    let previousUnreadCount = 0;

    function playAdminNotificationSound() {
        const audio = document.getElementById('adminNotificationSound');
        if (audio) {
            audio.play().catch(e => console.log('Could not play notification sound:', e));
        }
    }

    function timeAgo(dateString) {
        const date = new Date(dateString);
        const now = new Date();
        const seconds = Math.floor((now - date) / 1000);
        
        if (seconds < 60) return 'Just now';
        if (seconds < 3600) return Math.floor(seconds / 60) + 'm ago';
        if (seconds < 86400) return Math.floor(seconds / 3600) + 'h ago';
        if (seconds < 604800) return Math.floor(seconds / 86400) + 'd ago';
        return date.toLocaleDateString();
    }

    function loadNotifications() {
        fetch('{{ route("admin.notifications") }}')
            .then(response => response.json())
            .then(data => {
                // Play sound if unread count increased
                if (previousUnreadCount >= 0 && data.unread_count > previousUnreadCount) {
                    playAdminNotificationSound();
                }
                previousUnreadCount = data.unread_count;
                
                updateNotificationUI(data);
            })
            .catch(error => {
                console.error('Error loading notifications:', error);
            });
    }

    function updateNotificationUI(data) {
        const badge = document.getElementById('notificationBadge');
        const list = document.getElementById('notificationList');
        
        if (data.unread_count > 0) {
            badge.style.display = 'block';
        } else {
            badge.style.display = 'none';
        }

        if (data.notifications.length > 0) {
            let html = '';
            data.notifications.forEach(function(notification) {
                const isUnread = notification.is_read == 0 ? 'unread' : '';
                const time = timeAgo(notification.created_at);
                
                html += `
                    <div class="notification-item ${isUnread}" onclick="handleNotificationClick(${notification.id}, '${notification.ticket_id}')">
                        <div class="notification-title">${notification.title}</div>
                        <div class="notification-message">${notification.message}</div>
                        <div class="notification-time">${time}</div>
                    </div>
                `;
            });
            list.innerHTML = html;
        } else {
            list.innerHTML = `
                <div class="p-4 text-center text-gray-500 dark:text-gray-400">
                    <svg class="w-12 h-12 mx-auto mb-3 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                    </svg>
                    <p class="text-sm font-medium">No notifications</p>
                    <p class="text-xs mt-1">You're all caught up!</p>
                </div>
            `;
        }
    }

    function handleNotificationClick(notificationId, ticketId) {
        // Mark as read
        fetch(`{{ route('admin.notifications.mark-read', '') }}/${notificationId}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success && ticketId) {
                window.location.href = `{{ route('admin.view_ticket', '') }}/${ticketId}`;
            }
        });
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Load notifications on page load
        loadNotifications();
        
        // Refresh notifications every 30 seconds
        notificationInterval = setInterval(loadNotifications, 30000);
        
        // Mark all as read
        document.getElementById('markAllRead')?.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            fetch('{{ route("admin.notifications.mark-all-read") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    loadNotifications();
                }
            });
        });
    });
</script>
