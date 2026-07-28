@extends('layouts.app')

@section('title', 'Hóa đơn đã bán')

@section('content')
<style>
    .page-title {
        color: #2a2a40;
        margin-top: 20px;
    }
    .table-container {
        background: white;
        border-radius: 16px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.03);
        overflow: hidden;
        border: 1px solid rgba(0,0,0,0.05);
    }
    .table th {
        background: #f8f9fa !important;
        color: #4a5568 !important;
        font-weight: 700;
        text-transform: uppercase;
        font-size: .85rem;
        padding: 16px;
        border-bottom: 2px solid #edf2f7;
    }
    .table td {
        padding: 16px;
        vertical-align: middle;
        border-bottom: 1px solid #edf2f7;
        font-size: 0.95rem;
    }
    .badge-status {
        padding: 6px 12px;
        border-radius: 8px;
        font-weight: 600;
        letter-spacing: 0.3px;
    }
    .btn-action {
        border-radius: 50%;
        width: 36px;
        height: 36px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s;
        border: 1px solid transparent;
    }
    .btn-action:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    }
</style>

<div class="container py-4 mb-5">

    <h3 class="fw-bold mb-4 page-title text-center">
        <i class="fa-solid fa-receipt text-primary me-2"></i> Hóa đơn bạn đã bán
    </h3>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="bi bi-exclamation-triangle-fill"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row mb-4">
        <div class="col-md-6 mx-auto">
            <input type="text"
                   id="searchInput"
                   class="form-control rounded-pill shadow-sm py-2 px-4"
                   placeholder="🔍 Tìm theo người mua, trạng thái, ngày, tổng tiền..." />
        </div>
    </div>

    <div class="table-container">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="hoaDonTable">
                    <thead>
                        <tr>
                            <th>Người mua</th>
                            <th>Ngày đặt</th>
                            <th>Ngày thanh toán</th>
                            <th class="text-center">Tổng tiền</th>
                            <th class="text-center">Trạng thái</th>
                            <th class="text-center">Thao tác</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($dsHoaDonBan as $item)
                            <tr>
                                <td>{{ $item->NguoiMua }}</td>

                                <td>
                                    {{ $item->NgayDat ? \Carbon\Carbon::parse($item->NgayDat)->format('d/m/Y H:i') : '-' }}
                                </td>

                                <td>
                                    {{ $item->NgayTT ? \Carbon\Carbon::parse($item->NgayTT)->format('d/m/Y H:i') : '-' }}
                                </td>

                                <td class="text-end fw-bold text-danger text-center">
                                    {{ number_format($item->TongTien, 0, ',', '.') }} đ
                                </td>

                                <td class="text-center">
                                    @php
                                        $badge = "bg-secondary";
                                        if ($item->TrangThai == "Đang chờ xử lý") {
                                            $badge = "bg-warning text-dark";
                                        } else if ($item->TrangThai == "Đã thanh toán") {
                                            $badge = "bg-success";
                                        } else if ($item->TrangThai == "Đã Huỷ" || $item->TrangThai == "Đã hủy") {
                                            $badge = "bg-danger";
                                        }
                                    @endphp
                                    <span class="badge-status {{ $badge }}">{{ $item->TrangThai }}</span>
                                </td>

                                <td class="text-center text-nowrap">
                                    <div class="d-flex gap-2 justify-content-center">
                                        <!-- XEM CHI TIẾT -->
                                        <a class="btn btn-outline-primary btn-action"
                                           href="{{ route('sanpham.ctsanphamdaban', ['id' => $item->MaHD]) }}" title="Xem chi tiết">
                                            <i class="fa-solid fa-eye"></i>
                                        </a>

                                        @if ($item->TrangThai == "Đang chờ xử lý")
                                            <!-- HOÀN THÀNH -->
                                            <form action="{{ route('sanpham.hoanthanhhoadon', ['id' => $item->MaHD]) }}"
                                                  method="post"
                                                  class="d-inline"
                                                  onsubmit="return confirm('Xác nhận hoàn thành toàn bộ sản phẩm trong hóa đơn này?')">
                                                @csrf
                                                <button type="submit"
                                                        class="btn btn-outline-success btn-action" title="Xác nhận hoàn thành">
                                                    <i class="fa-solid fa-check"></i>
                                                </button>
                                            </form>

                                            <!-- HUỶ -->
                                            <form action="{{ route('sanpham.huyhoadonban', ['id' => $item->MaHD]) }}"
                                                  method="post"
                                                  class="d-inline"
                                                  onsubmit="return confirm('Bạn có chắc muốn hủy hóa đơn này?')">
                                                @csrf
                                                <button type="submit" class="btn btn-outline-danger btn-action" title="Hủy đơn hàng">
                                                    <i class="fa-solid fa-xmark"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">
                                    <i class="bi bi-inbox"></i> Chưa có hóa đơn nào
                                </td>
                            </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.getElementById("searchInput").addEventListener("keyup", function () {
        const keyword = this.value.toLowerCase();
        const rows = document.querySelectorAll("#hoaDonTable tbody tr");

        rows.forEach(row => {
            const text = row.innerText.toLowerCase();
            row.style.display = text.includes(keyword) ? "" : "none";
        });
    });
</script>
@endsection
