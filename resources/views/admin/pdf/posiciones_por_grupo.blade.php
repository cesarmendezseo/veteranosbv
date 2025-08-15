{{-- resources/views/admin/pdf/posiciones_por_grupo.blade.php --}}
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>{{ $title }}</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 4px;
            text-align: center;
        }

        th {
            background-color: #f0f0f0;
        }

        h2 {
            margin-top: 30px;
        }
    </style>
</head>

<body>
    <table style="width: 100%; margin-bottom: 20px;">
        <tr>
            <td style="width: 20%;">
                {{-- Logo del campeonato --}}
                <img src="{{ public_path('images/logo.png') }}" alt="Logo" style="height: 60px;">
            </td>
            <td style="width: 60%; text-align: center;">
                <h1 style="margin: 0;">{{ $title }}</h1>

            </td>
            <td style="width: 20%; text-align: right;">
                <p style="margin: 0;">Fecha: {{ \Carbon\Carbon::now()->format('d/m/Y') }}</p>
            </td>
        </tr>
    </table>


    @foreach ($posiciones as $grupo => $tabla)
    <h2>{{ strtoupper($grupo) }}</h2>
    <table>
        <thead>
            <tr>
                <th>Pos.</th>
                <th>Equipo</th>
                <th>PJ</th>
                <th>G</th>
                <th>E</th>
                <th>P</th>
                <th>GF</th>
                <th>GC</th>
                <th>DG</th>
                <th>FPlay</th>
                <th>PTS</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($tabla as $index => $equipo)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td style="text-align: left;">{{ strtoupper($equipo['equipo']) }}</td>
                <td>{{ $equipo['jugados'] }}</td>
                <td>{{ $equipo['ganados'] }}</td>
                <td>{{ $equipo['empatados'] }}</td>
                <td>{{ $equipo['perdidos'] }}</td>
                <td>{{ $equipo['goles_favor'] }}</td>
                <td>{{ $equipo['goles_contra'] }}</td>
                <td>{{ $equipo['diferencia_goles'] }}</td>
                <td>{{ $equipo['fair_play'] }}</td>
                <td>{{ $equipo['puntos'] }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endforeach
    <br><br><br>
    <table style="width: 100%;">
        <tr>
            <td style="width: 50%; text-align: center;">
                ___________________________<br>
                Firma del Responsable
            </td>
            <td style="width: 50%; text-align: center;">
                ___________________________<br>
                Sello del Campeonato
            </td>
        </tr>
    </table>
</body>

</html>