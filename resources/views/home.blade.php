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

        <div class="temperature {{ $tempColor }}">
            <div class="celsius">{{ $celsius }}&thinsp;&deg;C</div>
            <div class="fahrenheit">{{ $fahrenheit }}&thinsp;&deg;F</div>
        </div>

        <div class="icons">
            @if ($icon_today)
                <div class="icon">
                    <div>Vandaag</div>
                    <img src="{{ $icon_today }}" alt="Vandaag">
                </div>
            @endif
            @if ($icon_tomorrow)
                <div class="icon">
                    <div>Morgen</div>
                    <img src="{{ $icon_tomorrow }}" alt="Morgen">
                </div>
            @endif
            @if ($icon_overmorrow)
                <div class="icon">
                    <div>Overmorgen</div>
                    <img src="{{ $icon_overmorrow }}" alt="Overmorgen">
                </div>
            @endif
        </div>

        @if (!empty($forecast) || $alarm)
            <div class="forecast">
                @if ($alarm != 0)
                    <span class="red">{{ $alarm_text }}</span><br>
                @endif
                {{ $forecast }}
            </div>
        @endif
    </div>

</div>

<div class="wrapper dark">

    <div class="container">

        <div class="weather-data">

            <div>Temperatuur</div>
            <div>
                {{ $celsius }}<span class="unit">&deg;C</span>
                &nbsp;&nbsp;&nbsp;
                {{ $fahrenheit }}<span class="unit">&deg;F</span>
            </div>

            <div>Gevoelstemperatuur</div>
            <div>
                {{ $apparent_celsius }}<span class="unit">&deg;C</span>
                &nbsp;&nbsp;&nbsp;
                {{ $apparent_fahrenheit }}<span class="unit">&deg;F</span>
            </div>

            <div>Windkracht</div>
            <div>
                {{ $wind_speed_bft }}<span class="unit">bft</span>
                &nbsp;&nbsp;&nbsp;
                {{ $wind_speed_kmh }}<span class="unit">km/u</span>
            </div>

            <div>Windrichting</div>
            <div>{{ $wind_direction }}</div>

            <div>Luchtvochtigheid</div>
            <div>{{ $humidity }}<span class="unit">%</span></div>

            <div>Dauwpunt</div>
            <div>{{ $dew_point }}<span class="unit">&deg;C</span></div>

            <div>Luchtdruk</div>
            <div>{{ $air_pressure }}<span class="unit">hPa</span></div>

            <div>Zicht</div>
            <div>{{ $sight }}<span class="unit">km</span></div>

            <div>Zonsopkomst</div>
            <div>{{ date('H:i', $sunrise) }} <span class="unit">uur</span></div>

            <div>Zonsondergang</div>
            <div>{{ date('H:i', $sunset) }} <span class="unit">uur</span></div>

            <div>Laatste update</div>
            <div>{{ date('d-m-Y H:i', strtotime($updated_at)) }}</div>

        </div>

        <div class="link">
            <a href="/history/">
<svg version="1.1" class="icon" xmlns="http://www.w3.org/2000/svg"
xmlns:xlink="http://www.w3.org/1999/xlink" x="0px"
y="0px" viewBox="0 0 512 512" xml:space="preserve"><g><g>
<path d="M460.8,25.6c-28.22-0.051-51.139,22.785-51.19,51.006c-0.024,13.301,5.14,26.088,14.394,35.642l-93.542,187.051
c-2.05-0.34-4.119-0.551-6.195-0.631c-6.61,0.013-13.154,1.312-19.268,3.823l-72.619-81.664
c4.239-7.495,6.495-15.948,6.554-24.559c0-28.277-22.923-51.2-51.2-51.2s-51.2,22.923-51.2,51.2
c0.05,12.222,4.511,24.015,12.561,33.212L60.348,384.922c-3.016-0.58-6.077-0.889-9.148-0.922C22.923,384,0,406.923,0,435.2
s22.923,51.2,51.2,51.2s51.2-22.923,51.2-51.2c-0.05-12.222-4.511-24.015-12.561-33.212l88.747-155.443
c9.527,1.882,19.399,0.872,28.348-2.901l72.619,81.664c-4.215,7.501-6.448,15.954-6.485,24.559
c-0.085,28.277,22.768,51.269,51.045,51.354c28.277,0.085,51.269-22.768,51.354-51.045c0.04-13.34-5.128-26.169-14.404-35.756
l93.542-187.051c2.05,0.34,4.119,0.551,6.195,0.631c28.277,0,51.2-22.923,51.2-51.2S489.077,25.6,460.8,25.6z M51.2,452.267
c-9.426,0-17.067-7.641-17.067-17.067s7.641-17.067,17.067-17.067s17.067,7.641,17.067,17.067S60.626,452.267,51.2,452.267z
M187.733,213.333c-9.426,0-17.067-7.641-17.067-17.067s7.641-17.067,17.067-17.067s17.067,7.641,17.067,17.067
S197.159,213.333,187.733,213.333z M324.267,366.933c-9.426,0-17.067-7.641-17.067-17.067c0-9.426,7.641-17.067,17.067-17.067
s17.067,7.641,17.067,17.067C341.333,359.292,333.692,366.933,324.267,366.933z M460.8,93.867
c-9.426,0-17.067-7.641-17.067-17.067s7.641-17.067,17.067-17.067c9.426,0,17.067,7.641,17.067,17.067
S470.226,93.867,460.8,93.867z"/>
</g></g></svg>
            </a>
        </div>

    </div>

    <div class="footer">
        <a href="https://github.com/ruudwelten/wtwt">github.com/ruudwelten/wtwt</a>
    </div>

</div>

<div class="scroll">
    <svg version="1.1" class="chevron" xmlns="http://www.w3.org/2000/svg"
        xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
         viewBox="0 0 256 256" xml:space="preserve">
        <g>
            <g>
                <polygon points="225.813,48.907 128,146.72 30.187,48.907
                0,79.093 128,207.093 256,79.093"/>
            </g>
        </g>
    </svg>
</div>

<script src="js/home.js" type="text/javascript"></script>

@endsection
