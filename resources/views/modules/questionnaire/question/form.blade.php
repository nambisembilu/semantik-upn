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
            <div class="d-block ms-auto " id="breadcrumb_elements">
                <div class="d-flex">
                    <a href="{{route($route.'index')}}" class="d-flex align-items-center text-body py-3">
                        <i class="ph-arrow-left me-2"></i>
                        Kembali
                    </a>
                </div>

            </div>
        </div>
    </div>
@endsection

@section('page-content')
    <div class="content container pt-0">

        <!-- Basic card -->
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">Header - Kuisioner</h6>
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
                        <label class="form-label">Judul :</label>
                        <input type="text" name="title" class="form-control" placeholder="Judul Kuisioner" @if($data) value="{{$data->title}}" @endif>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Deskripsi:</label>
                        <input type="text" name="description" class="form-control" placeholder="Deskripsi" @if($data) value="{{$data->description}}" @endif>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Pedoman:</label>
                        <textarea name="guidelines" class="form-control" placeholder="Pedoman pengisian kuisioner"></textarea>
                    </div>
                    <div class="d-flex align-items-center">
                        <button type="submit" class="btn btn-primary ms-3"><i class="ph-check me-2"></i> Simpan</button>
                    </div>
                </form>
            </div>
        </div>
        <!-- /basic card -->
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">Body - Kuisioner</h6>
            </div>

            <div class="card-body">
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th class="text-center bg-dark bg-opacity-50 text-light">
                            <h3 class="fw-bolder">{{$data->title}}</h3>
                            <hr/>
                            <h4>{{$data->description}}</h4>
                        </th>
                    </tr>
                    @if(!empty($data->guidelines))
                        <tr>
                            <th class="text-center">{{$data->guidelines}}</th>
                        </tr>
                    @endif
                    </thead>
                    <tbody>
                    @foreach($data->question as $q)
                        <tr>
                            <td>
                                <div class="d-flex flex-row justify-content-between align-items-center">
                                    <div>
                                        @if(!empty($q->code))
                                            <b>{{$q->code}}.</b>
                                        @endif {{$q->title}}
                                    </div>
                                    <div>
                                        <button class="btn btn-sm btn-outline-warning p-1">
                                            <i class="ph-pencil"></i>
                                        </button>
                                        <button class="btn btn-sm btn-outline-danger p-1">
                                            <i class="ph-x"></i>
                                        </button>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @if(!empty($q))
                            @php
                                $q_ans = $q->answer_content;
                                $q_grid_count = round(12/(count($q_ans)))
                            @endphp
                            <tr>
                                <td>
                                    <div class="row">
                                        @foreach($q_ans as $ans)
                                            <div class="col-{{$q_grid_count}}">
                                                <div class="d-inline-flex align-items-center">
                                                    <input type="radio" name="q_{{$q->id}}" id="q_{{$q->id}}_{{$ans['value']}}" value="{{$ans['value']}}">
                                                    <label class="ms-2" for="q_{{$q->id}}_{{$ans['value']}}">{{$ans['title']}}</label>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </td>
                            </tr>
                        @endif
                    @endforeach
                    <tr>
                        <td class="text-center">
                            <div class="p-3">
                                <button class="btn btn-success">
                                    <i class="ph-plus"></i> Pertanyaan
                                </button>
                                <button class="btn btn-indigo">
                                    <i class="ph-plus"></i> Variabel
                                </button>
                                <button class="btn btn-info">
                                    <i class="ph-plus"></i> Indikator
                                </button>
                                <button class="btn btn-primary">
                                    <i class="ph-plus"></i> Sub Bagian
                                </button>
                            </div>
                        </td>
                    </tr>

                    </tbody>
                </table>
            </div>
        </div>


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
