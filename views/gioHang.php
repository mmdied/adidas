<?php require_once 'layout/header.php'; ?>

<?php require_once 'layout/menu.php'; ?>

<main>
    <!-- breadcrumb area start -->
    <div class="breadcrumb-area">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="breadcrumb-wrap">
                        <nav aria-label="breadcrumb">
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="index.html"><i class="fa fa-home"></i></a></li>
                                <li class="breadcrumb-item"><a href="shop.html">shop</a></li>
                                <li class="breadcrumb-item active" aria-current="page">cart</li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- breadcrumb area end -->

    <!-- cart main wrapper start -->
    <div class="cart-main-wrapper section-padding">
        <div class="container">
            <div class="section-bg-color">
                <div class="row">
                    <div class="col-lg-12">
                        <!-- Cart Table Area -->
                        <div class="cart-table table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th class="pro-thumbnail">Ảnh sản phẩm</th>
                                        <th class="pro-title">
                                            Tên sản phẩm
                                        </th>
                                        <th class="pro-price">Giá tiền</th>
                                        <th class="pro-quantity">Số lượng</th>
                                        <th class="pro-subtotal">Tổng tiền</th>
                                        <th class="pro-remove">Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    <?php
                                    $tongGioHang = 0;
                                    foreach ($chiTietGioHang as $key => $sanPham):
                                    ?>
                                        <tr>
                                            <td class="pro-thumbnail"><a href="#"><img class="img-fluid" src="<?= BASE_URL .  $sanPham['hinh_anh'] ?>" alt="Product" /></a></td>
                                            <td class="pro-title"><a href="#"><?= $sanPham['ten_san_pham'] ?></a></td>
                                            <td class="pro-price"><span>
                                                    <?php if ($sanPham["gia_khuyen_mai"]) { ?>
                                                        <?= $sanPham["gia_khuyen_mai"] . " đ" ?></span></td>
                                        <?php } else {  ?>
                                            <?= $sanPham["gia_san_pham"] . " đ" ?></span></td>
                                        <?php } ?>
                                        <td>
                                            <div class="quantity d-flex mb-20">
                                                <button class="dec qtybutton"><i class="fa fa-angle-down"></i></button>
                                                <input type="number" class="quantity-input w-75" name="quantity[<?= $sanPham['san_pham_id'] ?>]" data-id="<?= $sanPham['id'] ?>" data-price="<?= $sanPham['gia_khuyen_mai'] ?: $sanPham['gia_san_pham'] ?>" value="<?= $sanPham['so_luong'] ?>" min="1">
                                                <button class="inc qtybutton"><i class="fa fa-angle-up"></i></button>
                                            </div>
                                        </td>
                                        <td class="pro-subtotal"><span>

                                                <?php
                                                $tong_tien = 0;
                                                if ($sanPham["gia_khuyen_mai"]) {
                                                    $tong_tien = $sanPham["gia_khuyen_mai"] * $sanPham["so_luong"];
                                                } else {
                                                    $tong_tien = $sanPham["gia_san_pham"] * $sanPham["so_luong"];
                                                }
                                                $tongGioHang += $tong_tien;
                                                echo number_format($tong_tien) . " đ";
                                                ?>

                                            </span></td>
                                        <td class="pro-remove">
                                            <form method="POST" action="<?= BASE_URL . '?act=xoa-san-pham-cart' ?>">
                                                <input type="hidden" name="_method" value="DELETE">
                                                <input type="hidden" name="gio_hang_id" value="<?= $sanPham['id'] ?>">
                                                <button type="submit"><i class="fa fa-times"></i></button>
                                            </form>
                                        </td>
                                        </tr>
                                    <?php endforeach ?>

                                </tbody>
                            </table>
                        </div>
                        <!-- Cart Update Option -->
                        <div class="cart-update-option d-block d-md-flex justify-content-between">
                            <div class="apply-coupon-wrapper">
                                <form action="#" method="post" class=" d-block d-md-flex">
                                    <input type="text" placeholder="Enter Your Coupon Code" required />
                                    <button class="btn btn-sqr">Apply Coupon</button>
                                </form>
                            </div>
                            <div class="cart-update">
                                <a href="#" class="btn btn-sqr">Update Cart</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-5 ml-auto">
                        <!-- Cart Calculation Area -->
                        <div class="cart-calculator-wrapper">
                            <div class="cart-calculate-items">
                                <h6>Tổng đơn hàng</h6>
                                <div class="table-responsive">
                                    <table class="table">
                                        <tr>
                                            <td>Tổng tiền sản phẩm</td>
                                            <td><?= number_format($tongGioHang) . "đ" ?></td>
                                        </tr>
                                        <tr>
                                            <td>Vận chuyển</td>
                                            <td><?= number_format(30000) ?></td>
                                        </tr>
                                        <tr class="total">
                                            <td>Tổng thanh toán</td>
                                            <td class="total-amount"><?= number_format($tongGioHang + 30000) ?></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            <a href="<?= BASE_URL . "?act=thanh-toan" ?>" class="btn btn-sqr d-block">Tiến hành đặt hàng</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- cart main wrapper end -->
</main>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    document.querySelectorAll('.quantity-input').forEach(function(input) {
        input.addEventListener('change', function() {
            // Lấy giá trị hiện tại của input
            let quantity = parseInt(this.value);

            // Kiểm tra nếu số lượng nhỏ hơn 1
            if (quantity < 1) {
                // Hiển thị thông báo lỗi
                alert('Số lượng phải lớn hơn hoặc bằng 1');

                // Đặt lại giá trị thành 1
                this.value = 1;
            }
        });
    });

    $(document).ready(function() {
        // Gán sự kiện chỉ một lần cho các nút tăng giảm
        $('.qtybutton').off('click').on('click', function() {
            var $button = $(this);
            var $input = $button.closest('.quantity').find('.quantity-input');
            var currentVal = parseInt($input.val());
            var price = parseFloat($input.data('price'));
            var cartId = parseInt($input.data('id'))
            // Kiểm tra nút nào đã được nhấp
            if ($button.hasClass('inc')) {
                // Tăng thêm 1
                $input.val(currentVal + 1);
                return window.location.href = '<?= BASE_URL ?>' + "?act=incQty&id=" + cartId
            } else if ($button.hasClass('dec')) {
                // Giảm đi 1, đảm bảo giá trị không giảm xuống dưới 1
                if (currentVal > 1) {
                    $input.val(currentVal - 1);
                    return window.location.href = '<?= BASE_URL ?>' + "?act=decQty&id=" + cartId
                }
            }

            // Cập nhật tổng sau khi thay đổi số lượng
            updateTotal();
        });

        $('.delete').on('click', function(e) {
            e.preventDefault();
            var productId = $(this).data('id');
            $(this).closest('.cart-item').remove(); // Xóa sản phẩm khỏi giỏ hàng
            updateTotal();
        });

        function updateTotal() {
            var tongTien = 0;
            $('.cart-item').each(function() {
                var $this = $(this);
                var $input = $this.find('.quantity-input');
                var price = parseFloat($input.data('price'));
                var quantity = parseInt($input.val());
                var totalPrice = price * quantity;
                $this.find('.total-price').text(totalPrice.toLocaleString('vi-VN') + 'đ');
                tongTien += totalPrice;
            });

            var vanChuyen = 30000; // Phí vận chuyển cố định
            $('.sub-total').text(tongTien.toLocaleString('vi-VN') + 'đ');
            $('.total-amount').text((tongTien + vanChuyen).toLocaleString('vi-VN') + 'đ');
        }
    });
</script>

<?php require_once 'layout/miniCart.php'; ?>

<?php require_once 'layout/footer.php'; ?>