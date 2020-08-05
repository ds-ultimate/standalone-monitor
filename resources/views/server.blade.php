@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    Server Übersicht
                    <div class="float-right form-row">
                        <div class="col-4">
                            <label for="inputEmail3" class="col-form-label col-form-label-sm">Update</label>
                        </div>
                        <div class="col">
                            <select id="updateTime" name="updateTime" class="form-control form-control-sm">
                                <option value="10000" >10 s</option>
                                <option value="60000" selected>1 min</option>
                                <option value="300000">5 min</option>
                                <option value="6000000">10 min</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-12 col-md-6 col-lg-4 col-xl-3 mb-4">
                            <div class="card text-center">
                                <div class="card-body">
                                    <h5 class="card-title">Load</h5>
                                    <p class="card-text"><span id="loadOneMin">{{ $load->oneMin }}</span> | <span id="loadFiveMin">{{ $load->fiveMin }}</span> | <span id="loadFifteenMin">{{ $load->fifteenMin }}</span></p>
                                    <p class="card-text"><small id="loadTimeDiff" class="text-muted">{{ $load->updated_at->diffForHumans() }}</small></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-6 col-lg-4 col-xl-3 mb-4">
                            <div class="card text-center">
                                <div class="card-body">
                                    <h5 class="card-title">SSH Sessions</h5>
                                    <p class="card-text"><span id="sshNumSessions">{{ $ssh->num_sessions }}</span></p>
                                    <p class="card-text"><small id="sshTimeDiff" class="text-muted">{{ $ssh->updated_at->diffForHumans() }}</small></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-6 col-lg-4 col-xl-3 mb-4">
                            <div class="card text-center">
                                <div class="card-body">
                                    <h5 class="card-title">Memory</h5>
                                    <div id="memoryPieChart" class="card-text"></div>
                                    <p class="card-text"><small class="text-muted">now</small></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-6 col-lg-4 col-xl-3 mb-4">
                            <div class="card text-center">
                                <div class="card-body">
                                    <h5 class="card-title">Memory</h5>
                                    <div id="memorySteppedBarChart" class="card-text"></div>
                                    <p class="card-text"><small class="text-muted">now</small></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-12 col-lg-12 col-xl-12 mb-4">
                            <div class="card text-center">
                                <div class="card-body">
                                    <h5 class="card-title">Memory</h5>
                                    <div id="memorySteppedAreaChart"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
    <script src="{{ asset('js/lava.js') }}"></script>
    {!! $chart !!}
    <script>
        var updateTime = 60000;
        var interval = null;
        var updaterInterval;

        $(document).on('change', '#updateTime', function (e) {
            updateTime = $('#updateTime').val()
            clearInterval(updaterInterval)
            startUpdater();
        })

        $(document).ready(function () {
            startUpdater();
        })

        function startUpdater() {
            updaterInterval = setInterval(function () {
                updateCurrentLoad();
                updateCurrentSsh();
                updatememorySteppedAreaChart();
            }, updateTime);
        }

        function updateCurrentLoad() {
            axios.post('{{ route('currentLoad', [$server->id]) }}')
                .then(function (response) {
                    var data = response.data
                    $('#loadOneMin').html(data.oneMin);
                    $('#loadFiveMin').html(data.fiveMin);
                    $('#loadFifteenMin').html(data.fifteenMin);
                    $('#loadTimeDiff').html(data.updated_at);
                })
                .catch(function (error) {
                    $('#loadOneMin').html('-');
                    $('#loadFiveMin').html('-');
                    $('#loadFifteenMin').html('-');
                    $('#loadTimeDiff').html('-');
                })
        }

        function updateCurrentSsh() {
            axios.post('{{ route('currentSsh', [$server->id]) }}')
                .then(function (response) {
                    var data = response.data
                    $('#sshNumSessions').html(data.num_Sessions);
                    $('#sshTimeDiff').html(data.updated_at);
                })
                .catch(function (error) {
                    $('#sshNumSessions').html('-');
                    $('#sshTimeDiff').html('-');
                })
        }

        function updatememorySteppedAreaChart() {
            $.post('{{ route('api.memorySteppedAreaChart', [$server->id]) }}', function (dataTableJson) {
                lava.loadData('memorySteppedAreaChart', dataTableJson, function (chart) {
                });
            });
        }
    </script>
@endsection
