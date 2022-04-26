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
                "productId": document.getElementById('id-' + id).innerHTML,
                "name": document.getElementById('name-' + id).innerHTML,
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

function displayCart() {
    subTotal = 0;
    totalDiscount = 0;
    totalTax = 0;
    discountPrice = 0;
    document.getElementById('container').innerHTML = "";
    document.getElementById('hidden-form').innerHTML = "";
    id = 1;

    let discount = 0;
    let tax = 0;

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

        subTotal += parseInt((cart[i].price));

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
        document.getElementById('div-main-' + id).appendChild(deleteIcon);

        id++;
    }

    const applyMinimumSpendAmounts = [];
    var minimumSpendAmount = null;
    var discountDigit = 0;
    var discountType = null;
    var discountId = null;
    var discountTierId = null;
    var minimum

    for (i = 0; i < discountsCount; i++) {
        minimumSpendAmounts = parseInt(document.getElementById('minimum-spend-amount-' + (i + 1)).innerHTML.trim());
        if (subTotal >= minimumSpendAmounts) {
            applyMinimumSpendAmounts[i] = minimumSpendAmounts;
        }
    }

    var count = [];
    var discountThread = document.getElementById('discounts-table');
    for (i = 0; i < discountsCount; i++) {
        discountThread.children[i].style.display = "none";
        if (parseInt(discountThread.children[i].children[3].innerHTML.trim()) == Math.max(...applyMinimumSpendAmounts)) {
            document.getElementById('discount-img').style.visibility = 'visible';
            minimumSpendAmount = Math.max(...applyMinimumSpendAmounts);
            discountId = parseInt(document.getElementById('discounts-table').children[i].children[0].innerHTML.trim());
            discountTierId = parseInt(document.getElementById('discount-tier-id-' + (i + 1)).innerHTML.trim());
            if (document.getElementById('discounts-table').children[i].children[4].children.length == 1)
                {
                    count[i] = i;
                }
            if (document.getElementById('discount-' + (i + 1)) != null) {
                discountDigit = parseInt(document.getElementById('discount-' + (i + 1)).innerHTML.trim());
                discountType = document.getElementById('discount-type-' + (i + 1)).innerHTML.trim();
            }
            discountThread.children[i].style.display = "table-row";
        }
    }
    for (i = 0; i < count.length; i++){
        if (count[i] != undefined  && count.length > 1){
            console.log(document.getElementById('discounts-table').children[i].children[4].children[0].alt);
        }
    }

    if (discountId != null) {
        var inputDiscountId = document.createElement('input');
        inputDiscountId.setAttribute('type', 'hidden');
        inputDiscountId.setAttribute('name', 'discount_id');
        inputDiscountId.value = discountId;
        document.getElementById('hidden-form').appendChild(inputDiscountId);

        var inputDiscountTierId = document.createElement('input');
        inputDiscountTierId.setAttribute('type', 'hidden');
        inputDiscountTierId.setAttribute('name', 'discount_tier_id');
        inputDiscountTierId.value = discountTierId;
        document.getElementById('hidden-form').appendChild(inputDiscountTierId);
    }

    if (minimumSpendAmount != null) {
        if (minimumSpendAmount <= subTotal) {
            if (discountType == "%" && subTotal > ((subTotal * parseInt(discountDigit)) / 100)) {
                discountPrice = (subTotal * parseInt(discountDigit)) / 100;
            }
            if (discountType == "$" && subTotal > discountDigit) {
                discountPrice = parseInt(discountDigit);
            }
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
