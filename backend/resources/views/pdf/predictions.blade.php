<h2 style="text-align:center;">Reporte de Predicciones</h2>
<br>

<table width="100%" border="1" cellspacing="0" cellpadding="6">
    <thead>
        <tr>
            <th>Fecha</th>
            <th>Clima</th>
            <th>Afluencia</th>
            <th>Reservas</th>
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
            <td>{{ number_format($p->porcentaje_ocupacion, 2) }}%</td>
            <td>{{ $p->dia_festivo ? 'Sí' : 'No' }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
