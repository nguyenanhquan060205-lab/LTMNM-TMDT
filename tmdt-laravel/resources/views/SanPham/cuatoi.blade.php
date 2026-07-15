@extends('shared._layout')
@section('content')
<div class="container mt-4 mb-5">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <h2 class="fw-bold text-dark mb-0"><i class="fa-solid fa-box-open text-warning me-2"></i>Quản Lý Sản Phẩm Của Bạn</h2>
        <a href="{{ route('sanpham.taomoi') }}" class="btn btn-warning rounded-pill fw-bold shadow-sm px-4">
            <i class="fa fa-plus-circle me-2"></i>Đăng bán mới
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success shadow-sm"><i class="fa-solid fa-circle-check me-2"></i>{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger shadow-sm"><i class="fa-solid fa-triangle-exclamation me-2"></i>{{ session('error') }}</div>
    @endif

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-4">
            @if ($dsSanPham->isEmpty())
                <div class="text-center p-5">
                    <i class="fa-solid fa-box fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">Bạn chưa đăng bán sản phẩm nào.</h5>
                    <a href="{{ route('sanpham.taomoi') }}" class="btn btn-outline-warning rounded-pill mt-3 px-4">Đăng bán ngay</a>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle border">
                        <thead class="table-light">
                            <tr>
                                <th class="text-center">Hình ảnh</th>
                                <th>Tên sản phẩm</th>
                                <th>Giá bán</th>
                                <th class="text-center">Số lượng</th>
                                <th>Tình trạng duyệt</th>
                                <th class="text-center">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($dsSanPham as $sp)
                                @php
                                    $anh = $sp->hinhAnhs->where('AnhBia', true)->first();
                                    $anhUrl = $anh ? $anh->URLAnh : 'noimage.jpg';
                                @endphp
                                <tr>
                                    <td class="text-center">
                                        <img src="{{ asset('Content/Images/' . $anhUrl) }}" class="rounded shadow-sm" style="width: 60px; height: 60px; object-fit: cover;" onerror="this.src='{{ asset('Content/Images/noimage.jpg') }}';" />
                                    </td>
                                    <td>
                                        <a href="{{ route('sanpham.chitiet', ['id' => $sp->MaSP]) }}" class="text-dark fw-bold text-decoration-none text-truncate d-block" style="max-width: 200px;">
                                            {{ $sp->TenSP }}
                                        </a>
                                        <small class="text-muted">{{ $sp->loaiSanPham->TenLoai ?? '' }}</small>
                                    </td>
                                    <td class="text-danger fw-bold">{{ number_format($sp->Gia, 0, ',', '.') }}₫</td>
                                    <td class="text-center">
                                        @if ($sp->SoLuong > 0)
                                            <span class="badge bg-success rounded-pill px-3">{{ $sp->SoLuong }}</span>
                                        @else
                                            <span class="badge bg-secondary rounded-pill px-3">Hết hàng</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($sp->TrangThai == 'Đã duyệt')
                                            <span class="badge bg-success"><i class="fa fa-check me-1"></i>Đã duyệt</span>
                                        @elseif ($sp->TrangThai == 'Từ chối')
                                            <span class="badge bg-danger"><i class="fa fa-times me-1"></i>Từ chối</span>
                                        @else
                                            <span class="badge bg-warning text-dark"><i class="fa fa-clock me-1"></i>Chờ duyệt</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group shadow-sm rounded-pill" role="group">
                                            <a href="{{ route('sanpham.sua', ['id' => $sp->MaSP]) }}" class="btn btn-sm btn-outline-primary" title="Sửa">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                            <a href="{{ route('sanpham.xoa', ['id' => $sp->MaSP]) }}" class="btn btn-sm btn-outline-danger" title="Xóa" onclick="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này?');">
                                                <i class="fa fa-trash"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">
                    {{ $dsSanPham->links('pagination::bootstrap-4') }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection