<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="banner.css">
    <title>Trang chủ</title>
</head>
<body>
    <div class="container">
<?php  include'header.php' ?>
        <?php
            include('../admin/control.php');
            $get_data = new data_dienthoai();
            $select_dt= $get_data->selectdt();
            shuffle($select_dt);
            $random_products = array_slice($select_dt, 0, 10);
            $banner = $get_data->getBanner();
                ?>
        <script>
            window.onload = function() {
                let list = document.querySelector('.slider .list');
                let items = document.querySelectorAll('.slider .list .item');
                let dots = document.querySelectorAll('.slider .dots li');
                let prev = document.getElementById('prev');
                let next = document.getElementById('next');
                let active = 0;
                let lenghItems = items.length - 1;

                dots[active].classList.add('active');
                items[active].classList.add('active');

                let refreshSlider = setInterval(() => { next.click(); }, 6000);

                next.onclick = function () {
                    if (active + 1 > lenghItems) {
                        active = 0;
                    } else {
                        active++;
                    }
                    reloadSlider();
                };

                prev.onclick = function () {
                    if (active - 1 < 0) {
                        active = lenghItems;
                    } else {
                        active--;
                    }
                    reloadSlider();
                };

                function reloadSlider() {
                    let checkLeft = items[active].offsetLeft;
                    list.style.left = -checkLeft + 'px';


                    let listActiveDot = document.querySelector('.slider .dots li.active');
                    listActiveDot.classList.remove('active');
                    dots[active].classList.add('active');

                    let listActiveItem = document.querySelector('.slider .list .item.active');
                    if (listActiveItem) {
                        listActiveItem.classList.remove('active');
                    }
                    items[active].classList.add('active');
                    clearInterval(refreshSlider);
                    refreshSlider = setInterval(() => { next.click(); }, 6000);
                }
            };

        </script>
        <div class="slider">

            <div class="list" >
        <?php
        $count = 0;
        if ($banner):
            foreach ($banner as $row){
                $count++;
            ?>
            <div class="item">
            <a href="chitietsanpham.php?id=<?php echo $row['id']; ?>">
                <img  src="../img/<?php echo $row['hinh']; ?>" alt="Banner" >
            </a>
            </div>

            <?php }
            else: ?>
            <p>Không có quảng cáo nào.</p>
        <?php endif; ?>
            </div>
            <div class="buttons">
                <button id="prev"><</button>
                <button id="next">></button>
            </div>
            <ul class="dots">
                <?php for ($i = 0; $i < $count; $i++): ?>

                    <li></li>
                <?php endfor; ?>
            </ul>



        </div>
        <div class="goiy">
        <h2>Gợi ý cho bạn</h2>
        <?php for ($i = 0; $i < 2; $i++): ?> 
        <div class="hang1">
            <?php for ($j = 0; $j < 5; $j++): ?>
                <?php
                    $index = $i * 5 + $j;
                    if (isset($random_products[$index])):
                        $dt = $random_products[$index];
                ?>
                <div class="goiy1" >
                <p>Trả góp 0%</p>
                    <a href="chitietsanpham.php?id=<?php echo $dt['id']?>" >
                       
                        <img src="../img/<?php echo $dt['hinhqc'] ?>" alt="" class="goiyimg">
                    </a>
                    <a href="chitietsanpham.php?id=<?php echo $dt['id']?>" class="bdtensp">
                        <?php echo $dt['tenhang'] . ' ' . $dt['ten'] . ' ' . $dt['ram']."GB" . '/' . $dt['rom']."GB"; ?>
                    </a>
                    <p class="gia"><?php echo $dt['gia']; ?>₫</p>
                </div>
                <?php endif; ?>
            <?php endfor; ?>
        </div>
        <?php endfor; ?>
    </div>

<?php  include'footer.php' ?>
</div>
</body>
</html>