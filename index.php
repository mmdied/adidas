
<?php 
session_start();
// Require file Common
require_once './commons/env.php'; // Khai báo biến môi trường
require_once './commons/function.php'; // Hàm hỗ trợ

// Require toàn bộ file Controllers
require_once './controllers/HomeController.php';

// Require toàn bộ file Models
require_once './models/SanPham.php'; 
require_once './models/TaiKhoan.php'; 
require_once './models/GioHang.php';
require_once './models/DanhMuc.php';
require_once './models/DonHang.php';



// Route
$act = $_GET['act'] ?? '/';

// Để bảo bảo tính chất chỉ gọi 1 hàm Controller để xử lý request thì mình sử dụng match

match ($act) {
    // Trang chủ
    '/' => (new HomeController())->home(),

    // Chi tiết sản phẩm
    'chi-tiet-san-pham' => (new HomeController())->chiTietSanPham(),

    // Đăng nhập
    'login' => (new HomeController())->formLogin(),
    'check-login' => (new HomeController())->postLogin(),

    // Đăng ký
   'register' => $_SERVER['REQUEST_METHOD'] === 'POST' 
    ? (new HomeController())->postRegister()
    : (new HomeController())->formRegister(),
    'logout' => (new HomeController())->logout(),
    
    'them-gio-hang' => (new HomeController())->addGioHang(),
    'gio-hang' => (new HomeController())->gioHang(),
    'san-pham' => (new HomeController())->sanPham(),
   
    'xoa-san-pham-cart' => (new HomeController())->deleteOneGioHang(),  

    'thanh-toan' => (new HomeController())->thanhToan(),
    'xu-ly-thanh-toan' =>(new HomeController())->postThanhToan(),
    // 'tim-kiem' => (new HomeController())->timKiem(),
    'incQty' => (new HomeController())->incQtyCart(),
    'decQty' => (new HomeController())->decQtyCart(),

};
