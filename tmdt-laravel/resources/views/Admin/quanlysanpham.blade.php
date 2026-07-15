@extends('shared._layoutadmin')
@section('content')
<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body p-4">
        <h5 class="fw-bold mb-4 border-bottom pb-3"><i class="fa fa-box me-2 text-warning"></i>Quản Lý Sản Phẩm</h5>
        
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Tên SP</th>
                        <th>Người đăng</th>
                        <th>Giá</th>
                        <th>SL</th>
                        <th>Ngày đăng</th>
                        <th>Trạng thái</th>
                        <th class="text-center">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($sanPhams as $sp)
                        <tr>
                            <td class="fw-bold text-truncate" style="max-width: 200px;">{{ $sp->TenSP }}</td>
                            <td>{{ $sp->nguoiDung->TaiKhoan ?? 'N/A' }}</td>
                            <td class="text-danger fw-bold">{{ number_format($sp->Gia, 0, ',', '.') }}₫</td>
                            <td>{{ $sp->SoLuong }}</td>
                            <td>{{ \Carbon\Carbon::parse($sp->NgayDang)->format('d/m/Y') }}</td>
                            <td>
                                @if ($sp->TrangThai == 'Đã duyệt')
                                    <span class="badge bg-success">Đã duyệt</span>
                                @elseif ($sp->TrangThai == 'Từ chối')
                                    <span class="badge bg-danger">Từ chối</span>
                                @else
                                    <span class="badge bg-warning text-dark">Chờ duyệt</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <form action="{{ route('admin.doitrangthai', ['id' => $sp->MaSP]) }}" method="POST" class="d-inline">
                                    @csrf
                                    <input type="hidden" name="status" value="Đã duyệt">
                                    <button class="btn btn-sm btn-success rounded-pill px-2 py-1" title="Duyệt" {{ $sp->TrangThai == 'Đã duyệt' ? 'disabled' : '' }}><i class="fa fa-check"></i></button>
                                </form>
                                <form action="{{ route('admin.doitrangthai', ['id' => $sp->MaSP]) }}" method="POST" class="d-inline">
                                    @csrf
                                    <input type="hidden" name="status" value="Từ chối">
                                    <button class="btn btn-sm btn-danger rounded-pill px-2 py-1" title="Từ chối" {{ $sp->TrangThai == 'Từ chối' ? 'disabled' : '' }}><i class="fa fa-times"></i></button>
                                </form>
                                <a href="{{ route('sanpham.chitiet', ['id' => $sp->MaSP]) }}" class="btn btn-sm btn-info text-white rounded-pill px-2 py-1" title="Xem" target="_blank"><i class="fa fa-eye"></i></a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-3">
            {{ $sanPhams->links('pagination::bootstrap-4') }}
        </div>
    </div>
</div>
@endsection