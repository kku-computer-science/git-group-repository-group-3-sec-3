@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Publications Summary</h1>
    <h2>Total: {{ $summary }}</h2>
    <ul>
        <li>Scopus: {{ $scopus }}</li>
        <li>Web of Science: {{ $wos }}</li>
        <li>TCI: {{ $tci }}</li>
    </ul>

    <h3>Publications by Year</h3>
    <ul>
        @foreach(json_decode($year) as $index => $singleYear)
            <li>{{ $singleYear }}: {{ json_decode($paper)[$index] }}</li>
        @endforeach
    </ul>

    <h3>Graph</h3>
    <canvas id="publicationChart"></canvas>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('publicationChart').getContext('2d');
    const publicationChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: {!! $year !!},
            datasets: [{
                label: 'Publications',
                data: {!! $paper !!},
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>
@endsection
