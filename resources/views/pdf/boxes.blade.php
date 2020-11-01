<head>
    <link rel="stylesheet" href="{{ public_path().'/css/bootstrap.min.css'}}" type="text/css"></link>
</head>

<div>
    <h4 class="text-center">Tamaños</h4>
</div>

<table class="table">
    <thead>
        <tr>
            <th>#</th>
            <th>Nombre</th>
        </tr>
    </thead>
    <tbody style="font-size: 12px;">
        @foreach(App\Box::all() as $box)
            <tr>
                <td>
                    {{ $loop->index + 1 }}
                </td>
                <td>
                    {{ $box->name }}
                </td>

            </tr>
        @endforeach
    </tbody>
</table>