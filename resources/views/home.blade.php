@extends('template.master')

@section('title', 'Home Apps')

@section('sidebar')
    @include('template.sidebar')
@endsection

@section('page-head')
    <div class="page-header">
        <div class="page-header-content d-lg-flex">
            <div class="d-flex">
                <h4 class="page-title mb-0">
                    Home - <span class="fw-normal">Dashboard</span>
                </h4>

                <a href="#page_header" class="btn btn-light align-self-center collapsed d-lg-none border-transparent rounded-pill p-0 ms-auto" data-bs-toggle="collapse">
                    <i class="ph-caret-down collapsible-indicator ph-sm m-1"></i>
                </a>
            </div>
        </div>
    </div>
@endsection

@section('page-content')
    <div class="content pt-0">
        @foreach($data as $d)
            <div class="mb-3 mt-2">
                <h3 class="mb-0">{{$d->nm_periode}}</h3>
                <div class="text-muted"><b>{{Carbon\Carbon::parse($d->start_data)->translatedFormat('d F Y')}}</b> sampai <b>{{Carbon\Carbon::parse($d->end_date)->translatedFormat('d F Y')}}</b></div>
            </div>
            <!-- Blocks with chart -->
            <div class="row">
                @if(!empty($d->detail))
                    @foreach($d->detail as $dd)
                        @php
                            $threshold = \App\Models\Transaksi\Threshold::where('id_periode',$dd->id_periode)
                            ->where('id_indikator',$dd->id_indikator)
                            ->first();
                        @endphp
                        <div class="col-lg-4 col-sm-12">
                            <div class="card card-body">
                                <div class="d-flex">
                                    <div class="flex-fill">
                                        <h4 class="mb-0">{{$dd->indikator->nm_indikator}}</h4>
                                        <p>
                                            Unit kerja <b>{{$dd->unit->nm_unit_kerja}}</b><br/>
                                            Treshold : <b>{{$threshold->value}}</b><br/>
                                            Skor <b>{{$dd->skor_pemonev}}</b><br/>
                                        </p>
                                    </div>

                                    <div class="ms-3 d-flex flex-column align-self-center">
                                        @if($dd->status_pemonev==1)
                                            <i class="ph-checks ph-3x text-success"></i>
                                            <span class="fs-sm">Memenuhi</span>
                                        @else
                                            <i class="ph-x ph-3x text-warning"></i><br/>
                                            <span class="fs-sm">Tidak</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
            <!-- /blocks with chart -->

        @endforeach
    </div>
@endsection

@push('scripts')
    <!-- Theme JS files -->
    <!-- /theme JS files -->
@endpush
