
<?php
class SanPham {
    public $conn;

    public function __construct()
    {
        $this->conn = connectDB();
    }
    //Viết hàm lấy toàn bộ danh sách sản phẩm 
    public function getAllSanPham(){
        try{
            $sql = 'SELECT products.*, categorys.ten_danh_muc
            FROM products
            INNER JOIN categorys ON products.danh_muc_id = categorys.id';  

            $stmt = $this->conn->prepare($sql);

            $stmt->execute();
            return $stmt->fetchAll();
        }catch (Exception $e){
            echo "Lỗi". $e->getMessage();
        }

    }
    public function getDetailSanPham($id){
        try{
            $sql = 'SELECT products.*, categorys.ten_danh_muc 
            FROM products 
            INNER JOIN categorys 
            ON products.danh_muc_id = categorys.id 
            WHERE products.id = :id';

            $stmt = $this->conn->prepare($sql);

            $stmt->execute([':id' => $id]);
            return $stmt->fetch();
        }catch (Exception $e){
            echo "Loi" . $e->getMessage();
        }

    }
    public function getListAnhSanPham($id){
        try{
            $sql = "SELECT * FROM product_images WHERE san_pham_id = :id";

            $stmt = $this->conn->prepare($sql);

            $stmt->execute([':id' => $id]);
            return $stmt->fetchAll();
        }catch (Exception $e){
            echo "Loi" . $e->getMessage();
        }

    }
    public function getBinhLuanFromSanPham($id){
        try{
            $sql = 'SELECT comments.*, users.ho_ten, users.anh_dai_dien
                    FROM comments 
                    INNER JOIN users ON comments.tai_khoan_id = users.id
                    WHERE comments.san_pham_id = :id';
            
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([":id" => $id]);
            return $stmt->fetchAll();
        }catch(Exception $e){
            echo "Lỗi: " . $e->getMessage();
        }
    }
    public function getListSanPhamDanhMuc($danh_muc_id){
        try{
            $sql = 'SELECT products.*, categorys.ten_danh_muc
            FROM products
            INNER JOIN categorys ON products.danh_muc_id = categorys.id
            WHERE products.danh_muc_id = '. $danh_muc_id;

            $stmt = $this->conn->prepare($sql);

            $stmt->execute();
            return $stmt->fetchAll();
        }catch (Exception $e){
            echo "Lỗi". $e->getMessage();
        }

    }
    public function getAllDanhMucClient()
    {
        try {
            $sql = "SELECT * FROM categorys limit 5";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();

            return $stmt->fetchAll();
        } catch (Exception $e) {
            echo "Lỗi: " . $e->getMessage();
        }
    }

}
?>
