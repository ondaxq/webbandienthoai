<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Giỏ hàng</title>
</head>
<style>
.order-form label {
    display: block;
    font-weight: bold;
    margin-bottom: 8px;
    color: #555;
}

.order-form input {
    width: 97%;
    padding: 10px;
    margin-bottom: 15px;
    border: 1px solid #ccc;
    border-radius: 4px;
    box-sizing: border-box;
}


</style>
<body>
<div class="container">
<?php include 'header.php';?>

<?php
if (isset($_POST['them'])) {
    $product = [
        'id' => $_POST['product_id'],
        'hinhsp' => $_POST['hinh'],
        'ten' => $_POST['ten'],
        'hang' => $_POST['hang'],
        'ram' => $_POST['ram'],
        'rom' => $_POST['rom'],
        'gia' => $_POST['gia'],
        'quantity' => 1,
    ];
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    $found = false;
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $product['id']) {
            $item['quantity'] += $product['quantity'];
            $found = true;
            break;
        }
    }
    unset($item);
    if (!$found) {
        $_SESSION['cart'][] = $product;
    }
}

if (isset($_POST['increase_quantity'])) {
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $_POST['product_id']) {
            $item['quantity']++;
            break;
        }
    }
    unset($item);
}
if (isset($_POST['decrease_quantity'])) {
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $_POST['product_id']) {
            $item['quantity'] = max(1, $item['quantity'] - 1);
            break;
        }
    }
    unset($item);
}

if (isset($_POST['remove'])) {
    foreach ($_SESSION['cart'] as $key => $item) {
        if ($item['id'] == $_POST['product_id']) {
            unset($_SESSION['cart'][$key]);
            break;
        }
    }
    $_SESSION['cart'] = array_values($_SESSION['cart']);
}

$cart_items = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
$total_price = 0;
foreach ($cart_items as $item) {
    $price = (int) str_replace('.', '', $item['gia']);
    $total_price += $price * $item['quantity'];
}
?>

<h1 style="text-align: center;">Giỏ Hàng</h1>
<div class="cart">
    <?php if (empty($cart_items)): ?>
        <p style="text-align: center; font-size: 18px; color: red;">Không có sản phẩm nào trong giỏ hàng.</p>
    <?php else: ?>
        <?php foreach ($cart_items as $item): ?>
            <div class="cartitem">
                <img src="../img/<?php echo $item['hinhsp']; ?>" alt="<?php echo $item['ten']; ?>">
                <p><?php echo 'Điện thoại ' . $item['hang'] . ' ' . $item['ten'] . ' ' . $item['ram'] . 'GB/' . $item['rom'] . 'GB'; ?></p>
                <div class="ft">
                    <p class="pgia"><?php echo $item['gia'] . 'đ'; ?></p>
                </div>
            </div>
            <div class="ft">
                <form action="" method="POST" style="display: inline;padding-right:15px">
                    <input type="hidden" name="product_id" value="<?php echo $item['id']; ?>">
                    <button type="submit" name="remove">Xóa</button>
                </form>
                <form action="" method="POST" style="display: inline;padding-right:10px">
                    <input type="hidden" name="product_id" value="<?php echo $item['id']; ?>">
                    <button type="submit" name="decrease_quantity">-</button>
                    <?php echo $item['quantity']; ?>
                    <button type="submit" name="increase_quantity">+</button>
                </form>
            </div>
            <hr>
        <?php endforeach; ?>
        <h3>Tổng giá trị giỏ hàng: <?php echo number_format($total_price); ?> VND</h3>
</div>


<div class="cart">
        <h2 style="text-align: center;">Thông tin đặt hàng</h2>
        <form class="order-form" action="checkout.php" method="POST">
            <input type="hidden" name="cart_items" value='<?php echo json_encode($cart_items); ?>'>
            <input type="hidden" name="total_price" value="<?php echo $total_price; ?>">
            <?php
        $name = '';
        $phone = '';
        $address = '';
        include('../admin/control.php');
        if (isset($_SESSION['user'])) {
            $user_name=$_SESSION['user']['username'];
            $get_data = new data_dienthoai();
            $user_info = $get_data->select_user_by_username($user_name); 
            foreach($user_info as $us){
                $name = $us['name'];
                $phone = $us['sdt'];
            }
        }
        ?>

        <label for="name">Họ và Tên</label>
        <input type="text" id="name" name="name" placeholder="Nhập họ và tên" value="<?php echo $name; ?>" required>

        <label for="phone">Số điện thoại</label>
        <input type="tel" id="phone" name="phone" placeholder="Nhập số điện thoại" pattern="[0-9]{10}" value="<?php echo $phone; ?>" required>

        <label for="address">Địa chỉ</label>
        <input type="text" id="address" name="address" placeholder="Nhập địa chỉ" required>


            <button type="submit" name="dathang">Đặt hàng</button>
        </form>
    <?php  endif;  ?>
    
    
</div>
<?php  echo "<script>
        window.history.back();  
      </script>";
      include 'footer.php'; ?>
</div>
</body>
</html>
