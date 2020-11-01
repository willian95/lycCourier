<table class="table">
        <thead>
            <tr>
                <th>#</th>
                <th style="width: 30px;">Nombre</th>
                <th style="width: 30px;">Email</th>
                <th style="width: 30px;">Dirección</th>
                <th style="width: 30px;">Teléfono</th>
            </tr>
        </thead>
        <tbody style="font-size: 12px;">
            @foreach($recipients as $recipient)
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