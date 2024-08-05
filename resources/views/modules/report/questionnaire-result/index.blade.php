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
                    Report - <span class="fw-normal">Hasil Kuisioner</span>
                </h4>

                <a href="#page_header"
                   class="btn btn-light align-self-center collapsed d-lg-none border-transparent rounded-pill p-0 ms-auto"
                   data-bs-toggle="collapse">
                    <i class="ph-caret-down coallapsible-indicator ph-sm m-1"></i>
                </a>
            </div>

        </div>
    </div>
@endsection

@section('page-content')
    <div id="my_content">
        <div class="card shadow mb-3">
            <div class="card-header py-3">
                <div style="float:left!important;">
                    <h4 class="m-0 font-weight-bold text-primary">Rekapitulasi Hasil</h4>
                </div>
            </div>

            <div class="card-body pt-3">
                <div class="table-responsive mb-4 px-1">
                    <table class="table">
                        <thead>
                        <tr>
                            <th style="width: 8%">No</th>
                            <th style="width: 20%">Kuisioner</th>
                            <th class="text-center" style="width: 12%">&Sigma; <br/>Pertanyaan</th>
                            <th class="text-center" style="width: 12%">&Sigma; <br/>Audience</th>
                            <th class="text-center" style="width: 12%">Rata Hasil</th>
                            <th style="width: 20%">Q. Hasil Terendah</th>
                            <th style="width: 20%">Q. Hasil Tertinggi</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($results as $result)
                            <tr>
                                <td>{{$loop->index+1}}</td>
                                <td>
                                    <h3>{{$result->title}}</h3>
                                    @if(!empty($result->description))
                                        <p>
                                            <b>Deskripsi :</b> {{$result->description}}
                                        </p>
                                    @endif
                                </td>
                                <td class="text-center">{{$result->count_question}}</td>
                                <td class="text-center">{{$result->count_audience}}</td>
                                <td class="text-center">{{$result->avg_result}}</td>
                                <td>{{$result->low_question}} <br/><b class="text-warning">{{$result->low_result}}</b></td>
                                <td>{{$result->high_question}} <br/><b class="text-success">{{$result->high_result}}</b></td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>

    </script>
@endpush
