<!DOCTYPE html>
<html>
<head>
	<title>Print SKP</title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>
<body>
	<style type="text/css">
		table tr td,
		table tr th{
			font-size: 9pt;
		}

		.page-break {
			page-break-after: always;
		}

		.clear {
			clear: both;
		}

		body {
			font-size: 9pt;
		}

		.title {
			font-size: 10pt;
			font-weight: bold;
		}
		.header,
		.footer {
			width: 100%;
			position: fixed;
		}
		.header {
			top: 0px;
		}
		.footer {
			bottom: 0px;
			margin-top: 70px;
		}
		.pagenum:before {
			content: counter(page);
		}
	</style>
	<div class="footer">
		<table class="table table-borderless">
			<tr>
				<td class="text-left"  style="font-size: 6pt;font-style: italic;">
					Dokumen milik {{ !empty($personalWorkUnit) ? $personalWorkUnit->name : '-' }} ( NIP {{ !empty($personalWorkUnit) ? $personalWorkUnit->workIdNumber : '-' }})	
				</td>
				<td class="text-right"  style="font-size: 6pt;font-style: italic;">
					Hal. <span class="pagenum"></span>
				</td>
			</tr>
		</table>
	</div>
	<div class="text-center mb-2">
		<span class="title">EVALUASI KINERJA PEGAWAI</span><br>
		<span class="title">PENDEKATAN HASIL KERJA KUALITATIF</span><br>
		<span class="title">BAGI PEJABAT ADMINISTRASI / FUNGSIONAL</span><br>
		<span class="title">PERIODE: <s>TRIWULAN I/II/III/IV</s> AKHIR*</span><br>
	</div>
	<div>
		<div class="float-left">Universitas Airlangga</div>
		<div class="float-right">Periode : {{$periodName}}</div>
		<div class="clear">
	</div>
	<!-- Header Data Pegawai -->
	<div class="table-responsive">
		<!-- Data Pegawai -->
		<table class="table table-bordered table-hover table-blue table-sm mb-4">
			<tbody>
				<tr class="table-secondary mpdf-table-header-gray">
					<td class="text-center">No</td>
					<td class="text-center" colspan="2">Pegawai yang Dinilai</td>
					<td class="text-center">No</td>
					<td class="text-center" colspan="2">Pejabat Penilai Kinerja</td>
				</tr>
				<tr>
					<td class="td-data-no text-center">1.</td>
					<td class="td-data-attr">Nama</td>
					<td class="td-data-val">{{ !empty($personalWorkUnit) ? $personalWorkUnit->name : '-' }}</td>
					<td class="td-data-no text-center">1.</td>
					<td class="td-data-attr">Nama</td>
					<td class="td-data-val">{{ !empty($officerWorkUnit) ? $officerWorkUnit->name : '-' }}</td>
				</tr>
				<tr>
					<td class="text-center">2.</td>
					<td>NIP</td>
					<td>{{ !empty($personalWorkUnit) ? $personalWorkUnit->workIdNumber : '-' }}</td>
					<td class="text-center">2.</td>
					<td>NIP</td>
					<td>{{ !empty($officerWorkUnit) ? $officerWorkUnit->workIdNumber : '-' }}</td>
				</tr>
				<tr>
					<td class="text-center">3.</td>
					<td>Pangkat&nbsp;/&nbsp;Gol.</td>
					<td>{{ !empty($personalWorkUnit) ? $personalWorkUnit->rank : '-' }}</td>
					<td class="text-center">3.</td>
					<td>Pangkat&nbsp;/&nbsp;Gol.</td>
					<td>{{ !empty($officerWorkUnit) ? $officerWorkUnit->rank : '-' }}</td>
				</tr>
				<tr>
					<td class="text-center">4.</td>
					<td>Jabatan</td>
					<td>{{ !empty($personalWorkUnit) ? $personalWorkUnit->position : '-' }}</td>
					<td class="text-center">4.</td>
					<td>Jabatan</td>
					<td>{{ !empty($officerWorkUnit) ? $officerWorkUnit->position : '-' }}</td>
				</tr>
				<tr>
					<td class="text-center">5.</td>
					<td>Unit Kerja</td>
					<td>{{ !empty($personalWorkUnit) ? $personalWorkUnit->workUnit : '-' }}</td>
					<td class="text-center">5.</td>
					<td>Unit Kerja</td>
					<td>{{ !empty($officerWorkUnit) ? $officerWorkUnit->workUnit : '-' }}</td>
				</tr>
			</tbody>
		</table>
	</div>
	
	<!-- Capaian -->
	<div class="table-responsive">
		<table class="table table-bordered table-hover table-blue table-sm mb-4">
			<tbody>
				<tr class="table-secondary mpdf-table-header-gray">
					<td class="text-left">CAPAIAN KINERJA ORGANISASI: {{$helper->GetOrganizationPerformanceTextUpper($period->organization_performance)}}</td>
				</tr>
				<tr class="table-secondary mpdf-table-header-gray">
					<td class="text-left">POLA DISTRIBUSI:</td>
				</tr>
				<tr class="table-secondary mpdf-table-header-gray">
					<td class="text-center">
						<img width="250px" height="150px" src="data:image/png;base64,<?php echo base64_encode(file_get_contents(base_path('public/assets/images/'.$period->organization_performance.'.jpg'))); ?>" />
					</td>
				</tr>
			</tbody>
		</table>
	</div>
	<!-- Hasil Kerja -->
	<div class="table-responsive">
		<table class="table table-bordered table-hover table-blue table-sm mb-4">
			<tbody>
				<tr class="table-secondary">
					<td colspan="2">HASIL KERJA</td>
					<td rowspan="2">Realisasi Berdasarkan Bukti Dukung</td>
					<td rowspan="2">Umpan Balik Berkelanjutan Berdasarkan Bukti Dukung</td>
				</tr>
				<!-- Hasil Kerja Utama -->
				<tr class="table-secondary">
					<td colspan="2">A. Utama</td>
				</tr>
				@if(!empty($mainSkp) && count($mainSkp) > 0)
					@foreach($mainSkp as $key => $skpPlanRealization)
					<!-- Hasil Kerja Utama -->
					<tr>
						<td class="text-center" style="width:5%;">
							{{$key + 1}}. </td>
						<td style="width:55%;">
								{{$skpPlanRealization->skpWorkPlan->title}} (Penugasan Dari : {{$skpPlanRealization->skpWorkPlan->get_task_from}})
								<br>
								Ukuran keberhasilan / Indikator Kinerja Individu, dan Target:
								<br>
								<ul class="mb-0" style="margin-left: -15px">
								@foreach($skpPlanRealization->skpWorkPlan->skpWorkIndicators as $skpWorkIndicator)
									<li>
										{{$skpWorkIndicator->title}} 
									</li>
								@endforeach
							</ul>
						</td>
						<td class="text-center" style="width:20%;">
						{{$skpPlanRealization->realization}}</td>
						<td class="text-center" style="width:20%;">
						{{$skpPlanRealization->feedback}}</td>
					</tr>
					@endforeach
				@endif
			   
				
				@if(!empty($additionalSkp) && count($additionalSkp) > 0)
					<!-- Hasil Kerja Tambahan -->
					<tr class="table-secondary">
						<td colspan="4">B. Tambahan</td>
					</tr>
					@foreach($additionalSkp as $key => $skpPlanRealization)
					<!-- Hasil Kerja Utama -->
					<tr>
						<td class="text-center" style="width:5%;">
							{{$key + 1}}. </td>
						<td style="width:95%;">
								{{$skpPlanRealization->skpWorkPlan->title}} (Penugasan Dari : {{$skpPlanRealization->skpWorkPlan->get_task_from}})
								<br>
								Ukuran keberhasilan / Indikator Kinerja Individu, dan Target:
								<br>
								<ul class="mb-0" style="margin-left: -15px">
								@foreach($skpPlanRealization->skpWorkPlan->skpWorkIndicators as $skpWorkIndicator)
									<li>
										{{$skpWorkIndicator->title}} 
									</li>
								@endforeach
							</ul>
						</td>
						<td class="text-center" style="width:20%;">
						{{$skpPlanRealization->realization}}</td>
						<td class="text-center" style="width:20%;">
						{{$skpPlanRealization->feedback}}</td>
					</tr>
					@endforeach
				@endif
				<tr class="table-secondary">
					<td colspan="2">RATING HASIL KERJA</td>
					<td colspan="2" style="font-weight: bold;">{{$skpRealization->feedbackWorkCategory->name}}</td>
				</tr>
			</tbody>
		</table>
	</div>

	<!-- Perilaku Kerja -->
	<div class="table-responsive">
		<table class="table table-bordered table-hover table-blue table-sm mb-4">
			<tbody>
				<tr class="table-secondary">
					<td colspan="3">PERILAKU KERJA</td>
					<td>Umpan Balik Berkelanjutan Berdasarkan Bukti Dukung</td>
				</tr>
				@if(!empty($skpBehaviorRealizations) && count($skpBehaviorRealizations) > 0)
					@foreach($skpBehaviorRealizations as $key => $skpBehaviorRealization)
					<tr>
						<td style="width: 5%" class="text-center align-top">
						{{$key + 1}}.
						</td>
						<td style="width: 45%" class="align-top">
							{{$skpBehaviorRealization->skpBehavior->behaviorCategory->name}} <br>
							<ul class="mb-2 pl-4">
								@foreach($skpBehaviorRealization->skpBehavior->behaviorCategory->behaviorCriterias as $key => $behaviorCriteria)
								<li>{{$behaviorCriteria->name}}</li>
								@endforeach
							</ul>
						</td>
						<td style="min-width: 20%; max-width: 20%;">
							Ekspektasi Khusus Pimpinan: {{$skpBehaviorRealization->skpBehavior->notes}}
							<br>
						</td>
						<td style="min-width: 20%; max-width: 20%;">
						{{$skpBehaviorRealization->feedback}}
						</td>
					</tr>
					@endforeach
				@endif
				<tr class="table-secondary">
					<td colspan="2">RATING PERILAKU</td>
					<td colspan="2" style="font-weight: bold;">{{$skpRealization->feedbackBehaviorCategory->name}}</td>
				</tr>
				<tr class="table-secondary">
					<td colspan="2">PREDIKAT KINERJA PEGAWAI</td>
					<td colspan="2" style="font-weight: bold;">{{$skpRealization->performance_predicate}}</td>
				</tr>
			</tbody>
		</table>
	</div>
	<table class="table borderless">
		<tr>
			<td style="width: 50%" class="text-center">
				&nbsp;
			</td>
			<td style="width: 50%" class="text-center">
				{{$dateSetting}}<br>
				Pejabat Penilai Kinerja,
				<br><br><br><br>
				{{$officerWorkUnit->name}}<br>
				NIP. {{$officerWorkUnit->workIdNumber}}
			</td>
		</tr>
	</table>
	
</body>
</html>