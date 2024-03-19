<div id="table-cus-info">
    @if ($customers->total() != 0)
        <div class="text-dark total_customer">Hiển thị {{ $customers->firstItem() }} đến {{ $customers->lastItem() }} trong
            tổng số {{ $customers->total() }}
        </div>
    @endif
    <div class="pagination" id="paginate_all" class="d-flex justify-content-center">
        {{ $customers->withQueryString()->links() }}
    </div>
    <table class="table">
        <thead>
            <tr class="bg-danger text-light">
                <th scope="col">ID</th>
                <th scope="col">Họ tên</th>
                <th scope="col">Email</th>
                <th scope="col">Địa chỉ</th>
                <th scope="col">Điện thoại</th>
                <th scope="col"></th>
            </tr>
        </thead>
        <tbody id="customer-table-body">
            @php
                $index = 0;
            @endphp
            @foreach ($customers as $customer)
                <tr>
                    <th scope="row">{{ $customer->customer_id }}</th>
                    <td class="customer_name">{{ $customer->customer_name }}</td>
                    <td class="customer_email">{{ $customer->email }}</td>
                    <td class="customer_address">{{ $customer->address }}</td>
                    <td class="customer_tel">{{ $customer->tel_num }}</td>
                    <td>
                        <i style="cursor: pointer" class="fa fa-trash-o delete-customer mr-5" aria-hidden="true" data-customer-id={{ $customer->customer_id }}></i>
                        <i style="cursor: pointer" class="fa fa-pencil edit-customer" aria-hidden="true"></i>
                        <button class="btn btn-primary update-customer-btn ml-2"
                            data-customer-id={{ $customer->customer_id }} style="display: none;">Cập nhật</button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="pagination" id="paginate_all" class="d-flex justify-content-center">
        {{ $customers->withQueryString()->links() }}
    </div>

</div>
