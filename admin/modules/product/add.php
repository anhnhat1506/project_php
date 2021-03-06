<?php 
    $open = "category";
    require_once __DIR__."/../../autoload/autoload.php";
    /**
     * Danh sách danh mục sản phẩm
     */
    $category = $db->fetchAll("category");
    if ($_SERVER["REQUEST_METHOD"] == "POST") {   
        $data = [
            "name"=>postInput('name'),
            "slug"=>to_slug(postInput('name')),
            "category_id"=>postInput('category_id'),
            "price"=>postInput('price'),
            "number"=>postInput('number'),
            "content"=>postInput('content'),
        ];              
        $error = [];
        if (postInput('name') == '') {
            $error['name'] = "Bạn vui lòng nhập tên sản phẩm";
        }
        if (postInput('category_id') == '') {
            $error['category_id'] = "Bạn vui lòng chọn tên danh mục";
        }
        if (postInput('price') == '') {
            $error['price'] = "Bạn vui lòng nhập vào giá sản phẩm";
        }
        if (postInput('number') == '') {
            $error['number'] = "Bạn vui lòng nhập vào số lượng sản phẩm này";
        }
        if (postInput('content') == '') {
            $error['content'] = "Bạn vui lòng nhập thông tin sản phẩm";
        }
        if (! isset($_FILES['thunbar'])) {
            $error['thunbar'] = "Bạn chọn hình sản phẩm ";
        }
        //erros trống có nghĩa là không có lỗi
        if (empty($error)) {
            /*$isset = $db->fetchOne("category"," name = '".$data['name']."' ");
            if(count($isset) > 0){
                $_SESSION['error'] = "Tên danh mục đã tồn tại! Bạn vui lòng nhập tên danh mục khác!";
            }
            else {
                $id_insert = $db->insert("category",$data);
                if($id_insert > 0){
                    $_SESSION['success'] = "Thêm mới thành công";
                    redirectAdmin("category");
                }
                else {
                //lỗi thêm thất bại
                    $_SESSION['success'] = "Thêm mới thất bại";
                }
            }*/
            if (isset($_FILES['thunbar'])) {
               $file_name = $_FILES['thunbar']['name'];
               $file_tmp = $_FILES['thunbar']['tmp_name']; 
               $file_type = $_FILES['thunbar']['type'];
               $file_erro = $_FILES['thunbar']['error'];
                if ($file_erro == 0) {
                    $part = ROOT ."product/";
                    $data['thunbar'] = $file_name;
                }
            }
            $id_insert = $db->insert("product",$data);
            if ($id_insert) {
                move_uploaded_file($file_tmp,$part.$file_name);
                $_SESSION['success'] = "Thêm mới sản phẩm thành công";
                redirectAdmin("product");
            }
            else {
                $_SESSION['error'] = "Thêm sản phẩm thất bại";
                
            } 
        }
    }
?>
<?php require_once __DIR__."/../../layouts/header.php" ?>
    <!-- Page Heading Nội dung chính của trang -->
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">
                Thêm mới sản phẩm
                <small>Subheading</small>
            </h1>
            <ol class="breadcrumb">
                <li>
                    <i class="fa fa-dashboard"></i>  <a href="index.html">Dashboard</a>
                </li>
                <li>
                    <i class=""></i>  <a href="#">Sản phẩm</a>
                </li>
                <li class="active">
                    <i class="fa fa-file"></i> Thêm mới
                </li>
            </ol>
            <div class="clearfix"></div>
            <!--Thông báo lỗi khi cần-->
            <?php require_once __DIR__."/../../../partials/notification.php" ?>  
        </div>
    </div>
    <!-- /.row -->
    <div class="col-md-12">
    <form method="post" action="" enctype="multipart/form-data">
        <div class="form-group row">
            <label for="inputEmail3" class="col-sm-2 col-form-label"><strong>Danh mục sản phẩm</strong></label>
            <div class="col-sm-10">
                <select name="category_id" id="" class="form-control col-md-8">
                    <option value="">- Mời bạn chọn vào danh mục sản phẩm -</option>
                    <?php foreach ($category as $item): ?>
                        <option value="<?=$item['id']?>"><?=$item['name']?></option>
                    <?php endforeach ?>
                </select>
                <?php if (isset($error['category_id'])): ?>
                    <p class="text-danger"><?php echo $error['category_id'] ?> </p>
                <?php endif ?>               
            </div>
        </div>
        <div class="form-group row">
            <label for="inputEmail3" class="col-sm-2 col-form-label"><strong>Tên sản phẩm</strong></label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="inputEmail3" placeholder="Nhập tên sản phẩm" name="name">
                <?php if (isset($error['name'])): ?>
                    <p class="text-danger"><?php echo $error['name'] ?> </p>
                <?php endif ?>               
            </div>
        </div>
        <div class="form-group row">
            <label for="inputEmail3" class="col-sm-2 col-form-label">Giá bán</label>
            <div class="col-sm-10">
                <input type="number" class="form-control" id="inputEmail3" placeholder="9.000.000" name="price">
                <?php if (isset($error['price'])): ?>
                    <p class="text-danger"><?php echo $error['price'] ?> </p>
                <?php endif ?>               
            </div>
        </div>
        <div class="form-group row">
            <label for="inputEmail3" class="col-sm-2 col-form-label">Số lượng</label>
            <div class="col-sm-10">
                <input type="number" class="form-control" id="inputEmail3" placeholder="1" name="number">
                <?php if (isset($error['number'])): ?>
                    <p class="text-danger"><?php echo $error['number'] ?> </p>
                <?php endif ?>               
            </div>
        </div>
        <div class="form-group row">
            <label for="inputEmail3" class="col-sm-2 col-form-label">Giảm giá</label>
            <div class="col-sm-3">
                <input type="number" class="form-control" id="inputEmail3" placeholder="10 %" name="sale" value="0">          
            </div>
            <label for="inputEmail3" class="col-sm-2 col-form-label">Hình ảnh: </label>
            <div class="col-sm-5">
                <input type="file" class="form-control" id="inputEmail3" placeholder="10 %" name="thunbar">
                <?php if (isset($error['thunbar'])): ?>
                    <p class="text-danger"><?php echo $error['thunbar'] ?> </p>
                <?php endif ?>         
            </div>
        </div>
        <div class="form-group row">
            <label for="inputEmail3" class="col-sm-2 col-form-label">Nội dung</label>
            <div class="col-sm-10">
                <textarea name="content" id="" cols="30" rows="5" class="form-control"></textarea>
                <?php if (isset($error['content'])): ?>
                    <p class="text-danger"><?php echo $error['content'] ?> </p>
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