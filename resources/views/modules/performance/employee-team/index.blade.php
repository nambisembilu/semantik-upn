@extends('template.master')

@section('title', 'Home Apps')

@section('sidebar')
    @include('template.sidebar')
@endsection

@push('styles')
    <style type="text/css">
        /* Set alternating row colors (define BEFORE standard css). */
        /*
          table.fancytree-ext-table tbody tr:nth-child(even){
            background-color: #f4f4f8;
          }
        */
        /* custom alignment (set by 'renderColumns'' event) */
        table.fancytree-ext-table {
            width: 80%;
        }

        /* custom alignment (class is set by row template) */
        td.alignCenter {
            text-align: center;
        }

        td.alignRight {
            text-align: right;
        }
    </style>
    <link href="{{ asset('assets/css/vendor/ui.fancytree.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/vendor/jquery.contextMenu.min.css') }}" rel="stylesheet">
@endpush

@section('page-head')
    <div class="page-header">
        <div class="page-header-content d-lg-flex">
            <div class="d-flex">
                <h4 class="page-title mb-0">
                    Kinerja - <span class="fw-normal">Home</span>
                </h4>

                <a href="#page_header"
                   class="btn btn-light align-self-center collapsed d-lg-none border-transparent rounded-pill p-0 ms-auto"
                   data-bs-toggle="collapse">
                    <i class="ph-caret-down collapsible-indicator ph-sm m-1"></i>
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
                    <h4 class="m-0 font-weight-bold text-primary">Tim Kerja</h4>
                </div>
            </div>

            <div class="card-body pt-2">
                <!-- Add a <table> element where the tree should appear: -->
                <div id="tree">
                    <ul id="treeData" style="display: none;min-height: 500px">
                        @if((session('role_name') == 'Superadmin'))
                            @if(count($work_unit_main->teams)>0)
                                @foreach($work_unit_main->teams as $team)
                                    <li id="{{$work_unit_main->id}}.{{$team->id}}" data-id="{{$team->id}}" data-unit-id="{{$work_unit_main->id}}" data-personal-id="{{$team->personal_id}}" @if($team->is_head) class="ketua"
                                        data-is-head="1" @else class="anggota" data-is-head="0" @endif>
                                        {{$team->name}}
                                        (<b>
                                            @if($team->is_head)
                                                Ketua
                                            @else
                                                Anggota
                                            @endif
                                        </b>)
                                    </li>
                                @endforeach
                            @endif
                        @endif
                        @foreach($work_units as $work_unit)
                            <li id="{{$work_unit->id}}" class="folder unit-subteam-parent" data-unit-id="{{$work_unit->id}}" title="">{{$work_unit->name}}
                                @php
                                    $work_unit_childs1 = \App\Models\Master\WorkUnit::where('parent_id',$work_unit->id)->get();
                                @endphp
                                <ul>
                                    @if(count($work_unit->teams)>0)
                                        @foreach($work_unit->teams as $team)
                                            <li id="{{$work_unit->id}}.{{$team->id}}" data-id="{{$team->id}}" data-unit-id="{{$work_unit->id}}" data-personal-id="{{$team->personal_id}}" @if($team->is_head) class="ketua"
                                                data-is-head="1" @else class="anggota" data-is-head="0" @endif>
                                                {{$team->name}}
                                                (<b>
                                                    @if($team->is_head)
                                                        Ketua
                                                    @else
                                                        Anggota
                                                    @endif
                                                </b>)
                                            </li>
                                        @endforeach
                                    @endif
                                    @if(count($work_unit_childs1)>0)
                                        @foreach($work_unit_childs1 as $work_unit_child1)
                                            @php
                                                $work_unit_childs2 = \App\Models\Master\WorkUnit::where('parent_id',$work_unit_child1->id)->get();
                                            @endphp
                                            <li id="{{$work_unit_child1->id}}" class="folder unit-subteam" data-unit-id="{{$work_unit_child1->id}}" title="">{{$work_unit_child1->name}}
                                                <ul>
                                                    @php
                                                        $teams=\App\Models\Master\PersonalWorkUnit::join('personals', 'personals.id', '=', 'personal_work_units.personal_id')
                                                            ->where('personal_work_units.work_unit_id', $work_unit_child1->id)
                                                            ->where('personals.name', 'not like', "%admin%")
                                                            ->whereNull('personals.deleted_at')
                                                            ->orderBy('personal_work_units.is_head', 'desc')
                                                            ->orderBy('personals.name', 'asc')
                                                            ->select('personal_work_units.*','personals.name')
                                                            ->get();
                                                    @endphp
                                                    @if(count($teams)>0)
                                                        @foreach($teams as $team)
                                                            <li id="{{$work_unit_child1->id}}.{{$team->id}}" data-id="{{$team->id}}" data-unit-id="{{$work_unit_child1->id}}" data-personal-id="{{$team->personal_id}}" @if($team->is_head) class="ketua" data-is-head="1" @else class="anggota" data-is-head="0" @endif>
                                                                {{$team->name}}
                                                                (<b>
                                                                    @if($team->is_head)
                                                                        Ketua
                                                                    @else
                                                                        Anggota
                                                                    @endif
                                                                </b>)
                                                            </li>
                                                        @endforeach
                                                    @endif
                                                    @if(count($work_unit_childs2)>0)
                                                        @foreach($work_unit_childs2 as $work_unit_child2)
                                                            <li id="{{$work_unit_child2->id}}" class="folder unit-subteam-last" data-unit-id="{{$work_unit_child2->id}}" title="">{{$work_unit_child2->name}}
                                                                @php
                                                                    $teams=\App\Models\Master\PersonalWorkUnit::join('personals', 'personals.id', '=', 'personal_work_units.personal_id')
                                                                        ->where('personal_work_units.work_unit_id', $work_unit_child2->id)
                                                                        ->where('personals.name', 'not like', "%admin%")
                                                                        ->whereNull('personals.deleted_at')
                                                                        ->orderBy('personal_work_units.is_head', 'desc')
                                                                        ->orderBy('personals.name', 'asc')
                                                                        ->select('personal_work_units.*','personals.name')
                                                                        ->get();
                                                                @endphp
                                                                @if(count($teams)>0)
                                                                    <ul>
                                                                        @foreach($teams as $team)
                                                                            <li id="{{$work_unit_child2->id}}.{{$team->id}}" data-id="{{$team->id}}" data-unit-id="{{$work_unit_child2->id}}" data-personal-id="{{$team->personal_id}}" @if($team->is_head) class="ketua" data-is-head="1" @else class="anggota" data-is-head="0" @endif>
                                                                                {{$team->name}}
                                                                                (<b>
                                                                                    @if($team->is_head)
                                                                                        Ketua
                                                                                    @else
                                                                                        Anggota
                                                                                    @endif
                                                                                </b>)
                                                                            </li>
                                                                        @endforeach
                                                                    </ul>
                                                                @endif
                                                            </li>
                                                        @endforeach
                                                    @endif

                                                </ul>
                                            </li>
                                        @endforeach
                                    @endif

                                </ul>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>


    </div>
@endsection

@push('modals')
    <div class="modal fade" id="modalTimKerja" aria-labelledby="modalTimKerjaLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div id="modalTimKerja-content" class="modal-content">

            </div>
        </div>
    </div>

    <div class="modal fade" id="modalSubtim" aria-labelledby="modalSubtimLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div id="modalSubtim-content" class="modal-content">

            </div>
        </div>
    </div>

    <div class="modal fade" id="modalDeleteSubtim" aria-labelledby="modalDeleteSubtimLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div id="modalDeleteSubtim-content" class="modal-content">
                <form action="{{route('modules.performance.employee-team.deleteSubteam')}}" method="post">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Tambah Tim</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>Apakah anda yakin menghapus subtim ini?</p>
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" id="delete-subtim-work-unit" name="work_unit_id" value=""/>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Ya</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endpush

@push('scripts')
    <script src="{{ asset('assets/js/vendor/fancytree/jquery.fancytree-all-deps.min.js') }}"></script>
    <script src="{{ asset('assets/js/vendor/fancytree/jquery.fancytree.table.js') }}"></script>
    <script src="{{ asset('assets/js/vendor/context/jquery.contextMenu.min.js') }}"></script>
    <script type="text/javascript">
        $(function () {
            $("#tree").fancytree();
            @if(session('role_name')!='JAJF')
            $.contextMenu({
                selector: "#tree .unit",
                items: {
                    "add_lead": {name: "Tambah Ketua"},
                    "add_staff": {name: "Tambah Anggota"},
                },
                callback: function (itemKey, opt) {
                    var node = $.ui.fancytree.getNode(opt.$trigger);
                    if (itemKey == 'add_lead' || itemKey == 'add_staff') {
                        if (itemKey == 'add_lead') {
                            ajaxLoadAddStaff(node.data.unitId, 1);
                        } else if (itemKey == 'add_staff') {
                            ajaxLoadAddStaff(node.data.unitId, 0);
                        }
                        var myModal = new bootstrap.Modal(document.getElementById('modalTimKerja'), {
                            keyboard: false
                        })
                        myModal.show();
                    }


                }
            });


            $.contextMenu({
                selector: "#tree .unit-subteam-parent",
                items: {
                    "add_lead": {name: "Tambah Ketua"},
                    "add_staff": {name: "Tambah Anggota"},
                    "add_team": {name: "Tambah Subtim"},
                },
                callback: function (itemKey, opt) {
                    var node = $.ui.fancytree.getNode(opt.$trigger);
                    if (itemKey == 'add_lead' || itemKey == 'add_staff') {
                        if (itemKey == 'add_lead') {
                            ajaxLoadAddStaff(node.data.unitId, 1);
                        } else if (itemKey == 'add_staff') {
                            ajaxLoadAddStaff(node.data.unitId, 0);
                        }
                        var myModal = new bootstrap.Modal(document.getElementById('modalTimKerja'), {
                            keyboard: false
                        })
                        myModal.show();
                    } else {
                        ajaxLoadSubteam(node.data.unitId);
                        var myModal = new bootstrap.Modal(document.getElementById('modalSubtim'), {
                            keyboard: false
                        })
                        myModal.show();
                    }


                }
            });

            $.contextMenu({
                selector: "#tree .unit-subteam",
                items: {
                    "add_lead": {name: "Tambah Ketua"},
                    "add_staff": {name: "Tambah Anggota"},
                    "add_team": {name: "Tambah Subtim"},
                    "delete_team": {name: "Hapus Subtim"},
                },
                callback: function (itemKey, opt) {
                    var node = $.ui.fancytree.getNode(opt.$trigger);
                    if (itemKey == 'add_lead' || itemKey == 'add_staff') {
                        if (itemKey == 'add_lead') {
                            ajaxLoadAddStaff(node.data.unitId, 1);
                        } else if (itemKey == 'add_staff') {
                            ajaxLoadAddStaff(node.data.unitId, 0);
                        }
                        var myModal = new bootstrap.Modal(document.getElementById('modalTimKerja'), {
                            keyboard: false
                        })
                        myModal.show();
                    } else if (itemKey == 'add_team') {
                        ajaxLoadSubteam(node.data.unitId);
                        var myModal = new bootstrap.Modal(document.getElementById('modalSubtim'), {
                            keyboard: false
                        })
                        myModal.show();
                    } else {
                        $('#delete-subtim-work-unit').val(node.data.unitId);
                        var myModal = new bootstrap.Modal(document.getElementById('modalDeleteSubtim'), {
                            keyboard: false
                        })
                        myModal.show();
                    }


                }
            });

            $.contextMenu({
                selector: "#tree .unit-subteam-last",
                items: {
                    "add_lead": {name: "Tambah Ketua"},
                    "add_staff": {name: "Tambah Anggota"},
                    "delete_team": {name: "Hapus Subtim"},
                },
                callback: function (itemKey, opt) {
                    var node = $.ui.fancytree.getNode(opt.$trigger);
                    if (itemKey == 'add_lead' || itemKey == 'add_staff') {
                        if (itemKey == 'add_lead') {
                            ajaxLoadAddStaff(node.data.unitId, 1);
                        } else if (itemKey == 'add_staff') {
                            ajaxLoadAddStaff(node.data.unitId, 0);
                        }
                        var myModal = new bootstrap.Modal(document.getElementById('modalTimKerja'), {
                            keyboard: false
                        })
                        myModal.show();
                    } else {
                        $('#delete-subtim-work-unit').val(node.data.unitId);
                        var myModal = new bootstrap.Modal(document.getElementById('modalDeleteSubtim'), {
                            keyboard: false
                        })
                        myModal.show();
                    }


                }
            });
            $.contextMenu({
                selector: "#tree .ketua",
                items: {
                    "change_staff": {name: "Ubah Jadi Anggota "},
                    "remove_lead": {name: "Hapus Ketua"},
                },
                callback: function (itemKey, opt) {
                    var node = $.ui.fancytree.getNode(opt.$trigger);
                    if (itemKey == 'change_staff') {
                        updateStatusTeam(node.data.personalId, node.data.unitId, 0)
                    } else if (itemKey == 'remove_lead') {
                        removeTeam(node.data.id);
                    }
                }
            });
            $.contextMenu({
                selector: "#tree .anggota",
                items: {
                    "change_lead": {name: "Ubah Jadi Ketua"},
                    "remove_staff": {name: "Hapus Anggota"},
                },
                callback: function (itemKey, opt) {
                    var node = $.ui.fancytree.getNode(opt.$trigger);
                    if (itemKey == 'change_lead') {
                        updateStatusTeam(node.data.personalId, node.data.unitId, 1)
                    } else if (itemKey == 'remove_staff') {
                        removeTeam(node.data.id);
                    }
                }
            });

            function ajaxLoadAddStaff(id, is_head) {
                $.ajax({
                    url: "{{route('modules.performance.employee-team.ajaxLoadStaff')}}",
                    method: 'POST',
                    data:
                        {
                            '_token': $("input[name='_token']").val(),
                            'unit_id': id,
                            'is_head': is_head
                        },
                    async: false,
                    success: function (response) {
                        $('#modalTimKerja-content').html(response);
                        $('.select').select2({dropdownParent: $("#modalTimKerja")});
                    }
                })
            }

            function ajaxLoadSubteam(id) {
                $.ajax({
                    url: "{{route('modules.performance.employee-team.ajaxLoadSubteam')}}",
                    method: 'POST',
                    data:
                        {
                            '_token': $("input[name='_token']").val(),
                            'unit_id': id,
                        },
                    async: false,
                    success: function (response) {
                        $('#modalSubtim-content').html(response);
                    }
                })
            }

            function updateStatusTeam(id, unit_id, is_head) {
                $.ajax({
                    url: "{{route('modules.performance.employee-team.setStatusTeam')}}",
                    method: 'POST',
                    data:
                        {
                            '_token': $("input[name='_token']").val(),
                            'personal_id': id,
                            'unit_id': unit_id,
                            'is_head': is_head
                        },
                    async: false,
                    success: function (response) {
                        window.location.reload();
                    }
                })
            }

            function removeTeam(id) {
                $.ajax({
                    url: "{{route('modules.performance.employee-team.deleteTeam')}}",
                    method: 'POST',
                    data:
                        {
                            '_token': $("input[name='_token']").val(),
                            'id': id,
                        },
                    async: false,
                    success: function (response) {
                        window.location.reload();
                    }
                })
            }

            function removeSubteam(id) {
                $.ajax({
                    url: "{{route('modules.performance.employee-team.deleteTeam')}}",
                    method: 'POST',
                    data:
                        {
                            '_token': $("input[name='_token']").val(),
                            'id': id,
                        },
                    async: false,
                    success: function (response) {
                        window.location.reload();
                    }
                })
            }
            @endif
        });
    </script>
@endpush
