<table class="table">
        <thead>
            <tr>
                <th>#</th>
                <th style="width: 30px;">Nombre</th>
            </tr>
        </thead>
        <tbody style="font-size: 12px;">
            @foreach($boxes as $box)
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