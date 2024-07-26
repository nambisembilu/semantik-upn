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
		<span class="title">SASARAN KINERJA PEGAWAI</span><br>
		<span class="title">PENDEKATAN HASIL KERJA KUALITATIF</span><br>
		<span class="title">BAGI PEJABAT ADMINISTRASI / FUNGSIONAL</span><br>
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
	<!-- Hasil Kerja -->
	<div class="table-responsive">
		<table class="table table-bordered table-hover table-blue table-sm mb-4">
			<tbody>
				<tr class="table-secondary">
					<td colspan="2">HASIL KERJA</td>
				</tr>
				<!-- Hasil Kerja Utama -->
				<tr class="table-secondary">
					<td colspan="2">A. Utama</td>
				</tr>
				@if(!empty($mainSkp) && count($mainSkp) > 0)
					@foreach($mainSkp[0]->skpWorkPlans as $key => $skpWorkPlan)
					<!-- Hasil Kerja Utama -->
					<tr>
						<td class="text-center" style="width:5%;">
							{{$key + 1}}. </td>
						<td style="width:95%;">
							{{$skpWorkPlan->title}} (Penugasan Dari : {{$skpWorkPlan->get_task_from}})
						</td>
					</tr>
					<!-- Indikator Hasil Kerja Utama -->
					<tr>
						<td class="border-top-0 border-bottom-0">&nbsp;</td>
						<td>Ukuran keberhasilan / Indikator Kinerja Individu, dan Target:</td>
					</tr>
					<tr>
						<td class="border-top-0 border-bottom-0">&nbsp;</td>
						<td>
							<ul class="mb-0" style="margin-left: -15px">
							@foreach($skpWorkPlan->skpWorkIndicators as $skpWorkIndicator)
								<li>
									{{$skpWorkIndicator->title}} 
								</li>
								@endforeach
							</ul>
						</td>
					</tr>
					<tr>
						<td class="border-top-0 border-bottom-0">&nbsp;</td>
						<td>&nbsp;</td>
					</tr>
					@endforeach
				@endif
			   

				<!-- Hasil Kerja Tambahan -->
				<tr class="table-secondary">
					<td colspan="2">B. Tambahan</td>
				</tr>
				@if(!empty($additionalSkp) && count($additionalSkp) > 0)
					@foreach($additionalSkp[0]->skpWorkPlans as $key => $skpWorkPlan)
					<!-- Hasil Kerja Utama -->
					<tr class="">
						<td class="text-center" style="width:5%;">
							{{$key + 1}}. </td>
						<td style="width:95%;">
							{{$skpWorkPlan->title}} (Penugasan Dari : {{$skpWorkPlan->get_task_from}})
						</td>
					</tr>
					<!-- Indikator Hasil Kerja Utama -->
					<tr>
						<td class="border-top-0 border-bottom-0">&nbsp;</td>
						<td>Ukuran keberhasilan / Indikator Kinerja Individu, dan Target:</td>
					</tr>
					<tr>
						<td class="border-top-0 border-bottom-0">&nbsp;</td>
						<td>
							<ul class="mb-0" style="margin-left: -15px">
							@foreach($skpWorkPlan->skpWorkIndicators as $skpWorkIndicator)
								<li>
									{{$skpWorkIndicator->title}} 
								</li>
							@endforeach
							</ul>
						</td>
					</tr>
					<tr>
						<td class="border-top-0 border-bottom-0">&nbsp;</td>
						<td>&nbsp;</td>
					</tr>
					@endforeach
				@endif    
				<tr>
					<td style="width: 50px">&nbsp;</td>
					<td>&nbsp;</td>
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
				</tr>
				@if(!empty($skpBehaviors) && count($skpBehaviors) > 0)
					@foreach($skpBehaviors as $key => $skpBehavior)
					<tr>
						<td style="width: 50px" class="text-center align-top">
						{{$key + 1}}.
						</td>
						<td class="align-top">
							{{$skpBehavior->behaviorCategory->name}} <br>
							<ul class="mb-2 pl-4">
								@foreach($skpBehavior->behaviorCategory->behaviorCriterias as $key => $behaviorCriteria)
								<li>{{$behaviorCriteria->name}}</li>
								@endforeach
							</ul>
						</td>
						<td style="min-width: 300px; max-width: 400px;">
							Ekspektasi Khusus Pimpinan: {{$skpBehavior->notes}}
							<br>
						</td>
					</tr>
					@endforeach
				@endif
			</tbody>
		</table>
	</div>
	<table class="table borderless">
		<tr>
			<td style="width: 50%" class="text-center">
				&nbsp;<br>
				Pegawai yang Diniliai,
				<br><br><br><br>
				{{$personalWorkUnit->name}}<br>
				NIP. {{$personalWorkUnit->workIdNumber}}
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
	<div class="page-break"></div>
	<div class="text-center mb-2">
		<span class="title">LAMPIRAN SASARAN KINERJA PEGAWAI</span><br>
	</div>
	<!-- Lampiran -->
	<div class="table-responsive mb-2">
		<table class="table table-bordered table-hover table-blue table-sm mb-4">
			<tbody>
				<tr class="table-secondary">
					<td colspan="2">LAMPIRAN</td>
				</tr>
				@if(!empty($attachmentCategories) && count($attachmentCategories) > 0)
					@foreach($attachmentCategories as $attachmentCategory)
					<tr class="table-secondary mpdf-table-header-gray">
						<td colspan="2">{{$attachmentCategory->name}}</td>
					</tr>
						@foreach($attachmentCategory->skpWorkAttachments as $key => $skpWorkAttachment)
						<tr>
							<td class="text-center" style="width: 50px">{{$key+1}}.</td>
							<td>{{$skpWorkAttachment->description}}</td>
						</tr>
						@endforeach
					@endforeach
				@endif
			</tbody>
		</table>
	</div>
	<table class="table borderless">
		<tr>
			<td style="width: 50%" class="text-center">
				&nbsp;<br>
				Pegawai yang Diniliai,
				<br><br><br><br>
				{{$personalWorkUnit->name}}<br>
				NIP. {{$personalWorkUnit->workIdNumber}}
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