@extends('frontend.master')

@section('content')
    <!-- ============================================================== -->
    <!-- Start Page Content -->
    <!-- ============================================================== -->
    <div class="content-page">
        <div class="content">
            <div class="container-fluid">

                <!-- Page Header -->
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="mb-0 fw-bold">Tracking Map</h4>

                </div>

                <!-- Data Table -->
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <div class="d-flex gap-2 mb-2 align-items-center">
                            <button id="btnPlay" class="btn btn-success">▶ Play</button>
                            <button id="btnPause" class="btn btn-warning">⏸ Pause</button>
                            <button id="btnReplay" class="btn btn-secondary">🔁 Replay</button>

                            <select id="speedControl" class="form-select w-auto">
                                <option value="1500">1x</option>
                                <option value="800">2x</option>
                                <option value="400">4x</option>
                            </select>
                        </div>

                        <input type="range" id="timeline" class="form-range mb-2" min="0" value="0">

                        <audio id="checkpointSound" src="/sounds/checkpoint.mp3"></audio>

                        <div id="map" style="height:600px;"></div>


                    </div>
                </div>

            </div> <!-- end container-fluid -->
        </div> <!-- end content -->

        @include('frontend.footer')
    </div>
    <!-- ============================================================== -->
    <!-- End Page Content -->
    <!-- ============================================================== -->
@endsection

@push('scripts')
    @include('frontend.aktivitas.tracking.map_js')
@endpush
