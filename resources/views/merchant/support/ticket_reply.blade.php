@extends('merchant.mrc_app')
@section('title', 'Dashboard')
@section('mrc_content')
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (session()->has('message'))
        <div class="alert alert-success" id="alert_success">
            {{ session('message') }}
        </div>
        @elseif (session()->has('alert'))
            <div class="alert alert-warning" id="alert_warning">
                {{ session('alert') }}
            </div>
        @endif
        <div class="row">
            <div class="col-xl-6">
                <div class="card">
                    <div class="card-body p-4">
                        <h5 class="mb-4">{{ translate('reply_ticket') }}</h5>
                        @if ($ticket_object->status == 1)
                            <button class="btn btn-warning pull-right"> Opened</button>
                        @elseif($ticket_object->status == 2)
                            <button type="button" class="btn btn-success pull-right"> Answered </button>
                        @elseif($ticket_object->status == 3)
                            <button type="button" class="btn btn-info pull-right"> Customer Reply </button>
                        @elseif($ticket_object->status == 9)
                            <button type="button" class="btn btn-danger pull-right"> Closed </button>
                        @endif
                        <a href="{{ route('merchant.ticketClose', $ticket_object->ticket) }}"
                            class="btn btn-danger pull-right make-close-support" style="height: 35px;">Click To Make
                            Close</a>
                        <div class="panel-heading"> #{{ $ticket_object->ticket }} - {{ $ticket_object->subject }}
                        </div>
                        <form class="row g-3" method="POST"
                            action="{{ route('merchant.ticketReplyStore', $ticket_object->ticket) }}" accept-charset="UTF-8" enctype="multipart/form-data">
                            {{ csrf_field() }}
                            <div class="form-group">
                                <div class="col-md-12">

                                    {{-- @dd($ticket_data) --}}

                                    @foreach ($ticket_data as $data)
                                        <div class="panel-body">
                                            <fieldset class="col-md-12">
                                                @if ($data->type == 1)
                                                    <legend><span style="color: #0e76a8">{{ auth('merchant')->user()->fullname }}</span>
                                                        ,
                                                        <small>{{ \Carbon\Carbon::parse($data->updated_at)->format('F dS, Y - h:i A') }}</small>
                                                    </legend>
                                                @else
                                                    <legend><span
                                                            style="color: #0e76a8">{{ app_config('AppTitle') }}</span> ,
                                                        <small>{{ \Carbon\Carbon::parse($data->updated_at)->format('F dS, Y - h:i A') }}</small>
                                                    </legend>
                                                @endif
                                                <div class="panel panel-danger">
                                                    <div class="panel-body">
                                                        <p>{!! $data->comment !!}</p>
                                                    </div>
                                                    <div class="panel-body">
                                                        <h5>Admin</h5>
                                                        <p>{!! $data->comment_reply !!}</p>
                                                    </div>
                                                </div>
                                            </fieldset>
                                            <div class="clearfix"></div>
                                        </div>
                                    @endforeach

                                    <!-- Attachments Section -->
                                    @if(isset($attachments) && $attachments->count() > 0)
                                        <div class="panel-body" style="margin-top: 20px; border-top: 2px solid #f0f0f0;">
                                            <h5 style="color: #0e76a8; font-weight: bold;">
                                                <i class="fa fa-paperclip"></i> Attachments ({{ $attachments->count() }})
                                            </h5>
                                            <div class="row" style="margin-top: 15px;">
                                                @foreach($attachments as $attachment)
                                                    <div class="col-md-6" style="margin-bottom: 10px;">
                                                        <div class="panel panel-default" style="border: 1px solid #ddd; border-radius: 5px;">
                                                            <div class="panel-body" style="padding: 10px; display: flex; align-items: center; justify-content: space-between;">
                                                                <div style="display: flex; align-items: center; flex: 1; min-width: 0;">
                                                                    @php
                                                                        $fileIcon = 'fa-file';
                                                                        $iconColor = '#6c757d';
                                                                        if(str_contains($attachment->file_type, 'image')) {
                                                                            $fileIcon = 'fa-file-image';
                                                                            $iconColor = '#007bff';
                                                                        } elseif(str_contains($attachment->file_type, 'pdf')) {
                                                                            $fileIcon = 'fa-file-pdf';
                                                                            $iconColor = '#dc3545';
                                                                        } elseif(str_contains($attachment->file_type, 'word')) {
                                                                            $fileIcon = 'fa-file-word';
                                                                            $iconColor = '#0d6efd';
                                                                        } elseif(str_contains($attachment->file_type, 'zip')) {
                                                                            $fileIcon = 'fa-file-archive';
                                                                            $iconColor = '#ffc107';
                                                                        }
                                                                    @endphp
                                                                    <i class="fa {{ $fileIcon }}" style="font-size: 24px; color: {{ $iconColor }}; margin-right: 10px;"></i>
                                                                    <div style="flex: 1; min-width: 0;">
                                                                        <p style="margin: 0; font-weight: 600; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="{{ $attachment->original_name }}">
                                                                            {{ $attachment->original_name }}
                                                                        </p>
                                                                        <small style="color: #6c757d;">
                                                                            {{ number_format($attachment->file_size / 1024, 2) }} KB
                                                                        </small>
                                                                    </div>
                                                                </div>
                                                                <a href="{{ route('merchant.support.download', $attachment->id) }}" 
                                                                   class="btn btn-sm btn-primary" 
                                                                   style="margin-left: 10px; white-space: nowrap;">
                                                                    <i class="fa fa-download"></i> Download
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif

                                </div>

                            </div>

                            <div class="form-group {{ $errors->has('comment') ? ' has-error' : '' }}">

                                <label class="col-md-12 bold">Reply: </label>

                                <div class="col-md-12">

                                    <textarea class="form-control" name="comment" rows="10"></textarea>

                                    @if ($errors->has('comment'))
                                        <span class="help-block">

                                            <strong>{{ $errors->first('comment') }}</strong>

                                        </span>
                                    @endif

                                </div>

                            </div>

                            <!-- File Attachments for Reply -->
                            <div class="form-group">
                                <label class="col-md-12 bold">Attachments (Optional):</label>
                                <div class="col-md-12">
                                    <div class="panel panel-default" id="dropZoneReply" style="border: 2px dashed #ddd; padding: 30px; text-align: center; cursor: pointer; transition: all 0.3s;">
                                        <input type="file" name="attachments[]" id="fileInputReply" multiple accept=".jpg,.jpeg,.png,.pdf,.doc,.docx,.txt,.zip" style="display: none;">
                                        <div id="dropZoneContentReply">
                                            <i class="fa fa-cloud-upload" style="font-size: 48px; color: #0e76a8; margin-bottom: 10px;"></i>
                                            <p style="margin: 10px 0; color: #666;">
                                                <button type="button" onclick="document.getElementById('fileInputReply').click()" style="color: #0e76a8; font-weight: bold; background: none; border: none; cursor: pointer; text-decoration: underline;">
                                                    Click to upload
                                                </button> or drag and drop
                                            </p>
                                            <p style="font-size: 12px; color: #999;">PNG, JPG, PDF, DOC, DOCX, TXT, ZIP (max 10MB each)</p>
                                        </div>
                                        <div id="fileListReply" style="margin-top: 20px; display: none; text-align: left;"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="box-footer">

                                <div class="row">



                                    <div class="col-md-12">

                                        <button type="submit"
                                            class="btn btn-info btn-block pull-right">{{ translate('submit_now') }}</button>

                                    </div>

                                </div>

                            </div>

                        </form>


                    </div>

                </div>

            </div>

        </div>



    @push('js')
    <script>
        // File upload functionality for reply
        const dropZoneReply = document.getElementById('dropZoneReply');
        const fileInputReply = document.getElementById('fileInputReply');
        const fileListReply = document.getElementById('fileListReply');
        const dropZoneContentReply = document.getElementById('dropZoneContentReply');
        let selectedFilesReply = [];

        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            dropZoneReply.addEventListener(eventName, preventDefaults, false);
        });

        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }

        ['dragenter', 'dragover'].forEach(eventName => {
            dropZoneReply.addEventListener(eventName, () => {
                dropZoneReply.style.borderColor = '#0e76a8';
                dropZoneReply.style.backgroundColor = '#f0f8ff';
            });
        });

        ['dragleave', 'drop'].forEach(eventName => {
            dropZoneReply.addEventListener(eventName, () => {
                dropZoneReply.style.borderColor = '#ddd';
                dropZoneReply.style.backgroundColor = 'transparent';
            });
        });

        dropZoneReply.addEventListener('drop', function(e) {
            const dt = e.dataTransfer;
            const files = dt.files;
            handleFilesReply(files);
        });

        fileInputReply.addEventListener('change', function() {
            handleFilesReply(this.files);
        });

        function handleFilesReply(files) {
            files = [...files];
            files.forEach(file => {
                if (validateFileReply(file)) {
                    selectedFilesReply.push(file);
                }
            });
            updateFileListReply();
        }

        function validateFileReply(file) {
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

        function updateFileListReply() {
            fileListReply.innerHTML = '';
            
            if (selectedFilesReply.length > 0) {
                fileListReply.style.display = 'block';
                dropZoneContentReply.style.display = 'none';
                
                selectedFilesReply.forEach((file, index) => {
                    const fileItem = document.createElement('div');
                    fileItem.style.cssText = 'display: flex; align-items: center; justify-content: space-between; padding: 10px; background: #f9f9f9; border: 1px solid #ddd; border-radius: 5px; margin-bottom: 8px;';
                    
                    const fileIcon = getFileIconReply(file.type);
                    const fileSize = formatFileSizeReply(file.size);
                    
                    fileItem.innerHTML = `
                        <div style="display: flex; align-items: center; flex: 1;">
                            ${fileIcon}
                            <div style="margin-left: 10px;">
                                <p style="margin: 0; font-weight: 600;">${file.name}</p>
                                <small style="color: #666;">${fileSize}</small>
                            </div>
                        </div>
                        <button type="button" onclick="removeFileReply(${index})" style="background: #dc3545; color: white; border: none; padding: 5px 10px; border-radius: 3px; cursor: pointer;">
                            <i class="fa fa-times"></i>
                        </button>
                    `;
                    
                    fileListReply.appendChild(fileItem);
                });

                const addMoreBtn = document.createElement('button');
                addMoreBtn.type = 'button';
                addMoreBtn.style.cssText = 'width: 100%; padding: 10px; border: 2px dashed #ddd; background: transparent; color: #0e76a8; cursor: pointer; border-radius: 5px; margin-top: 10px;';
                addMoreBtn.innerHTML = '<i class="fa fa-plus"></i> Add more files';
                addMoreBtn.onclick = () => fileInputReply.click();
                fileListReply.appendChild(addMoreBtn);

                updateFileInputReply();
            } else {
                fileListReply.style.display = 'none';
                dropZoneContentReply.style.display = 'block';
            }
        }

        function removeFileReply(index) {
            selectedFilesReply.splice(index, 1);
            updateFileListReply();
        }

        window.removeFileReply = removeFileReply;

        function updateFileInputReply() {
            const dataTransfer = new DataTransfer();
            selectedFilesReply.forEach(file => dataTransfer.items.add(file));
            fileInputReply.files = dataTransfer.files;
        }

        function getFileIconReply(fileType) {
            if (fileType.startsWith('image/')) {
                return '<i class="fa fa-file-image" style="font-size: 24px; color: #007bff;"></i>';
            } else if (fileType === 'application/pdf') {
                return '<i class="fa fa-file-pdf" style="font-size: 24px; color: #dc3545;"></i>';
            } else if (fileType.includes('word')) {
                return '<i class="fa fa-file-word" style="font-size: 24px; color: #0d6efd;"></i>';
            } else if (fileType === 'application/zip') {
                return '<i class="fa fa-file-archive" style="font-size: 24px; color: #ffc107;"></i>';
            } else {
                return '<i class="fa fa-file" style="font-size: 24px; color: #6c757d;"></i>';
            }
        }

        function formatFileSizeReply(bytes) {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const sizes = ['Bytes', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return Math.round(bytes / Math.pow(k, i) * 100) / 100 + ' ' + sizes[i];
        }
    </script>
    @endpush

    @endsection
