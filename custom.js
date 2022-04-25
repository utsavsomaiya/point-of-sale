const cart = [];
if ((document.getElementById('subtotal').innerHTML).localeCompare('$0') == 0) {
    document.getElementById('discount-img').style.visibility = 'hidden';
}

function containerClean() {
    while (cart.length) {
        cart.pop();
    }
    displayCart();
}

function addToCart(id) {
    if (document.getElementById('stock-' + id).innerHTML > 0) {
        if (existsInArray(id)) {
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

function existsInArray(id) {
    return cart.some(function (element) {
        return element.id === id;
    });
}
const discountDigits = [];
const discountIds = [];
const discountTierIds = [];
const minimumSpendAmounts = [];
const discountTypes = [];
function displayCart() {
    subTotal = 0;
    totalDiscount = 0;
    totalTax = 0;
    discountPrice = 0;
    document.getElementById('container').innerHTML = "";
    document.getElementById('hidden-form').innerHTML = "";

    document.getElementById('discount-img').style.visibility = 'visible';

    let discount = 0;
    let tax = 0;

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
        increaseButtons.forEach(function (button,key) {
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

    for (i = 0; i < discountsCount; i++) {
        minimumSpendAmounts[i] = parseInt(document.getElementById('minimum-spend-amount-' + (i + 1)).innerHTML.trim());
        discountDigits[i] = parseInt(document.getElementById('discount-' + (i + 1)).innerHTML.trim());
        discountTypes[i] = document.getElementById('discount-type-' + (i + 1)).innerHTML.trim();
        discountIds[i] = document.getElementById('discount-id-' + (i + 1)).innerHTML.trim();
        discountTierIds[i] = document.getElementById('discount-tier-id-' + (i + 1)).innerHTML.trim();
    }

    for (i = 0; i < discountsCount; i++) {
        if (subTotal >= minimumSpendAmounts[i]) {
            minimumSpendAmount = minimumSpendAmounts[i];
            discountId = discountIds[i]
            discountDigit = discountDigits[i];
            discountTierId = discountTierIds[i];
            discountType = discountTypes[i];
        }
    }

    var count = 0;
    for (i = 0; i < discountsCount; i++) {
        if (subTotal >= minimumSpendAmounts[i]) {
            discountThread = document.getElementById('discounts-table');
            count = i;
        }
    }
    for (i = 0; i < discountsCount; i++) {
        discountThread.children[i].style.display = "none";
        discountThread.children[count].style.display = "table-row";
    }

    let inputDiscountId = document.querySelectorAll('.discount-id');
    inputDiscountId.forEach(function (id) {
        id.value = discountId;
    });

    let inputDiscountTierId = document.querySelectorAll('.discount-tier-id');
    inputDiscountTierId.forEach(function (tierId){
        tierId.value = discountTierId;
    });

    if (minimumSpendAmount <= subTotal && subTotal > discountDigit) {
        discountPrice = (subTotal * parseInt(discountDigit)) / 100;
        if (discountType == "$") {
            discountPrice = parseInt(discountDigit);
        }
    }
    for (let i = 0; i < cart.length; i++) {
        discount = (parseInt(cart[i].price) * discountPrice) / subTotal;
        totalDiscount += discount;
        tax = ((parseInt(cart[i].price) - discount) * parseInt(cart[i].tax) / 100);
        totalTax += tax;
    }
    grandTotal = subTotal - totalDiscount + totalTax;
    document.getElementById('subtotal').innerHTML = "$" + subTotal;
    document.getElementById('sales-tax').innerHTML = "+ $" + (totalTax).toFixed(2);
    document.getElementById('discount-price').innerHTML = "- $" + (totalDiscount).toFixed(2);
    document.getElementById('total').innerHTML = "$" + (grandTotal).toFixed(2);

    if ((document.getElementById('subtotal').innerHTML).localeCompare('$0') == 0) {
        document.getElementById('discount-img').style.visibility = 'hidden';
    }
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
    if (indexOfProduct > -1) {
        cart.splice(indexOfProduct, 1);
        displayCart();
    }
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
