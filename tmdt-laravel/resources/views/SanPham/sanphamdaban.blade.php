@extends('layouts.app')

@section('title', 'Hóa đơn đã bán')

@section('content')
<style>
    .page-title {
        font-size: 28px;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 12px;
        background: linear-gradient(90deg, #0d6efd, #00b4d8);
        -webkit-background-clip: text;
        color: transparent;
        margin-bottom: 20px;
    }

    .table thead th {
        background: #1e1e1e !important;
        color: #fff;
        font-weight: 600;
    }

    .table-hover tbody tr:hover {
        background-color: #f4f8ff;
    }

    .badge {
        padding: 6px 10px;
        font-size: 13px;
        border-radius: 6px;
    }

    .btn-soft:hover {
        transform: scale(1.05);
        transition: 0.2s;
    }
</style>

<div class="container py-4 mb-5">

    <h2 class="page-title">
        <i class="bi bi-receipt-cutoff text-primary"></i> Hóa đơn bạn đã bán
    </h2>

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

    <div class="row mb-3">
        <div class="col-md-5">
            <input type="text"
                   id="searchInput"
                   class="form-control shadow-sm"
                   placeholder="🔍 Tìm theo người mua, trạng thái, ngày, tổng tiền..." />
        </div>
    </div>

    <div class="card shadow-sm border-0 rounded-4">
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
                                    <span class="badge {{ $badge }}">{{ $item->TrangThai }}</span>
                                </td>

                                <td class="text-center text-nowrap">

                                    <!-- XEM CHI TIẾT -->
                                    <a class="btn btn-primary btn-sm btn-soft"
                                       href="{{ route('sanpham.ctsanphamdaban', ['id' => $item->MaHD]) }}">
                                        <i class="bi bi-eye-fill"></i>
                                    </a>

                                    @if ($item->TrangThai == "Đang chờ xử lý")
                                        <!-- HOÀN THÀNH -->
                                        <form action="{{ route('sanpham.hoanthanhhoadon', ['id' => $item->MaHD]) }}"
                                              method="post"
                                              class="d-inline"
                                              onsubmit="return confirm('Xác nhận hoàn thành toàn bộ sản phẩm trong hóa đơn này?')">
                                            @csrf
                                            <button type="submit"
                                                    class="btn btn-success btn-sm btn-soft">
                                                <i class="bi bi-check-circle-fill"></i>
                                            </button>
                                        </form>

                                        <!-- HUỶ -->
                                        <form action="{{ route('sanpham.huyhoadonban', ['id' => $item->MaHD]) }}"
                                              method="post"
                                              class="d-inline"
                                              onsubmit="return confirm('Bạn có chắc muốn hủy hóa đơn này?')">
                                            @csrf
                                            <button type="submit" class="btn btn-danger btn-sm btn-soft">
                                                <i class="bi bi-x-circle-fill"></i>
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif

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
