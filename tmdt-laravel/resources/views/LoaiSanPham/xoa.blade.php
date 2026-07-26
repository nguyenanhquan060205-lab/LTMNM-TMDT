@extends('Shared._LayoutAdmin')
@section('title', 'Xóa loại sản phẩm')

@section('content')
<h2>Xóa</h2>

<h3>Bạn có chắc chắn muốn xóa loại sản phẩm này?</h3>
<div>
    <h4>Loại Sản Phẩm</h4>
    <hr />
    <dl class="row">
        <dt class="col-sm-3">Tên Loại:</dt>
        <dd class="col-sm-9">{{ $model->TenLoai ?? '' }}</dd>
    </dl>

    <form action="{{ url('/LoaiSanPham/Xoa/' . ($model->MaLoai ?? '')) }}" method="POST">
        @csrf
        <div class="form-actions no-color mt-3">
            <input type="submit" value="Xóa" class="btn btn-danger" />
            <a href="{{ url('/loaisanpham/index') }}" class="btn btn-secondary ms-2">Quay lại danh sách</a>
        </div>
    </form>
</div>
@endsection
