<div id="paginate_all" class="d-flex justify-content-center">
    {{ $customers->links() }}
</div>
<table class="table">
    <thead>
        <tr class="bg-danger text-light">
            <th scope="col">#</th>
            <th scope="col">Họ tên</th>
            <th scope="col">Email</th>
            <th scope="col">Địa chỉ</th>
            <th scope="col">Điện thoại</th>
            <th scope="col"></th>
        </tr>
    </thead>
    <tbody id="user-table-body">
        @php
            $index = 0;
        @endphp
        @foreach ($customers as $customer)
            <tr>
                <th scope="row">{{ ++$index }}</th>
                <td class="customer_name">{{ $customer->customer_name }}</td>
                <td>{{ $customer->email }}</td>
                <td>{{ $customer->address }}</td>
                <td>{{ $customer->tel_num }}</td>
                <td>
                    <i style="cursor: pointer" class="fa fa-pencil" aria-hidden="true"></i>
                </td>
            </tr>
        @endforeach
        <div id="search-results"></div>
    </tbody>
</table>
<div id="pagination-links"></div>