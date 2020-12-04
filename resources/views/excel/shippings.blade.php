<table class="table">
    <thead>
        <tr>
            <th>#</th>
            <th style="width: 30px;">Tracking #</th>
            <th style="width: 30px;">Warehouse #</th>
            <th style="width: 30px;">Destinatario</th>
            <th style="width: 30px;">Dirección Destino</th>
            <th style="width: 30px;">Fecha de creación</th>
            <th style="width: 30px;">Descripción</th>
            <th style="width: 30px;">Status</th>
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
                    {{ $shipping->address }}
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