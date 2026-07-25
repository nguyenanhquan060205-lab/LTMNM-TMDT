@extends('Shared._Layout')
@section('title', 'Đoạn chat')

@section('content')
<div class="p-3">
    <h4 class="text-primary fw-bold mb-3">💬 Đoạn chat</h4>

    <ul class="list-group">
        @if (isset($model))
            @foreach ($model as $u)
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <span>
                        <i class="fa-solid fa-user text-secondary me-2"></i>
                        <b>{{ $u->HoTen }}</b>
                    </span>
                    <a href="{{ url('/tinnhan/chat?idNguoiDung=' . $u->MaKH) }}"
                       class="btn btn-sm btn-outline-primary">Chat</a>
                </li>
            @endforeach
        @endif
    </ul>
</div>
@endsection
