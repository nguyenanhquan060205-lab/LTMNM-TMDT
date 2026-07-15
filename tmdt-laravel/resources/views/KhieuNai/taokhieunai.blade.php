@extends('shared._layout')
@section('content')
<div class="container mt-5 mb-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card border-0 shadow-lg rounded-4">
                <div class="card-body p-5">
                    <h3 class="fw-bold mb-4 text-center text-dark"><i class="fa fa-flag text-danger me-2"></i>Gửi Báo Cáo / Khiếu Nại</h3>
                    
                    <div class="alert alert-info border-0 shadow-sm mb-4">
                        <strong>Sản phẩm:</strong> {{ $sanPham->TenSP }}<br>
                        Người bán: {{ $sanPham->nguoiDung->HoTen ?? 'N/A' }}
                    </div>

                    @if(session('error'))
                        <div class="alert alert-danger shadow-sm">{{ session('error') }}</div>
                    @endif

                    <form action="{{ route('khieunai.taokhieunai', ['id' => $sanPham->MaSP]) }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label class="form-label fw-bold">Chi tiết vi phạm</label>
                            <textarea name="MoTa" class="form-control bg-light" rows="5" placeholder="Mô tả lý do bạn báo cáo sản phẩm này (hàng giả, lừa đảo, sai mô tả...)" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-danger btn-lg w-100 fw-bold rounded-pill shadow-sm">Gửi Báo Cáo</button>
                        <a href="{{ route('sanpham.chitiet', ['id' => $sanPham->MaSP]) }}" class="btn btn-outline-secondary w-100 mt-2 rounded-pill">Hủy</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection