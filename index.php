<?php require 'admin/layout/db_connect.php'; ?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
    <link rel="manifest" href="/site.webmanifest">
    <link rel="mask-icon" href="/safari-pinned-tab.svg" color="#0ed3cf">
    <meta name="msapplication-TileColor" content="#0ed3cf">
    <meta name="theme-color" content="#0ed3cf">
    <meta property="og:image" content="https://tailwindcomponents.com/storage/2190/temp35198.png?v=2022-01-04 10:47:18" />
    <meta property="og:image:width" content="1280" />
    <meta property="og:image:height" content="640" />
    <meta property="og:image:type" content="image/png" />
    <meta property="og:url" content="https://tailwindcomponents.com/component/point-of-sale-system/landing" />
    <meta property="og:title" content="Dashboard Point of sale system by rahulsya" />
    <meta property="og:description" content="template design from dribble https://dribbble.com/shots/14139155-Point-of-Sale-System-Design/attachments/5763503?mode=media" />
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:site" content="@TwComponents" />
    <meta name="twitter:title" content="Dashboard Point of sale system by rahulsya" />
    <meta name="twitter:description" content="template design from dribble https://dribbble.com/shots/14139155-Point-of-Sale-System-Design/attachments/5763503?mode=media" />
    <meta name="twitter:image" content="https://tailwindcomponents.com/storage/2190/temp35198.png?v=2022-01-04 10:47:18" />
    <title>Retail Shop</title>
    <link rel="stylesheet" href="https://unpkg.com/tailwindcss@2.0.2/dist/tailwind.min.css">
    <script type="text/javascript">
        const cart = [];

        function addToCart(id) {
            const product = {
                "id": id,
                "name": document.getElementById('name-' + id).innerHTML,
                "img": document.getElementById('image-' + id).src,
                "price": document.getElementById('price-' + id).innerHTML,
                "quantity": 1
            };
            if (existsInArray(id)) {
                var index = cart.findIndex((obj => obj.id == id));
                cart[index].quantity += 1;
                var price = document.getElementById('price-' + id).innerHTML;
                price = parseInt(price.slice(1));
                price *= cart[index].quantity;
                price = "$" + price;
                cart[index].price = price;
            } else {
                cart.push(product);
            }
            displayCart();
        }

        function existsInArray(id) {
            return cart.some(function(element) {
                return element.id === id;
            });
        }

        function displayCart() {

            document.getElementById('container').innerHTML = "";
            let id = 1;
            let subTotal = 0;
            for (let i = 0; i < cart.length; i++) {

                var divMain = document.createElement('div');
                divMain.setAttribute('class', 'flex flex-row justify-between items-center mb-4');
                divMain.setAttribute('id', 'div-main-' + id);
                document.getElementById('container').appendChild(divMain);
                var divMainSubFirst = document.createElement('div');
                divMainSubFirst.setAttribute('class', 'flex flex-row items-center w-2/5');
                divMainSubFirst.setAttribute('id', 'div-sub-first-' + id);
                document.getElementById('div-main-' + id).appendChild(divMainSubFirst);
                var productId = cart[i].id;
                var productImg = cart[i].img;
                var productName = cart[i].name;
                var productPrice = cart[i].price;
                var productQuantity = cart[i].quantity;

                subTotal += parseInt((cart[i].price).slice(1));

                var imageTag = document.createElement('img');
                imageTag.src = productImg;
                imageTag.setAttribute('class', 'w-10 h-10 object-cover rounded-md');
                imageTag.setAttribute('alt', productName);
                document.getElementById('div-sub-first-' + id).appendChild(imageTag);
                var spanTag = document.createElement('span');
                spanTag.setAttribute('class', 'ml-4 font-semibold text-sm');
                spanTag.innerHTML = productName;
                document.getElementById('div-sub-first-' + id).appendChild(spanTag);
                var divMainSubSecond = document.createElement('div');
                divMainSubSecond.setAttribute('class', 'w-32 flex justify-between');
                divMainSubSecond.setAttribute('id', 'div-sub-second-' + id);
                document.getElementById('div-main-' + id).appendChild(divMainSubSecond);
                var decreaseButton = document.createElement('button');
                decreaseButton.setAttribute('class', 'px-3 py-1 rounded-md bg-gray-300');
                decreaseButton.setAttribute('onclick', 'changeQuantity(' + productId + ',' + id + ',' + '"decrease"' + ')');
                decreaseButton.innerHTML = "-";
                document.getElementById('div-sub-second-' + id).appendChild(decreaseButton);
                var inputTag = document.createElement('input');
                inputTag.setAttribute('class', 'mx-2 border text-center w-8');
                inputTag.setAttribute('id', 'input-id-' + id);
                inputTag.setAttribute('value', productQuantity);
                document.getElementById('div-sub-second-' + id).appendChild(inputTag);
                var increaseButton = document.createElement('button');
                increaseButton.setAttribute('class', 'px-3 py-1 rounded-md bg-gray-300');
                increaseButton.setAttribute('onclick', 'changeQuantity(' + productId + ',' + id + ',' + '"increase"' + ')');
                increaseButton.innerHTML = "+";
                document.getElementById('div-sub-second-' + id).appendChild(increaseButton);
                var divMainSubThird = document.createElement('div');
                divMainSubThird.setAttribute('class', 'font-semibold text-lg w-16 text-center');
                divMainSubThird.setAttribute('id', 'div-sub-third-' + id);
                divMainSubThird.innerHTML = productPrice;
                document.getElementById('div-main-' + id).appendChild(divMainSubThird);

                document.getElementById('subtotal').innerHTML = "$" + subTotal;
                id++;
            }
        }

        function changeQuantity(productId, id, checked) {
            var indexOfProduct = cart.findIndex((obj => obj.id == productId));
            cart[indexOfProduct].quantity = isNaN(cart[indexOfProduct].quantity) ? 0 : cart[indexOfProduct].quantity;
            if (checked == "increase") {
                cart[indexOfProduct].quantity += 1;
            }
            if (checked == "decrease") {
                cart[indexOfProduct].quantity = isNaN(cart[indexOfProduct].quantity) ? 0 : cart[indexOfProduct].quantity;
                cart[indexOfProduct].quantity < 1 ? cart[indexOfProduct].quantity = 1 : '';
                cart[indexOfProduct].quantity -= 1;
            }
            price = parseInt((document.getElementById('price-' + id).innerHTML).slice(1)) * cart[indexOfProduct].quantity;
            cart[indexOfProduct].price = '$' + price;
            displayCart();
        }
    </script>
</head>

<body class="bg-gray-200">
<<<<<<< HEAD
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
                                    $fetch = $pdo->prepare("select name from category where id = {$product["category"]}");
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
                                <span class="self-end font-bold text-lg text-yellow-500" id="<?= "price-" . $id; ?>"><?= "$" . $product["price"] ?></span>
                                <img src="<?= 'admin/images/' . $product["image"] ?>" id="<?= "image-" . $id; ?>" class=" h-14 w-14 object-cover rounded-md" alt="">
                            </div>
                        </div>
                    <?php
                    } ?>
                </div>
=======
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
                  $fetch = $pdo->prepare("select name from category where id = {$product["category"]}");
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
                <span class="self-end font-bold text-lg text-yellow-500" id="<?= "price-" . $id; ?>"><?= "$" . $product["price"] ?></span>
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
              <span class="font-bold">$0</span>
              <span class="font-bold" id="subtotal">$0</span>
            </div>

>>>>>>> b6dc076112ae49f8e2dbd1537abe8e0f82902ea6
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
                            <span class="font-bold">$0</span>
                            <span class="font-bold" id="subtotal">$0</span>
                        </div>
                        <div class=" px-4 flex justify-between ">
                            <span class="font-semibold text-sm">Discount</span>
                            <span class="font-bold">- $0</span>
                        </div>
                        <div class=" px-4 flex justify-between ">
                            <span class="font-semibold text-sm">Sales Tax</span>
                            <span class="font-bold">$0</span>
                        </div>
                        <div class="border-t-2 mt-3 py-2 px-4 flex items-center justify-between">
                            <span class="font-semibold text-2xl">Total</span>
                            <span class="font-bold text-2xl">$0</span>
                        </div>
                    </div>
                </div>
                <div class="px-5 mt-5">
                    <div class="px-4 py-4 rounded-md shadow-lg text-center bg-yellow-500 text-white font-semibold">
                        Pay With Cashless Credit
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>