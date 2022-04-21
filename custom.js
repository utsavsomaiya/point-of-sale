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

        var productId = cart[i].id;
        var productImg = cart[i].img;
        var productName = cart[i].name;
        var productPrice = cart[i].price;
        var productQuantity = cart[i].quantity;

        var template = document.getElementById('cart-item').innerHTML;
        var container = document.getElementById('container');
        var hiddenTemplate = document.getElementById('cart-hidden').innerHTML;
        var hiddenForm = document.getElementById('hidden-form');
        container.innerHTML += template;
        hiddenForm.innerHTML += hiddenTemplate;

        document.querySelector('.w-10').src = productImg;
        document.querySelector('.w-10').alt = productName;

        document.querySelector('.ml-4').innerHTML = productName;

        document.querySelectorAll('.bg-gray-300')[0].onclick = function () { changeQuantity(productId, "decrease") };

        document.querySelector('input[type="number"]').value = productQuantity;
        document.querySelector('input[type="number"]').onchange = function () { inputQuantity(productId, (i+1)) };
        document.querySelector('input[type="number"]').setAttribute('id', 'input-id-' + (i + 1));
        document.querySelector('input[type="number"]').setAttribute('max', document.getElementById('stock-' + id).innerHTML);

        document.querySelectorAll('.bg-gray-300')[0].onclick = function () { };

        /* subTotal += parseInt((cart[i].price)); */


        /*

        var increaseButton = document.createElement('button');
        increaseButton.setAttribute('class', 'px-3 py-1 rounded-md bg-gray-300');
        increaseButton.setAttribute('onclick', 'changeQuantity(' + productId + ',' + '"increase"' + ')');
        increaseButton.innerHTML = "+";
        document.getElementById('div-sub-second-' + id).appendChild(increaseButton);

        var divMainSubThird = document.createElement('div');
        divMainSubThird.setAttribute('class', 'flex pl-3 font-semibold text-lg w-16 text-center');
        divMainSubThird.setAttribute('id', 'div-sub-third-' + id);
        document.getElementById('div-main-' + id).appendChild(divMainSubThird);

        var divPriceSign = document.createElement('div');
        divPriceSign.setAttribute('id', 'div-sign-' + id);
        divPriceSign.innerHTML = "$";
        document.getElementById('div-sub-third-' + id).appendChild(divPriceSign);

        var divPrice = document.createElement('div');
        divPrice.setAttribute('id', 'div-price-' + id);
        divPrice.innerHTML = productPrice;
        document.getElementById('div-sub-third-' + id).appendChild(divPrice);


        var deleteIcon = document.createElement('span');
        deleteIcon.setAttribute('class', 'px-3 py-1 rounded-md bg-red-300 text-white');
        deleteIcon.setAttribute('onclick', 'removeFromCart(' + productId + ')');
        deleteIcon.innerHTML = "x";
        document.getElementById('div-main-' + id).appendChild(deleteIcon); */

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
    /* for (i = 0; i < discountsCount; i++) {
        discountThread.children[i].style.display = "none";
        discountThread.children[count].style.display = "table-row";
    } */


    /* var inputDiscountId = document.createElement('input');
    inputDiscountId.setAttribute('type', 'hidden');
    inputDiscountId.setAttribute('name', 'discount_id');
    inputDiscountId.value = discountId;
    document.getElementById('hidden-form').appendChild(inputDiscountId);

    var inputDiscountTierId = document.createElement('input');
    inputDiscountTierId.setAttribute('type', 'hidden');
    inputDiscountTierId.setAttribute('name', 'discount_tier_id');
    inputDiscountTierId.value = discountTierId;
    document.getElementById('hidden-form').appendChild(inputDiscountTierId); */

    /* if (minimumSpendAmount <= subTotal && subTotal > discountDigit) {
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
    } */
}

function inputQuantity(productId, id) {
    var indexOfProduct = cart.findIndex((obj => obj.id == productId));
    if (parseInt(document.getElementById('stock-' + productId).innerHTML.trim()) > parseInt(document.getElementById('input-id-' + id).value)) {
        cart[indexOfProduct].quantity = document.getElementById('input-id-' + id).value;
    } else {
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

/* function discountApply(count) {

    discountId = document.getElementById('discount-id-' + count).innerHTML.trim();
    discountDigit = document.getElementById('discount-' + count).innerHTML.trim();
    discountType = document.getElementById('discount-type-' + count).innerHTML.trim();
    minimumSpendAmount = document.getElementById('minimum-spend-amount-' + count).innerHTML.trim();

    for (i = 1; i <= discountsCount; i++){
        if (count != i) {
            document.getElementById('discount-button-' + count).setAttribute('class', 'bg-green-500 text-white font-bold py-2 px-4 rounded-full');
            document.getElementById('discount-button-' + count).innerHTML = "Applied";
            document.getElementById('discount-button-' + count).disabled = true;

           document.getElementById('discount-button-' + i).setAttribute('class', 'bg-red-500 text-white font-bold py-2 px-4 rounded-full');
            document.getElementById('discount-button-' + i).innerHTML = "Apply";
            document.getElementById('discount-button-' + i).disabled = false;
        }
    }
    displayCart();
} */

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
