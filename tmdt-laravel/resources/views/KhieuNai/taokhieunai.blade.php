@extends('layouts.app')

@section('title', 'Gửi khiếu nại')

@section('content')
<div class="container my-5">
    <h3 class="fw-bold mb-4 text-center">📝 Gửi khiếu nại sản phẩm</h3>

    <div class="card shadow-sm p-4">
        <div class="mb-3">
            <label class="form-label fw-bold">Tên sản phẩm:</label>
            <div class="border p-2 bg-light">{{ $sp->TenSP ?? '' }}</div>
        </div>

        <div class="mb-3">
            <label class="form-label fw-bold">Nội dung khiếu nại:</label>
            <textarea name="MoTa" id="MoTa" class="form-control" rows="5" placeholder="Nhập chi tiết vấn đề bạn gặp phải..."></textarea>
        </div>

        <div class="text-end">
            <a href="{{ route('sanpham.chitiet', ['id' => $sp->MaSP]) }}" class="btn btn-secondary me-2">
                Quay lại
            </a>
            <button id="btnGui" class="btn btn-danger">
                <i class="bi bi-send"></i> Gửi khiếu nại
            </button>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $("#btnGui").click(function () {
        let moTa = $("#MoTa").val().trim();
        if (!moTa) {
            alert("Vui lòng nhập nội dung khiếu nại!");
            return;
        }

        $.post("{{ route('khieunai.taokhieunai', ['idsanpham' => $sp->MaSP]) }}", {
            _token: "{{ csrf_token() }}",
            MoTa: moTa
        }, function () {
            alert("Gửi khiếu nại thành công! Vui lòng chờ Admin xử lý.");
            window.location.href = "{{ route('sanpham.chitiet', ['id' => $sp->MaSP]) }}";
        }).fail(function() {
            alert("Có lỗi xảy ra, vui lòng thử lại.");
        });
    });
</script>
@endsection
