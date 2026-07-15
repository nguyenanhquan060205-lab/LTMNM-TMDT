@extends('shared._layout')
@section('content')
<div class="container mt-4 mb-5">
    <div class="row">
        <!-- Danh sách user -->
        <div class="col-md-4 mb-4">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-header bg-white border-bottom p-3">
                    <h5 class="fw-bold mb-0"><i class="fa-regular fa-comments text-primary me-2"></i>Tin nhắn</h5>
                </div>
                <div class="card-body p-0" style="height: 500px; overflow-y: auto;">
                    <div class="list-group list-group-flush">
                        @foreach ($users as $u)
                            @php
                                $uAnh = $u->AnhDaiDien ? asset('Content/Avatars/' . $u->AnhDaiDien) : asset('Content/Avatars/default.jpg');
                                $isActive = $activeUser && $activeUser->MaKH == $u->MaKH;
                            @endphp
                            <a href="{{ route('tinnhan.index', ['userId' => $u->MaKH]) }}" class="list-group-item list-group-item-action p-3 {{ $isActive ? 'bg-light border-start border-4 border-warning' : '' }}">
                                <div class="d-flex align-items-center">
                                    <img src="{{ $uAnh }}" class="rounded-circle me-3 border" width="45" height="45" style="object-fit: cover;">
                                    <div>
                                        <h6 class="mb-0 fw-bold">{{ $u->TaiKhoan }}</h6>
                                        <small class="text-muted">{{ $u->HoTen }}</small>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Khung chat -->
        <div class="col-md-8">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                @if ($activeUser)
                    @php
                        $activeAnh = $activeUser->AnhDaiDien ? asset('Content/Avatars/' . $activeUser->AnhDaiDien) : asset('Content/Avatars/default.jpg');
                    @endphp
                    <div class="card-header bg-white border-bottom p-3 d-flex align-items-center">
                        <img src="{{ $activeAnh }}" class="rounded-circle me-3 border" width="40" height="40" style="object-fit: cover;">
                        <h5 class="fw-bold mb-0">{{ $activeUser->TaiKhoan }}</h5>
                    </div>
                    <div class="card-body" id="chat-box" style="height: 400px; overflow-y: auto; background-color: #f8f9fa;">
                        @foreach ($messages as $msg)
                            @if ($msg->NguoiGui == session('user')->MaKH)
                                <div class="d-flex justify-content-end mb-3">
                                    <div class="bg-warning text-dark p-3 rounded-4 shadow-sm" style="max-width: 75%; border-bottom-right-radius: 4px !important;">
                                        {{ $msg->NoiDung }}
                                        <div class="text-end mt-1"><small class="text-muted" style="font-size: 0.7rem;">{{ \Carbon\Carbon::parse($msg->NgayGui)->format('H:i d/m/Y') }}</small></div>
                                    </div>
                                </div>
                            @else
                                <div class="d-flex justify-content-start mb-3">
                                    <div class="bg-white text-dark p-3 rounded-4 shadow-sm border" style="max-width: 75%; border-bottom-left-radius: 4px !important;">
                                        {{ $msg->NoiDung }}
                                        <div class="text-end mt-1"><small class="text-muted" style="font-size: 0.7rem;">{{ \Carbon\Carbon::parse($msg->NgayGui)->format('H:i d/m/Y') }}</small></div>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                    <div class="card-footer bg-white p-3 border-top">
                        <form id="chat-form" action="{{ route('tinnhan.send') }}" method="POST">
                            @csrf
                            <input type="hidden" name="nguoiNhan" value="{{ $activeUser->MaKH }}">
                            <div class="input-group">
                                <input type="text" name="noiDung" class="form-control rounded-pill bg-light ps-4" placeholder="Nhập tin nhắn..." required id="msg-input">
                                <button type="submit" class="btn btn-warning rounded-pill ms-2 px-4 shadow-sm"><i class="fa fa-paper-plane"></i></button>
                            </div>
                        </form>
                    </div>
                    <script>
                        // Scroll to bottom
                        var chatBox = document.getElementById("chat-box");
                        chatBox.scrollTop = chatBox.scrollHeight;

                        // AJAX form submit
                        $('#chat-form').on('submit', function(e) {
                            e.preventDefault();
                            var form = $(this);
                            var input = $('#msg-input');
                            if(input.val().trim() === '') return;
                            
                            $.ajax({
                                type: "POST",
                                url: form.attr('action'),
                                data: form.serialize(),
                                success: function(response) {
                                    if(response.success) {
                                        var newMsg = '<div class="d-flex justify-content-end mb-3"><div class="bg-warning text-dark p-3 rounded-4 shadow-sm" style="max-width: 75%; border-bottom-right-radius: 4px !important;">' + input.val() + '</div></div>';
                                        $('#chat-box').append(newMsg);
                                        input.val('');
                                        chatBox.scrollTop = chatBox.scrollHeight;
                                    }
                                }
                            });
                        });
                    </script>
                @else
                    <div class="card-body d-flex align-items-center justify-content-center text-muted" style="height: 500px;">
                        <div class="text-center">
                            <i class="fa-regular fa-comments fa-4x mb-3 opacity-50"></i>
                            <h5>Chọn một người để bắt đầu trò chuyện</h5>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection