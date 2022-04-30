const cart = [];
const discountCart = [];
if ((document.getElementById('subtotal').innerHTML).localeCompare('$0') == 0) {
    document.getElementById('discount-img').style.visibility = 'hidden';
}
document.addEventListener('keydown', (event) => {
  if (event.key === 'Escape') {
    document.getElementById('discount-modal-id').classList.toggle("hidden");
    document.getElementById('discount-modal-id' + "-backdrop").classList.toggle("hidden");
    document.getElementById('discount-modal-id').classList.toggle("flex");
    document.getElementById('discount-modal-id' + "-backdrop").classList.toggle("flex");
  }
})

function containerClean() {
    while (cart.length) {
        cart.pop();
    }
    discountCart.length = 0;
    displayCart();
}

function addToCart(id) {
    if (document.getElementById('stock-' + id).innerHTML > 0) {
        if (existsInArray(id,null)) {
            var index = cart.findIndex((obj => obj.id == id));
            if (document.getElementById('stock-' + id).innerHTML > cart[index].quantity) {
                cart[index].quantity = parseInt(cart[index].quantity) + 1;
            } else {
                alert('Out of stock!!');
            }
            var price = parseInt(document.getElementById('price-' + id).innerHTML.trim());
            price *= cart[index].quantity;
            cart[index].price = price;
        } else {
            const product = {
                "id": id,
                "productId": document.getElementById('id-' + id).innerHTML.trim(),
                "name": document.getElementById('name-' + id).innerHTML.trim(),
                "img": document.getElementById('image-' + id).src,
                "price": document.getElementById('price-' + id).innerHTML.trim(),
                "tax": document.getElementById('tax-' + id).innerHTML.trim(),
                "stock": document.getElementById('stock-'+ id).innerHTML.trim(),
                "quantity": 1
            };
            cart.push(product);
        }
        displayCart();
    } else {
        alert('Stock is not available');
    }
}

function existsInArray(id, name) {
    if (name == null) {
        return cart.some(function (element) {
            return element.id === id;
        });
    }
    if (id == null) {
        return cart.some(function (element) {
            return element.name == name;
        });
    }
}

var minimumSpendAmount = null;
var discountId = null;
var discountTierId = null;
var discountDigit = 0;
var discountPrice = 0;
var discountProductPrice = 0;
var discountProductName = null;
var finalDiscountAmount = 0;
var totalDiscount = 0;
var totalTax = 0;

function discountApply() {
    if (discountCart.length == 0) {
        discountProductPrice = 0;
        discountPrice = 0;
        displayDiscountCart();
    }
    discountThread = document.getElementById('discounts-table');
    discountsCount = document.getElementById('discounts-table').children.length;
    document.getElementById('discount-img').style.visibility = 'hidden';
    for (i = 0; i < discountsCount; i++){
        if (document.getElementById('discount-type-' + (i + 1)) != null) {
            discountType = document.getElementById('discount-type-' + (i + 1)).innerHTML.trim();
            if (discountType == "%") {
                discountDigit = parseInt(document.getElementById('discount-digit-' + (i + 1)).innerHTML.trim());
                discountFinalPrice = (subTotal * discountDigit) / 100;
                document.getElementById('discounts-price-' + (i + 1)).innerHTML = "";
                discountPriceHTML = [
                    '<div>$</div>',
                    '<div id="discount-digit-'+(i+1)+'">'+discountFinalPrice+'</div>'
                ].join("\n");
                document.getElementById('discounts-price-' + (i + 1)).innerHTML = discountPriceHTML;
            }
        }
    }
    for (i = 0; i < discountsCount; i++){
        document.getElementById('discounts-table').children[i].style.display = "none";
        minimumSpendAmount = parseInt(document.getElementById('minimum-spend-amount-' + (i + 1)).innerHTML.trim());
        if (subTotal >= minimumSpendAmount) {
            document.getElementById('discounts-table').children[i].style.display = "table-row";
            document.getElementById('discount-img').style.visibility = 'visible';
            document.getElementById('apply-button-' + (i + 1)).children[0].setAttribute('onclick', 'appliedDiscount(' + i + ');');
        }
    }
    for (i = 0; i < discountsCount; i++){
        minimumSpendAmount = parseInt(document.getElementById('minimum-spend-amount-' + (i + 1)).innerHTML.trim());
        if (subTotal >= minimumSpendAmount) {
            discountProductPrice = 0;
            discountPrice = 0;
            if (discountPrice == 0 && discountProductPrice == 0) {
                if (document.getElementById('discounts-price-' + (i + 1)) != null) {
                    if (document.getElementById('discounts-price-' + (i + 1)).children[1].src == null) {
                        discountId = document.getElementById('discount-id-' + (i + 1)).innerHTML.trim();
                        discountTierId = document.getElementById('discount-tier-id-' + (i + 1)).innerHTML.trim();
                        discountPrice = parseInt(document.getElementById('discount-digit-' + (i + 1)).innerHTML.trim());
                        discountProductPrice = 0;
                        discountProductName = null;
                        document.getElementById('apply-button-' + (i + 1)).children[0].innerHTML = "Applied";
                        document.getElementById('apply-button-' + (i + 1)).children[0].classList.add("bg-green-100","text-green-500");
                        document.getElementById('apply-button-' + (i + 1)).children[0].setAttribute("disabled", true);
                        if (discountPrice >= subTotal) {
                            alert('Your discount Price is greater than the subtotal.');
                            discountPrice = subTotal;
                        }
                        console.log(discountPrice);
                        displayDiscountCart();
                        break;
                    } else {
                        discountProductName = document.getElementById('discount-product-name-' + (i + 1)).innerHTML.trim();
                        if (existsInArray(null, discountProductName)) {
                            discountId = document.getElementById('discount-id-' + (i + 1)).innerHTML.trim();
                            discountTierId = document.getElementById('discount-tier-id-' + (i + 1)).innerHTML.trim();
                            discountProductPrice = document.getElementById('discount-product-price-' + (i + 1)).innerHTML.trim();
                            discountPrice = 0;
                            document.getElementById('apply-button-' + (i + 1)).children[0].innerHTML = "Applied";
                            document.getElementById('apply-button-' + (i + 1)).children[0].classList.add("bg-green-100","text-green-500");
                            document.getElementById('apply-button-' + (i + 1)).children[0].setAttribute("disabled", true);
                            console.log(discountProductPrice);
                            displayDiscountCart();
                            break;
                        }
                    }
                }
            }
        }
    }
}

function appliedDiscount(count) {
    if (document.getElementById('discount-digit-' + (count + 1)) == null) {
        discountProductPrice = document.getElementById('discount-product-price-' + (count + 1)).innerHTML.trim();
        discountProductName = document.getElementById('discount-product-name-' + (count + 1)).innerHTML.trim();
        if (!existsInArray(null, discountProductName)) {
            return alert('Please purchase this item for applied this discount');
        }
    }
    for (i = 0; i < discountsCount; i++) {
        document.getElementById('apply-button-' + (i + 1)).children[0].innerHTML = "Apply";
        document.getElementById('apply-button-' + (i + 1)).children[0].classList.remove("bg-green-100","text-green-500");
        document.getElementById('apply-button-' + (i + 1)).children[0].removeAttribute("disabled");
        document.getElementById('apply-button-' + (i + 1)).children[0].classList.add("bg-red-100","text-red-500");
    }
    document.getElementById('apply-button-' + (count + 1)).children[0].innerHTML = "Applied";
    document.getElementById('apply-button-' + (count + 1)).children[0].classList.add("bg-green-100","text-green-500");
    document.getElementById('apply-button-' + (count + 1)).children[0].setAttribute("disabled", true);
    minimumSpendAmount = parseInt(document.getElementById('minimum-spend-amount-' + (count + 1)).innerHTML.trim());
    if (subTotal >= minimumSpendAmount) {
        if (document.getElementById('discount-digit-' + (count + 1)) != null) {
            discountId = document.getElementById('discount-id-' + (count + 1)).innerHTML.trim();
            discountTierId = document.getElementById('discount-tier-id-' + (count + 1)).innerHTML.trim();
            discountPrice = parseInt(document.getElementById('discount-digit-' + (count + 1)).innerHTML.trim());
            discountProductPrice = 0;
            discountProductName = null;
            if (discountPrice >= subTotal) {
                alert('Your discount Price is greater than the subtotal.');
                discountPrice = subTotal;
            }
        } else {
            discountId = document.getElementById('discount-id-' + (count + 1)).innerHTML.trim();
            discountTierId = document.getElementById('discount-tier-id-' + (count + 1)).innerHTML.trim();
            discountProductPrice = document.getElementById('discount-product-price-' + (count + 1)).innerHTML.trim();
            discountProductName = document.getElementById('discount-product-name-' + (count + 1)).innerHTML.trim();
            discountPrice = 0;
        }
        displayDiscountCart();
    }
}

function displayDiscountCart() {
    if (discountPrice == 0) {
        if (existsInArray(null, discountProductName)) {
            var discountItemFetchFromCart = cart.find(element => element.name == discountProductName);
            discountCart.length = 0;
            var discountItem = {
                'id' : discountItemFetchFromCart.id,
                'img': discountItemFetchFromCart.img,
                'name': discountItemFetchFromCart.name,
                'price' : discountProductPrice
            }
            discountCart.push(discountItem);
        }
    }
    var discountContainer = document.getElementById('discount-container');
    discountContainer.innerHTML = "";
    for (i = 0; i < discountCart.length; i++){
        var discountCartItemHTML = [
            '<div class="flex flex-row justify-between items-center mb-4">',
            '<div class="flex flex-row items-center w-2/5">',
            '<img class="w-11 h-11 object-cover rounded-md" src="' + discountCart[i].img + '" />',
            '<span class="ml-2 font-semibold text-sm">' + discountCart[i].name + '<span class="bg-red-100 text-red-800 text-xs font-semibold ml-2 px-2.5 py-0.5 rounded dark:bg-red-200 dark:text-red-900">Free</span></span>',
            '</div>',
            '<div class="w-32 flex justify-between">',
            '<button class="px-3 py-1 rounded-md bg-gray-300" disabled>-</button>',
            '<input class="mx-2 border text-center w-8 disabled" type="number" disabled value="1">',
            '<button class="px-3 py-1 rounded-md bg-gray-300" disabled>+</button>',
            '</div>',
            '<div class="flex pl-3 font-semibold text-lg w-16 text-center">',
            '<div>$' + discountCart[i].price + '</div>',
            '</div>',
            '<span class="px-3 py-1 rounded-md bg-purple-300 text-white">x</span>',
            '</div>'
        ].join("\n");
        discountContainer.innerHTML += discountCartItemHTML;
    }
    if (discountProductPrice == 0) {
        finalDiscountAmount = discountPrice;
    } else {
        finalDiscountAmount = parseInt(discountProductPrice);
    }
    document.getElementById('discount-price').innerHTML = "- $" + finalDiscountAmount;
    totalDiscount = 0;
    totalTax = 0;
    for (let i = 0; i < cart.length; i++) {
        discount = (parseInt(cart[i].price) * finalDiscountAmount) / subTotal;
        totalDiscount += discount;
        tax = ((parseInt(cart[i].price) - discount) * parseInt(cart[i].tax) / 100);
        totalTax += tax;
    }
    var hiddenFromHTML = [
        '<input type="hidden" name="discounts_id" value="' + discountId + '">',
        '<input type="hidden" name="discounts_tier_id" value="' + discountTierId + '">'
    ].join("\n");
    document.querySelector('#discount-form').innerHTML = hiddenFromHTML;
    grandTotal = subTotal - finalDiscountAmount + totalTax;
    document.getElementById('subtotal').innerHTML = "$" + subTotal;
    document.getElementById('sales-tax').innerHTML = "+ $" + (totalTax).toFixed(2);
    document.getElementById('total').innerHTML = "$" + (grandTotal).toFixed(2);
}

function displayCart() {
    subTotal = 0;

    document.getElementById('container').innerHTML = "";
    document.getElementById('hidden-form').innerHTML = "";

    discountTemplateContainer = document.getElementById('discount-modal-id');
    discountTemplate = document.getElementById('discount-template').innerHTML;
    discountTemplateContainer.innerHTML = discountTemplate;

    for (let i = 0; i < cart.length; i++) {

        var productImg = cart[i].img;
        var productName = cart[i].name;
        var productPrice = cart[i].price;

        var template = document.getElementById('cart-item').innerHTML;
        var container = document.getElementById('container');
        container.innerHTML += template;

        var hiddenTemplate = document.getElementById('cart-hidden').innerHTML;
        var hiddenForm = document.getElementById('hidden-form');
        hiddenForm.innerHTML += hiddenTemplate;

        let hiddenIds = document.querySelectorAll('.product-id');
        hiddenIds.forEach(function (hiddenId, key) {
            hiddenId.value = cart[key].productId;
        });

        let hiddenQuantities = document.querySelectorAll('.product-quantity');
        hiddenQuantities.forEach(function (hiddenQuantity,key) {
            hiddenQuantity.value = cart[key].quantity;
        });

        document.querySelectorAll('.w-10')[i].src = productImg;
        document.querySelectorAll('.w-10')[i].alt = productName;
        document.querySelectorAll('.ml-4')[i].innerHTML = productName;

        let decreaseButtons = document.querySelectorAll('.decrease');
        decreaseButtons.forEach(function (button,key) {
            button.onclick =  function (){ changeQuantity (cart[key].id,"decrease") };
        });

        let quantities = document.querySelectorAll('.input-quantity');
        quantities.forEach(function (quantity, key) {
            quantity.value = cart[key].quantity;
            quantity.onchange = function () { inputQuantity(cart[key].id, (i + 1)) };
            quantity.id = 'input-id-' + (i + 1);
            quantity.max = cart[key].stock;
        });

        let increaseButtons = document.querySelectorAll('.increase');
        increaseButtons.forEach(function (button, key) {
            button.onclick =  function (){ changeQuantity (cart[key].id,"increase") };
        });

        document.querySelectorAll('.currency-sign')[i].innerHTML = "$";
        document.querySelectorAll('.price')[i].innerHTML = productPrice;

        let removeButtons = document.querySelectorAll('.bg-red-300');
        removeButtons.forEach(function (button, key){
            button.onclick = function () { removeFromCart(cart[key].id) };
        });

        subTotal += parseInt((cart[i].price));
    }
    document.getElementById('subtotal').innerHTML = "$" + subTotal;
    discountApply();
}

function inputQuantity(productId, id) {
    var indexOfProduct = cart.findIndex((obj => obj.id == productId));
    if (parseInt(document.getElementById('stock-' + productId).innerHTML.trim()) > parseInt(document.getElementById('input-id-' + id).value)) {
        cart[indexOfProduct].quantity = document.getElementById('input-id-' + id).value;
    } else {
        alert('Available stock is -> ' + document.getElementById('stock-' + productId).innerHTML.trim());
        document.getElementById('input-id-' + id).value = document.getElementById('stock-' + productId).innerHTML.trim();
        cart[indexOfProduct].quantity = document.getElementById('input-id-' + id).value;
    }
    price = parseInt((document.getElementById('price-' + productId).innerHTML).trim()) * cart[indexOfProduct].quantity;
    cart[indexOfProduct].price = price;
    displayCart();
}

function changeQuantity(productId, checked) {
    var indexOfProduct = cart.findIndex((obj => obj.id == productId));
    cart[indexOfProduct].quantity = isNaN(cart[indexOfProduct].quantity) ? 0 : cart[indexOfProduct].quantity;
    if (checked == "increase") {
        if (document.getElementById('stock-' + productId).innerHTML > cart[indexOfProduct].quantity) {
            cart[indexOfProduct].quantity = parseInt(cart[indexOfProduct].quantity) + 1;
        }
        price = parseInt((document.getElementById('price-' + productId).innerHTML).trim()) * cart[indexOfProduct].quantity;
        cart[indexOfProduct].price = price;
        subTotal += price;
        displayCart();
    }
    if (checked == "decrease") {
        cart[indexOfProduct].quantity = isNaN(cart[indexOfProduct].quantity) ? 0 : cart[indexOfProduct].quantity;
        if (cart[indexOfProduct].quantity > 1) {
            cart[indexOfProduct].quantity = parseInt(cart[indexOfProduct].quantity) - 1;
            price = parseInt((document.getElementById('price-' + productId).innerHTML).trim()) * cart[indexOfProduct].quantity;
            cart[indexOfProduct].price = price;
            subTotal += price;
            displayCart();
        } else {
            removeFromCart(productId);
        }
    }
}

function removeFromCart(productId) {
    var indexOfProduct = cart.findIndex((obj => obj.id == productId));
    var indexOfDiscountProduct = discountCart.findIndex((obj => obj.id == productId));
    if (indexOfDiscountProduct > -1) {
        discountCart.splice(indexOfDiscountProduct, 1);
    }
    if (indexOfProduct > -1) {
        cart.splice(indexOfProduct, 1);
    }
    displayCart();
}

function displayApplicableDiscountsModal(modalId) {
    document.getElementById(modalId).classList.toggle("hidden");
    document.getElementById(modalId + "-backdrop").classList.toggle("hidden");
    document.getElementById(modalId).classList.toggle("flex");
    document.getElementById(modalId + "-backdrop").classList.toggle("flex");
}

function toast() {
    var toast = document.getElementById("snackbar");
    toast.className = "show";
    setTimeout(function() {
        toast.className = toast.className.replace("show", "");
    }, 3000);
}

function searchProducts() {
    var input = document.getElementById('searchbar').value.toLowerCase();
    var hasResults = false;
    for (i = 1; i <= productsCount; i++) {
        var name = document.getElementById('name-' + i).innerHTML;
        var category = document.getElementById('category-' + i).innerHTML;
        var price = document.getElementById('price-' + i).innerHTML;
        document.getElementById('products-list-' + i).style.display = 'none';
        if (name.toLowerCase().includes(input) || category.toLowerCase().includes(input) || price.toLowerCase().includes(input)) {
            document.getElementById('products-list-' + i).style.display = 'block';
            hasResults = true;
        }
    }
    document.getElementById('not-available').style.display = hasResults ? 'none' : 'block';
}
