let product = document.getElementById("wishlist");
let count = document.getElementById('count');
let heartCount = JSON.parse(localStorage.getItem("heartStatus")) || [];

let basket = JSON.parse(localStorage.getItem("data")) || [];

// document.addEventListener('DOMContentLoaded', function() {
//   const navItems = document.querySelectorAll('nav li');

//   navItems.forEach(item => {
//       item.addEventListener('click', function() {
//           // Remove the 'selected' class from all items
//           navItems.forEach(item => {
//               item.classList.remove('selected');
//           });
      
//           // Add the 'selected' class to the clicked item
//           this.classList.add('selected');
//       });
//   });
// });


// total count number of customer items
let calculation = () => {
    let cartIcon = document.getElementById("cartAmount");
    if (cartIcon) {
        cartIcon.innerHTML = basket.map((x) => x.item).reduce((x, y) => x + y, 0);
    }
};


calculation();

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
    document.getElementById("count").innerHTML = heartCount.length;
}

function generateWishListProduct() {
    const product = document.getElementById("wishlist"); // Ensure the element is correctly selected
  
    if (!product) {
      console.error("Product element not found in the DOM.");
      return;
    }
  
    if (alldata.length === 0) {
      console.warn("The 'alldata' array is empty.");
      return;
    }
  
    product.innerHTML = alldata
      .map((x) => {
        let { id, name, price, image } = x;
        if (heartCount.includes(id)) {
          return `
            <div id="product-${id}">
              <div class="favourite_product">
                <img class="size" src="${image}" height="200px" width="100%" >
              </div>
              <div class="spec_product">
                <p>${name}</p>
                <b>RM ${price}</b>
                <div class="button">
                  <button class="addToCart" onclick="addToCart('${id}')">Add to Cart</button>
                  <button class="remove" onclick="remove('${id}')">Remove Item</button>
                </div>
                <br><br/>
              </div>
            </div>`;
        } else {
          return ""; // Return an empty string if the product is not in the wish list
        }
      })
      .join("");
  };
  
  
document.addEventListener('DOMContentLoaded', () => {
    generateWishListProduct();
  });
  

  let addToCart = (id) => {
    const quantityInput = document.getElementById(`quantity-${id}`);
    const selectedQuantity = parseInt(quantityInput.value, 10);
    const sizeSelect = document.getElementById(`size-${id}`);
    const selectedSize = sizeSelect.value;

    let search = basket.find((x) => x.id === id);

    if (search === undefined) {
        basket.push({
            id: id,
            item: selectedQuantity,
            size: selectedSize
        });
    } else {
        search.item += selectedQuantity;
    }

    localStorage.setItem("data", JSON.stringify(basket));

    // Prompt the user with a success message
    alert("Item added to cart!");
    
    // Prevent the default link behavior and navigate to the main menu page
    const link = document.createElement("a");
    link.href = "Product_Women.html";
    link.click();
};




// remove wishlist 的红色数字
let remove = (id) => {
    // Remove the item from the heartCount array
    heartCount = heartCount.filter((item) => item !== String(id));
    localStorage.setItem("heartStatus", JSON.stringify(heartCount));
    document.getElementById("wishlistAmount").innerHTML = heartCount.length;
    document.getElementById("count").innerHTML = heartCount.length;
  
    // Remove the wishlist item from the DOM
    let wishlistItem = document.getElementById(`product-${id}`);
    if (wishlistItem) {
        wishlistItem.parentNode.removeChild(wishlistItem);
    } else {
      console.log(`Wishlist item with ID ${id} does not exist.`);
    }
  };
  

window.onload = function() {
    let wishlistAmountElement = document.getElementById("wishlistAmount");
    if (wishlistAmountElement) {
        wishlistAmountElement.innerHTML = heartCount.length;
    }
    let countElement = document.getElementById("count");
    if (countElement) {
        countElement.innerHTML = heartCount.length;
    }
};
