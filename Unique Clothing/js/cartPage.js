let label = document.getElementById('label');
let ShoppingCart = document.getElementById('shopping-cart');
let heartCount = JSON.parse(localStorage.getItem("heartStatus")) || [];
let basket = JSON.parse(localStorage.getItem("data")) || [];


  
// Total count of customer items
let calculation = () => {
    let cartIcon = document.getElementById("cartAmount");
    cartIcon.innerHTML = basket.map((x) => x.item).reduce((x,y)=> x + y, 0);
};
calculation();

function toggleHeart(event, id) {
    event.target.classList.toggle("red");

    if (event.target.classList.contains("red")) {
        heartCount.push(id);
    } else if (heartCount.includes(id)) {
        heartCount = heartCount.filter(item => item !== id);
    }

    localStorage.setItem("heartStatus", JSON.stringify(heartCount));
    document.getElementById("wishlistAmount").innerHTML = heartCount.length;
}

let generateCartItems = () => {
    if (basket.length !== 0) {
        return (ShoppingCart.innerHTML = basket.map((cartItem) => {
            let { id, item, size } = cartItem;
            let search = kidsItemsData.find((x) => x.id === id) || womenItemsData.find((y) => y.id === id) || menItemsData.find((z) => z.id === id) || [];
            return `
                <tbody>
                    <tr class="cart-item" data-item-id="${id}">
                        <td>
                            <img width="100px" height="100px" src="${search.image}" alt="" />
                        </td>
                        <td>
                            <p>${search.name}</p>
                        </td>
                        <td>
                            <p>${size}</p>
                        </td>
                        <td>
                            <p>RM${search.price}</p>
                        </td>
                        <td>
                            <div class="buttons">
                                <i onclick="decrement('${id}')" class="fa-solid fa-minus"></i>
                                <div id="${id}" class="quantity">${cartItem.item}</div>
                                <i onclick="increment('${id}')" class="fa-solid fa-plus"></i>
                            </div>
                        </td>
                        <td>
                            <p>RM ${(cartItem.item * search.price).toFixed(2)}</p> 
                        </td>
                    </tr>
                </tbody>`;
        }).join(""));
    } else {
        ShoppingCart.innerHTML = `
        <table>
            <tr>
                <td>N/A</td>
                <td>N/A</td>
                <td>N/A</td>
                <td>N/A</td>
                <td>N/A</td>
                <td>N/A</td>
            </tr>
        </table>`;
        
        label.innerHTML = `
        <h2 style="text-align:center;">Cart is Empty</h2>
        <a href="Product_Women.html">
            <button class="HomeBtn">Back to home</button>
        </a>`;
    }
};
generateCartItems();

let increment = (id) => {
    let selectedItem = { id };
    let search = basket.find((item) => item.id === selectedItem.id);

    if (search === undefined) {
        basket.push({
            id: selectedItem.id,
            size: selectedItem.size,
            item: 1,
        });
    } else {
        search.item += 1;
    }

    generateCartItems();
    update(selectedItem.id);
    localStorage.setItem("data", JSON.stringify(basket));
};

let decrement = (id) => {
    let selectedItem = { id };
    let search = basket.find((item) => item.id === selectedItem.id);

    if (search === undefined)
        return;
    else if (search.item === 0) {
        return;
    } else {
        search.item -= 1;
    }

    update(selectedItem.id);
    basket = basket.filter((item) => item.item !== 0);
    generateCartItems();
    localStorage.setItem("data", JSON.stringify(basket));
};

let update = (id) => {
    let search = basket.find((x) => x.id === id);
    document.getElementById(id).innerHTML = search.item;
    calculation();
    TotalAmount();
};

let clearCart = () => {
    basket = [];
    generateCartItems();
    localStorage.setItem("data", JSON.stringify(basket));
};

let payment = () => {
    localStorage.setItem("callDisplayCartItems", "true");
    window.location.href = "payment.html";
}; 

let TotalAmount = () => {
    if (basket.length !== 0) {
        let amount = basket.map((x) => {
            let { item, id } = x;
            let search = kidsItemsData.find((x) => x.id === id) || womenItemsData.find((y) => y.id === id) || menItemsData.find((z) => z.id === id) || [];
            return item * search.price;
        }).reduce((x, y) => x + y, 0);
        
        label.innerHTML = `
        <h2 class="totalPrice">Total Price: RM ${amount.toFixed(2)}</h2>
        <button onclick="clearCart()" class="removeAll">Clear Cart</button><button id="displayCartButton" onclick="payment()" class="checkout">Checkout</button>`;
    } else return;
};
TotalAmount();
window.onload = document.getElementById("wishlistAmount").innerHTML = heartCount.length;
