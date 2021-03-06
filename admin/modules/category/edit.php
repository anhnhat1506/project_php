<?php 
    $open = "category";
    require_once __DIR__."/../../autoload/autoload.php";
    $id = intval(getInput('id'));
    //_debug($id);
    $EditCategory = $db->fetchID("category",$id);
    if(empty($EditCategory)){
        $_SESSION['error'] = "Dữ liệu cần sửa của bạn không tồn tại";
        redirectAdmin("category");
    }
    if ($_SERVER["REQUEST_METHOD"] == "POST") {   
        $data = [
            "name"=>postInput('name'),
            "slug"=>to_slug(postInput('name')),
        ];              
        $error = [];
        if (postInput('name') == '') {
            $error['name'] = "Bạn vui lòng nhập đầy đủ tên danh mục";
        }
        //erros trống có nghĩa là không có lỗi
        if (empty($error)) {
            if ($EditCategory['name'] != $data['name']) {
                $isset = $db->fetchOne("category"," name = '".$data['name']."' ");
                if(count($isset)>0){
                $_SESSION['error'] = "Tên danh mục đã tồn tại! Bạn vui lòng sửa tên danh mục khác!";
                }
                else {
                    $id_update = $db->update("category",$data,array("id"=>$id));
                    if($id_update > 0){
                        $_SESSION['success'] = "Cập nhật thành công";
                        redirectAdmin("category");
                    }
                    else {
                        //lỗi thêm thất bại
                        $_SESSION['success'] = "Dữ liệu không thay đổi";
                        redirectAdmin("category");
                    }
                }
            }
            else {
                $id_update = $db->update("category",$data,array("id"=>$id));
                if($id_update > 0){
                    $_SESSION['success'] = "Cập nhật thành công";
                    redirectAdmin("category");
                }
                else {
                    //lỗi thêm thất bại
                    $_SESSION['success'] = "Dữ liệu không thay đổi";
                    redirectAdmin("category");
                }
            }            
        }
    }
?>
<?php require_once __DIR__."/../../layouts/header.php" ?>
    <!-- Page Heading Nội dung chính của trang -->
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">
                Thêm mới danh mục
                <small>Subheading</small>
            </h1>
            <ol class="breadcrumb">
                <li>
                    <i class="fa fa-dashboard"></i>  <a href="index.html">Dashboard</a>
                </li>
                <li>
                    <i class=""></i>  <a href="#">Danh mục</a>
                </li>
                <li class="active">
                    <i class="fa fa-file"></i> Sửa
                </li>
            </ol>
           <div class="clearfix"></div>
           <!--Thông báo lỗi khi cần-->
           <?php require_once __DIR__."/../../../partials/notification.php" ?>  
        </div>
    </div>
    <!-- /.row -->
    <div class="col-md-12">
    <form method="post" action="">
        <div class="form-group row">
            <label for="inputEmail3" class="col-sm-2 col-form-label">Tên danh mục</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="inputEmail3" placeholder="Nhập tên danh mục" name="name" value="<?=$EditCategory['name']?>">
                <?php if (isset($error['name'])): ?>
                    <p class="text-danger"><?php echo $error['name'] ?> </p>
                <?php endif ?>
                
            </div>
        </div>
        <div class="form-group row">
            <div class="col-sm-offset-2 col-sm-10">
                <button type="submit" class="btn btn-primary">Lưu</button>
            </div>
        </div>
    </form>
    </div>
<?php require_once __DIR__."/../../layouts/footer.php" ?>