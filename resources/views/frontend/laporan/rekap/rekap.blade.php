@extends('frontend.master')

@section('content')
<div class="container-fluid">

    <h4 class="mb-3 fw-bold text-center">Laporan Rekap Patroli & Aktivitas Satpam</h4>

    <!-- Filter -->
    <div class="card mb-3 shadow-sm">
        <div class="card-body">
            <div class="row g-2 align-items-end">
                <div class="col-md-2">
                    <label>Dari Tanggal</label>
                    <input type="date" id="filter_start" class="form-control">
                </div>
                <div class="col-md-2">
                    <label>Sampai Tanggal</label>
                    <input type="date" id="filter_end" class="form-control">
                </div>
                <div class="col-md-3">
                    <label>Nama Satpam</label>
                    <select id="filter_satpam" class="form-select">
                        <option value="">Semua</option>
                        @foreach($satpams as $satpam)
                            <option value="{{ $satpam->id }}">{{ $satpam->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-5 d-flex gap-1">
                    <button id="btnFilter" class="btn btn-primary flex-fill">Filter</button>
                    <button id="btnReset" class="btn btn-secondary flex-fill">Reset</button>
                    <button id="btnExportXls" class="btn btn-success flex-fill">Export Excel</button>
                    <button id="btnExportPdf" class="btn btn-danger flex-fill">Export PDF</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabel Rekap -->
    <div id="rekapContainer">
        <!-- Data akan di load via AJAX -->
    </div>
</div>
@endsection

@push('scripts')
<script>
function loadRekap() {
    let start = $('#filter_start').val();
    let end = $('#filter_end').val();
    let satpam_id = $('#filter_satpam').val();

    $.ajax({
        url: "{{ route('laporan.rekap.data') }}",
        type: "GET",
        data: {start, end, satpam_id},
        success: function(res){
            let html = '';
            // Loop setiap modul
            for(let key in res){
                html += `<h5 class="mt-3">${key.toUpperCase()}</h5>`;
                html += `<table class="table table-bordered table-sm mb-3"><thead><tr>`;
                html += Object.keys(res[key][0] || {}).map(k=>`<th>${k}</th>`).join('');
                html += `</tr></thead><tbody>`;
                res[key].forEach((row,i)=>{
                    html += `<tr>${Object.values(row).map(v=>v ?? '').join('')}</tr>`;
                });
                html += `</tbody></table>`;
            }
            $('#rekapContainer').html(html);
        }
    });
}

$(document).ready(function(){
    loadRekap();

    $('#btnFilter').click(loadRekap);
    $('#btnReset').click(function(){
        $('#filter_start').val('');
        $('#filter_end').val('');
        $('#filter_satpam').val('');
        loadRekap();
    });

    $('#btnExportXls').click(function(){
        let start = $('#filter_start').val();
        let end = $('#filter_end').val();
        let satpam_id = $('#filter_satpam').val();
        window.location.href = "{{ route('laporan.rekap.excel') }}?start="+start+"&end="+end+"&satpam_id="+satpam_id;
    });

    $('#btnExportPdf').click(function(){
        let start = $('#filter_start').val();
        let end = $('#filter_end').val();
        let satpam_id = $('#filter_satpam').val();
        window.location.href = "{{ route('laporan.rekap.pdf') }}?start="+start+"&end="+end+"&satpam_id="+satpam_id;
    });
});
</script>
@endpush
