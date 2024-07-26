@extends('template.master')

@section('title', $menu_title)

@section('sidebar')
    @include('template.sidebar')
@endsection

@section('page-head')
    <div class="page-header">
        <div class="page-header-content container d-lg-flex">
            <div class="d-flex">
                <h4 class="page-title mb-0">
                    {{$menu_title}} -
                    <span class="fw-normal">
                        @if($data)
                            Edit
                        @else
                            Tambah
                        @endif
                    </span>
                </h4>

                <a href="#page_header" class="btn btn-light align-self-center collapsed d-lg-none border-transparent rounded-pill p-0 ms-auto" data-bs-toggle="collapse">
                    <i class="ph-caret-down collapsible-indicator ph-sm m-1"></i>
                </a>
            </div>

        </div>
    </div>
@endsection

@section('page-content')
    <div class="content container pt-0">

        <!-- Basic card -->
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">Form</h6>
            </div>

            <div class="card-body">

                @if($errors->any())
                    <div class="alert alert-danger alert-icon-start alert-dismissible">
                        <span class="alert-icon bg-danger text-white">
                            <i class="ph-x-circle"></i>
                        </span>
                        <span class="fw-semibold"> Gagal !</span> {{$errors->first()}}.
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                <form action="@if($data) {{route($route.'save')}} @else {{route($route.'store')}} @endif" method="post">
                    @csrf
                    @if($data)
                        <input type="hidden" name="id" value="{{$data->id}}">
                    @endif
                    <div class="mb-3">
                        <label class="form-label">Unit Kerja Atasan:</label>
                        <div class="col-12">
                            <select name="parent_id" data-placeholder="Pilih..." class="form-control select">
                                <option></option>
                                @foreach($work_units as $work_unit)
                                    <option value="{{$work_unit->id}}" @if(!empty($data)) @if($data->parent_id==$work_unit->id) selected @endif @endif>{{$work_unit->name}}</option>
                                    @if(count($work_unit->childs)>0&&!empty($work_unit->parent_id))
                                        @foreach($work_unit->childs as $work_unit_child1)
                                            <option value="{{$work_unit_child1->id}}" @if(!empty($data)) @if($data->parent_id==$work_unit_child1->id) selected @endif @endif>{{$work_unit->name}} > {{$work_unit_child1->name}}</option>
                                            @if(count($work_unit_child1->childs)>0&&!empty($work_unit_child1->parent_id))
                                                @foreach($work_unit_child1->childs as $work_unit_child2)
                                                    <option value="{{$work_unit_child2->id}}" @if(!empty($data)) @if($data->parent_id==$work_unit_child2->id) selected @endif @endif>{{$work_unit->name}} > {{$work_unit_child1->name}} > {{$work_unit_child2->name}}</option>
                                                @endforeach
                                            @endif
                                        @endforeach
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nama :</label>
                        <input type="text" name="name" class="form-control" placeholder="Nama Unit" @if($data) value="{{$data->name}}" @endif>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Deskripsi:</label>
                        <input type="text" name="description" class="form-control" placeholder="Deskripsi Unit" @if($data) value="{{$data->description}}" @endif>
                    </div>
                    <div class="d-flex align-items-center">
                        <a href="{{route($route.'index')}}" class="btn btn-light">Kembali</a>
                        <button type="submit" class="btn btn-primary ms-3"><i class="ph-check me-2"></i> Simpan</button>
                    </div>
                </form>
            </div>
        </div>
        <!-- /basic card -->


    </div>
    <!-- /content area -->

@endsection

@push('scripts')
    <!-- Theme JS files -->
    <script>
        // Default initialization
        $('.select').select2();
    </script>
    <!-- /theme JS files -->
@endpush