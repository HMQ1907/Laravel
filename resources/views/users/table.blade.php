<div id="table-user-info">
    <div id="paginate_all" class="d-flex justify-content-center">
        {{ $users->links() }}
    </div>
    <table class="table">
        <thead>
            <tr class="bg-danger text-light">
                <th scope="col">#</th>
                <th scope="col">Họ tên</th>
                <th scope="col">Email</th>
                <th scope="col">Nhóm</th>
                <th scope="col">Trạng thái</th>
                <th scope="col"></th>
            </tr>
        </thead>
        <tbody id="user-table-body">
            @php
                $index = 0;
            @endphp
            @foreach ($users as $user)
                <tr>
                    <th scope="row">{{ ++$index }}</th>
                    <td class="user_name">{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->group_role }}</td>
                    <td>
                        @if ($user->is_active == 1)
                            <span data-active="1" class="text-success"> Đang hoạt động</span>
                        @else
                            <span data-active="0" class="text-danger"> Tạm khóa</span>
                        @endif
                    </td>
                    <td style="gap: 5px;" class="d-flex flex-row">
                        <i id="edit_user_{{ $user->id }}" style="color: blue;cursor:pointer"
                            class="fa fa-pencil edit_user" aria-hidden="true"></i>
                        <i id="remove_user_{{ $user->id }}" style="color: red;cursor:pointer"
                            class="fa fa-trash remove_user" aria-hidden="true"></i>
                        <i id="block_user_{{ $user->id }}"style="color: black;cursor:pointer"
                            class="fa fa-user-times block_user" aria-hidden="true"></i>
                    </td>
                </tr>
            @endforeach
            <div id="search-results"></div>
        </tbody>
    </table>
    <div id="pagination-links"></div>
</div>
