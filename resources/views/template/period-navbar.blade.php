<div class="navbar navbar-expand-xl navbar-static shadow" 
style="display:block !important;background-color:#f2f2f0;">
    <div class="text-center">
        <form id="state_form">
        @csrf
        <input type="hidden" id="personal_id" value="{{session('personal_id')}}" />
        <input type="hidden" id="work_unit_id" value="{{session('work_unit_id')}}" />
        <input type="hidden" id="period_id" value="{{session('period_id')}}" />
            <select id="state_period" name="state_period" class="form-select" 
            style="display:inline-block !important;width:auto !important;">
                @foreach($periodStates as $periodState)
                <option value="{{$periodState->id}}"
                    {{$periodState->id == session('period_id') ? 'selected' : ''}} />{{$periodState->year}}</option>   
                @endforeach
            </select>
            <input disabled type="text" id="state_period_text" name="state_period_text" class="form-control"
            style="display:inline-block !important;width:30% !important;" /> 
            <select id="state_unit" name="state_unit" class="form-select" 
            style="display:inline-block !important;width:auto !important;">
            </select>
            <input disabled type="text" id="state_position_text" name="state_position_text" class="form-control"
            style="display:inline-block !important;width:20% !important;" /> 
            <button type="submit" class="btn btn-primary ms-3">SET</button>
        </form>
    </div>
</div>

@push('scripts')
<script>
function GetPersonalWorkUnits(personal_id, period_id) {
    $.ajax({
        url: "{{route('modules.master.personal_work_unit.get_work_units_by_personal_period')}}",
            method: 'POST',
            data: 
            {
                personal_id: personal_id,
                period_id: period_id,
                _token: $("input[name='_token']").val(),
            },
            async: false,
            success: function (response) {
                $('#state_unit').empty();
                response.forEach(function(item) {
                    $('#state_unit').append(new Option(item.work_unit.name, item.work_unit_id))
                });

                if($('#work_unit_id').val())
                {
                    $('#state_unit').val($('#work_unit_id').val());
                }
            }
        })
}


$(document).ready(function() {
    $.ajaxSetup({
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
    })

      $('#state_period').on('change', function () {
        $.ajax({
            url: "{{route('modules.master.period.get_by_id')}}",
            method: 'POST',
            data: 
            {
                id: $(this).val(),
                _token: $("input[name='_token']").val(),
            },
            async: false,
            success: function (response) {
                $('#state_period_text').val(response.description);

                GetPersonalWorkUnits($('#personal_id').val(), $("#state_period").val())
                $('#state_unit').trigger("change");
            }
        })
    });

    $('#state_unit').on('change', function () {
        $.ajax({
            url: "{{route('modules.master.personal_work_unit.get_roleposition_by_personal_period_workunit')}}",
            method: 'POST',
            data: 
            {
                work_unit_id: $(this).val(),
                personal_id: $('#personal_id').val(),
                period_id: $("#state_period").val(),
                _token: $("input[name='_token']").val(),
            },
            async: false,
            success: function (response) {
                $('#state_position_text').val(response.work_position.name);
            }
        })
    });
    
    $('#state_period').trigger("change");

    $("#state_form").submit(function(e){
        e.preventDefault();
        let periodYear = $("#state_period option:selected").text();
        if(confirm("Apakah anda yakin ingin membuat SKP tahun "+periodYear))
        {
        //execute submit
        $.ajax({
            url: "{{route('modules.def.save_period_state')}}",
            type:'POST',
            data: 
            {
                work_unit_id: $("#state_unit").val(),
                personal_id: $('#personal_id').val(),
                period_id: $("#state_period").val(),
                _token: $("input[name='_token']").val(),
            },
            success: function(data) {
                location.reload();
            }
            });
        }        
        
        }); 
    });


</script>
@endpush
