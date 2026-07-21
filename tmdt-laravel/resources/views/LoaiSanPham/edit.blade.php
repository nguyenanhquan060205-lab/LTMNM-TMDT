@extends('shared._layoutadmin')
@section('content')
<div class="card border-0 shadow-sm rounded-4" style="max-width: 600px; margin: 0 auto;">
    <div class="card-body p-5">
        <h5 class="fw-bold mb-4 border-bottom pb-3">Sửa Danh Mục #{{ $loai->MaLoai }}</h5>
        <form action="{{ route('loaisanpham.update', ['id' => $loai->MaLoai]) }}" method="POST">
            @csrf
            <div class="mb-4">
                <label class="form-label fw-bold">Tên danh mục <span class="text-danger">*</span></label>
                <input type="text" name="TenLoai" class="form-control form-control-lg bg-light" value="{{ $loai->TenLoai }}" required>
            </div>
            <button class="btn btn-warning btn-lg w-100 rounded-pill fw-bold shadow-sm mb-2">Lưu</button>
            <a href="{{ route('loaisanpham.index') }}" class="btn btn-outline-secondary w-100 rounded-pill">Quay lại</a>
        </form>
    </div>
</div>
@endsection
