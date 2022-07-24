@extends('layout.admin-layout')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-6">
                    <h1>Tạo món mới</h1>
                </div>
                <div class="col-6">
                    <div class="float-right">
                        <a class="btn btn-primary" href="{{ route('list-food') }}">Trở lại</a>
                    </div>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    @if (\Session::has('status'))
        <div class="alert alert-success">
            <span>{!! \Session::get('status') !!}</span>
        </div>
    @endif
    <section class="content">
        <form method="POST" action="{{ route('create-food') }}" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-12">
                    <div class="card card-primary">
                        <div class="card-body">
                            <div class="form-group">
                                <label for="vietnamese_name">Tên tiếng việt</label>
                                <input type="text" id="vietnamese_name" name="vietnamese_name" class="form-control @error('vietnamese_name') is-invalid @enderror" value="{{ old('vietnamese_name') }}">
                            </div>
                            @error('vietnamese_name')
                            <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                            <div class="form-group">
                                <label for="japanese_name">Tên tiếng nhật</label>
                                <input type="text" id="japanese_name" name="japanese_name" class="form-control @error('japanese_name') is-invalid @enderror" value="{{ old('japanese_name') }}">
                            </div>
                            @error('japanese_name')
                            <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                            <div class="form-group">
                                <label for="english_name">Tên khác</label>
                                <input type="text" id="english_name" name="english_name" class="form-control @error('english_name') is-invalid @enderror" value="{{ old('english_name') }}">
                            </div>
                            @error('english_name')
                            <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                            <div class="form-group">
                                <label for="short_name">Tên rút gọn</label>
                                <input type="text" id="short_name" name="short_name" class="form-control @error('short_name') is-invalid @enderror" value="{{ old('short_name') }}">
                            </div>
                            @error('short_name')
                            <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                            <div class="form-group">
                                <label for="price">Giá món</label>
                                <input type="number" id="price" min="0" name="price" class="form-control @error('price') is-invalid @enderror" value="{{ old('price') }}">
                            </div>
                            @error('price')
                            <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                            <div class="form-group">
                                <label for="category">Danh mục</label>
                                <select id="category" name="category" class="form-control custom-select @error('category') is-invalid @enderror">
                                    <option disabled selected>Select one</option>
                                    <option value="1">SASHI</option>
                                    <option value="3">KON</option>
                                    <option value="2">AGE</option>
                                    <option value="4">YAKI</option>
                                    <option value="5">NOMIMOMO</option>
                                </select>
                            </div>
                            @error('category')
                            <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                            <div class="form-group">
                                <label for="food_recipe">Công thức</label>
                                <textarea type="text" id="food_recipe" name="food_recipe" class="form-control @error('food_recipe') is-invalid @enderror">{{ old('food_recipe') }}</textarea>
                            </div>
                            @error('food_recipe')
                            <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                            <div class="form-group">
                                <label for="description">Mô tả</label>
                                <textarea type="text" id="description" name="description" class="form-control @error('description') is-invalid @enderror">{{ old('description') }}</textarea>
                            </div>
                            @error('description')
                            <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                            <div class="form-group">
                                <label for="image">Ảnh</label>
                                <input type="file" id="image" name="image" class="form-control @error('image') is-invalid @enderror">
                            </div>
                            @error('image')
                            <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <a href="{{ route('list-food') }}" class="btn btn-secondary">Hủy bỏ</a>
                    <input type="submit" value="Tạo mới" class="btn btn-success float-right">
                </div>
            </div>
        </form>
    </section>
@endsection
