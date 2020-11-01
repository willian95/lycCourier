<head>
    <link rel="stylesheet" href="{{ public_path().'/css/bootstrap.min.css'}}" type="text/css"></link>
</head>

<div>
    <h4 class="text-center">Destinatarios</h4>
</div>

<table class="table">
    <thead>
        <tr>
            <th>#</th>
            <th >Nombre</th>
            <th >Email</th>
            <th >Dirección</th>
            <th >Teléfono</th>
        </tr>
    </thead>
    
    <tbody style="font-size: 12px;">
        @foreach(App\Recipient::all() as $recipient)
            <tr>
                <td>
                    {{ $loop->index + 1 }}
                </td>
                <td>
                    {{ $recipient->name }}
                </td>
                <td>
                    {{ $recipient->email }}
                </td>
                <td>
                    {{ $recipient->address }}
                </td>
                <td>
                    {{ $recipient->phone }}
                </td>

            </tr>
        @endforeach
    </tbody>
</table>