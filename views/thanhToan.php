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
                                <li class="breadcrumb-item"><a href="<?= BASE_URL ?>"><i class="fa fa-home"></i></a></li>
                                <li class="breadcrumb-item"><a href="shop.html">shop</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Thanh toán</li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- breadcrumb area end -->

    <!-- checkout main wrapper start -->
    <div class="checkout-page-wrapper section-padding">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <!-- Checkout Login Coupon Accordion Start -->
                    <div class="checkoutaccordion" id="checkOutAccordion">
                        <div class="card">
                            <h6>Thêm mã giảm giá <span data-bs-toggle="collapse" data-bs-target="#couponaccordion">Click
                                    Nhập mã giảm giá</span></h6>
                            <div id="couponaccordion" class="collapse" data-parent="#checkOutAccordion">
                                <div class="card-body">
                                    <div class="cart-update-option">
                                        <div class="apply-coupon-wrapper">
                                            <form action="#" method="post" class=" d-block d-md-flex">
                                                <input type="text" placeholder="Nhập mã giảm giá" required />
                                                <button class="btn btn-sqr">Áp dụng mã giảm giá</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Checkout Login Coupon Accordion End -->
                </div>
            </div>
            <form action="<?= BASE_URL . "?act=xu-ly-thanh-toan" ?>" method="POST">
                <div class="row">
                    <!-- Checkout Billing Details -->
                    <div class="col-lg-5">
                        <div class="checkout-billing-details-wrap">
                            <h5 class="checkout-title">Thông tin người nhận</h5>
                            <div class="billing-form-wrap">

                                <div class="single-input-item">
                                    <label for="ten_nguoi_nhan" class="required">Tên người nhận</label>
                                    <input type="text" id="ten_nguoi_nhan" name="ten_nguoi_nhan" value="<?= $user["ho_ten"] ?>" placeholder="Nhập tên người nhận" required />
                                </div>

                                <div class="single-input-item">
                                    <label for="email_nguoi_nhan" class="required">Email người nhận</label>
                                    <input type="email" id="email_nguoi_nhan" name="email_nguoi_nhan" value="<?= $user["email"] ?>" required />
                                </div>
                                <div class="single-input-item">
                                    <label for="dia_chi" class="required">Địa chỉ người nhận</label>
                                    <input type="text" id="dia_chi" name="dia_chi_nguoi_nhan" value="<?= $user["dia_chi"] ?>" required />
                                </div>

                                <div class="single-input-item">
                                    <label for="so_dien_thoai" class="required">Số điện thoại người nhận</label>
                                    <input type="number" id="sdt_nguoi_nhan" name="sdt_nguoi_nhan" value="<?= $user["so_dien_thoai"] ?>" required />
                                </div>

                                <div class="single-input-item">
                                    <label for="ghi_chu">Ghi chú</label>
                                    <textarea name="ghi_chu" id="ghi_chu" name="ghi_chu" cols="30" rows="3" placeholder="Nhập ghi chú đơn hàng"></textarea>
                                </div>


                            </div>
                        </div>
                    </div>

                    <!-- Order Summary Details -->
                    <div class="col-lg-7">
                        <div class="order-summary-details">
                            <h5 class="checkout-title">Thông tin đặt hàng</h5>
                            <div class="order-summary-content">
                                <!-- Order Summary Table -->
                                <div class="order-summary-table table-responsive text-center">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Sản phẩm</th>
                                                <th>Tổng</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $tongGioHang = 0;
                                            foreach ($chiTietGioHang as $key => $sanPham):
                                            ?>
                                                <tr>
                                                    <td>
                                                        <input type="hidden" name="san_pham_id[]" value="<?= $sanPham['san_pham_id'] ?>">

                                                        <a href="">
                                                            <?= $sanPham["ten_san_pham"] ?> <strong> × <?= $sanPham["so_luong"] ?></strong>
                                                        </a>
                                                    </td>
                                                    <td>
                                                        <?php
                                                        $tong_tien = 0;
                                                        if ($sanPham["gia_khuyen_mai"]) {
                                                            $tong_tien = $sanPham["gia_khuyen_mai"] * $sanPham["so_luong"];
                                                            echo '<input type="hidden" name="prices[]" value="' . $tong_tien . '">';
                                                        } else {
                                                            $tong_tien = $sanPham["gia_san_pham"] * $sanPham["so_luong"];
                                                            echo '<input type="hidden" name="prices[]" value="' . $tong_tien . '">';
                                                        }
                                                        $tongGioHang += $tong_tien;
                                                        echo $tong_tien . " đ";
                                                        ?>
                                                        <input type="hidden" name="total_amounts[]" value="<?= $tong_tien ?>">
                                                        <input type="hidden" name="quantities[]" value="<?= $sanPham['so_luong'] ?>">

                                                    </td>
                                                </tr>
                                            <?php endforeach ?>

                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td>Tổng tiền sản phẩm</td>

                                                <td><strong><?= $tongGioHang . " đ" ?></strong></td>
                                            </tr>
                                            <tr>
                                                <td>Phí vận chuyển</td>
                                                <td class="d-flex justify-content-center">
                                                    <strong>30000 đ</strong>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Tổng đơn hàng</td>
                                                <input type="hidden" name="tong_tien" value="<?= $tongGioHang + 30000 ?>">
                                                <td><strong><?= $tongGioHang + 30000 . " đ" ?></strong></td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                                <!-- Order Payment Method -->
                                <div class="order-payment-method">
                                    <div class="single-payment-method show">
                                        <div class="payment-method-name">
                                            <div class="custom-control custom-radio">
                                                <input type="radio" id="cashon" name="phuong_thuc_thanh_toan_id" value="1" class="custom-control-input" checked />
                                                <label class="custom-control-label" for="cashon">Thanh toán khi nhận hàng</label>
                                            </div>
                                        </div>
                                        <div class="payment-method-details" data-method="cash">
                                            <p>Khách hàng thanh toán sau khi đã nhận hàng thành công.</p>
                                        </div>
                                    </div>
                                    <div class="single-payment-method">
                                        <div class="payment-method-name">
                                            <div class="custom-control custom-radio">
                                                <input type="radio" id="directbank" name="phuong_thuc_thanh_toan_id" value="2" class="custom-control-input" />
                                                <label class="custom-control-label" for="directbank">Thanh toán online</label>
                                            </div>
                                        </div>
                                        <div class="payment-method-details" data-method="bank">
                                            <p>Khách hàng cần thanh toán online</p>
                                        </div>
                                    </div>


                                    <div class="summary-footer-area">
                                        <div class="custom-control custom-checkbox mb-20">
                                            <input type="checkbox" class="custom-control-input" id="terms" required />
                                            <label class="custom-control-label" for="terms">Xác nhận đặt hàng</label>
                                        </div>
                                        <button type="submit" class="btn btn-sqr">Tiến hành đặt hàng</button>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!-- checkout main wrapper end -->
</main>


<?php require_once 'layout/miniCart.php'; ?>

<?php require_once 'layout/footer.php'; ?>