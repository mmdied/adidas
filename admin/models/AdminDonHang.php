<?php
class AdminDonHang
{
    public $conn;

    public function __construct()
    {
        $this->conn = connectDB();
    }
    public function getAllDonHang()
    {
        try {
            $sql = 'SELECT orders.*, order_status.ten_trang_thai
            FROM orders 
            INNER JOIN order_status 
            ON orders.trang_thai_id = order_status.id
            ORDER BY orders.ngay_dat DESC';
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchALL();
        } catch (Exception $e) {
            echo "Lỗi" . $e->getMessage();
        }
    }
    public function getDetailDonHang($id)
    {
        try {
            $sql = "SELECT orders.*, 
                        order_status.ten_trang_thai, 
                        users.ho_ten, 
                        users.email, 
                        users.so_dien_thoai,
                        payment_method.ten_phuong_thuc
                        FROM orders 
                        INNER JOIN order_status ON orders.trang_thai_id = order_status.id 
                        INNER JOIN users ON orders.tai_khoan_id = users.id 
                        INNER JOIN payment_method ON orders.phuong_thuc_thanh_toan_id = payment_method.id 
                        WHERE orders.id = :id";

            $stmt = $this->conn->prepare($sql);

            $stmt->execute([':id' => $id]);
            return $stmt->fetch();
        } catch (Exception $e) {
            echo "Loi" . $e->getMessage();
        }
    }

    public function getListSpDonHang($id)
    {
        try {
            $sql = "SELECT order_details.*, products.ten_san_pham
                            FROM order_details
                            INNER JOIN products ON order_details.san_pham_id = products.id
                            WHERE order_details.don_hang_id = :id";

            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT); // Ràng buộc kiểu dữ liệu

            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC); // Sử dụng chế độ FETCH_ASSOC

        } catch (Exception $e) {
            error_log("Lỗi khi lấy danh sách sản phẩm trong đơn hàng: " . $e->getMessage()); // Ghi lỗi vào log
            echo "Có lỗi xảy ra, vui lòng thử lại sau.";
        }
    }
    public function getAllTrangThaiDonHang()
    {
        try {
            $sql = 'SELECT * FROM order_status';
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchALL();
        } catch (Exception $e) {
            echo "Lỗi" . $e->getMessage();
        }
    }


    public function updateDonHang(
        $id,
        $ten_nguoi_nhan,
        $sdt_nguoi_nhan,
        $email_nguoi_nhan,
        $dia_chi_nguoi_nhan,
        $ghi_chu,
        $trang_thai_id
    ) {
        try {
            $sql = "UPDATE orders
                        SET 
                        ten_nguoi_nhan = :ten_nguoi_nhan,
                        sdt_nguoi_nhan = :sdt_nguoi_nhan,
                        email_nguoi_nhan = :email_nguoi_nhan,
                        dia_chi_nguoi_nhan = :dia_chi_nguoi_nhan,
                        ghi_chu = :ghi_chu,
                        trang_thai_id = :trang_thai_id
                        WHERE id = :id";

            $stmt = $this->conn->prepare($sql);
            // var_dump($stmt);die;
            $stmt->execute([
                ':ten_nguoi_nhan' => $ten_nguoi_nhan,
                ':sdt_nguoi_nhan' => $sdt_nguoi_nhan,
                ':email_nguoi_nhan' => $email_nguoi_nhan,
                ':dia_chi_nguoi_nhan' => $dia_chi_nguoi_nhan,
                ':ghi_chu' => $ghi_chu,
                ':trang_thai_id' => $trang_thai_id,
                ':id' => $id
            ]);
            return true;
        } catch (Exception $e) {
            echo "Lỗi: " . $e->getMessage();
        }
    }
    public function getDonHangFromKhachHang($id)
    {
        try {
            $sql = 'SELECT orders.*, order_status.ten_trang_thai
                FROM orders 
                INNER JOIN order_status ON orders.trang_thai_id = order_status.id
                WHERE orders.tai_khoan_id = :id';

            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':id' => $id]);
            return $stmt->fetchALL();
        } catch (Exception $e) {
            echo "Lỗi" . $e->getMessage();
        }
    }
}

// 
