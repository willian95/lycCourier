<table class="table">
        <thead>
            <tr>
                <th>#</th>
                <th style="width: 30px;">Nombre</th>
                <th style="width: 30px;">Email</th>
                <th style="width: 30px;">Role</th>
            </tr>
        </thead>
        <tbody style="font-size: 12px;">
            @foreach($users as $user)
                <tr>
                    <td>
                        {{ $loop->index + 1 }}
                    </td>
                    <td>
                        {{ $user->name }}
                    </td>
                    <td>
                        {{ $user->email }}
                    </td>
                    <td>
                        {{ $user->role->name }}
                    </td>

                </tr>
            @endforeach
        </tbody>
    </table>