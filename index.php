<?php
require_once 'config.php'; require_login();
$page_title='Dashboard';
$products=(int)$pdo->query('SELECT COUNT(*) FROM products')->fetchColumn();
$active=(int)$pdo->query("SELECT COUNT(*) FROM products WHERE status='active'")->fetchColumn();
$stock=(int)$pdo->query('SELECT COALESCE(SUM(stock),0) FROM products')->fetchColumn();
$orders=(int)$pdo->query('SELECT COUNT(*) FROM orders')->fetchColumn();
$recent=$pdo->query('SELECT id,order_number,total,status,created_at FROM orders ORDER BY id DESC LIMIT 6')->fetchAll();
require 'partials/header.php';
?><div class="welcome"><div><span class="eyebrow">OVERVIEW</span><h1>Welcome back, <?=e($_SESSION['admin_username'])?> 👋</h1><p>Here is what's happening with your store.</p></div><a class="btn" href="product-add.php">＋ Add product</a></div><div class="stats"><div class="stat"><span>Total products</span><strong><?=$products?></strong><small><?=$active?> active</small></div><div class="stat"><span>Total stock units</span><strong><?=$stock?></strong><small>Across all products</small></div><div class="stat"><span>Total orders</span><strong><?=$orders?></strong><small>All order statuses</small></div></div><div class="panel"><div class="panel-head"><h3>Recent orders</h3><a href="orders.php">View all →</a></div><?php if(!$recent): ?><p class="empty">No orders yet. Orders added in the admin will appear here.</p><?php else: ?><div class="table-wrap"><table><thead><tr><th>Order</th><th>Date</th><th>Total</th><th>Status</th></tr></thead><tbody><?php foreach($recent as $o): ?><tr><td><?=e($o['order_number'])?></td><td><?=e($o['created_at'])?></td><td>₹<?=number_format((float)$o['total'],2)?></td><td><span class="status"><?=e($o['status'])?></span></td></tr><?php endforeach; ?></tbody></table></div><?php endif; ?></div><?php require 'partials/footer.php'; ?>
