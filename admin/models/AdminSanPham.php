<?php
class AdminSanPham
{
    public $conn;

    public function __construct()
    {
        $this->conn = connectDB();
    }
    public function getAllSanPham()
    {
        try {
            $sql = 'SELECT products.*, categorys.ten_danh_muc
                    FROM products
                    INNER JOIN categorys ON products.danh_muc_id = categorys.id
                    ORDER BY products.ngay_nhap DESC';
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchALL();
        } catch (Exception $e) {
            echo "Lỗi" . $e->getMessage();
        }
    }
    public function insertSanPham($ten_san_pham, $gia_san_pham, $gia_khuyen_mai, $so_luong, $ngay_nhap, $danh_muc_id, $trang_thai, $mo_ta, $hinh_anh)
    {
        try {
            $sql = "INSERT INTO products(ten_san_pham, gia_san_pham, gia_khuyen_mai, so_luong, ngay_nhap, danh_muc_id, trang_thai, mo_ta, hinh_anh) VALUES(:ten_san_pham, :gia_san_pham, :gia_khuyen_mai, :so_luong, :ngay_nhap, :danh_muc_id, :trang_thai, :mo_ta, :hinh_anh)";

            $stmt = $this->conn->prepare($sql);

            $stmt->execute([
                ':ten_san_pham' => $ten_san_pham,
                ':gia_san_pham' => $gia_san_pham,
                ':gia_khuyen_mai' => $gia_khuyen_mai,
                ':so_luong' => $so_luong,
                ':ngay_nhap' => $ngay_nhap,
                ':danh_muc_id' => $danh_muc_id,
                ':trang_thai' => $trang_thai,
                ':mo_ta' => $mo_ta,
                ':hinh_anh' => $hinh_anh,
            ]);
            //Lấy id sản phẩm vừa thêm

            return $this->conn->lastInsertId();
        } catch (Exception $e) {
            echo "Lỗi: " . $e->getMessage();
        }
    }
    public function insertAlbumAnhSanPham($san_pham_id, $link_hinh_anh)
    {
        try {
            $sql = "INSERT INTO product_images (san_pham_id, link_hinh_anh) 
                VALUES (:san_pham_id, :link_hinh_anh)";

            $stmt = $this->conn->prepare($sql);

            $stmt->execute([
                ':san_pham_id' => $san_pham_id,
                ':link_hinh_anh' => $link_hinh_anh,

            ]);
            //Lấy id sản phẩm vừa thêm
            return true;
        } catch (Exception $e) {
            echo "Lỗi: " . $e->getMessage();
        }
    }

    public function getDetailSanPham($id)
    {
        try {
            $sql = 'SELECT products.*, categorys.ten_danh_muc 
                FROM products 
                INNER JOIN categorys 
                ON products.danh_muc_id = categorys.id 
                WHERE products.id = :id';

            $stmt = $this->conn->prepare($sql);

            $stmt->execute([':id' => $id]);
            return $stmt->fetch();
        } catch (Exception $e) {
            echo "Loi" . $e->getMessage();
        }
    }
    public function getListAnhSanPham($id)
    {
        try {
            $sql = "SELECT * FROM product_images WHERE san_pham_id = :id";

            $stmt = $this->conn->prepare($sql);

            $stmt->execute([':id' => $id]);
            return $stmt->fetchAll();
        } catch (Exception $e) {
            echo "Loi" . $e->getMessage();
        }
    }
    public function updateSanPham($san_pham_id, $ten_san_pham, $gia_san_pham, $gia_khuyen_mai, $so_luong, $ngay_nhap, $danh_muc_id, $trang_thai, $mo_ta, $hinh_anh)
    {
        try {
            // var_dump('abc');die;
            $sql = "UPDATE products
                        SET 
                        ten_san_pham = :ten_san_pham,
                        gia_san_pham = :gia_san_pham,
                        gia_khuyen_mai = :gia_khuyen_mai,
                        so_luong = :so_luong,
                        ngay_nhap = :ngay_nhap,
                        danh_muc_id = :danh_muc_id,
                        trang_thai = :trang_thai,
                        mo_ta = :mo_ta,
                        hinh_anh = :hinh_anh
                        WHERE id = :id";

            $stmt = $this->conn->prepare($sql);

            $stmt->execute([
                ':ten_san_pham' => $ten_san_pham,
                ':gia_san_pham' => $gia_san_pham,
                ':gia_khuyen_mai' => $gia_khuyen_mai,
                ':so_luong' => $so_luong,
                ':ngay_nhap' => $ngay_nhap,
                ':danh_muc_id' => $danh_muc_id,
                ':trang_thai' => $trang_thai,
                ':mo_ta' => $mo_ta,
                ':hinh_anh' => $hinh_anh,
                ':id' => $san_pham_id

            ]);
            //Lấy id sản phẩm vừa thêm
            return true;
        } catch (Exception $e) {
            echo "Lỗi: " . $e->getMessage();
        }
    }

    public function getDetailAnhSanPham($id)
    {
        try {
            $sql = "SELECT * FROM product_images WHERE id = :id";

            $stmt = $this->conn->prepare($sql);

            $stmt->execute([':id' => $id]);
            return $stmt->fetch();
        } catch (Exception $e) {
            echo "Loi" . $e->getMessage();
        }
    }
    public function updateAnhSanPham($id, $new_file)
    {
        try {
            // var_dump('abc');die;
            $sql = "UPDATE product_images
                    SET 
                    link_hinh_anh = :new_file,
                    
                    WHERE id = :id";

            $stmt = $this->conn->prepare($sql);

            $stmt->execute([
                ':new_file' => $new_file,
                ':id' => $id,


            ]);
            //Lấy id sản phẩm vừa thêm
            return true;
        } catch (Exception $e) {
            echo "Lỗi: " . $e->getMessage();
        }
    }
    public function destroyAnhSanPham($id)
    {
        try {
            $sql = 'DELETE FROM product_images WHERE id = :id';
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                ':id' => $id,
            ]);
            return true;
        } catch (Exception $e) {
            echo "Lỗi" . $e->getMessage();
        }
    }

    public function destroySanPham($id)
    {
        try {
            $sql = 'DELETE FROM products WHERE id = :id';
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                ':id' => $id,
            ]);
            return true;
        } catch (Exception $e) {
            echo "Lỗi" . $e->getMessage();
        }
    }
    //Bình luận

    public function getBinhLuanFromKhachHang($id)
    {
        try {
            $sql = 'SELECT comments.*, products.ten_san_pham
                    FROM comments 
                    INNER JOIN products ON comments.san_pham_id = products.id
                    WHERE comments.tai_khoan_id = :id';

            $stmt = $this->conn->prepare($sql);
            $stmt->execute([":id" => $id]);
            $result = $stmt->fetchALL();
            return $result ?: []; // Trả về mảng rỗng nếu không có kết quả
        } catch (Exception $e) {
            echo "Lỗi: " . $e->getMessage();
            return [];
        }
    }
    public function getDetailBinhLuan($id)
    {
        try {
            $sql = "SELECT * FROM comments WHERE id = :id";

            $stmt = $this->conn->prepare($sql);

            $stmt->execute([':id' => $id]);
            return $stmt->fetch();
        } catch (Exception $e) {
            echo "Loi" . $e->getMessage();
        }
    }
    public function updateTrangThaiBinhLuan($id_binh_luan, $trang_thai_update)
    {
        try {
            $sql = 'UPDATE comments SET trang_thai = :trang_thai WHERE id = :id';
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                ':trang_thai' => $trang_thai_update,
                ':id' => $id_binh_luan
            ]);
            return true;
        } catch (Exception $e) {
            echo "Lỗi: " . $e->getMessage();
            return false;
        }
    }
    public function getBinhLuanFromSanPham($id)
    {
        try {
            $sql = 'SELECT comments.*, users.ho_ten
                    FROM comments 
                    INNER JOIN users ON comments.tai_khoan_id = users.id
                    WHERE comments.san_pham_id = :id';

            $stmt = $this->conn->prepare($sql);
            $stmt->execute([":id" => $id]);
            return $stmt->fetchAll();
        } catch (Exception $e) {
            echo "Lỗi: " . $e->getMessage();
        }
    }
}
