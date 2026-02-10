@extends('admin.layouts.app')

@section('content')
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-md-3">
                <a href="{{ route('admin.chat') }}" class="btn btn-light mb-3 w-100 shadow-sm border text-start px-3">
                    <i class="bi bi-arrow-left me-1"></i> Kembali
                </a>
                <div class="card border-0 shadow-sm text-center p-3" style="border-radius: 15px;">
                    <i class="bi bi-person-circle text-primary" style="font-size: 4rem;"></i>
                    <h6 class="mt-2 fw-bold mb-0">{{ $user->name }}</h6>
                    <small class="text-muted d-block mb-3">{{ $user->email }}</small>
                    <hr>
                    <div class="text-start small p-2 bg-light rounded-3">
                        <div class="d-flex justify-content-between mb-2 pb-2 border-bottom">
                            <span class="text-muted"><i class="bi bi-telephone me-2"></i>No. Telp</span>
                            <strong class="text-dark">{{ $user->penyewa->no_telp ?? '-' }}</strong>
                        </div>

                        <div class="d-flex justify-content-between mb-2 pb-2 border-bottom">
                            <span class="text-muted"><i class="bi bi-briefcase me-2"></i>Pekerjaan</span>
                            <strong class="text-dark">{{ $user->penyewa->pekerjaan ?? '-' }}</strong>
                        </div>

                        <div class="d-flex justify-content-between mb-2 pb-2 border-bottom">
                            <span class="text-muted d-block mb-1"><i class="bi bi-geo-alt me-2"></i>Alamat</span>
                            <strong class="text-dark d-block">{{ $user->penyewa->alamat ?? '-' }}</strong>
                        </div>

                        <div class="d-flex justify-content-between mb-2 pb-2 border-bottom align-items-center">
                            <span class="text-muted"><i class="bi bi-shield-check me-2"></i>Status</span>
                            <span class="badge bg-success-soft text-success border border-success px-2 py-1"
                                style="font-size: 0.65rem;">
                                PELANGGAN AKTIF
                            </span>
                        </div>

                        <div class="d-flex justify-content-between mt-2">
                            <span class="text-muted"><i class="bi bi-calendar-event me-2"></i>Bergabung</span>
                            <span class="text-dark fw-bold">{{ $user->created_at->format('d M Y') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-7">
                <div class="card border-0 shadow-sm"
                    style="border-radius: 15px; height: 600px; display: flex; flex-direction: column;">
                    <div class="card-header bg-primary text-white py-3" style="border-radius: 15px 15px 0 0;">
                        <h6 class="mb-0">Percakapan dengan {{ $user->name }}</h6>
                    </div>

                    <div class="card-body p-4" id="chat-box" style="flex: 1; overflow-y: auto; background-color: #f0f2f5;">
                        @foreach ($messages as $msg)
                            <div class="d-flex mb-4 {{ $msg->sender == 'admin' ? 'justify-content-end' : 'justify-content-start' }}"
                                id="msg-container-{{ $msg->id }}">
                                <div class="d-flex align-items-center {{ $msg->sender == 'admin' ? 'flex-row' : 'flex-row-reverse' }}"
                                    style="max-width: 85%;">

                                    @if ($msg->sender == 'admin')
                                        <div class="dropdown me-2">
                                            <button class="btn btn-link text-secondary p-0" type="button"
                                                data-bs-toggle="dropdown">
                                                <i class="bi bi-three-dots-vertical fs-5"></i>
                                            </button>
                                            <ul class="dropdown-menu shadow border-0">
                                                <li><a class="dropdown-item py-2" href="javascript:void(0)"
                                                        onclick="editChat({{ $msg->id }}, '{{ $msg->message }}')"><i
                                                            class="bi bi-pencil me-2 text-primary"></i> Edit</a></li>
                                                <li><a class="dropdown-item py-2 text-danger" href="javascript:void(0)"
                                                        onclick="deleteChat({{ $msg->id }})"><i
                                                            class="bi bi-trash me-2"></i> Hapus</a></li>
                                            </ul>
                                        </div>
                                    @endif

                                    <div class="p-3 shadow-sm {{ $msg->sender == 'admin' ? 'bg-dark text-white' : 'bg-white text-dark' }}"
                                        style="border-radius: {{ $msg->sender == 'admin' ? '20px 20px 0 20px' : '20px 20px 20px 0' }};">
                                        <p class="mb-1 small message-text" style="word-break: break-word;">
                                            {{ $msg->message }}</p>
                                        <small
                                            class="d-block text-end {{ $msg->sender == 'admin' ? 'text-white-50' : 'text-muted' }}"
                                            style="font-size: 0.65rem;">
                                            {{ $msg->created_at->timezone('Asia/Jakarta')->format('H:i') }}
                                        </small>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="card-footer bg-white p-3" style="border-radius: 0 0 15px 15px;">
                        <form id="chat-form-admin">
                            @csrf
                            <input type="hidden" name="user_id" value="{{ $user->id }}">
                            <div class="input-group shadow-sm border rounded-pill overflow-hidden">
                                <input type="text" id="message-input" name="message" class="form-control border-0 px-3"
                                    placeholder="Balas pesan..." required autocomplete="off">
                                <button class="btn btn-primary px-4" type="submit">
                                    <i class="bi bi-send-fill"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script>
        const chatBox = document.getElementById("chat-box");

        function scrollToBottom() {
            chatBox.scrollTop = chatBox.scrollHeight;
        }
        scrollToBottom();

        $('#chat-form-admin').on('submit', function(e) {
            e.preventDefault();
            $.ajax({
                url: "{{ route('user.chat.send') }}",
                type: "POST",
                data: $(this).serialize(),
                success: function() {
                    location.reload();
                }
            });
        });

        function deleteChat(id) {
            if (confirm('Hapus pesan?')) {
                $.ajax({
                    url: "/user/chat/delete/" + id,
                    type: "DELETE",
                    data: {
                        _token: "{{ csrf_token() }}"
                    },
                    success: function() {
                        $(`#msg-container-${id}`).fadeOut();
                    }
                });
            }
        }

        function editChat(id, oldMsg) {
            let newMsg = prompt("Edit pesan:", oldMsg);
            if (newMsg && newMsg !== oldMsg) {
                $.ajax({
                    url: "/user/chat/update/" + id,
                    type: "PUT",
                    data: {
                        message: newMsg,
                        _token: "{{ csrf_token() }}"
                    },
                    success: function() {
                        $(`#msg-container-${id} .message-text`).text(newMsg);
                    }
                });
            }
        }
    </script>
@endsection
