const cart = [];
let subTotal = 0;

function containerClean() {
    document.getElementById('container').innerHTML = "";
    document.getElementById('hidden-form').innerHTML = "";
    document.getElementById('subtotal').innerHTML = "$0";
    document.getElementById('discount-price').innerHTML = "- $0";
    document.getElementById('sales-tax').innerHTML = "$0.00";
    document.getElementById('total').innerHTML = "$0.00";
}

function addToCart(id) {
    if (document.getElementById('stock-' + id).innerHTML > 0) {
        if (existsInArray(id)) {
            var index = cart.findIndex((obj => obj.id == id));
            if (document.getElementById('stock-' + id).innerHTML > cart[index].quantity) {
                cart[index].quantity = parseInt(cart[index].quantity) + 1;
            }
            var price = document.getElementById('price-' + id).innerHTML;
            price = parseInt(price.slice(1));
            price *= cart[index].quantity;
            price = "$" + price;
            cart[index].price = price;
        } else {
            const product = {
                "id": id,
                "productId": document.getElementById('id-' + id).innerHTML,
                "name": document.getElementById('name-' + id).innerHTML,
                "img": document.getElementById('image-' + id).src,
                "price": document.getElementById('price-' + id).innerHTML,
                "tax": document.getElementById('tax-' + id).innerHTML,
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

function displayCart() {
    subTotal = 0;
    totalDiscount = 0;
    totalTax = 0;
    document.getElementById('container').innerHTML = "";
    document.getElementById('hidden-form').innerHTML = "";
    id = 1;
    discountId = document.getElementById('discount-selection').value.trim();
    discountDigit = document.getElementById('discount-option-' + discountId).innerHTML.trim();
    let discount = new Array(cart.length);
    let tax = new Array(cart.length);
    if (discountDigit.substr(discountDigit.length - 1) == "%") {
        discountType = 1;
    } else {
        discountType = 2;
    }
    discountDigit = parseInt(discountDigit.replace(/[$%]/, ''));

    var inputDiscountId = document.createElement('input');
    inputDiscountId.setAttribute('type', 'hidden');
    inputDiscountId.setAttribute('name', 'discount_id');
    inputDiscountId.value = discountId;
    document.getElementById('hidden-form').appendChild(inputDiscountId);

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

        var inputId = document.createElement('input');
        inputId.setAttribute('type', 'hidden');
        inputId.setAttribute('name', 'productId[]');
        inputId.value = cart[i].productId;
        document.getElementById('hidden-form').appendChild(inputId);

        var productImg = cart[i].img;
        var productName = cart[i].name;
        var productPrice = cart[i].price;
        var productQuantity = cart[i].quantity;

        var inputQuantity = document.createElement('input');
        inputQuantity.setAttribute('type', 'hidden');
        inputQuantity.setAttribute('name', 'productQuantity[]');
        inputQuantity.value = productQuantity;
        document.getElementById('hidden-form').appendChild(inputQuantity);

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
        decreaseButton.setAttribute('onclick', 'changeQuantity(' + productId + ',' + '"decrease"' + ')');
        decreaseButton.innerHTML = "-";
        document.getElementById('div-sub-second-' + id).appendChild(decreaseButton);

        var inputTag = document.createElement('input');
        inputTag.setAttribute('class', 'mx-2 border text-center w-8');
        inputTag.setAttribute('id', 'input-id-' + id);
        inputTag.setAttribute('type', 'number');
        inputTag.setAttribute('max', document.getElementById('stock-' + id).innerHTML);
        inputTag.setAttribute('onchange', 'inputQuantity(' + productId + ',' + id + ')');
        inputTag.setAttribute('value', productQuantity);
        document.getElementById('div-sub-second-' + id).appendChild(inputTag);

        var increaseButton = document.createElement('button');
        increaseButton.setAttribute('class', 'px-3 py-1 rounded-md bg-gray-300');
        increaseButton.setAttribute('onclick', 'changeQuantity(' + productId + ',' + '"increase"' + ')');
        increaseButton.innerHTML = "+";
        document.getElementById('div-sub-second-' + id).appendChild(increaseButton);


        var divMainSubThird = document.createElement('div');
        divMainSubThird.setAttribute('class', 'font-semibold text-lg w-16 text-center');
        divMainSubThird.setAttribute('id', 'div-sub-third-' + id);
        divMainSubThird.innerHTML = productPrice;
        document.getElementById('div-main-' + id).appendChild(divMainSubThird);

        var deleteIcon = document.createElement('span');
        deleteIcon.setAttribute('class', 'px-3 py-1 rounded-md bg-red-300 text-white');
        deleteIcon.setAttribute('onclick', 'removeFromCart(' + productId + ')');
        deleteIcon.innerHTML = "x";
        document.getElementById('div-main-' + id).appendChild(deleteIcon);

        id++;
    }
    if (subTotal > discountDigit) {
        if (discountType == "2") {
            discountPrice = discountDigit;
        } else {
            discountPrice = (subTotal * discountDigit) / 100;
        }
        for (let i = 0; i < cart.length; i++) {
            discount[i] = (parseInt((cart[i].price).slice(1)) * discountPrice) / subTotal;
            totalDiscount += discount[i];
            tax[i] = ((parseInt((cart[i].price).slice(1)) - discount[i]) * parseInt(cart[i].tax.slice(0, 2)) / 100);
            totalTax += tax[i];
        }
    }
    grandTotal = subTotal - totalDiscount + totalTax;
    document.getElementById('subtotal').innerHTML = "$" + subTotal;
    document.getElementById('sales-tax').innerHTML = "+ $" + (totalTax).toFixed(2);
    document.getElementById('discount-price').innerHTML = "- $" + (totalDiscount).toFixed(2);
    document.getElementById('total').innerHTML = "$" + (grandTotal).toFixed(2);
}

function inputQuantity(productId, id) {
    var indexOfProduct = cart.findIndex((obj => obj.id == productId));
    if (document.getElementById('stock-' + productId).innerHTML > document.getElementById('input-id-' + id).value) {
        cart[indexOfProduct].quantity = document.getElementById('input-id-' + id).value;
    } else {
        document.getElementById('input-id-' + id).value = document.getElementById('stock-' + productId).innerHTML;
        cart[indexOfProduct].quantity = document.getElementById('input-id-' + id).value;
    }
    price = parseInt((document.getElementById('price-' + productId).innerHTML).slice(1)) * cart[indexOfProduct].quantity;
    cart[indexOfProduct].price = '$' + price;
    displayCart();
}

function changeQuantity(productId, checked) {
    var indexOfProduct = cart.findIndex((obj => obj.id == productId));
    cart[indexOfProduct].quantity = isNaN(cart[indexOfProduct].quantity) ? 0 : cart[indexOfProduct].quantity;
    if (checked == "increase") {
        if (document.getElementById('stock-' + productId).innerHTML > cart[indexOfProduct].quantity) {
            cart[indexOfProduct].quantity = parseInt(cart[indexOfProduct].quantity) + 1;
        }
        price = parseInt((document.getElementById('price-' + productId).innerHTML).slice(1)) * cart[indexOfProduct].quantity;
        cart[indexOfProduct].price = '$' + price;
        subTotal += price;
        displayCart();
    }
    if (checked == "decrease") {
        cart[indexOfProduct].quantity = isNaN(cart[indexOfProduct].quantity) ? 0 : cart[indexOfProduct].quantity;
        if (cart[indexOfProduct].quantity > 1) {
            cart[indexOfProduct].quantity = parseInt(cart[indexOfProduct].quantity) - 1;
            price = parseInt((document.getElementById('price-' + productId).innerHTML).slice(1)) * cart[indexOfProduct].quantity;
            cart[indexOfProduct].price = '$' + price;
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

function discountModal(modalId) {
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

function discountApply() {
    displayCart();
}
