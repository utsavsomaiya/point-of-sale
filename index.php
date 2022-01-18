<?php
require 'admin/layout/db_connect.php';
session_start();
if (isset($_POST["submit"])) {
    $productId = $_POST['productId'];
    $productPrice = $_POST['productPrice'];
    $productQuantity = $_POST['productQuantity'];
    $productTax = $_POST['productTax'];
    $discount = $_POST['discountOfProduct'];
    $subtotal = $_POST['subtotalOfProduct'];
    $total = $_POST['totalOfProduct'];
    $tax = $_POST['totalTaxPrice'];
    $productTaxAmount = $_POST['productTaxAmount'];
    $fetch = $pdo->prepare("INSERT INTO `sales` (`subtotal`, `total_tax`, `discount`, `total`) VALUES (:subtotal,:total_tax,:discount,:total)");
    $fetch->bindParam(':subtotal', $subtotal);
    $fetch->bindParam(':total_tax', $tax);
    $fetch->bindParam(':discount', $discount);
    $fetch->bindParam(':total', $total);
    $result = $fetch->execute();
    if (isset($result)) {
        $_SESSION['msg'] = "Add Successfully";
        header('location:/');
    } else {
        $_SESSION['msg'] = "Not Successfully";
        header('location:/');
    }
    for ($i = 0; $i < sizeof($productId); $i++) {
        $fetch = $pdo->prepare("INSERT INTO `sales_item` (`sales_id`,`product_id`, `product_price`, `product_quantity`, `product_tax_percentage`, `product_tax_price`) SELECT max(`id`),'$productId[$i]','$productPrice[$i]','$productQuantity[$i]','$productTax[$i]','$productTaxAmount[$i]' FROM `sales`");
        $fetch->execute();
    }
}
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <link rel="icon" href="/admin/images/retail-store-icon-18.png" type="image/x-icon">
    <title>Retail Shop</title>
    <link rel="stylesheet" href="https://unpkg.com/tailwindcss@2.0.2/dist/tailwind.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="/admin/vendors/mdi/css/custom_styles.css">
</head>

<body class="bg-gray-200">
    <div class="container mx-auto px-5 bg-white">
        <div class="flex lg:flex-row flex-col-reverse shadow-lg">
            <div class="w-full lg:w-3/5 min-h-screen shadow-lg">
                <div class="flex flex-row justify-between items-center px-5 mt-5">
                    <div class="text-gray-800">
                        <div class="font-bold text-xl">Utsav's Retail Shop</div>
                        <span class="text-xs">"Aashirvad", 7-Nandhinagar, Nanavati Chowk, Rajkot-360007</span>
                    </div>
                </div>
                <div class="grid grid-cols-3 gap-4 px-5 mt-5 overflow-y-auto h-3/4">
                    <?php
                    $fetch = $pdo->prepare("select * from product");
                    $fetch->execute();
                    $result = $fetch->fetchAll();
                    $id = 0;
                    foreach ($result as $product) {
                        $id++; ?>
                        <div class="px-3 py-3 flex flex-col border border-gray-200 rounded-md h-32 justify-between" onclick="addToCart(<?php echo $id; ?>)">
                            <div>
                                <div class="font-bold text-gray-800" id="<?= "name-" . $id; ?>"><?= $product["name"] ?></div>
                                <span class="font-light text-sm text-gray-400">
                                    <?php
                                    $fetch = $pdo->prepare("select name from category where id =:id");
                                    $fetch->bindParam(':id', $product['category']);
                                    $fetch->execute();
                                    $result = $fetch->fetchAll();
                                    foreach ($result as $category) {
                                        if (!empty($category)) {
                                            echo $category["name"];
                                        }
                                    } ?>
                                </span>
                            </div>
                            <div class="flex flex-row justify-between items-center">
                                <span class="self-end font-bold text-lg text-yellow-500" id="<?= "price-" . $id; ?>" name="<?= "price-" . $id; ?>"><?= "$" . $product["price"] ?></span>
                                <span class="self-end font-bold text-small text-red-500" id="<?= "tax-" . $id; ?>"><?= $product["tax"] . "%" ?></span>
                                <img src="<?= 'admin/images/' . $product["image"] ?>" id="<?= "image-" . $id; ?>" class=" h-14 w-14 object-cover rounded-md" alt="">
                            </div>
                        </div>
                    <?php
                    } ?>
                </div>
            </div>
            <div class="w-full lg:w-2/5">

                <div class="flex flex-row items-center justify-between px-5 mt-5">
                    <div class="font-bold text-xl">Current Order</div>
                    <div class="font-semibold">
                        <span class="px-4 py-2 rounded-md bg-red-100 text-red-500">Clear All</span>
                    </div>
                </div>
                <div class="px-5 py-4 mt-5 overflow-y-auto h-64" id="container">

                </div>

                <div class="px-5 mt-5">
                    <div class="py-4 rounded-md shadow-lg">
                        <div class=" px-4 flex justify-between ">
                            <span class="font-semibold text-sm">Subtotal</span>
                            <span class="font-bold" id="subtotal">$0</span>
                        </div>
                        <div class=" px-4 flex justify-between ">
                            <span class="font-semibold text-sm" id='sales-tax'>Sales Tax()</span>
                            <span class="font-bold" id='sales-tax-price'>$0</span>
                        </div>
                        <div class=" px-4 flex justify-between ">
                            <span class="font-semibold text-sm">Discount(
                                <?php
                                $percentage = 0;
                                $fetch = $pdo->prepare("select * from discount where id = 1");
                                $fetch->execute();
                                $result = $fetch->fetchAll();
                                foreach ($result as $discount) {
                                    if (!empty($discount)) {
                                        $percentage = $discount["percentage"];
                                        echo $discount["percentage"] . "%";
                                    }
                                }
                                ?>
                                )
                            </span>
                            <label hidden id="discount-percentage">
                                <?= $percentage; ?>
                            </label>
                            <span class="font-bold" id="discount-price">- $0
                            </span>
                        </div>
                        <div class="border-t-2 mt-3 py-2 px-4 flex items-center justify-between">
                            <span class="font-semibold text-2xl">Total</span>
                            <span class="font-bold text-2xl" id="total">
                                $0
                            </span>
                        </div>
                    </div>
                </div>
                <div class="px-5 mt-5">
                    <form method="post">
                        <div id='hidden-form'>
                        </div>
                        <button name="submit" class="px-4 py-4 rounded-md shadow-lg text-center bg-yellow-500 text-white font-semibold" style="width: 500px;">
                            Complete Sale
                        </button>
                    </form>
                    <?php
                    if (isset($_SESSION["msg"])) {
                    ?>
                        <div id="snackbar"> <?php echo $_SESSION["msg"]; ?></div>
                    <?php
                    }
                    ?>
                </div>
            </div>
            <script>
                function toast() {
                    var x = document.getElementById("snackbar");
                    x.className = "show";
                    setTimeout(function() {
                        x.className = x.className.replace("show", "");
                    }, 3000);
                }
            </script>
            <script src="custom.js"></script>
            <script>
                <?php
                if (isset($_SESSION["msg"])) {
                    echo "toast()";
                }
                ?>
            </script>
</body>

</html>