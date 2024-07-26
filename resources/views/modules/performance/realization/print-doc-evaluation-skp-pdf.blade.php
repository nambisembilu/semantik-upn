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
	<img width="80px" height="80px" src="data:image/png;base64,<?php echo base64_encode(file_get_contents(base_path('public/assets/images/logos/garuda-pancasila.png'))); ?>" />
	<br><span class="title">DOKUMEN EVALUASI KINERJA PEGAWAI</span>
	<br><span class="title">PERIODE: <s>TRIWULAN I/II/III/IV</s> AKHIR*</span><br>
	</div>
	<div>
		<div class="float-left">Kementerian Pendidikan, Kebudayaan, Riset dan Teknologi</div>
		<div class="float-right">Periode : {{$periodName}}</div>
		<div class="clear">
		<div class="float-left">Universitas Airlangga</div>
		<div class="clear">
	</div>
	<div class="table-responsive">
		<table class="table table-bordered table-hover table-blue table-sm mb-4">
			<tbody>
				<tr class="table-secondary">
					<td rowspan="6">1</td>
					<td colspan="3">PEGAWAI YANG DINILAI</td>
				</tr>
				<tr>
					<td>NAMA</td>
					<td>:</td>
					<td>{{ !empty($personalWorkUnit) ? $personalWorkUnit->name : '-' }}</td>
				</tr>
				<tr>
					<td>NIP</td>
					<td>:</td>
					<td>{{ !empty($personalWorkUnit) ? $personalWorkUnit->workIdNumber : '-' }}</td>
				</tr>
				<tr>
					<td>PANGKAT / GOL RUANG</td>
					<td>:</td>
					<td>{{ !empty($personalWorkUnit) ? $personalWorkUnit->rank : '-' }}</td>
				</tr>
				<tr>
					<td>JABATAN</td>
					<td>:</td>
					<td>{{ !empty($personalWorkUnit) ? $personalWorkUnit->position : '-' }}</td>
				</tr>
				<tr>
					<td>UNIT KERJA</td>
					<td>:</td>
					<td>{{ !empty($personalWorkUnit) ? $personalWorkUnit->workUnit : '-' }}</td>
				</tr>

				<tr class="table-secondary">
					<td rowspan="6">2</td>
					<td colspan="3">PEJABAT PENILAI KINERJA</td>
				</tr>
				<tr>
					<td>NAMA</td>
					<td>:</td>
					<td>{{ !empty($officerWorkUnit) ? $officerWorkUnit->name : '-' }}</td>
				</tr>
				<tr>
					<td>NIP</td>
					<td>:</td>
					<td>{{ !empty($officerWorkUnit) ? $officerWorkUnit->workIdNumber : '-' }}</td>
				</tr>
				<tr>
					<td>PANGKAT / GOL RUANG</td>
					<td>:</td>
					<td>{{ !empty($officerWorkUnit) ? $officerWorkUnit->rank : '-' }}</td>
				</tr>
				<tr>
					<td>JABATAN</td>
					<td>:</td>
					<td>{{ !empty($officerWorkUnit) ? $officerWorkUnit->position : '-' }}</td>
				</tr>
				<tr>
					<td>UNIT KERJA</td>
					<td>:</td>
					<td>{{ !empty($officerWorkUnit) ? $officerWorkUnit->workUnit : '-' }}</td>
				</tr>

				<tr class="table-secondary">
					<td rowspan="6">3</td>
					<td colspan="3">ATASAN PEJABAT PENILAI KINERJA</td>
				</tr>
				<tr>
					<td>NAMA</td>
					<td>:</td>
					<td>{{ !empty($upperOfficerWorkUnit) ? $upperOfficerWorkUnit->name : '-' }}</td>
				</tr>
				<tr>
					<td>NIP</td>
					<td>:</td>
					<td>{{ !empty($upperOfficerWorkUnit) ? $upperOfficerWorkUnit->workIdNumber : '-' }}</td>
				</tr>
				<tr>
					<td>PANGKAT / GOL RUANG</td>
					<td>:</td>
					<td>{{ !empty($upperOfficerWorkUnit) ? $upperOfficerWorkUnit->rank : '-' }}</td>
				</tr>
				<tr>
					<td>JABATAN</td>
					<td>:</td>
					<td>{{ !empty($upperOfficerWorkUnit) ? $upperOfficerWorkUnit->position : '-' }}</td>
				</tr>
				<tr>
					<td>UNIT KERJA</td>
					<td>:</td>
					<td>{{ !empty($upperOfficerWorkUnit) ? $upperOfficerWorkUnit->workUnit : '-' }}</td>
				</tr>

				<tr class="table-secondary">
					<td rowspan="3">4</td>
					<td colspan="3">EVALUASI KINERJA</td>
				</tr>
				<tr>
					<td>CAPAIAN KINERJA ORGANISASI</td>
					<td>:</td>
					<td>{{$helper->GetOrganizationPerformanceText($period->organization_performance)}}</td>
				</tr>
				<tr>
					<td>PREDIKAT KINERJA PEGAWAI</td>
					<td>:</td>
					<td>{{ $skpRealization->performance_predicate }}</td>
				</tr>

				<tr class="table-secondary">
					<td rowspan="2">5</td>
					<td colspan="3">CATATAN / REKOMENDASI</td>
				</tr>
				<tr>
					<td colspan="4"></td>
				</tr>
			</tbody>
		</table>
	</div>
	<table class="table borderless">
		<tr>
			<td style="width: 50%" class="text-center">
				7.{{$dateSettingEmployee}}<br>
				Pegawai yang Diniliai,
				<br><br><br><br>
				{{$personalWorkUnit->name}}<br>
				NIP. {{$personalWorkUnit->workIdNumber}}
			</td>
			<td style="width: 50%" class="text-center">
				6.{{$dateSettingOfficer}}<br>
				Pejabat Penilai Kinerja,
				<br><br><br><br>
				{{$officerWorkUnit->name}}<br>
				NIP. {{$officerWorkUnit->workIdNumber}}
			</td>
		</tr>
	</table>
	
</body>
</html>