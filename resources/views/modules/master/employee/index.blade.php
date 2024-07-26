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
                    Konfigurasi - <span class="fw-normal">Master Pegawai</span>
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

        <!-- Basic card -->
        <div class="card">
            <div class="card-header d-sm-flex align-items-sm-center py-sm-0">
                <h6 class="py-sm-3 mb-auto">Tabel</h6>
                @if(session('role_name') == 'Superadmin')
                    <div class="d-flex ms-auto my-auto">
                        <div class="d-flex ms-auto my-auto">
                            <a href="{{route($route.'create')}}" class="btn btn-success btn-labeled btn-labeled-start me-2">
                        <span class="btn-labeled-icon bg-black bg-opacity-20">
                            <i class="ph-plus"></i>
                        </span>
                                Tambah
                            </a>
                        </div>
                    </div>
                @endif
            </div>

            <table class="table datatable-server">
            </table>

        </div>
        <!-- /basic card -->


    </div>
    <!-- /content area -->

@endsection

@push('scripts')
    <!-- Theme JS files -->
    <script>
        // Setup module
        // ------------------------------
        const DatatableBasic = function () {
            //
            // Setup module components
            //
            // Basic Datatable examples
            const _componentDatatableBasic = function () {
                if (!$().DataTable) {
                    console.warn('Warning - datatables.min.js is not loaded.');
                    return;
                }
                // Setting datatable defaults
                $.extend($.fn.dataTable.defaults, {
                    autoWidth: false,
                    columnDefs: [{
                        orderable: false,
                        width: 100,
                    }],
                    dom: '<"datatable-header"fl><"datatable-scroll"t><"datatable-footer"ip>',
                    language: {
                        search: '<span class="me-3">Filter:</span> <div class="form-control-feedback form-control-feedback-end flex-fill">_INPUT_<div class="form-control-feedback-icon"><i class="ph-magnifying-glass opacity-50"></i></div></div>',
                        searchPlaceholder: 'Type to filter...',
                        lengthMenu: '<span class="me-3">Show:</span> _MENU_',
                        paginate: {'first': 'First', 'last': 'Last', 'next': document.dir == "rtl" ? '&larr;' : '&rarr;', 'previous': document.dir == "rtl" ? '&rarr;' : '&larr;'}
                    }
                });

                // Basic datatable
                $('.datatable-server').DataTable({
                    "processing": true,
                    "responsive": false,
                    "serverSide": true,
                    "order": [[1, "asc"]],
                    "ajax": {
                        "url": "{{ route($route.'datatable') }}",
                        "type": "get",
                        "data": function (d) {
                            // d.additional_param = additional_value;
                        }
                    },
                    "lengthMenu": [10, 25, 50, 100],
                    "scrollX": true,
                    "columns": [
                        {data: 'identity', title: 'Nama', orderable: true, searchable: true},
                        {data: 'grade_code', className: 'text-center', title: 'Golongan', orderable: true, searchable: true},
                        {data: 'position_unit', title: 'Jabatan', orderable: true, searchable: true},
                        {
                            data: 'action', className: 'text-center', title: 'Action', orderable: false, searchable: false,
                            render: function (data) {
                                return data
                            }
                        },
                    ],
                });

                // Resize scrollable table when sidebar width changes
                $('.sidebar-control').on('click', function () {
                    table.columns.adjust().draw();
                });
            };


            //
            // Return objects assigned to module
            //

            return {
                init: function () {
                    _componentDatatableBasic();
                }
            }
        }();


        // Initialize module
        // ------------------------------

        document.addEventListener('DOMContentLoaded', function () {
            DatatableBasic.init();
        });

        function deleteRow(e) {
            // Select all delete buttons
            e.preventDefault();
            // Select parent row
            const parent = e.target.closest('tr');
            // Get customer name
            const name = parent.querySelectorAll('td')[0].innerText;

            // SweetAlert2 pop up --- official docs reference: https://sweetalert2.github.io/
            Swal.fire({
                title: 'Konfirmasi',
                html: "Apakah anda yakin akan menghapus <b>" + name + "</b> ?",
                icon: "warning",
                showCancelButton: true,
                buttonsStyling: false,
                confirmButtonText: "Ya",
                cancelButtonText: "Batal",
                customClass: {
                    confirmButton: "btn fw-bold btn-danger",
                    cancelButton: "btn fw-bold btn-active-light-primary"
                }
            }).then(function (result) {
                if (result.value) {
                    parent.querySelectorAll('form')[0].submit();
                }
            });
        }
    </script>
@endpush
