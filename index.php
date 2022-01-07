<?php require 'admin/layout/db_connect.php';?>
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
        function array(id) {
          const product = {
            "id": id,
            "name": document.getElementById('name-' + id).innerHTML,
            "img": document.getElementById('image-' + id).src,
            "price": document.getElementById('price-' + id).innerHTML,
            "quantity" : 1
          };
          if (!add_to_array(cart, id)) {
            cart.push(product);
          } else {
            var index = cart.findIndex((obj => obj.name == document.getElementById('name-' + id).innerHTML));
            cart[index].quantity += 1;
            var price = document.getElementById('price-' + id).innerHTML;
            price = parseInt(price.slice(1));
            price *= cart[index].quantity;
            price = "$" + price;
            cart[index].price = price;
          }
          displayCart(id);
        }
        function add_to_array(cart,id){
           return cart.some(function(e1){
             return e1.name === document.getElementById('name-' + id).innerHTML;
          });
        }
        function displayCart(id) {

          document.getElementById('container').innerHTML = "";

          for (let i = 0; i < cart.length; i++) {

            var div_main = document.createElement('div');
            div_main.setAttribute('class', 'flex flex-row justify-between items-center mb-4');
            div_main.setAttribute('id', 'div_main_' + id);
            document.getElementById('container').appendChild(div_main);

            var div_main_sub_first = document.createElement('div');
            div_main_sub_first.setAttribute('class', 'flex flex-row items-center w-2/5');
            div_main_sub_first.setAttribute('id', 'div_sub_first_' + id);
            document.getElementById('div_main_' + id).appendChild(div_main_sub_first);

            var product_id = cart[i].id;
            var product_img = cart[i].img;
            var product_name = cart[i].name;
            var product_price = cart[i].price;
            var product_quantity = cart[i].quantity;

            var img = document.createElement('img');
            img.src = product_img;
            img.setAttribute('class', 'w-10 h-10 object-cover rounded-md');
            img.setAttribute('alt', product_name);
            document.getElementById('div_sub_first_' + id).appendChild(img);

            var span = document.createElement('span');
            span.setAttribute('class', 'ml-4 font-semibold text-sm');
            var name_text = document.createTextNode(product_name);
            span.appendChild(name_text);
            document.getElementById('div_sub_first_' + id).appendChild(span);

            var div_main_sub_second = document.createElement('div');
            div_main_sub_second.setAttribute('class', 'w-32 flex justify-between');
            div_main_sub_second.setAttribute('id', 'div_sub_second_' + id);
            document.getElementById('div_main_' + id).appendChild(div_main_sub_second);

            var button_1 = document.createElement('button');
            button_1.setAttribute('class', 'px-3 py-1 rounded-md bg-gray-300');
            button_1.setAttribute('onclick', 'minus()');
            button_1.innerHTML = "-";
            document.getElementById('div_sub_second_' + id).appendChild(button_1);

            var input = document.createElement('input');
            input.setAttribute('class', 'mx-2 border text-center w-8');
            input.setAttribute('id','input_id_'+id);
            input.setAttribute('value',product_quantity);
            document.getElementById('div_sub_second_' + id).appendChild(input);

            var button_2 = document.createElement('button');
            button_2.setAttribute('class', 'px-3 py-1 rounded-md bg-gray-300');
            button_2.setAttribute('onclick', 'plus()');
            button_2.innerHTML = "+";
            document.getElementById('div_sub_second_' + id).appendChild(button_2);


            var div_main_sub_third = document.createElement('div');
            div_main_sub_third.setAttribute('class', 'font-semibold text-lg w-16 text-center');
            div_main_sub_third.innerHTML = product_price;
            document.getElementById('div_main_' + id).appendChild(div_main_sub_third);

            id++;
          }
          //input_value = 1;
        }

    </script>
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
            <div class="px-3 py-3 flex flex-col border border-gray-200 rounded-md h-32 justify-between" onclick="array(<?php echo $id; ?>)">
              <div>
                <div class="font-bold text-gray-800" id="<?= "name-".$id; ?>"><?= $product["name"] ?></div>
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
                <span class="self-end font-bold text-lg text-yellow-500" id="<?= "price-".$id; ?>"><?= "$".$product["price"] ?></span>
                <img src="<?= 'admin/images/'.$product["image"] ?>" id="<?= "image-".$id; ?>" class=" h-14 w-14 object-cover rounded-md" alt="">
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
