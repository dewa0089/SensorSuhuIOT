@extends('layouts.masterSecondSensor')

@section('content')
<div class="container-fluid">
    <div class="row">
      
        <div class="col-md-4">
            <div class="card">
            <div class="card-body text-center">
    <h5>Suhu Air Terbaru</h5>
    <h1 class="{{ 
    ($latestTemperature < 28) ? 'text-info' : 
    (($latestTemperature > 31) ? 'text-danger' : 'text-success') }}">
    {{ $latestTemperature ?? 'Tidak tersedia' }} °C
</h1>
                        <h4 class="text-muted">
                            ({{ isset($latestTemperature) ? round($latestTemperature * 9 / 5 + 32, 2) : 'N/A' }} °F)
                        </h4>
                        <small class="text-muted">Kategori: {{ $condition ?? 'Tidak tersedia' }}</small><br>
                        <small class="text-muted">Timestamp Terakhir: {{ $latestTimestamp ?? 'Tidak tersedia' }}</small>
                    </div>

            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body text-center">
                    <h5>Kondisi Air</h5>
                    <h1 class="{{ 
    ($description == 'Dingin') ? 'text-info' : 
    (($description == 'Panas') ? 'text-danger' : 'text-success') }}">
    {{ $description ?? 'Tidak tersedia' }}
</h1>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body text-center">
                    <h5>Keterangan</h5>
                    <h1 class="text-primary">
                    {{ ($latestTemperature > 31 || $latestTemperature < 28) ? 'Air Harus Diganti' : 'Air Tidak Perlu Diganti' }}
                    </h1>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-3">
       
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Tingkat Suhu Air</h5>
                    <canvas id="temperatureThermometer"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Riwayat Pengecekan Suhu</h5>
                        <div style="max-height: 400px; overflow-y: auto;">
                            <table class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>Waktu</th>
                                        <th>Suhu (°C)</th>
                                        <th>Suhu (°F)</th>
                                        <th>Kondisi</th>
                                    </tr>
                                </thead>
                                <tbody>
@foreach(collect($history)->sortByDesc('time') as $record)
<tr>
    <td>{{ isset($record['time']) ? \Carbon\Carbon::parse($record['time'])->format('d-m-Y H:i:s') : 'N/A' }}</td>
    <td>{{ $record['temperature'] ?? 'N/A' }} °C</td>
    <td>{{ isset($record['temperature']) ? round($record['temperature'] * 9 / 5 + 32, 2) : 'N/A' }} °F</td>
    <td>{{ $record['condition'] ?? 'Tidak tersedia' }}</td>
</tr>
@endforeach
</tbody>

                            </table>
                        </div>
                    </div>
                </div>
            </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>
<script>
    
    const currentTemperature = {{ $latestTemperature ?? 0 }};
    const historyData = @json($history ?? []);
    const historyLabels = historyData.map(data => data.time ?? 'N/A');
    const historyValues = historyData.map(data => data.temperature ?? 0);

    
    const ctxThermometer = document.getElementById('temperatureThermometer').getContext('2d');
    const thermometerChart = new Chart(ctxThermometer, {
        type: 'bar',
        data: {
            labels: [''],
            datasets: [{
                label: 'Suhu (°C)',
                data: [currentTemperature],
                backgroundColor: currentTemperature > 31 ? '#dc3545' : (currentTemperature < 28 ? '#007bff' : '#28a745'),
                borderColor: '#000',
                borderWidth: 1,
            }]
        },
        options: {
            responsive: true,
            scales: {
                x: {
                    display: false,
                },
                y: {
                    min: 0,
                    max: 100,
                    ticks: {
                        stepSize: 10,
                        callback: function(value) {
                            return value + '°C';
                        }
                    }
                }
            },
            plugins: {
                legend: { display: false },
                datalabels: {
                    display: true,
                    align: 'end',
                    color: '#000',
                    formatter: function(value) {
                        return value + '°C';
                    }
                }
            }
        },
        plugins: [ChartDataLabels]
    });

    const ctxHistory = document.getElementById('temperatureHistory').getContext('2d');
        const temperatureHistory = new Chart(ctxHistory, {
            type: 'line',
            data: {
                labels: historyLabels, 
                datasets: [{
                    label: 'Temperature (°C)',
                    data: historyValues, 
                    borderColor: '#007bff',
                    fill: false,
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: true
                    },
                    zoom: {
                        pan: {
                            enabled: true, 
                            mode: 'x', 
                        },
                        zoom: {
                            wheel: {
                                enabled: true, 
                            },
                            pinch: {
                                enabled: true, 
                            },
                            limits: {
                                x: {
                                    min: 5,
                                    max: 500
                                }, 
                            },
                            mode: 'x', 
                        },
                    }
                },
                scales: {
                    x: {
                        type: 'category',
                        ticks: {
                            maxRotation: 45,
                            minRotation: 0,
                        },
                    },
                    y: {
                        ticks: {
                            stepSize: 5,
                        },
                    },
                },
            }
        });
</script>
@endsection
