<?php
class TaiKhoan
{
    public $conn;

    public function __construct()
    {
        $this->conn = connectDB();
    }
    public function checkLogin($email, $mat_khau)
    {
        try {
            $sql = "SELECT * FROM users WHERE email = :email";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute(['email' => $email]);
            $user = $stmt->fetch(); 

            // Kiểm tra thông tin người dùng
            if ($user && password_verify($mat_khau, $user['mat_khau'])) {
                if ($user['chuc_vu_id'] == 2) { // Chỉ xử lý nếu tài khoản đang hoạt động
                    if ($user["trang_thai"] == 1) {
                        return $user["email"]; // TH đăng nhập thành công
                    } else {
                        return "Tài khoản bị cấm !";
                    }
                } else {
                    return "Tài khoản không có quyền đăng nhập !";
                }
            } else {
                return "Bạn nhập sai thông tin mật khẩu hoặc tài khoản"; // Sai mật khẩu hoặc tài khoản
            }
        } catch (Exception $e) {
            echo "Lỗi: " . $e->getMessage();
            return false;
        }
    }
    public function register($ho_ten, $email, $mat_khau, $so_dien_thoai, $ngay_sinh, $gioi_tinh, $dia_chi)
    {
        try {
            $sql = "INSERT INTO users (ho_ten, email, mat_khau, so_dien_thoai, ngay_sinh, gioi_tinh, dia_chi, chuc_vu_id, trang_thai) 
                    VALUES (:ho_ten, :email, :mat_khau, :so_dien_thoai, :ngay_sinh, :gioi_tinh, :dia_chi, 2, 1)";
            $stmt = $this->conn->prepare($sql);

            $stmt->execute([
                ':ho_ten' => $ho_ten,
                ':email' => $email,
                ':mat_khau' => $mat_khau,
                ':so_dien_thoai' => $so_dien_thoai,
                ':ngay_sinh' => $ngay_sinh,
                ':gioi_tinh' => $gioi_tinh,
                ':dia_chi' => $dia_chi,
            ]);

            return true; // Đăng ký thành công
        } catch (Exception $e) {
            echo "Lỗi: " . $e->getMessage();
            return false; // Đăng ký thất bại
        }
    }
    public function getTaiKhoanFromEmail($email)
    {
        try {
            $sql = 'SELECT * FROM users WHERE email= :email';
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                ':email' => $email,
            ]);
            return $stmt->fetch();
        } catch (Exception $e) {
            echo "Lỗi" . $e->getMessage();
        }
    }
}
