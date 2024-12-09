<!-- offcanvas mini cart start -->
<?php
$chiTietGioHang = $chiTietGioHang ?? []; // Nếu chưa được khởi tạo, đặt là mảng rỗng
?>
<div class="offcanvas-minicart-wrapper">
    <div class="minicart-inner">
        <div class="offcanvas-overlay"></div>
        <div class="minicart-inner-content">
            <div class="minicart-close">
                <i class="pe-7s-close"></i>
            </div>
            <div class="minicart-content-box">
                <?php if (!empty($chiTietGioHang) && is_array($chiTietGioHang)): ?>
                    <?php
                    $tongGioHang = 0;
                    foreach ($chiTietGioHang as $key => $sanPham):
                    ?>
                        <div class="minicart-item-wrapper">
                            <ul>
                                <li class="minicart-item">
                                    <div class="minicart-thumb">
                                        <a href="#">
                                            <img src="<?= BASE_URL .  $sanPham['hinh_anh'] ?>" alt="product">
                                        </a>
                                    </div>
                                    <div class="minicart-content">
                                        <h3 class="product-name">
                                            <a href="#"><?= $sanPham['ten_san_pham'] ?></a>
                                        </h3>
                                    </div>
                                    <form method="POST" action="<?= BASE_URL . '?act=xoa-san-pham-cart' ?>">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <input type="hidden" name="gio_hang_id" value="<?= $sanPham['id'] ?>">
                                        <button type="submit"><i class="fa fa-times"></i></button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                        <?php
                        $tong_tien = 0;
                        if ($sanPham["gia_khuyen_mai"]) {
                            $tong_tien = $sanPham["gia_khuyen_mai"] * $sanPham["so_luong"];
                        } else {
                            $tong_tien = $sanPham["gia_san_pham"] * $sanPham["so_luong"];
                        }
                        $tongGioHang += $tong_tien;
                        ?>
                        <div class="minicart-pricing-box">
                            <ul>
                                <li>
                                    <td>Tổng tiền sản phẩm:</td>
                                    <td><?= number_format($tongGioHang) . "đ" ?></td>
                                </li>
                                <li>
                                    <td>Vận chuyển:</td>
                                    <td><?= number_format(30000) ?></td>
                                </li>
                                <li class="total">
                                    <td>Tổng thanh toán:</td>
                                    <td class="total-amount"><?= number_format($tongGioHang + 30000) ?></td>
                                </li>
                            </ul>
                        </div>
                        <div class="minicart-button">
                            <a href="<?= BASE_URL . '?act=gio-hang' ?>"><i class="fa fa-shopping-cart"></i> Xem giỏ hàng</a>
                            <a href="<?= BASE_URL . '?act=thanh-toan' ?>"></i>Thanh toán</a>
                        </div>
                    <?php endforeach ?>
                <?php else: ?>
                    <p>Giỏ hàng của bạn đang trống.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<!-- offcanvas mini cart end -->