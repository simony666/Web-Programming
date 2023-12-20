let checkout = JSON.parse(localStorage.getItem("checkout")) || [];

function updateSize(id, newSize) {
    const selectedItem = basket.find((x) => x.id === id);

    if (selectedItem) {
        selectedItem.size = newSize;
        localStorage.setItem("data", JSON.stringify(basket));
    }
}

// Function to update the displayed quantity in the UI
function updateProductQuantityUI(id, quantity) {
    const quantityElement = document.getElementById(`quantity-${id}`);
    if (quantityElement) {
        quantityElement.value = quantity;
    }
}

// Function to update the displayed size in the UI
function updateProductSizeUI(id, size) {
    const sizeSelect = document.getElementById(`size-${id}`);
    if (sizeSelect) {
        sizeSelect.value = size;
    }
}

document.addEventListener("DOMContentLoaded", () => {
    // Get the product ID from the URL parameters
     const urlParams = new URLSearchParams(window.location.search);
    const productId = urlParams.get("id");

    // Find the selected product by its ID
     const selectedProduct = alldata.find((product) => product.id === productId);

    if (selectedProduct) {
        const productDetailsContainer = document.getElementById("container");
        productDetailsContainer.innerHTML =  `
                <div class="product">
                    <div class="gallery">
                        <img src="${selectedProduct.image}" alt="Pants">
                    </div>
                    <div class="details">
                        <h1>${selectedProduct.name}</h1>
                        <h2>RM ${selectedProduct.price}</h2>
                        <p>${selectedProduct.description || ''}</p>
                        <form>
                            <div class="selection">
                                <label for="size">Size: </label>
                                <select name="size" id="size-${selectedProduct.id}" class="size">
                                    <option value="S">S</option>
                                    <option value="M">M</option>
                                    <option value="L">L</option>
                                    <option value="XL">XL</option>
                                </select>

                                <label for="qty">Quantity: </label>
                                <input type="number" id="quantity-${selectedProduct.id}" value="1" min="1" max="10">
                           </div>

                           <div class="buttons">
                                <button onclick="addToCart('${selectedProduct.id}');  return false;" class="addToCart">Add to cart</button>
                                
                                <button class="displayProduct" id="displayProduct" type="button" onclick="displayProduct('${selectedProduct.id}', '${selectedProduct.quantity}','${selectedProduct.size}', '${selectedProduct.image}')">Checkout</button>
                            </div>
                        </form>
                    </div>
                </div>
            `;
          // Add event listener to size select element
        const sizeSelect = document.getElementById(`size-${selectedProduct.id}`);
        sizeSelect.addEventListener("change", () => {
            updateSize(selectedProduct.id, sizeSelect.value);
        });

        // Add event listener to quantity input element
        const quantityInput = document.getElementById(`quantity-${selectedProduct.id}`);
        quantityInput.addEventListener("change", () => {
            // Get the updated quantity from the input element
            const updatedQuantity = parseInt(quantityInput.value);
            // Update the quantity in the basket array and local storage
            //updateQuantity(selectedProduct.id, updatedQuantity);
            // Update the displayed product quantity in the UI
            updateProductQuantityUI(selectedProduct.id, updatedQuantity);
        });

        // Add event listener to "Checkout" button
        const checkoutButton = document.getElementById("displayProduct"); // Change to appropriate ID
        checkoutButton.addEventListener("click", () => {
            const selectedQuantity = parseInt(quantityInput.value);
            const selectedSize = sizeSelect.value;
            displayProduct(selectedProduct.id, selectedQuantity, selectedSize, selectedProduct.image);
        });
        
    } else {
        console.log("Selected product not found.");
        // Handle the case when the selected product is not found
        // You can display a message or redirect to another page
        // alert("Product Not Found!");
    }
});

// Update the quantity in the UI when basket is updated
function updateProduct(id) {
    const search = basket.find((x) => x.id === id);
    if (search) {
        updateProductQuantityUI(id, search.item);
        updateProductSizeUI(id, search.size);
    }
}


let displayProduct = (id, selectedQuantity, selectedSize, image) => {

    let checkoutItem = {
        id: id,
        item: selectedQuantity,
        size: selectedSize,
        image: image  // Include the image property
    };

    // Set a flag in the local storage to indicate which function to call
    localStorage.setItem("callDisplayCartItems", "false");

    // Clear previous checkout items and add the latest one
    checkout = [];
    checkout.push(checkoutItem);

    localStorage.setItem("checkout", JSON.stringify(checkout));
    
    window.location.href = "payment.html";
   
}

