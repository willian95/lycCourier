<head>
    <link rel="stylesheet" href="{{ public_path().'/css/bootstrap.min.css'}}" type="text/css"></link>
</head>

<div>
    <h4 class="text-center">Envíos</h4>
</div>

<table class="table">
    <thead>
        <tr>
            <th>#</th>
            <th>Tracking #</th>
            <th>Warehouse #</th>
            <th>Destinatario</th>
            <th>Dirección Destino</th>
            <th>Fecha de creación</th>
            <th>Descripción</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody style="font-size: 12px;">
        @foreach($shippings as $shipping)
            <tr>
                <td>
                    {{ $loop->index + 1 }}
                </td>
                <td>
                    {{ $shipping->tracking }}
                </td>
                <td>
                    {{ $shipping->warehouse_number }}
                </td>
                <td>
                    {{ $shipping->recipient->name }}
                </td>
                <td>
                    {{ $shipping->recipient->address }}
                </td>
                <td>
                    {{ $shipping->created_at->format('d-m-Y') }}
                </td>
                <td>
                    {{ $shipping->description }}
                </td>
                <td>
                    {{ $shipping->shippingStatus->name }}
                </td>

            </tr>
        @endforeach
    </tbody>
</table>