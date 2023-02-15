<?php
    session_start();
    require 'admin/layout/db_connect.php';
    $fetchProducts = $pdo->prepare("SELECT p.id,p.name,p.price,c.name as category_name,p.tax,p.stock,p.image FROM product p JOIN category c ON c.id = p.category_id ORDER BY p.name ASC");
    $fetchProducts->execute();
    $products = $fetchProducts->fetchAll();

    $fetchDiscounts = $pdo->prepare("SELECT discount.id,discount.name,discount.type,discount_tier.minimum_spend_amount,discount_tier.discount_digit,discount_tier.tier_id,discount_tier.discount_product FROM `discount` JOIN `discount_tier` ON discount.id = discount_tier.discount_id WHERE discount.status = 2");
    $fetchDiscounts->execute();
    $discounts = $fetchDiscounts->fetchAll();
    $i = 0;
    $discountProduct = [];
    foreach ($discounts as $discount) {
        if ($discount['discount_product'] != null) {
            $discountProduct[$i++] = $discount['discount_product'];
        }
    }

    $discountProduct = join("','", $discountProduct);
    $fetchProducts = $pdo->prepare("SELECT `name`,`image`,`price` FROM `product` WHERE `name` IN ('$discountProduct')");
    $fetchProducts->execute();
    $discountProducts = $fetchProducts->fetchAll();

    define("DISCOUNT", ["flat"=>2, "percentage"=>1]);

    if (isset($_POST["submit"])) {
        if (empty($_POST['productId'])) {
            $_SESSION['message'] = "Please add some item in your cart.";
            header('location:/');
            exit;
        }
        $productIds = $_POST['productId'];
        $productQuantities = $_POST['productQuantity'];
        $productPrices = [];
        $productTaxes = [];
        $productsTax = [];
        $productTaxablePrice = [];
        $productDiscount = [];
        $subtotal = 0;
        for ($i = 0; $i < sizeof($productIds); $i++) {
            $fetchProducts = $pdo->prepare("SELECT `price`,`tax` FROM `product` WHERE `id` = :productId");
            $fetchProducts->bindParam(':productId', $productIds[$i]);
            $fetchProducts->execute();
            $products = $fetchProducts->fetchAll();
            foreach ($products as $product) {
                $productPrice = (int) $product['price'];
                $productTax = (int) $product['tax'];
            }
            $productPrices[$i] = $productPrice;
            $productTaxes[$i] = $productTax;
            $subtotal +=  ($productPrices[$i] * $productQuantities[$i]) ;
        }

        $totalDiscount = 0;
        $totalTax = 0;
        $discountPrice = 0;
        $discountId = null;
        $discountTierId = null;
        $discountType = null;
        $discountProduct = null;
        $minimumSpendAmount = null;
        $discountCategory = null;

        $fetchDiscount = $pdo->prepare("SELECT discount.*,discount_tier.* FROM discount,discount_tier WHERE discount.id = :id AND discount_tier.tier_id = :tier_id");
        $fetchDiscount->bindParam(':id', $_POST['discounts_id']);
        $fetchDiscount->bindParam(':tier_id', $_POST['discounts_tier_id']);
        $fetchDiscount->execute();
        $discounts = $fetchDiscount->fetchAll();
        foreach ($discounts as $discount) {
            $minimumSpendAmount = (int) $discount['minimum_spend_amount'];
            if ($discount['discount_digit'] != null) {
                $discountType = (int) $discount['type'];
                $discountPrice = (float) $discount['discount_digit'];
                if ($discountType == DISCOUNT["percentage"]) {
                    $discountPrice = ($subtotal * (float) $discount['discount_digit']) / 100;
                }
                $discountCategory = "price";
            } else {
                $discountProduct = $discount['discount_product'];
                $discountCategory = "gift";
            }
        }

        if (isset($discountProduct)) {
            $fetchDiscountProduct = $pdo->prepare("SELECT * FROM `product` WHERE `name` = :name");
            $fetchDiscountProduct->bindParam(':name', $discountProduct);
            $fetchDiscountProduct->execute();
            $fetchDiscountProduct = $fetchDiscountProduct->fetchAll();
            $discountProductId = $fetchDiscountProduct[0]['id'];
            $discountProductName = $fetchDiscountProduct[0]['name']."(Free)";
            $discountPrice = (int) $fetchDiscountProduct[0]['price'];
            $discountProductQuantity = 1;
            array_push($productIds, $discountProductId);
            array_push($productPrices, $discountPrice);
            $subtotal += $discountPrice;
            array_push($productQuantities, $discountProductQuantity);
            array_push($productTaxes, (int)$fetchDiscountProduct[0]['tax']);
        }
        if ($discountType == DISCOUNT["flat"]) {
            if ($discountPrice >= $subtotal) {
                $discountPrice = $subtotal;
            }
        }


        if ($minimumSpendAmount < $subtotal && $subtotal > $discountPrice) {
            $discountId = $_POST['discounts_id'];
            $discountTierId = $_POST['discounts_tier_id'];
            if ($_POST['discounts_id'] == 'null') {
                $discountId = null;
                $discountTierId = null;
            }
        }

        for ($i = 0; $i < sizeof($productIds); $i++) {
            $productDiscount[$i] = round((($productPrices[$i] * $productQuantities[$i] * $discountPrice)/$subtotal), 2);
            $totalDiscount += $productDiscount[$i];
            if ($discountCategory == "gift") {
                $productDiscount[$i] = round((($productPrices[$i] * $productQuantities[$i] * $discountPrice)/($subtotal - $discountPrice)), 2);
                $totalDiscount = $discountPrice;
                if ($i == sizeof($productIds)-1) {
                    $productDiscount[array_key_last($productDiscount)] = $discountPrice;
                }
            }
        }
        for ($i = 0; $i < sizeof($productIds); $i++) {
            $productTaxablePrice[$i] = $productPrices[$i] * $productQuantities[$i] - $productDiscount[$i];
            $productsTax[$i] = ($productTaxablePrice[$i] * $productTaxes[$i])/100;
            $totalTax += $productsTax[$i];
        }

        $grandTotal = $subtotal - $totalDiscount + $totalTax;

        $insertSales = $pdo->prepare("INSERT INTO `sales` (`subtotal`, `total_tax`, `total_discount`, `discount_id`, `discount_tier_id`, `discount_category`, `total`) VALUES (:subtotal,:total_tax, :total_discount,:discount_id, :discount_tier_id,:discount_category,:total)");
        $insertSales->bindParam(':subtotal', $subtotal);
        $insertSales->bindParam(':total_tax', $totalTax);
        $insertSales->bindParam(':total_discount', $totalDiscount);
        $insertSales->bindParam(':discount_id', $discountId);
        $insertSales->bindParam(':discount_tier_id', $discountTierId);
        $insertSales->bindParam(':discount_category', $discountCategory);
        $insertSales->bindParam(':total', $grandTotal);
        $insertSales->execute();
        $salesId = $pdo->lastInsertId();

        for ($i = 0; $i < sizeof($productIds); $i++) {
            $productIds[$i] = (int) $productIds[$i];
            $productQuantities[$i] = (int) $productQuantities[$i];
            $discountId = (int) $discountId;
            $productTotalPrice = ($productQuantities[$i] * $productPrices[$i]);
            $insertSalesItems = $pdo->prepare("INSERT INTO `sales_item` (`sales_id`, `product_id`, `product_price`, `product_quantity`, `product_total_price`, `product_discount_id`, `product_discount_tier_id`, `product_discount`, `product_tax_percentage`, `product_taxable_price`, `product_tax_amount`) VALUES(:sales_id, :product_id, :product_price, :product_quantity, :product_total_price, :discount_id, :discount_tier_id, :product_discount, :product_tax_percentage, :product_taxable_price, :product_tax_amount)");
            $insertSalesItems->bindParam(':sales_id', $salesId);
            $insertSalesItems->bindParam(':product_id', $productIds[$i]);
            $insertSalesItems->bindParam(':product_price', $productPrices[$i]);
            $insertSalesItems->bindParam(':product_quantity', $productQuantities[$i]);
            $insertSalesItems->bindParam(':product_total_price', $productTotalPrice);
            $insertSalesItems->bindParam(':discount_id', $discountId);
            $insertSalesItems->bindParam(':discount_tier_id', $discountTierId);
            $insertSalesItems->bindParam(':product_discount', $productDiscount[$i]);
            $insertSalesItems->bindParam(':product_tax_percentage', $productTaxes[$i]);
            $insertSalesItems->bindParam(':product_taxable_price', $productTaxablePrice[$i]);
            $insertSalesItems->bindParam(':product_tax_amount', $productsTax[$i]);
            $isExecuted = $insertSalesItems->execute();
            $updateStock = $pdo->prepare("UPDATE `product` SET `stock` = `stock` - :productQuantity WHERE `id` = :productId");
            $updateStock->bindParam(':productQuantity', $productQuantities[$i]);
            $updateStock->bindParam(':productId', $productIds[$i]);
            $updateStock->execute();
        }
        if ($isExecuted) {
            $_SESSION['message'] = "Order added successfully.";
            header('location:/');
            exit;
        }
        $_SESSION['message'] = "Something went wrong.";
        header('location:/');
        exit;
    }
?>
<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Retail Shop</title>
        <link rel="stylesheet" href="/tailwind.min.css">
        <link rel="stylesheet" href="/admin/vendors/mdi/css/custom_styles.css">
        <link rel="stylesheet" href="custom.css">
        <link rel = "icon" href ="/admin/image/retail-store-icon-18.png" type = "image/x-icon">
    </head>
    <body class="bg-gray-200">
        <div class="container mx-auto px-5 bg-white">
            <div class="flex lg:flex-row flex-col-reverse shadow-lg">
                <div class="w-full lg:w-3/5 min-h-screen shadow-lg">
                    <div class="flex flex-row justify-between items-center px-5 mt-5">
                        <div class="text-gray-800">
                            <div class="font-bold text-xl">Utsav's Retail Shop</div>
                            <span class="text-xs">
                                "Aashirvad", 7-Nandhinagar, Nanavati Chowk, Rajkot-360007
                            </span>
                        </div>
                        <input type="text" placeholder="Search Products" class="w-full h-12 px-4 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg lg:w-20 xl:transition-all xl:duration-300 xl:w-36 xl:focus:w-44 lg:h-10 dark:bg-gray-900 dark:text-gray-300 dark:border-gray-600 focus:border-teal-500 dark:focus:border-teal-500 focus:outline-none focus:ring focus:ring-primary dark:placeholder-gray-400 focus:ring-opacity-40" onkeyup="searchProducts()" id="searchbar">
                    </div>
                    <div class="grid grid-cols-3 gap-4 px-5 mt-5 overflow-y-auto h-3/4">
                        <div id="not-available" style="display: none;">
                            <h5 style="width: 400px;">
                                Sorry, the products has not been added yet...
                            </h5>
                        </div>
                        <?php $id = 0; foreach ($products as $product) { ?>
                            <?php $id++; ?>
                            <div class="transform hover:scale-105 transition duration-300 px-3 py-3 flex flex-col border border-gray-200 rounded-md h-32 justify-between" onclick="addToCart(<?= $id; ?>)" id="products-list-<?= $id; ?>"
                                style = " <?php
                                    if ($product['stock'] <=0) {
                                        echo "opacity:0.5";
                                    }
                                ?>"
                            >
                                <div>
                                    <label hidden id="<?= "id-".$id; ?>">
                                        <?= $product["id"] ?>
                                    </label>
                                    <label hidden id="<?= "stock-".$id; ?>">
                                        <?= $product["stock"] ?>
                                    </label>
                                    <div class="font-bold text-gray-800" id="<?= "name-".$id; ?>">
                                        <?= $product["name"] ?>
                                    </div>
                                    <span class="font-light text-sm text-gray-400" id="<?= "category-".$id; ?>">
                                        <?= $product["category_name"] ?>
                                    </span>
                                </div>
                                <div class="flex flex-row justify-between items-center">
                                    <div class="flex space-x-0 self-end font-bold text-lg text-yellow-500">
                                        <span>$</span>
                                        <span id="<?= "price-".$id; ?>">
                                            <?= $product["price"] ?>
                                        </span>
                                    </div>
                                    <div class="flex space-x-0 self-end font-bold text-lg text-red-500">
                                        <span id="<?= "tax-".$id; ?>">
                                            <?= $product["tax"] ?>
                                        </span>
                                        <span>%</span>
                                    </div>
                                    <img src="<?= 'admin/images/'.$product["image"] ?>" id="<?= "image-".$id; ?>" class=" h-14 w-14 object-cover rounded-md" alt="Product Image">
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </div>
                <div class="w-full lg:w-2/5">
                    <div class="flex flex-row items-center justify-between px-5 mt-5">
                        <div class="font-bold text-xl">Current Order</div>
                        <div class="font-semibold">
                            <span class="px-4 py-2 rounded-md bg-red-100 text-red-500" onclick="containerClean()">
                                Clear All
                            </span>
                        </div>
                    </div>
                    <div class="px-5 py-4 mt-5 overflow-y-auto h-64">
                        <div id="container"></div>
                        <div id="discount-container"></div>
                    </div>
                    <div class="px-5 mt-5">
                        <div class="py-4 rounded-md shadow-lg">
                            <div class=" px-4 flex justify-between">
                                <span class="font-semibold text-sm">Subtotal</span>
                                <span class="font-bold" id="subtotal">$0</span>
                            </div>
                            <div class=" px-4 flex justify-between">
                                <?php if (sizeof($discounts) > 0) { ?>
                                    <span class="font-semibold text-sm">Discount</span>
                                    <img src="/images/discount.png" style="width:20px;margin-right: 0px;position:absolute;right: 430px; visibility: hidden;" onclick="displayApplicableDiscountsModal('discount-modal-id')" id="discount-img">
                                    <div class="hidden overflow-x-hidden overflow-y-auto fixed inset-0 z-50 outline-none focus:outline-none justify-center items-center" id="discount-modal-id" style="overflow: auto;">
                                    </div>
                                    <div class="hidden opacity-25 fixed inset-0 z-40 bg-black" id="discount-modal-id-backdrop"></div>
                                    <span class="font-bold" id="discount-price">- $0.00</span>
                                <?php } ?>
                            </div>
                            <div class=" px-4 flex justify-between">
                                <span class="font-semibold text-sm">Sales Tax</span>
                                <span class="font-bold" id='sales-tax'>+ $0.00</span>
                            </div>
                            <div class="border-t-2 mt-3 py-2 px-4 flex items-center justify-between">
                                <span class="font-semibold text-2xl">Total</span>
                                <span class="font-bold text-2xl" id="total">$0.00</span>
                            </div>
                        </div>
                    </div>
                    <div class="px-5 mt-5">
                        <form method="post">
                            <div id='hidden-form'></div>
                            <div id="discount-form"></div>
                            <button name="submit" class="px-4 py-4 rounded-md shadow-lg text-center bg-yellow-500 text-white font-semibold" style="width: 500px;">Complete Sale</button>
                        </form>
                        <?php if (isset($_SESSION['message'])) { ?>
                            <div id="snackbar"> <?= $_SESSION['message']; ?> </div>
                        <?php } ?>
                    </div>
                </div>
                <?php require 'cart_template.php'; ?>
                <script>productsCount = <?= sizeof($products) ?>;</script>
                <script>discountsCount = <?= sizeof($discounts) ?>;</script>
                <script type="text/javascript" src="custom.js"></script>
                <script>
                    <?php
                        if (isset($_SESSION['message'])) {
                            echo "toast()";
                        }
                    ?>
                </script>
                <?php unset($_SESSION['message']); ?>
            </div>
        </div>
    </body>
    <?php include 'discount.php'; ?>
</html>