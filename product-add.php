<?php
require_once 'config.php'; require_login();
$page_title='Add product'; $errors=[];
$name='';$sku='';$description='';$price='';$mrp='';$stock='0';$status='active';$imageName=null;
if($_SERVER['REQUEST_METHOD']==='POST'){
 verify_csrf(); $name=trim($_POST['name']??'');$sku=trim($_POST['sku']??'');$description=trim($_POST['description']??'');$price=$_POST['price']??'0';$mrp=$_POST['mrp']??'0';$stock=$_POST['stock']??'0';$status=($_POST['status']??'active')==='inactive'?'inactive':'active';
 if($name==='')$errors[]='Product name is required.';
 if(!is_numeric($price)||$price<0||!is_numeric($mrp)||$mrp<0)$errors[]='Enter valid prices.';
 if(filter_var($stock,FILTER_VALIDATE_INT)===false||$stock<0)$errors[]='Stock must be a non-negative whole number.';
 if(isset($_FILES['image'])&&$_FILES['image']['error']!==UPLOAD_ERR_NO_FILE){
  if($_FILES['image']['error']!==UPLOAD_ERR_OK)$errors[]='Image upload failed.';
  elseif($_FILES['image']['size']>4*1024*1024)$errors[]='Image must be 4 MB or smaller.';
  else{$finfo=new finfo(FILEINFO_MIME_TYPE);$mime=$finfo->file($_FILES['image']['tmp_name']);$ext=['image/jpeg'=>'jpg','image/png'=>'png','image/webp'=>'webp'][$mime]??null;if(!$ext)$errors[]='Only JPG, PNG or WEBP images are allowed.';else{$imageName=bin2hex(random_bytes(16)).'.'.$ext;}}
 }
 if(!$errors){if($imageName){if(!is_dir(__DIR__.'/uploads'))mkdir(__DIR__.'/uploads',0755,true);if(!move_uploaded_file($_FILES['image']['tmp_name'],__DIR__.'/uploads/'.$imageName)){$errors[]='Could not save image.';}}
  if(!$errors){$stmt=$pdo->prepare('INSERT INTO products(name,sku,description,price,mrp,stock,image,status) VALUES(?,?,?,?,?,?,?,?)');$stmt->execute([$name,$sku?:null,$description,(float)$price,(float)$mrp,(int)$stock,$imageName,$status]);redirect('products.php?saved=1');}
 }
}
require 'partials/header.php';
?><div class="panel form-panel"><form method="post" enctype="multipart/form-data"><input type="hidden" name="csrf_token" value="<?=e(csrf_token())?>"><?php foreach($errors as $err):?><div class="notice error"><?=e($err)?></div><?php endforeach;?><div class="form-grid"><div class="field wide"><label>Product name *</label><input name="name" required value="<?=e($name)?>"></div><div class="field"><label>SKU / Product code</label><input name="sku" value="<?=e($sku)?>"></div><div class="field"><label>Stock quantity *</label><input type="number" name="stock" min="0" step="1" required value="<?=e($stock)?>"></div><div class="field"><label>Selling price (₹) *</label><input type="number" name="price" min="0" step="0.01" required value="<?=e($price)?>"></div><div class="field"><label>MRP (₹) *</label><input type="number" name="mrp" min="0" step="0.01" required value="<?=e($mrp)?>"></div><div class="field wide"><label>Description</label><textarea name="description" rows="5"><?=e($description)?></textarea></div><div class="field"><label>Product image (JPG/PNG/WEBP, max 4 MB)</label><input type="file" name="image" accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp"></div><div class="field"><label>Status</label><select name="status"><option value="active" <?=$status==='active'?'selected':''?>>Active</option><option value="inactive" <?=$status==='inactive'?'selected':''?>>Inactive</option></select></div></div><div class="form-actions"><a class="btn secondary" href="products.php">Cancel</a><button class="btn">Save product</button></div></form></div><?php require 'partials/footer.php';?>
