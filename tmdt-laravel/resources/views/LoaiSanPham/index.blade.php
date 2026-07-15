@extends('shared._layoutadmin')
@section('content')
<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body p-4">
        <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3">
            <h5 class="fw-bold mb-0"><i class="fa fa-tags me-2 text-primary"></i>Danh Mục Sản Phẩm</h5>
            <a href="{{ route('loaisanpham.create') }}" class="btn btn-warning rounded-pill px-4 fw-bold shadow-sm">
                <i class="fa fa-plus-circle me-2"></i>Thêm Danh Mục
            </a>
        </div>
        
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Mã loại</th>
                        <th>Tên loại</th>
                        <th class="text-center">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($dsLoai as $loai)
                        <tr>
                            <td class="fw-bold">#{{ $loai->MaLoai }}</td>
                            <td>{{ $loai->TenLoai }}</td>
                            <td class="text-center">
                                <a href="{{ route('loaisanpham.edit', ['id' => $loai->MaLoai]) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">Sửa</a>
                                <form action="{{ route('loaisanpham.delete', ['id' => $loai->MaLoai]) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc chắn muốn xóa?');">
                                    @csrf
                                    <button class="btn btn-sm btn-outline-danger rounded-pill px-3">Xóa</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection