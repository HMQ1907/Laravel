<div id="table-product-info">
    @if ($products->total() != 0)
        <div class="text-dark total_user">Hiển thị {{ $products->firstItem() }} đến {{ $products->lastItem() }} trong
            tổng số {{ $products->total() }}
        </div>
    @endif
    <div class="pagination" id="paginate_all" class="d-flex justify-content-center">
        {{ $products->withQueryString()->links() }}
    </div>
    <table class="table">
        <thead>
            <tr class="bg-danger text-light">
                <th scope="col">#</th>
                <th scope="col">Tên sản phẩm</th>
                <th scope="col">Mô tả</th>
                <th scope="col">Giá</th>
                <th scope="col">Tình trạng</th>
                <th></th>
            </tr>
        </thead>
        <tbody id="product-table-body">
            @foreach ($products as $product)
                <tr>
                    <th scope="row">{{ $product->product_id }}</th>
                    <td class="product_name">{{ $product->product_name }}
                        <span class="text-muted product_img d-none">qwe</span>
                    </td>
                    
                    <td style="width: 40%" class="product_desc">{!! $product->description !!}</td>
                    <td class="product_price">${{ number_format($product->product_price) }}</td>
                    <td class="product_stt">
                        @if ($product->is_active == 1)
                            <span class="text-success">Đang bán</span>
                        @else
                            <span class="text-danger">Ngưng bán</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('product.edit', ['id' => $product->product_id]) }}">
                            <i data-product-id="{{ $product->product_id }}" style="cursor: pointer" class="fa fa-pencil edit-product" aria-hidden="true"></i>
                        </a>                        
                        <i style="cursor: pointer" class="fa fa-trash-o delete-product ml-2" aria-hidden="true"
                            data-product-id={{ $product->product_id }}></i>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="pagination" id="paginate_all" class="d-flex justify-content-center">
        {{ $products->withQueryString()->links() }}
    </div>

</div>
