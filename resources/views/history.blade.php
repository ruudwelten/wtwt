@extends('default')


@section('content')

<div class="wrapper">

    <div class="container">
        <div class="header">
            <div>
                <h1>WTWT</h1>
                What's The Weather Today?
            </div>
        </div>

        <div class="link">
            <a href="/">
<svg version="1.1" class="icon" xmlns="http://www.w3.org/2000/svg"
    xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
     viewBox="0 0 512 512" xml:space="preserve"><g><g>
<path d="M503.401,228.884l-43.253-39.411V58.79c0-8.315-6.741-15.057-15.057-15.057H340.976c-8.315,0-15.057,6.741-15.057,15.057
    v8.374l-52.236-47.597c-10.083-9.189-25.288-9.188-35.367-0.001L8.598,228.885c-8.076,7.36-10.745,18.7-6.799,28.889
    c3.947,10.189,13.557,16.772,24.484,16.772h36.689v209.721c0,8.315,6.741,15.057,15.057,15.057h125.913
    c8.315,0,15.057-6.741,15.057-15.057V356.931H293v127.337c0,8.315,6.741,15.057,15.057,15.057h125.908
    c8.315,0,15.057-6.741,15.056-15.057V274.547h36.697c10.926,0,20.537-6.584,24.484-16.772
    C514.147,247.585,511.479,236.245,503.401,228.884z M433.965,244.433c-8.315,0-15.057,6.741-15.057,15.057v209.721h-95.793
    V341.874c0-8.315-6.742-15.057-15.057-15.057H203.942c-8.315,0-15.057,6.741-15.057,15.057v127.337h-95.8V259.49
    c0-8.315-6.741-15.057-15.057-15.057H36.245l219.756-200.24l74.836,68.191c4.408,4.016,10.771,5.051,16.224,2.644
    c5.454-2.41,8.973-7.812,8.973-13.774V73.847h74.002v122.276c0,4.237,1.784,8.276,4.916,11.13l40.803,37.18H433.965z"/>
</g></g></svg>
            </a>
        </div>

        <div class="chart">
            <h2>Afgelopen 24 uur</h2>
            <canvas id="dayChart" width="800" height="400"></canvas>
        </div>

        <div class="chart">
            <h2>Afgelopen week</h2>
            <canvas id="weekChart" width="800" height="400"></canvas>
        </div>

        <div class="chart">
            <h2>Afgelopen maand</h2>
            <canvas id="monthChart" width="800" height="400"></canvas>
        </div>

        <div class="chart">
            <h2>Afgelopen jaar</h2>
            <canvas id="yearChart" width="800" height="400"></canvas>
        </div>
    </div>

    <div class="footer">
        <a href="https://github.com/ruudwelten/wtwt">github.com/ruudwelten/wtwt</a>
    </div>

</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.27.0/moment.min.js" integrity="sha512-rmZcZsyhe0/MAjquhTgiUcb4d9knaFc7b5xAfju483gbEXTkeJRUMIPk6s3ySZMYUHEcjKbjLjyddGWMrNEvZg==" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.27.0/locale/nl.min.js" integrity="sha512-W5okC/3IpAsagiVtKQLJhPGr5nhEtDJJtY1FFzp89O6YbbCPSvamzJkE8S9BWw0BCGFB8FXot1KuyHr8IVkcJQ==" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.min.js" integrity="sha512-s+xg36jbIujB2S2VKfpGmlC3T5V2TF3lY48DX7u2r9XzGzgPsa6wTpOQA7J9iffvdeBN0q9tKzRxVxw1JviZPg==" crossorigin="anonymous"></script>
<script src="/js/history.js"></script>
<script>
    var dayChart = initializeChartSingle('dayChart', 'hour', 'Afgelopen 24 uur', @json($dayDataset));
    var weekChart = initializeChartHighLow('weekChart', 'day', 'Afgelopen week', @json($weekDataset['high']), @json($weekDataset['low']));
    var monthChart = initializeChartHighLow('monthChart', 'week', 'Afgelopen maand', @json($monthDataset['high']), @json($monthDataset['low']));
    var yearChart = initializeChartHighLow('yearChart', 'month', 'Afgelopen jaar', @json($yearDataset['high']), @json($yearDataset['low']));
</script>

@endsection
