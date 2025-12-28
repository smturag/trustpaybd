@extends('admin.layouts.admin_app')
@section('title', 'Ticket Reply')
@push('css')
<style>
    .message-bubble {
        animation: slideIn 0.3s ease-out;
    }
    @keyframes slideIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>
@endpush
@section('content')

<div class="max-w-7xl mx-auto space-y-6">
    <!-- Ticket Header Card -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-6 py-5">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <div class="flex items-center space-x-3 mb-2">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-white/20 text-white backdrop-blur-sm">
                            #{{ $data['ticket_head']->ticket }}
                        </span>
                        @if($data['ticket_head']->status == 1)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-yellow-400 text-yellow-900">Open</span>
                        @elseif($data['ticket_head']->status == 2)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-400 text-green-900">Answered</span>
                        @elseif($data['ticket_head']->status == 3)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-blue-400 text-blue-900">Customer Reply</span>
                        @elseif($data['ticket_head']->status == 9)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-gray-400 text-gray-900">Closed</span>
                        @endif
                        @if($data['ticket_head']->priority)
                            @php
                                $priorityColors = [
                                    'low' => 'bg-green-100 text-green-800',
                                    'medium' => 'bg-yellow-100 text-yellow-800',
                                    'high' => 'bg-orange-100 text-orange-800',
                                    'urgent' => 'bg-red-100 text-red-800'
                                ];
                            @endphp
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold {{ $priorityColors[$data['ticket_head']->priority] ?? 'bg-gray-100 text-gray-800' }}">
                                {{ ucfirst($data['ticket_head']->priority) }}
                            </span>
                        @endif
                    </div>
                    <h2 class="text-2xl font-bold text-white">{{ $data['ticket_head']->subject }}</h2>
                </div>
                <div class="flex items-center space-x-2">
                    <button type="button" onclick="closeTicket('{{ $data['ticket_head']->ticket }}')" class="inline-flex items-center px-4 py-2 bg-white/20 hover:bg-white/30 text-white font-semibold rounded-lg backdrop-blur-sm transition-all duration-200">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                        Close Ticket
                    </button>
                </div>
            </div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 p-6 bg-gray-50 dark:bg-gray-900/50 border-b border-gray-200 dark:border-gray-700">
            <div class="flex items-start space-x-3">
                <div class="flex-shrink-0 w-10 h-10 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-xs text-gray-500 dark:text-gray-400 font-medium">Customer</p>
                    <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $data['ticket_user']->fullname }}</p>
                    <p class="text-xs text-gray-600 dark:text-gray-400">{{ $data['ticket_head']->customer_type == 0 ? 'Merchant' : 'User' }}</p>
                </div>
            </div>
            <div class="flex items-start space-x-3">
                <div class="flex-shrink-0 w-10 h-10 bg-green-100 dark:bg-green-900/30 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-xs text-gray-500 dark:text-gray-400 font-medium">Created</p>
                    <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ \Carbon\Carbon::parse($data['ticket_head']->created_at)->format('M d, Y') }}</p>
                    <p class="text-xs text-gray-600 dark:text-gray-400">{{ \Carbon\Carbon::parse($data['ticket_head']->created_at)->format('h:i A') }}</p>
                </div>
            </div>
            @if($data['ticket_head']->last_reply_at)
            <div class="flex items-start space-x-3">
                <div class="flex-shrink-0 w-10 h-10 bg-purple-100 dark:bg-purple-900/30 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-xs text-gray-500 dark:text-gray-400 font-medium">Last Reply</p>
                    <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ \Carbon\Carbon::parse($data['ticket_head']->last_reply_at)->diffForHumans() }}</p>
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- Conversation Thread -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50">
            <h3 class="text-lg font-bold text-gray-900 dark:text-white flex items-center">
                <svg class="w-5 h-5 mr-2 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                </svg>
                Conversation
            </h3>
        </div>
        
        <div class="p-6 space-y-6 max-h-[600px] overflow-y-auto">
            @foreach ($data['ticket'] as $item)
                <!-- Customer Message -->
                <div class="message-bubble">
                    <div class="flex items-start space-x-3">
                        <div class="flex-shrink-0 w-10 h-10 bg-gradient-to-br from-orange-400 to-pink-500 rounded-full flex items-center justify-center text-white font-bold text-sm">
                            {{ substr($data['ticket_user']->fullname, 0, 2) }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center space-x-2 mb-1">
                                <span class="text-sm font-semibold text-gray-900 dark:text-white">{{ $data['ticket_user']->fullname }}</span>
                                <span class="text-xs text-gray-500 dark:text-gray-400">{{ \Carbon\Carbon::parse($item->created_at)->format('M d, Y - h:i A') }}</span>
                            </div>
                            <div class="bg-gray-100 dark:bg-gray-700 rounded-2xl rounded-tl-none px-4 py-3">
                                <p class="text-sm text-gray-800 dark:text-gray-200 whitespace-pre-wrap">{{ $item->comment }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                @if($item->comment_reply !== null)
                    <!-- Admin Reply -->
                    <div class="message-bubble ml-12">
                        <div class="flex items-start space-x-3">
                            <div class="flex-shrink-0 w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-full flex items-center justify-center text-white font-bold text-sm">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-6-3a2 2 0 11-4 0 2 2 0 014 0zm-2 4a5 5 0 00-4.546 2.916A5.986 5.986 0 0010 16a5.986 5.986 0 004.546-2.084A5 5 0 0010 11z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center space-x-2 mb-1">
                                    <span class="text-sm font-semibold text-blue-600 dark:text-blue-400">Admin</span>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-300">Support Team</span>
                                </div>
                                <div class="bg-gradient-to-br from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 border border-blue-200 dark:border-blue-800 rounded-2xl rounded-tl-none px-4 py-3">
                                    <p class="text-sm text-gray-800 dark:text-gray-200 whitespace-pre-wrap">{{ $item->comment_reply }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            @endforeach

            <!-- Attachments Display -->
            @if(isset($attachments) && $attachments->count() > 0)
                <div class="border-t border-gray-200 dark:border-gray-700 pt-6 mt-6">
                    <h4 class="text-sm font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/>
                        </svg>
                        Attachments ({{ $attachments->count() }})
                    </h4>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                        @foreach($attachments as $attachment)
                            <a href="{{ route('admin.support.download', $attachment->id) }}" class="flex items-center space-x-3 p-3 bg-gray-50 dark:bg-gray-900/50 border border-gray-200 dark:border-gray-700 rounded-lg hover:shadow-md transition-all duration-200 group">
                                @php
                                    $fileIcon = 'fa-file';
                                    $iconColor = 'text-gray-600';
                                    if(str_contains($attachment->file_type, 'image')) {
                                        $fileIcon = 'fa-file-image';
                                        $iconColor = 'text-blue-600';
                                    } elseif(str_contains($attachment->file_type, 'pdf')) {
                                        $fileIcon = 'fa-file-pdf';
                                        $iconColor = 'text-red-600';
                                    } elseif(str_contains($attachment->file_type, 'word')) {
                                        $fileIcon = 'fa-file-word';
                                        $iconColor = 'text-blue-700';
                                    } elseif(str_contains($attachment->file_type, 'zip')) {
                                        $fileIcon = 'fa-file-archive';
                                        $iconColor = 'text-yellow-600';
                                    }
                                @endphp
                                <i class="fa {{ $fileIcon }} text-2xl {{ $iconColor }}"></i>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900 dark:text-white truncate group-hover:text-blue-600 dark:group-hover:text-blue-400">{{ $attachment->original_name }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ number_format($attachment->file_size / 1024, 2) }} KB</p>
                                </div>
                                <svg class="w-5 h-5 text-gray-400 group-hover:text-blue-600 dark:group-hover:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                </svg>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Reply Form -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20">
            <h3 class="text-lg font-bold text-gray-900 dark:text-white flex items-center">
                <svg class="w-5 h-5 mr-2 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/>
                </svg>
                Send Reply
            </h3>
        </div>
        
        <form method="POST" action="{{ route('admin.submitSolutionTicket') }}" enctype="multipart/form-data" class="p-6">
            @csrf
            <input type="hidden" name="ticket" value="{{ $data['ticket_head']->ticket }}">
            
            <div class="space-y-6">
                <!-- Message Input -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                        Your Reply <span class="text-red-500">*</span>
                    </label>
                    <textarea name="detail" rows="6" required
                        class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none transition-all duration-200 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100"
                        placeholder="Type your reply here..."></textarea>
                </div>

                <!-- File Attachments -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">
                        <i class="fa fa-paperclip text-blue-600 mr-1"></i>
                        Attachments (Optional)
                        <span class="text-xs text-gray-500 font-normal ml-2">Max 10MB per file</span>
                    </label>
                    <div class="border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-xl p-8 text-center hover:border-blue-500 dark:hover:border-blue-400 transition-all duration-200 bg-gray-50 dark:bg-gray-900/50" id="adminDropZone">
                        <input type="file" name="attachments[]" id="adminFileInput" multiple 
                            accept=".jpg,.jpeg,.png,.pdf,.doc,.docx,.txt,.zip" class="hidden">
                        <div id="adminDropZoneContent">
                            <div class="flex justify-center mb-4">
                                <div class="w-16 h-16 bg-blue-100 dark:bg-blue-900/30 rounded-full flex items-center justify-center">
                                    <svg class="w-8 h-8 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                    </svg>
                                </div>
                            </div>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">
                                <button type="button" onclick="document.getElementById('adminFileInput').click()" class="font-semibold text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 transition-colors">
                                    Click to upload
                                </button> 
                                <span class="text-gray-500 dark:text-gray-500">or drag and drop</span>
                            </p>
                            <p class="text-xs text-gray-500 dark:text-gray-500">
                                PNG, JPG, PDF, DOC, DOCX, TXT, ZIP (max 10MB each)
                            </p>
                        </div>
                        <div id="adminFileList" class="mt-6 space-y-2 hidden text-left"></div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex items-center justify-between pt-4 border-t border-gray-200 dark:border-gray-700">
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Reply will be sent immediately
                    </p>
                    <button type="submit" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-200">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                        </svg>
                        Send Reply
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>


@endsection

@push('js')
    <script>
        // File upload functionality for admin
        const adminDropZone = document.getElementById('adminDropZone');
        const adminFileInput = document.getElementById('adminFileInput');
        const adminFileList = document.getElementById('adminFileList');
        const adminDropZoneContent = document.getElementById('adminDropZoneContent');
        let adminSelectedFiles = [];

        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            adminDropZone.addEventListener(eventName, preventDefaults, false);
        });

        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }

        ['dragenter', 'dragover'].forEach(eventName => {
            adminDropZone.addEventListener(eventName, () => {
                adminDropZone.style.borderColor = '#3b82f6';
                adminDropZone.style.backgroundColor = '#eff6ff';
            });
        });

        ['dragleave', 'drop'].forEach(eventName => {
            adminDropZone.addEventListener(eventName, () => {
                adminDropZone.style.borderColor = '';
                adminDropZone.style.backgroundColor = '';
            });
        });

        adminDropZone.addEventListener('drop', function(e) {
            const dt = e.dataTransfer;
            const files = dt.files;
            handleAdminFiles(files);
        });

        adminFileInput.addEventListener('change', function() {
            handleAdminFiles(this.files);
        });

        function handleAdminFiles(files) {
            files = [...files];
            files.forEach(file => {
                if (validateAdminFile(file)) {
                    adminSelectedFiles.push(file);
                }
            });
            updateAdminFileList();
        }

        function validateAdminFile(file) {
            const validTypes = ['image/jpeg', 'image/jpg', 'image/png', 'application/pdf', 
                               'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                               'text/plain', 'application/zip'];
            const maxSize = 10 * 1024 * 1024;

            if (!validTypes.includes(file.type)) {
                alert('Invalid file type: ' + file.name);
                return false;
            }

            if (file.size > maxSize) {
                alert('File too large: ' + file.name + '. Maximum size is 10MB.');
                return false;
            }

            return true;
        }

        function updateAdminFileList() {
            adminFileList.innerHTML = '';
            
            if (adminSelectedFiles.length > 0) {
                adminFileList.classList.remove('hidden');
                adminDropZoneContent.classList.add('hidden');
                
                adminSelectedFiles.forEach((file, index) => {
                    const fileItem = document.createElement('div');
                    fileItem.style.cssText = 'display: flex; align-items: center; justify-content: space-between; padding: 10px; background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 8px; margin-bottom: 8px;';
                    
                    const fileIcon = getAdminFileIcon(file.type);
                    const fileSize = formatAdminFileSize(file.size);
                    
                    fileItem.innerHTML = `
                        <div style="display: flex; align-items: center; flex: 1;">
                            ${fileIcon}
                            <div style="margin-left: 12px;">
                                <p style="margin: 0; font-weight: 600; font-size: 14px;">${file.name}</p>
                                <small style="color: #6b7280;">${fileSize}</small>
                            </div>
                        </div>
                        <button type="button" onclick="removeAdminFile(${index})" style="background: #ef4444; color: white; border: none; padding: 6px 12px; border-radius: 6px; cursor: pointer; font-size: 12px;">
                            Remove
                        </button>
                    `;
                    
                    adminFileList.appendChild(fileItem);
                });

                const addMoreBtn = document.createElement('button');
                addMoreBtn.type = 'button';
                addMoreBtn.style.cssText = 'width: 100%; padding: 10px; border: 2px dashed #d1d5db; background: transparent; color: #3b82f6; cursor: pointer; border-radius: 8px; margin-top: 10px; font-weight: 600;';
                addMoreBtn.innerHTML = '<i class="fa fa-plus"></i> Add more files';
                addMoreBtn.onclick = () => adminFileInput.click();
                adminFileList.appendChild(addMoreBtn);

                updateAdminFileInput();
            } else {
                adminFileList.classList.add('hidden');
                adminDropZoneContent.classList.remove('hidden');
            }
        }

        function removeAdminFile(index) {
            adminSelectedFiles.splice(index, 1);
            updateAdminFileList();
        }

        window.removeAdminFile = removeAdminFile;

        function updateAdminFileInput() {
            const dataTransfer = new DataTransfer();
            adminSelectedFiles.forEach(file => dataTransfer.items.add(file));
            adminFileInput.files = dataTransfer.files;
        }

        function getAdminFileIcon(fileType) {
            if (fileType.startsWith('image/')) {
                return '<i class="fa fa-file-image" style="font-size: 24px; color: #3b82f6;"></i>';
            } else if (fileType === 'application/pdf') {
                return '<i class="fa fa-file-pdf" style="font-size: 24px; color: #ef4444;"></i>';
            } else if (fileType.includes('word')) {
                return '<i class="fa fa-file-word" style="font-size: 24px; color: #2563eb;"></i>';
            } else if (fileType === 'application/zip') {
                return '<i class="fa fa-file-archive" style="font-size: 24px; color: #eab308;"></i>';
            } else {
                return '<i class="fa fa-file" style="font-size: 24px; color: #6b7280;"></i>';
            }
        }

        function formatAdminFileSize(bytes) {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const sizes = ['Bytes', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return Math.round(bytes / Math.pow(k, i) * 100) / 100 + ' ' + sizes[i];
        }

        // Close ticket function
        function closeTicket(ticket) {
            var csrfToken = $('meta[name="csrf-token"]').attr('content');
            const formdata = {
                ticket_name:ticket
            }
            $.ajax({
                url: "{{ route('admin.closeTicket') }}",
                type: "POST",
                data: formdata,
                headers: {
                'X-CSRF-TOKEN': csrfToken
            },
                success: function(response) {
                    if(response.success == true){
                        var confirmation = confirm('This support is closed submitted');
                        window.location.href = '{{ route("admin.support_list") }}';
                     }
                },
                error: function(error) {
                    console.error(error);
                }
            });
        };
    </script>
@endpush
