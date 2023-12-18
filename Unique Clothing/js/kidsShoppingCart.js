let shop = document.getElementById('shop');

let basket = JSON.parse(localStorage.getItem("data")) || [];

let heartCount = JSON.parse(localStorage.getItem("heartStatus")) || [];

document.addEventListener('DOMContentLoaded', function() {
    const navItems = document.querySelectorAll('.product_bar li');
  
    navItems.forEach(item => {
      item.addEventListener('click', function() {
        // Remove 'selected' class from all items
        navItems.forEach(item => {
          item.classList.remove('selected');
        });
        
        // Add 'selected' class to the clicked item
        this.classList.add('selected');
      });
    });
  
    // Check the URL to determine the initial selected category
    const currentPage = window.location.href;
    if (currentPage.includes("Product_Men.html")) {
      document.querySelector('#mens').classList.add('selected');
    }else if(currentPage.includes("Product_Kids.html")){
        document.querySelector('#kids').classList.add('selected');
    }else if(currentPage.includes("Product_Women.html"))
        document.querySelector('#womens').classList.add('selected');
  });
  
let generateShop = () => {
    return (shop.innerHTML = kidsItemsData
        .map((x) => {
            let { id, name, price, image } = x;
            let search = basket.find((item) => item.id === id) || [];
            let producthtml = `
                <div id="product-id-${id}" class="item" >
                    <div class="top">
                    <a href="kidsDetailProducts.html?id=${id}"> <!-- Add product ID to the URL -->
                        <img src="${image}" alt="Pants" width="220" height="215">
                    </a>
            `;

            if (heartCount.includes(id)) {
                producthtml += `<i onclick="toggleHeart(event,'${id}')" class="red fa-regular fa-heart"></i>`;
            } else {
                producthtml += `<i onclick="toggleHeart(event,'${id}')" class="fa-regular fa-heart"></i>`;
            }

            producthtml += `
                    </div>

                    <div class="details">
                        <h3>
                            <a href="kidsDetailProducts.html?id=${id}">${name}</a> <!-- Add product ID to the URL -->
                        </h3></br>
                        <div class="price-quantity">
                            <h2>RM ${price}</h2>
                            <!-- <div class="buttons">
                                <i class="fa-solid fa-cart-shopping" onclick="addToCart('${id}')"></i>
                            </div> -->
                        </div>
                    </div>
                </div>
            `;

            return producthtml;
        })
        .join(""));
};

generateShop();

function toggleHeart(event,id) {
    event.target.classList.toggle("red");

    if (event.target.classList.contains("red")) {
        heartCount.push(id);
    } else if (heartCount.includes(id)){
        heartCount = heartCount.filter(item => item !== id);
    }
    // console.log("Heart count:", heartCount);
    localStorage.setItem("heartStatus",JSON.stringify(heartCount));
    document.getElementById("wishlistAmount").innerHTML = heartCount.length;
}


let increment = (id) => {
    let selectedItem = id;
    let search = basket.find((x) => x.id === selectedItem.id);

    if(search === undefined){
        basket.push({
            id:selectedItem.id,
            item:1,
        });
    }else{
        search.item += 1;
    }


    // console.log(basket);
    update(selectedItem.id);
    localStorage.setItem("data", JSON.stringify(basket));
};



let decrement = (id) => {
    let selectedItem = id;
    let search = basket.find((x) => x.id === selectedItem.id);

    if(search === undefined)
        return;
    else if(search.item === 0){
       return;
    }else{
        search.item -= 1;
    }
    
    // console.log(basket);
    update(selectedItem.id); 
    basket = basket.filter((x) => x.item !== 0);

    localStorage.setItem("data", JSON.stringify(basket));
};



let update = (id) => {
    let search = basket.find((x) => x.id === id);
    // console.log(search.item);
    document.getElementById(id).innerHTML = search.item;
    calculation();
};

 

let addToCart = (id) => {
    let selectedItem = alldata.find((x) => x.id === id);
    let search = basket.find((x) => x.id === id);

    if (search === undefined) {
        basket.push({
            id: selectedItem.id,
            item: 1,
            size: selectedSize
        });
    } else {
        search.item += 1;
    }

    localStorage.setItem("data", JSON.stringify(basket));
    calculation();

    // Prompt the user with a success message
    alert("Item added to cart!");
};

let calculation = () => {
    let cartIcon = document.getElementById("cartAmount");
    cartIcon.innerHTML = basket.map((x) => x.item).reduce((x,y)=> x + y, 0);
};

calculation();

window.onload = document.getElementById("wishlistAmount").innerHTML = heartCount.length;