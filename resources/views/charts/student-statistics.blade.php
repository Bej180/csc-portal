@php
    $results = new \App\Models\Result();
    $results = $results->groupResultsByLevelSemesterSession();
    $sessions = array_keys($results);
    $values = array_values($results);
    $data = ['RAIN' => [0], 'HARMATTAN' => [0]];

    foreach ($values as $calculations) {
        
        $data['RAIN'][] = empty($calculations['RAIN']['GPA']) ? 0 : $calculations['RAIN']['GPA'];
        $data['HARMATTAN'][] = empty($calculations['HARMATTAN']['GPA']) ? 0 : $calculations['HARMATTAN']['GPA'];
    }
    
   
@endphp

<div class="box">

    <div class="box-body">
        <div id="student-statistics"></div>
    </div>
</div>

<script src="{{ asset('js/apexchart.js') }}"></script>
<script>
    var options = {
        series: [{
                name: "Rain",
                data: {!! json_encode($data['RAIN']) !!},
            },
            {
                name: "Harmattan",
                data: {!! json_encode($data['HARMATTAN']) !!},
            }
        ],
        chart: {
            height: 350,
            type: 'line',
            dropShadow: {
                enabled: true,
                color: '#000',
                top: 18,
                left: 7,
                blur: 10,
                opacity: 0.2
            },
            toolbar: {
                show: false
            }
        },
        colors: ['#ea580c', '#10a37f'],
        dataLabels: {
            enabled: true,
        },
        stroke: {
            curve: 'smooth'
        },
        title: {
            text: 'Your performance',
            align: 'left'
        },
        grid: {
            borderColor: '#e7e7e7',
            row: {
                colors: ['transparent', 'transparent'], // takes an array which will be repeated on columns
                opacity: 0.5
            },
        },
        markers: {
            size: 1
        },
        xaxis: {
            categories: {!! json_encode(['0', ...$sessions]) !!},
            title: {
                text: 'Sessions'
            }
        },
        yaxis: {
            title: {
                text: 'Semesters'
            },
            min: 0,
            max: 5
        },
        legend: {
            position: 'top',
            horizontalAlign: 'right',
            floating: true,
            offsetY: -25,
            offsetX: -5
        }
    };

    var chart = new ApexCharts(document.querySelector("#student-statistics"), options);
    chart.render();
</script>
