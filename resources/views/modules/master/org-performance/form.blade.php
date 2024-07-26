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
                    {{$menu_title}}
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
                <form action="{{route($route.'save')}}" method="post">
                    @csrf
                    @if($data)
                        <input type="hidden" name="id" value="{{$data->id}}">
                    @endif

                    <div class="mb-3">
                        <label class="form-label">Capaian & Kurva Kinerja Organisasi :</label>
                        <div class="col-6">
                            <select name="organization_performance" id="organization_performance" data-placeholder="Pilih Kinerja..." class="form-control">
                                <option value="">Pilih Kinerja Organisasi</option>
                                @foreach($org_performances as $org_performance)
                                    <option value="{{$org_performance['value']}}" @if($data) @if($data->organization_performance==$org_performance['value']) selected @endif @endif >{{$org_performance['text']}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="col-6" id="img-performance">
                            @if(!empty($data->organization_performance))
                                <img src="{{asset('assets/images/'.$data->organization_performance.'.jpg')}}" width="80%">
                            @else
                                <div class="alert alert-danger">Kinerja organisasi belum di set</div>
                            @endif
                        </div>
                    </div>

                    <div class="d-flex align-items-center">
                        <button type="submit" class="btn btn-primary"><i class="ph-check me-2"></i> Simpan</button>
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
        $('#organization_performance').change(function () {
            console.log($(this).val());
            if($(this).val()!==''){
                $('#img-performance').html('<img src="'+window.location.origin+'/assets/images/'+$(this).val()+'.jpg" width="80%" />')
            }else{
                $('#img-performance').html('');
            }
        });
    </script>
    <!-- /theme JS files -->
@endpush
