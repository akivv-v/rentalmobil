@extends('user.layouts.app')

@section('content')
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-sm border-0" style="border-radius: 15px;">
                    <div class="card-header bg-primary text-white p-3" style="border-radius: 15px 15px 0 0;">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-person-circle fs-3 me-2"></i>
                            <div>
                                <h6 class="mb-0 text-white">Customer Service Rentbill</h6>
                                <small class="opacity-75">Admin Online</small>
                            </div>
                        </div>
                    </div>

                    <div class="card-body" id="chat-box"
                        style="height: 400px; overflow-y: auto; background-color: #f0f2f5;">
                        @forelse($messages as $msg)
                            <div class="d-flex mb-4 {{ $msg->sender == 'user' ? 'justify-content-end' : 'justify-content-start' }}"
                                id="msg-container-{{ $msg->id }}">

                                <div class="d-flex align-items-center {{ $msg->sender == 'user' ? 'flex-row' : 'flex-row-reverse' }}"
                                    style="max-width: 80%;">

                                    @if ($msg->sender == 'user')
                                        <div class="dropdown me-2">
                                            <button class="btn btn-light btn-sm rounded-circle shadow-sm" type="button"
                                                data-bs-toggle="dropdown"
                                                style="width: 35px; height: 35px; display: flex; align-items: center; justify-content: center;">
                                                <i class="bi bi-three-dots-vertical fs-5 text-dark"></i>
                                            </button>
                                            <ul class="dropdown-menu shadow border-0">
                                                <li>
                                                    <a class="dropdown-item py-2" href="javascript:void(0)"
                                                        onclick="editChat({{ $msg->id }}, '{{ $msg->message }}')">
                                                        <i class="bi bi-pencil me-2 text-primary"></i> Edit Pesan
                                                    </a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item py-2 text-danger" href="javascript:void(0)"
                                                        onclick="deleteChat({{ $msg->id }})">
                                                        <i class="bi bi-trash me-2"></i> Hapus Pesan
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    @endif

                                    <div class="p-3 shadow-sm {{ $msg->sender == 'user' ? 'bg-primary text-white' : 'bg-white text-dark' }}"
                                        style="border-radius: {{ $msg->sender == 'user' ? '20px 20px 0 20px' : '20px 20px 20px 0' }}; min-width: 100px;">

                                        <p class="mb-1 message-text" style="word-break: break-word;">{{ $msg->message }}</p>

                                        <small
                                            class="d-block text-end {{ $msg->sender == 'user' ? 'text-white-50' : 'text-muted' }}"
                                            style="font-size: 0.7rem;">
                                            {{ $msg->created_at->timezone('Asia/Jakarta')->format('H:i') }}
                                        </small>
                                    </div>
                                </div>
                            </div>
                        @empty
                        <div class="text-center mt-5 text-muted empty-state">
                            <i class="bi bi-chat-dots fs-1 d-block mb-2"></i>
                            <p>Halo! Ada yang bisa kami bantu?</p>
                        </div>
                        @endforelse
                    </div>

                    <div class="card-footer bg-white border-0 p-3">
                        <form id="chat-form">
                            @csrf
                            <div class="input-group">
                                <input type="text" id="message-input" name="message"
                                    class="form-control border-0 bg-light" placeholder="Tulis pesan..." required
                                    autocomplete="off">
                                <button class="btn btn-primary px-4" type="submit" id="btn-kirim">
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

        $('#chat-form').on('submit', function(e) {
            e.preventDefault();
            let message = $('#message-input').val();
            let _token = $('input[name="_token"]').val();

            if (message.trim() == '') return;
            $('#btn-kirim').prop('disabled', true);

            $.ajax({
                url: "{{ route('user.chat.send') }}",
                type: "POST",
                data: {
                    message: message,
                    _token: _token
                },
                success: function() {
                    // Ambil waktu Jakarta via JS
                    let now = new Date();
                    let time = now.toLocaleTimeString('id-ID', {
                        hour: '2-digit',
                        minute: '2-digit',
                        hour12: false
                    });

                    let newChat = `
                    <div class="d-flex mb-3 justify-content-end">
                        <div class="p-3 shadow-sm bg-primary text-white" style="max-width: 70%; border-radius: 15px 15px 0 15px;">
                            <p class="mb-1 small">${message}</p>
                            <small class="d-block text-end text-white-50" style="font-size: 0.7rem;">${time}</small>
                        </div>
                    </div>`;

                    $('.empty-state').remove();
                    $('#chat-box').append(newChat);
                    $('#message-input').val('');
                    $('#btn-kirim').prop('disabled', false);
                    scrollToBottom();
                },
                error: function() {
                    alert('Gagal mengirim!');
                    $('#btn-kirim').prop('disabled', false);
                }
            });
        });

        // Cek pesan baru setiap 3 detik agar terasa real-time
        setInterval(function() {
            $.ajax({
                url: window.location.href,
                type: 'GET',
                success: function(data) {
                    let newContent = $(data).find('#chat-box').html();
                    // Hanya update jika ada perbedaan jumlah bubble chat
                    if ($('#chat-box').html().trim() !== newContent.trim()) {
                        $('#chat-box').html(newContent);
                        scrollToBottom();
                    }
                }
            });
        }, 3000);

        // Fungsi Hapus Chat
        function deleteChat(id) {
            if (confirm('Hapus pesan ini?')) {
                $.ajax({
                    url: "/user/chat/delete/" + id,
                    type: "DELETE",
                    data: {
                        _token: "{{ csrf_token() }}"
                    },
                    success: function() {
                        $(`#msg-container-${id}`).fadeOut(300, function() {
                            $(this).remove();
                        });
                    }
                });
            }
        }

        // Fungsi Edit Chat
        function editChat(id, oldMessage) {
            let newMessage = prompt("Edit pesan kamu:", oldMessage);
            if (newMessage && newMessage !== oldMessage) {
                $.ajax({
                    url: "/user/chat/update/" + id,
                    type: "PUT",
                    data: {
                        message: newMessage,
                        _token: "{{ csrf_token() }}"
                    },
                    success: function() {
                        $(`#msg-container-${id} .message-text`).text(newMessage);
                    }
                });
            }
        }
    </script>
@endsection
