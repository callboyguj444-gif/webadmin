<?php
require_once 'config.php'; require_login();
$page_title='Products';
if (isset($_GET['delete'])) {
    $id=(int)$_GET['delete'];
    if ($_SERVER['REQUEST_METHOD']==='POST') { verify_csrf(); $stmt=$pdo->prepare('DELETE FROM products WHERE id=?'); $stmt->execute([$id]); redirect('products.php?deleted=1'); }
}
$q=trim($_GET['q']??'');
if($q!==''){ $stmt=$pdo->prepare('SELECT * FROM products WHERE name LIKE ? OR sku LIKE ? ORDER BY id DESC'); $like="%$q%"; $stmt->execute([$like,$like]); $products=$stmt->fetchAll(); }
else $products=$pdo->query('SELECT * FROM products ORDER BY id DESC')->fetchAll();
require 'partials/header.php';
?><div class="page-actions"><form class="search" method="get"><input name="q" placeholder="Search product or SKU..." value="<?=e($q)?>"><button class="btn secondary">Search</button></form><a class="btn" href="product-add.php">＋ Add product</a></div><?php if(isset($_GET['saved'])):?><div class="notice">Product saved successfully.</div><?php endif;?><?php if(isset($_GET['deleted'])):?><div class="notice">Product deleted.</div><?php endif;?><div class="panel"><div class="table-wrap"><table><thead><tr><th>Product</th><th>SKU</th><th>Price / MRP</th><th>Stock</th><th>Status</th><th>Actions</th></tr></thead><tbody><?php foreach($products as $p):?><tr><td><div class="product-cell"><?php if($p['image'] && is_file(__DIR__.'/uploads/'.$p['image'])):?><img src="uploads/<?=e($p['image'])?>" alt=""><?php else:?><div class="thumb-placeholder">NH</div><?php endif;?><b><?=e($p['name'])?></b></div></td><td><?=e($p['sku']?:'—')?></td><td>₹<?=number_format((float)$p['price'],2)?> <small class="muted">/ ₹<?=number_format((float)$p['mrp'],2)?></small></td><td><?= (int)$p['stock']?></td><td><span class="status <?=e($p['status'])?>"><?=e($p['status'])?></span></td><td class="actions"><a href="product-edit.php?id=<?=(int)$p['id']?>">Edit</a><form method="post" action="products.php?delete=<?=(int)$p['id']?>" onsubmit="return confirm('Delete this product?')"><input type="hidden" name="csrf_token" value="<?=e(csrf_token())?>"><button class="link danger">Delete</button></form></td></tr><?php endforeach;?></tbody></table></div><?php if(!$products):?><p class="empty">No products found. Add your first product.</p><?php endif;?></div><?php require 'partials/footer.php';?>
