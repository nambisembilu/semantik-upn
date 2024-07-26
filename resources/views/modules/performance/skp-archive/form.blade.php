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
            <div class="card-header d-sm-flex pt-sm-0 pb-0">
                <h6 class="align-self-sm-center mb-sm-0">Form Dokumen Arsip SKP <span class="ms-2 badge bg-teal">Tipe file yang diperbolehkan (PDF), dan Maksimal 2MB</span>
                </h6>
                <div class="ms-sm-auto">
                    <ul class="nav nav-tabs nav-tabs-highlight card-header-tabs mb-0" role="tablist">
                        <li class="nav-item" role="presentation">
                            <a href="#card-tab1" class="nav-link active" data-bs-toggle="tab" aria-selected="false" role="tab" tabindex="-1">
                                <i class="icon-file-text2 me-2"></i>
                                Rencana SKP
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a href="#card-tab2" class="nav-link" data-bs-toggle="tab" aria-selected="true" role="tab">
                                <i class="icon-files-empty me-2"></i>
                                Evaluasi SKP
                            </a>
                        </li>

                        <li class="nav-item" role="presentation">
                            <a href="#card-tab3" class="nav-link" data-bs-toggle="tab" aria-selected="false" role="tab" tabindex="-1">
                                <i class="icon-file-check me-2"></i>
                                Dok.Evaluasi SKP
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="card-body tab-content">
                <div class="tab-pane fade active show" id="card-tab1" role="tabpanel">
                    <form method="post" action="{{route('modules.performance.skp-archive.saveFile')}}" enctype="multipart/form-data">
                        @csrf
                        <div class="row mb-3">
                            <label class="col-form-label col-lg-3">Berkas Rencana SKP
                                @if(!empty($skp_archive))
                                    @if($skp_archive->plan_status=='1')
                                        <span class="badge bg-info ms-2">Diajukan</span>
                                    @elseif($skp_archive->plan_status=='2')
                                        <span class="badge bg-success ms-2">Sudah disetujui</span>
                                    @elseif($skp_archive->plan_status=='3')
                                        <span class="badge bg-warning ms-2">Revisi</span>
                                    @endif
                                @endif
                            </label>
                            <div class="col-lg-7 d-flex">
                                @if(!empty($skp_archive))
                                    @if(!empty($skp_archive->plan_file))
                                        <a target="_blank" href="{{asset(Illuminate\Support\Facades\Storage::disk('public')->url($skp_archive->plan_file))}}" class="btn btn-sm btn-danger fs-9 me-2"><i class="icon-file-pdf me-2"></i> Dokumen</a>
                                    @endif
                                @endif
                                @if(empty($skp_archive)||(!empty($skp_archive)&&$skp_archive->plan_status!=2))
                                    <input type="hidden" name="type" value="rencana">
                                    <input type="file" name="file_archive" class="form-control mi-exposure-neg-2" accept="application/pdf">
                                @endif
                            </div>
                            <div class="col-lg-2">
                                @if(empty($skp_archive)||(!empty($skp_archive)&&$skp_archive->plan_status!=2))
                                    <button type="submit" class="btn btn-primary"><i class="ph-check me-2"></i> Simpan</button>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>

                <div class="tab-pane fade" id="card-tab2" role="tabpanel">
                    <form method="post" action="{{route('modules.performance.skp-archive.saveFile')}}" enctype="multipart/form-data">
                        @csrf
                        <div class="row mb-3">
                            <label class="col-form-label col-lg-3">Berkas Evaluasi SKP
                                @if(!empty($skp_archive))
                                    @if($skp_archive->eval_status=='1')
                                        <span class="badge bg-info ms-2">Diajukan</span>
                                    @elseif($skp_archive->eval_status=='2')
                                        <span class="badge bg-success ms-2">Sudah disetujui</span>
                                    @elseif($skp_archive->eval_status=='3')
                                        <span class="badge bg-warning ms-2">Revisi</span>
                                    @endif
                                @endif
                            </label>
                            <div class="col-lg-7 d-flex">
                                @if(!empty($skp_archive))
                                    @if(!empty($skp_archive->eval_file))
                                        <a target="_blank" href="{{asset(Illuminate\Support\Facades\Storage::disk('public')->url($skp_archive->eval_file))}}" class="btn btn-sm btn-danger fs-9 me-2"><i class="icon-file-pdf me-2"></i> Dokumen</a>
                                    @endif
                                @endif
                                @if(empty($skp_archive)||(!empty($skp_archive)&&$skp_archive->eval_status!=2))
                                    <input type="hidden" name="type" value="eval">
                                    <input type="file" name="file_archive" class="form-control" accept="application/pdf">
                                @endif
                            </div>
                            <div class="col-lg-2">
                                @if(empty($skp_archive)||(!empty($skp_archive)&&$skp_archive->eval_status!=2))
                                    <button type="submit" class="btn btn-primary"><i class="ph-check me-2"></i> Simpan</button>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>

                <div class="tab-pane fade" id="card-tab3" role="tabpanel">
                    <form method="post" action="{{route('modules.performance.skp-archive.saveFile')}}" enctype="multipart/form-data">
                        @csrf
                        <div class="row mb-3">
                            <label class="col-form-label col-lg-3">Berkas Dok.Evaluasi SKP
                                @if(!empty($skp_archive))
                                    @if($skp_archive->doc_eval_status=='1')
                                        <span class="badge bg-info ms-2">Diajukan</span>
                                    @elseif($skp_archive->doc_eval_status=='2')
                                        <span class="badge bg-success ms-2">Sudah disetujui</span>
                                    @elseif($skp_archive->doc_eval_status=='3')
                                        <span class="badge bg-warning ms-2">Revisi</span>
                                    @endif
                                @endif
                            </label>
                            <div class="col-lg-7 d-flex">
                                @if(!empty($skp_archive))
                                    @if(!empty($skp_archive->doc_eval_file))
                                        <a target="_blank" href="{{asset(Illuminate\Support\Facades\Storage::disk('public')->url($skp_archive->doc_eval_file))}}" class="btn btn-sm btn-danger fs-9 me-2"><i class="icon-file-pdf me-2"></i> Dokumen</a>
                                    @endif
                                @endif
                                @if(empty($skp_archive)||(!empty($skp_archive)&&$skp_archive->doc_eval_status!=2))
                                    <input type="hidden" name="type" value="doc_eval">
                                    <input type="file" name="file_archive" class="form-control" accept="application/pdf">
                                @endif
                            </div>
                            <div class="col-lg-2">
                                @if(empty($skp_archive)||(!empty($skp_archive)&&$skp_archive->doc_eval_status!=2))
                                    <button type="submit" class="btn btn-primary"><i class="ph-check me-2"></i> Simpan</button>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>

            </div>
        </div>
        <!-- /basic card -->


    </div>
    <!-- /content area -->

@endsection

