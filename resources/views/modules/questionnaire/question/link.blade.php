@extends('template.master-nolayout')

@section('title', $menu_title)

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
        <!-- /basic card -->
        <div class="card">

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
                        <td class="text-center ">
                            <div class="p-3">
                                <button class="btn btn-primary">
                                    <i class="ph-check"></i> Simpan
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
