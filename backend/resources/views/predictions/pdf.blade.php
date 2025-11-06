<!DOCTYPE html>
<html>
<head>
    <title>Reporte de Predicciones</title>
    <style>
        table, th, td { border: 1px solid #333; border-collapse: collapse; padding: 6px; font-size: 12px; }
        th { background: #7a86a1; color: white; }
    </style>
</head>
<body>
<h2 style="text-align:center;">Reporte de Predicciones Generadas</h2>

<table width="100%">
    <thead>
        <tr>
            <th>Fecha</th>
            <th>Clima</th>
            <th>Afluencia Turística</th>
            <th>N° Reservas</th>
            <th>% Ocupación</th>
            <th>Día Festivo</th>
        </tr>
    </thead>
    <tbody>
        @foreach($predictions as $p)
        <tr>
            <td>{{ \Carbon\Carbon::parse($p->date)->format('d/m/Y') }}</td>
            <td>{{ $p->clima }}</td>
            <td>{{ $p->afluencia_turistica }}</td>
            <td>{{ $p->num_reservas }}</td>
            <td>{{ $p->porcentaje_ocupacion }}%</td>
            <td>{{ $p->dia_festivo ? 'Sí' : 'No' }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

</body>
</html>
