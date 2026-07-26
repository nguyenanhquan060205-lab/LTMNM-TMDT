@extends('Shared._Layout')
@section('title', 'Giỏ hàng của bạn')

@section('content')
<div class="cart-page container my-5">

    <!-- 🛒 TIÊU ĐỀ -->
    <h2 class="cart-title mb-4">
        <i class="bi bi-cart4"></i> Giỏ hàng của bạn
    </h2>

    <!-- 🔔 THÔNG BÁO -->
    @foreach (['CartOK', 'CartError', 'CartWarning'] as $key)
        @if (session()->has($key))
            @php
                $alertClass = $key == 'CartOK' ? 'alert-success' :
                             ($key == 'CartError' ? 'alert-danger' : 'alert-warning');
            @endphp
            <div id="alertBox" class="alert {{ $alertClass }} text-center fw-bold shadow-sm">
                {{ session($key) }}
            </div>
            {{ session()->forget($key) }}
        @endif
    @endforeach

    <!-- 🛒 GIỎ HÀNG RỖNG -->
    @if (!$model || $model->isEmpty())
        <div class="empty-cart-wrapper">
            <div class="empty-cart-icon">🛒</div>
            <h4 class="empty-cart-title">Giỏ hàng của bạn đang trống</h4>
            <p class="empty-cart-subtitle">Hãy thêm sản phẩm yêu thích để bắt đầu mua sắm</p>
            <a href="{{ url('/sanpham') }}" class="btn btn-primary btn-lg rounded-pill px-5">
                <i class="bi bi-shop me-2"></i> Khám phá sản phẩm
            </a>
        </div>
    @else
        @php
            $tongTien = $model->sum('ThanhTien');
        @endphp

        <!-- 📦 BẢNG GIỎ HÀNG -->
        <div class="cart-table-wrapper">
            <table class="table cart-table align-middle">
                <thead>
                    <tr>
                        <th style="width: 100px;">Ảnh</th>
                        <th>Tên sản phẩm</th>
                        <th style="width: 180px;" class="text-center">Số lượng</th>
                        <th style="width: 150px;" class="text-end">Thành tiền</th>
                        <th style="width: 120px;" class="text-center">Thao tác</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($model as $item)
                        @php
                            $anh = $item->sanPham && $item->sanPham->hinhAnhSPs ? $item->sanPham->hinhAnhSPs->where('AnhBia', true)->first() : null;
                            $url = $anh ? url('Content/Images/' . $anh->URLAnh) : url('content/images/default.jpg');
                        @endphp

                        <tr class="cart-row">
                            <td>
                                <div class="cart-img-wrapper">
                                    <img src="{{ $url }}" alt="{{ $item->sanPham->TenSP ?? '' }}" class="cart-img" />
                                </div>
                            </td>

                            <td>
                                <div class="product-name">{{ $item->sanPham->TenSP ?? '' }}</div>
                                <div class="product-price-unit">{{ number_format($item->sanPham->Gia ?? 0, 0, ',', '.') }} ₫ / sản phẩm</div>
                            </td>

                            <td>
                                <div class="quantity-control">
                                    <a href="{{ url('/giohang/giam/' . $item->MaSP) }}" class="qty-btn qty-minus">
                                        <i class="bi bi-dash"></i>
                                    </a>
                                    <span class="qty-value">{{ $item->SoLuong }}</span>
                                    <a href="{{ url('/giohang/tang/' . $item->MaSP) }}" class="qty-btn qty-plus">
                                        <i class="bi bi-plus"></i>
                                    </a>
                                </div>
                            </td>

                            <td class="text-end">
                                <div class="cart-price">{{ number_format($item->ThanhTien, 0, ',', '.') }} ₫</div>
                            </td>

                            <td class="text-center">
                                <a href="{{ url('/giohang/xoa/' . $item->MaSP) }}"
                                   class="btn-delete"
                                   onclick="return confirm('Bạn có chắc muốn xóa sản phẩm này?');"
                                   title="Xóa sản phẩm">
                                    <i class="bi bi-trash"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- 💰 TỔNG TIỀN -->
        <div class="cart-footer">
            <div class="cart-summary">
                <div class="summary-header">
                    <i class="bi bi-receipt-cutoff me-2"></i>
                    <span>Tổng đơn hàng</span>
                </div>

                <div class="summary-body">
                    <div class="summary-row">
                        <span class="summary-label">Tạm tính ({{ collect($model)->count() }} sản phẩm):</span>
                        <span class="summary-value">{{ number_format($tongTien, 0, ',', '.') }} ₫</span>
                    </div>

                    <div class="summary-row">
                        <span class="summary-label">Phí vận chuyển:</span>
                        <span class="summary-value text-success fw-semibold">
                            <i class="bi bi-truck me-1"></i>Miễn phí
                        </span>
                    </div>

                    <div class="summary-divider"></div>

                    <div class="summary-total">
                        <span class="total-label">Tổng cộng:</span>
                        <span class="total-amount">{{ number_format($tongTien, 0, ',', '.') }} ₫</span>
                    </div>
                </div>

                <form action="{{ url('/hoadon/dathang') }}" method="post" class="mt-3">
                    @csrf
                    <button type="submit" class="btn-checkout">
                        <i class="bi bi-credit-card me-2"></i>
                        Thanh toán khi nhận hàng
                    </button>
                </form>

                <a href="{{ url('/sanpham/index') }}" class="btn-continue">
                    <i class="bi bi-arrow-left me-2"></i>
                    Tiếp tục mua sắm
                </a>
            </div>
        </div>
    @endif
</div>

<script>
    setTimeout(() => {
        var alertBox = document.getElementById("alertBox");
        if (alertBox) {
            alertBox.style.transition = "0.4s";
            alertBox.style.opacity = "0";
            setTimeout(() => alertBox.remove(), 400);
        }
    }, 3000);
</script>

<style>
    /* =======================================
       CART PAGE - TECHSECOND DESIGN
    ======================================= */

    .cart-page {
        min-height: 70vh;
    }

    /* ===== HEADER ===== */
    .cart-title {
        font-weight: 800;
        font-size: 2.2rem;
        color: #2d3748; /* Đen nhám hiện đại */
        margin-bottom: 2rem;
    }

    /* ===== EMPTY CART ===== */
    .empty-cart-wrapper {
        background: white;
        border-radius: 25px;
        padding: 5rem 2rem;
        text-align: center;
        box-shadow: 0 8px 30px rgba(102, 126, 234, 0.12);
    }

    .empty-cart-icon {
        font-size: 6rem;
        margin-bottom: 1.5rem;
        opacity: 0.3;
    }

    .empty-cart-title {
        color: #2d3748;
        font-weight: 700;
        margin-bottom: 0.8rem;
    }

    .empty-cart-subtitle {
        color: #718096;
        margin-bottom: 2rem;
    }

    /* ===== TABLE WRAPPER ===== */
    .cart-table-wrapper {
        background: white;
        border-radius: 20px;
        padding: 0;
        box-shadow: 0 8px 30px rgba(102, 126, 234, 0.12);
        overflow: hidden;
        margin-bottom: 2rem;
    }

    /* ===== TABLE ===== */
    .cart-table {
        margin-bottom: 0;
    }

    .cart-table thead th {
        background-color: #f8fafc !important; /* Xám nhạt thay vì gradient đen */
        color: #4a5568 !important; /* Chữ xám đậm dễ đọc */
        font-weight: 700;
        padding: 1.2rem 1rem;
        border: none;
        border-bottom: 2px solid #e2e8f0;
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .cart-table tbody {
        background: white;
    }

    .cart-row {
        border-bottom: 2px solid #f0f2ff;
        transition: all 0.3s;
    }

    .cart-row:hover {
        background: #fafbff;
    }

    .cart-row td {
        padding: 1.5rem 1rem;
        vertical-align: middle;
    }

    /* ===== IMAGE ===== */
    .cart-img-wrapper {
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .cart-img {
        width: 80px;
        height: 80px;
        object-fit: contain;
        border-radius: 12px;
        background: #f8f9ff;
        padding: 0.5rem;
        border: 2px solid #e8eaff;
        transition: transform 0.3s;
    }

    .cart-img:hover {
        transform: scale(1.08);
    }

    /* ===== PRODUCT INFO ===== */
    .product-name {
        font-weight: 600;
        color: #2d3748;
        font-size: 1rem;
        margin-bottom: 0.3rem;
        line-height: 1.4;
    }

    .product-price-unit {
        color: #6c757d;
        font-size: 0.85rem;
        font-weight: 500;
    }

    /* ===== QUANTITY CONTROL ===== */
    .quantity-control {
        display: inline-flex;
        align-items: center;
        background: #f8f9ff;
        border-radius: 12px;
        border: 2px solid #e8eaff;
        overflow: hidden;
    }

    .qty-btn {
        width: 36px;
        height: 36px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: transparent;
        color: #0d6efd;
        border: none;
        cursor: pointer;
        transition: all 0.2s;
        text-decoration: none;
        font-size: 1.1rem;
        font-weight: 600;
    }

    .qty-btn:hover {
        background: #f1f5f9;
        color: #0b5ed7;
    }

    .qty-value {
        min-width: 50px;
        text-align: center;
        font-weight: 700;
        color: #2d3748;
        font-size: 1.05rem;
        padding: 0 0.5rem;
    }

    /* ===== PRICE ===== */
    .cart-price {
        font-weight: 700;
        font-size: 1.2rem;
        color: #0d6efd; /* Xanh dương đậm */
    }

    /* ===== DELETE BUTTON ===== */
    .btn-delete {
        width: 40px;
        height: 40px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: #fff5f5;
        color: #f56565;
        border-radius: 10px;
        border: none;
        cursor: pointer;
        transition: all 0.3s;
        text-decoration: none;
        font-size: 1.1rem;
    }

    .btn-delete:hover {
        background: #f56565;
        color: white;
        transform: scale(1.1);
    }

    /* ===== CART FOOTER ===== */
    .cart-footer {
        display: flex;
        justify-content: flex-end;
    }

    .cart-summary {
        background: white;
        border-radius: 20px;
        padding: 2rem;
        box-shadow: 0 8px 30px rgba(102, 126, 234, 0.12);
        min-width: 420px;
    }

    .summary-header {
        display: flex;
        align-items: center;
        font-size: 1.3rem;
        font-weight: 700;
        color: #2d3748;
        margin-bottom: 1.5rem;
        padding-bottom: 1rem;
        border-bottom: 2px solid #f0f2ff;
    }

    .summary-body {
        margin-bottom: 1.5rem;
    }

    .summary-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.8rem 0;
        font-size: 0.95rem;
    }

    .summary-label {
        color: #4a5568;
        font-weight: 500;
    }

    .summary-value {
        color: #2d3748;
        font-weight: 600;
    }

    .summary-divider {
        height: 2px;
        background: linear-gradient(to right, transparent, #e8eaff, transparent);
        margin: 1rem 0;
    }

    .summary-total {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1rem 0;
        font-size: 1.1rem;
    }

    .total-label {
        color: #2d3748;
        font-weight: 700;
    }

    .total-amount {
        font-size: 1.8rem;
        font-weight: 700;
        color: #0d6efd;
    }

    /* ===== BUTTONS ===== */
    .btn-checkout {
        width: 100%;
        padding: 1rem 1.5rem;
        background-color: #0d6efd;
        color: white;
        border: none;
        border-radius: 15px;
        font-weight: 700;
        font-size: 1rem;
        box-shadow: 0 4px 15px rgba(13, 110, 253, 0.3);
        transition: all 0.3s;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .btn-checkout:hover {
        background-color: #0b5ed7;
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(13, 110, 253, 0.4);
    }

    .btn-continue {
        width: 100%;
        padding: 0.9rem 1.5rem;
        background: white;
        color: #4a5568;
        border: 2px solid #e2e8f0;
        border-radius: 15px;
        font-weight: 600;
        text-align: center;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-top: 1rem;
        transition: all 0.3s;
        text-decoration: none;
    }

    .btn-continue:hover {
        background: #f8fafc;
        color: #2d3748;
        border-color: #cbd5e1;
        text-decoration: none;
    }

    /* ===== RESPONSIVE ===== */
    @media (max-width: 992px) {
        .cart-summary {
            min-width: 100%;
        }

        .cart-footer {
            justify-content: stretch;
        }
    }

    @media (max-width: 768px) {
        .cart-title {
            font-size: 1.8rem;
        }

        .cart-table thead th {
            font-size: 0.75rem;
            padding: 1rem 0.5rem;
        }

        .cart-row td {
            padding: 1rem 0.5rem;
        }

        .cart-img {
            width: 60px;
            height: 60px;
        }

        .product-name {
            font-size: 0.9rem;
        }

        .cart-price {
            font-size: 1rem;
        }

        .qty-btn {
            width: 32px;
            height: 32px;
        }

        .qty-value {
            min-width: 40px;
            font-size: 0.95rem;
        }

        .cart-summary {
            padding: 1.5rem;
        }

        .summary-header {
            font-size: 1.1rem;
        }

        .total-amount {
            font-size: 1.5rem;
        }
    }

    @media (max-width: 576px) {
        .empty-cart-wrapper {
            padding: 3rem 1.5rem;
        }

        .empty-cart-icon {
            font-size: 4rem;
        }
    }
</style>
@endsection
