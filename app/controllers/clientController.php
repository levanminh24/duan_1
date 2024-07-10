<?php
if (isset($_GET['redirect'])) {
    $redirect = $_GET['redirect'];
    switch ($redirect) {
        case 'listspHome':
            include  "app/views/Client/home.php";
            break;

        case 'sanphamct':
           
            $danhmuc = loadall_danhmuc();
            $size = loadall_size();
           
            include "app/views/Client/sanpham/ctsp.php";
            break;
        case 'dangnhap':
            $errors = ['tendangnhap' => '', 'matkhau' => ''];
            $tendangnhap = $matkhau = '';

            if (isset($_POST['dangnhap'])) {
                $tendangnhap = $_POST['tendangnhap'];
                $matkhau = $_POST['matkhau'];

                if (empty($tendangnhap)) {
                    $errors['tendangnhap'] = "Tên đăng nhập không được để trống.";
                }

                if (empty($matkhau)) {
                    $errors['matkhau'] = "Mật khẩu không được để trống.";
                }

                if (empty($errors['tendangnhap']) && empty($errors['matkhau'])) {
                    $taikhoan = dangnhap($tendangnhap, $matkhau);
                    if ($taikhoan && $taikhoan['tendangnhap'] == $tendangnhap && $taikhoan['matkhau'] == $matkhau) {
                        $_SESSION['tendangnhap'] = $tendangnhap;
                        $_SESSION['role'] = $taikhoan['role'];
                        $_SESSION['idtendangnhap'] = $taikhoan['id'];
                        echo '<script>alert("Đăng nhập thành công")</script>';
                        echo '<script>window.location.href = "index.php"</script>';
                    } else {
                        $errors['tendangnhap'] = "Sai tài khoản hoặc mật khẩu.";
                    }
                }
            }
            include "app/views/Client/taikhoan/dangnhap.php";

            break;
        case 'dangky':
            $errors = ['tendangnhap' => '', 'matkhau' => '', 'email' => '', 'sodienthoai' => '', 'diachi' => ''];
            $tendangnhap = $matkhau = $email = $sodienthoai = $diachi = '';

            if (isset($_POST['dangky'])) {
                $tendangnhap = $_POST['tendangnhap'];
                $matkhau = $_POST['matkhau'];
                $email = $_POST['email'];
                $sodienthoai = $_POST['sodienthoai'];
                $diachi = $_POST['diachi'];

                // Kiểm tra tên đăng nhập
                if (empty($tendangnhap)) {
                    $errors['tendangnhap'] = "Tên đăng nhập không được để trống.";
                } elseif (!preg_match("/^[a-zA-Z0-9_]{3,20}$/", $tendangnhap)) {
                    $errors['tendangnhap'] = "Tên đăng nhập phải viết liền ko dấu từ 3 đến 20 ký tự";
                }

                // Kiểm tra mật khẩu
                if (empty($matkhau)) {
                    $errors['matkhau'] = "Mật khẩu không được để trống.";
                } elseif (strlen($matkhau) < 6 || strlen($matkhau) > 20) {
                    $errors['matkhau'] = "Mật khẩu phải từ 8 ký tự trở lên.";
                }

                // Kiểm tra email
                if (empty($email)) {
                    $errors['email'] = "Email không được để trống.";
                } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $errors['email'] = "Email không hợp lệ.";
                }

                // Kiểm tra số điện thoại
                if (empty($sodienthoai)) {
                    $errors['sodienthoai'] = "Số điện thoại không được để trống.";
                } elseif (!preg_match("/^[0-9]{10,11}$/", $sodienthoai)) {
                    $errors['sodienthoai'] = "Số điện thoại phải chứa 10-11 chữ số.";
                }

                // Kiểm tra địa chỉ
                if (empty($diachi)) {
                    $errors['diachi'] = "Địa chỉ không được để trống.";
                }

                // Nếu không có lỗi, tiến hành đăng ký
                if (empty($errors['tendangnhap']) && empty($errors['matkhau']) && empty($errors['email']) && empty($errors['sodienthoai']) && empty($errors['diachi'])) {
                    $dangky = dangky($tendangnhap, $matkhau, $email, $sodienthoai, $diachi);
                    echo '<script>alert("Đăng ký thành công")</script>';
                    echo '<script>window.location.href = "index.php?redirect=dangnhap"</script>';
                } 
               
            }
            include "app/views/Client/taikhoan/dangky.php";
            break;
            case 'dangxuat':
                unset($_SESSION['tendangnhap']);
                unset($_SESSION['role']);
              
                echo '<script>window.location.href = "index.php"</script>';
                break;
                case 'addbl':
                                
                    if (isset($_POST['guibinhluan'])) {
                       if(isset($_SESSION['tendangnhap'])){
                        $idtaikhoan = $_POST['idtaikhoan'];
                        $idsanpham = $_POST['idsanpham'];
                        $noidung = $_POST['noidung'];
                        $ngaybinhluan = $_POST['ngaybinhluan'];
                        insert_bl($idtaikhoan,$idsanpham,$noidung,$ngaybinhluan);
                        echo "<script>window.location.href='index.php?redirect=sanphamct&idsp=$idsanpham';</script>";
                       } else{
                        echo '<script>alert("chưa đăng nhập")</script>';
                        echo '<script>window.location.href = "index.php?redirect=dangnhap"</script>';
                       }
                    }
                    include "app/views/Client/binhluan/binhluan.php";
                    break;
               
                
                
              
    }
} else {
    include  "app/views/Client/home.php";
}
