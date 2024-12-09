
<?php

class HomeController
{
    public $modelSanPham;
    public $modelDanhMuc;
    public $modelDonHang;
    public $modelTaiKhoan;
    public $modelGioHang;
    // public $modelDangKy;
    public function __construct()
    {
        $this->modelSanPham = new SanPham();
        $this->modelTaiKhoan = new TaiKhoan();
        $this->modelDanhMuc = new DanhMuc();
        $this->modelDonHang = new DonHang();
        $this->modelGioHang = new GioHang();
    }

    public function sanPham()
    {
        // Lấy danh sách danh mục
        $danhMuc = $this->modelDanhMuc->getAllDanhMuc();

        // Lấy danh sách sản phẩm từ model SanPham
        $listSanPham = $this->modelSanPham->getAllSanPham();

        // Truyền vào view
        require_once './views/sanPham.php';
    }


    public function home()
    {
        $listSanPham = $this->modelSanPham->getAllSanPham();
        require_once './views/home.php';
    }

    public function chiTietSanPham()
    {
        $id = $_GET['id_san_pham'];

        $sanPham = $this->modelSanPham->getDetailSanPham($id);

        $listAnhSanPham = $this->modelSanPham->getListAnhSanPham($id);

        $listBinhLuan = $this->modelSanPham->getBinhLuanFromSanPham($id);

        $listSanPhamCungDanhMuc = $this->modelSanPham->getListSanPhamDanhMuc($sanPham['danh_muc_id']);
        // var_dump($listAnhSanPham); die();
        if ($sanPham) {
            require_once './views/detailSanPham.php';
        } else {
            header("Location:" . BASE_URL);
            exit();
        }
    }

    public function formLogin()
    {
        require_once "./views/auth/formLogin.php";

        deleteSessionError();
    }

    public function postLogin()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $email = $_POST['email'];
            $password = $_POST['password'];

            $user = $this->modelTaiKhoan->checkLogin($email, $password);


            if ($user == $email) { // TH đăng nhập thành công
                // Lưu thông tin vào session
                $_SESSION["user_client"] = $user;
                header("Location: " . BASE_URL);
                exit();
            } else {
                // Lỗi thì lưu lỗi vào SESSION
                $_SESSION["error"] = $user;
                // var_dump($_SESSION['error']);die();
                $_SESSION["flash"] = true;
                header("Location: " . BASE_URL . "?act=login");
                exit();
            }
        }
    }




    // Hiển thị form đăng ký
    public function formRegister()
    {
        require_once "./views/auth/formRegister.php";
        deleteSessionError(); // Xóa lỗi nếu có
    }

    // Xử lý dữ liệu đăng ký
    public function postRegister()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Lấy dữ liệu từ form
            $ho_ten = $_POST['ho_ten'];
            $email = $_POST['email'];
            $password = $_POST['password'];
            $confirm_password = $_POST['confirm_password'];
            $so_dien_thoai = $_POST['so_dien_thoai']; 
            $ngay_sinh = $_POST['ngay_sinh'];
            $gioi_tinh = $_POST['gioi_tinh'];
            $dia_chi = $_POST['dia_chi'];
            if ($password !== $confirm_password) {
                $_SESSION['error'] = "Mật khẩu không khớp.";
                header("Location: " . BASE_URL . "?act=register");
                exit();
            }
            // Mã hóa mật khẩu
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Kiểm tra email đã tồn tại
            $existingUser = $this->modelTaiKhoan->getTaiKhoanfromEmail($email);
            if ($existingUser) {
                $_SESSION['error'] = "Email đã được sử dụng.";
                header("Location: " . BASE_URL . "?act=register");
                exit();
            }

            // Thêm tài khoản mới
            $result = $this->modelTaiKhoan->register($ho_ten, $email, $hashed_password, $so_dien_thoai, $ngay_sinh, $gioi_tinh, $dia_chi);

            if ($result) {
                echo "<script>
                  alert('Đăng kí thành công, mời bạn đăng nhập');
                  window.location.href = '" . BASE_URL . "?act=login';
              </script>";
                exit();
            } else {
                echo "<script>
                  alert('Đã có lỗi xảy ra, vui lòng thử lại.');
                window.location.href = '" . BASE_URL . "?act=register';
              </script>";
                exit();
            }
        }
    }
    public function logout()
    {
        // Xóa thông tin người dùng khỏi session
        unset($_SESSION['user_client']);
        session_destroy();

        // Điều hướng về trang đăng nhập hoặc trang chủ
        header("Location: " . BASE_URL); // Hoặc BASE_URL nếu bạn muốn về trang chủ
        exit();
    }



    public function addGioHang()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (isset($_SESSION['user_client'])) {
                $email = $this->modelTaiKhoan->getTaiKhoanFromEmail($_SESSION["user_client"]);
                $gioHang = $this->modelGioHang->getGioHangFromUser($email['id']);

                if (!$gioHang) {
                    $gioHangId = $this->modelGioHang->addGioHang($email['id']);
                    $gioHang = ['id' => $gioHangId];
                    $chiTietGioHang = $this->modelGioHang->getDetailGioHang($gioHang['id']);
                } else {
                    $chiTietGioHang = $this->modelGioHang->getDetailGioHang($gioHang['id']);
                }

                $san_pham_id = $_POST['san_pham_id'];

                $so_luong = $_POST['so_luong'];

                $checkSanPham = false;

                foreach ($chiTietGioHang as $detail) {
                    if ($detail['san_pham_id'] == $san_pham_id) {
                        $newSoLuong = $detail['so_luong'] + $so_luong;
                        $this->modelGioHang->updateSoLuong($gioHang['id'], $san_pham_id, $newSoLuong);
                        $checkSanPham = true;
                        break;
                    }
                }
                if (!$checkSanPham) {
                    $this->modelGioHang->addDetailGioHang($gioHang['id'], $san_pham_id, $so_luong);
                }
                header("Location:" . BASE_URL . '?act=gio-hang');
            } else {
                var_dump('Chua Dang Nhap');
                die;
            }
        }
    }

    public function gioHang()
    {
        $listDanhMuc = $this->modelSanPham->getAllDanhMucClient();
        if (isset($_SESSION['user_client'])) {
            $mail = $this->modelTaiKhoan->getTaiKhoanFromEmail($_SESSION['user_client']);
            // var_dump($mail['id']);
            $gioHang = $this->modelGioHang->getGioHangFromUser($mail['id']);

            if (!$gioHang) {
                $gioHangId = $this->modelGioHang->addGioHang($mail['id']);
                $gioHang = ['id' => $gioHangId];
                $chiTietGioHang = $this->modelGioHang->getDetailGioHang($gioHang['id']);
            } else {
                $chiTietGioHang = $this->modelGioHang->getDetailGioHang($gioHang['id']);
            }

            require_once './views/gioHang.php';
        } else {
            header("Location: " . BASE_URL . '?act=login');
            die;
        }
    }
    public function thanhToan()
    { {
            if (isset($_SESSION['user_client'])) {
                $user = $this->modelTaiKhoan->getTaiKhoanFromEmail($_SESSION['user_client']);
                // var_dump($mail['id']);
                $gioHang = $this->modelGioHang->getGioHangFromUser($user['id']);

                if (!$gioHang) {
                    $gioHangId = $this->modelGioHang->addGioHang($user['id']);
                    $gioHang = ['id' => $gioHangId];
                }
                $chiTietGioHang = $this->modelGioHang->getDetailGioHang($gioHang['id']);
                require_once './views/thanhToan.php';
            } else {
                header("Location: " . BASE_URL . '?act=login');
                exit;
            }
        }
        require_once './views/thanhToan.php';
    }

    public function postThanhToan()
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
        //       echo '<pre>';
        // var_dump($_POST);
        // echo '</pre>'; die;
            // Lấy thông tin từ form
            $ten_nguoi_nhan = $_POST["ten_nguoi_nhan"];
            $email_nguoi_nhan = $_POST["email_nguoi_nhan"];
            $sdt_nguoi_nhan = $_POST["sdt_nguoi_nhan"];
            $dia_chi_nguoi_nhan = $_POST["dia_chi_nguoi_nhan"];
            $ghi_chu = $_POST["ghi_chu"];
            $tong_tien = $_POST["tong_tien"];
            $phuong_thuc_thanh_toan_id = $_POST["phuong_thuc_thanh_toan_id"]; // phuong_thuc_thanh_toan_id

            $ngay_dat = date('Y-m-d');
            $trang_thai_id = 1; // Trạng thái đơn hàng = 1 (chưa xác nhận)

            // Lấy thông tin người dùng
            $user = $this->modelTaiKhoan->getTaiKhoanFromEmail($_SESSION["user_client"]);
            $tai_khoan_id = $user['id']; // Sử dụng $mail thay vì $user
            $ma_don_hang = "DH-" . rand(1000, 9999);

            // Thêm đơn hàng vào DB
            $don_hang_id = $this->modelDonHang->addDonHang(
                $tai_khoan_id,
                $ten_nguoi_nhan,
                $sdt_nguoi_nhan,
                $email_nguoi_nhan,
                $dia_chi_nguoi_nhan,
                $ghi_chu,
                $tong_tien,
                $phuong_thuc_thanh_toan_id,
                $ngay_dat,
                $trang_thai_id,
                $ma_don_hang
            );

            // Thêm chi tiết đơn hàng vào DB
            if (!empty($_POST['san_pham_id']) && is_array($_POST['san_pham_id'])) {
                foreach ($_POST['san_pham_id'] as $key => $san_pham_id) {
                    $this->modelDonHang->addDetailOrder(
                        $san_pham_id,
                        $don_hang_id,
                        $_POST['prices'][$key],
                        $_POST['quantities'][$key],
                        $_POST['total_amounts'][$key]
                    );

                    // Xóa sản phẩm trong giỏ hàng đã được đặt
                    $this->modelDonHang->deleteCartBought(
                        $san_pham_id,
                        $this->modelDonHang->getCartIdByUser($tai_khoan_id)
                    );
                }
            } else {
                echo "Không có sản phẩm nào được chọn.";
            }
            // Hiển thị thông báo và chuyển hướng
            echo '<script>alert("Đặt hàng thành công")</script>';
            echo '<script>window.location.href = "?act=/";</script>';
        }
    }



    public function deleteOneGioHang()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['_method'] == 'DELETE') {
            $gio_hang_id = $_POST['gio_hang_id'];
            $this->modelGioHang->deleteChiTietGioHang($gio_hang_id);
            header('Location: ' . BASE_URL . '?act=gio-hang');
            exit();
        }
    }

    // public function timKiem() {
    //     $danh_muc_id = $_GET['danh_muc_id'] ?? null;

    //     // Kiểm tra nếu có danh mục
    //     if ($danh_muc_id) {
    //         // Lấy sản phẩm thuộc danh mục
    //         $sanPhamTheoDanhMuc = $this->modelSanPham->getSanPhamByDanhMuc($danh_muc_id);
    //     } else {
    //         // Nếu không có danh mục thì lấy tất cả sản phẩm
    //         $sanPhamTheoDanhMuc = $this->modelSanPham->getAllSanPham();
    //     }

    //     // Gửi dữ liệu ra view
    //     $this->view('sanPham', ['sanPhamTheoDanhMuc' => $sanPhamTheoDanhMuc]);
    // }

    // }
    public function incQtyCart()
    {
        // die(123);
        $this->modelGioHang->updateIncQty($_GET['id']);
        header('Location: ' . BASE_URL . '?act=gio-hang');
        exit();
    }
    public function decQtyCart()
    {
        $this->modelGioHang->updateDecQty($_GET['id']);
        header('Location: ' . BASE_URL . '?act=gio-hang');
        exit();
    }


}
