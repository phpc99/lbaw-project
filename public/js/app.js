function addEventListeners() {
    let itemCheckers = document.querySelectorAll('article.card li.item input[type=checkbox]');
    [].forEach.call(itemCheckers, function(checker) {
      checker.addEventListener('change', sendItemUpdateRequest);
    });
  
    let itemCreators = document.querySelectorAll('article.card form.new_item');
    [].forEach.call(itemCreators, function(creator) {
      creator.addEventListener('submit', sendCreateItemRequest);
    });
  
    let itemDeleters = document.querySelectorAll('article.card li a.delete');
    [].forEach.call(itemDeleters, function(deleter) {
      deleter.addEventListener('click', sendDeleteItemRequest);
    });
  
    let cardDeleters = document.querySelectorAll('article.card header a.delete');
    [].forEach.call(cardDeleters, function(deleter) {
      deleter.addEventListener('click', sendDeleteCardRequest);
    });
  
    let cardCreator = document.querySelector('article.card form.new_card');
    if (cardCreator != null)
      cardCreator.addEventListener('submit', sendCreateCardRequest);
  }
  
  function encodeForAjax(data) {
    if (data == null) return null;
    return Object.keys(data).map(function(k){
      return encodeURIComponent(k) + '=' + encodeURIComponent(data[k])
    }).join('&');
  }
  
  function sendAjaxRequest(method, url, data, handler) {
    let request = new XMLHttpRequest();
  
    request.open(method, url, true);
    request.setRequestHeader('X-CSRF-TOKEN', document.querySelector('meta[name="csrf-token"]').content);
    request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    request.addEventListener('load', handler);
    request.send(encodeForAjax(data));
  }
  
  function sendItemUpdateRequest() {
    let item = this.closest('li.item');
    let id = item.getAttribute('data-id');
    let checked = item.querySelector('input[type=checkbox]').checked;
  
    sendAjaxRequest('post', '/api/item/' + id, {done: checked}, itemUpdatedHandler);
  }
  
  function sendDeleteItemRequest() {
    let id = this.closest('li.item').getAttribute('data-id');
  
    sendAjaxRequest('delete', '/api/item/' + id, null, itemDeletedHandler);
  }
  
  function sendCreateItemRequest(event) {
    let id = this.closest('article').getAttribute('data-id');
    let description = this.querySelector('input[name=description]').value;
  
    if (description != '')
      sendAjaxRequest('put', '/api/cards/' + id, {description: description}, itemAddedHandler);
  
    event.preventDefault();
  }
  
  function sendDeleteCardRequest(event) {
    let id = this.closest('article').getAttribute('data-id');
  
    sendAjaxRequest('delete', '/api/cards/' + id, null, cardDeletedHandler);
  }
  
  function sendCreateCardRequest(event) {
    let name = this.querySelector('input[name=name]').value;
  
    if (name != '')
      sendAjaxRequest('put', '/api/cards/', {name: name}, cardAddedHandler);
  
    event.preventDefault();
  }
  
  function itemUpdatedHandler() {
    let item = JSON.parse(this.responseText);
    let element = document.querySelector('li.item[data-id="' + item.id + '"]');
    let input = element.querySelector('input[type=checkbox]');
    element.checked = item.done == "true";
  }
  
  function itemAddedHandler() {
    if (this.status != 200) window.location = '/';
    let item = JSON.parse(this.responseText);
  
    // Create the new item
    let new_item = createItem(item);
  
    // Insert the new item
    let card = document.querySelector('article.card[data-id="' + item.card_id + '"]');
    let form = card.querySelector('form.new_item');
    form.previousElementSibling.append(new_item);
  
    // Reset the new item form
    form.querySelector('[type=text]').value="";
  }
  
  function itemDeletedHandler() {
    if (this.status != 200) window.location = '/';
    let item = JSON.parse(this.responseText);
    let element = document.querySelector('li.item[data-id="' + item.id + '"]');
    element.remove();
  }
  
  function cardDeletedHandler() {
    if (this.status != 200) window.location = '/';
    let card = JSON.parse(this.responseText);
    let article = document.querySelector('article.card[data-id="'+ card.id + '"]');
    article.remove();
  }
  
  function cardAddedHandler() {
    if (this.status != 200) window.location = '/';
    let card = JSON.parse(this.responseText);
  
    // Create the new card
    let new_card = createCard(card);
  
    // Reset the new card input
    let form = document.querySelector('article.card form.new_card');
    form.querySelector('[type=text]').value="";
  
    // Insert the new card
    let article = form.parentElement;
    let section = article.parentElement;
    section.insertBefore(new_card, article);
  
    // Focus on adding an item to the new card
    new_card.querySelector('[type=text]').focus();
  }
  
  function createCard(card) {
    let new_card = document.createElement('article');
    new_card.classList.add('card');
    new_card.setAttribute('data-id', card.id);
    new_card.innerHTML = `
  
    <header>
      <h2><a href="cards/${card.id}">${card.name}</a></h2>
      <a href="#" class="delete">&#10761;</a>
    </header>
    <ul></ul>
    <form class="new_item">
      <input name="description" type="text" placeholder="Description">
    </form>`;
  
    let creator = new_card.querySelector('form.new_item');
    creator.addEventListener('submit', sendCreateItemRequest);
  
    let deleter = new_card.querySelector('header a.delete');
    deleter.addEventListener('click', sendDeleteCardRequest);
  
    return new_card;
  }
  
  function createItem(item) {
    let new_item = document.createElement('li');
    new_item.classList.add('item');
    new_item.setAttribute('data-id', item.id);
    new_item.innerHTML = `
    <label>
      <input type="checkbox"> <span>${item.description}</span><a href="#" class="delete">&#10761;</a>
    </label>
    `;
  
    new_item.querySelector('input').addEventListener('change', sendItemUpdateRequest);
    new_item.querySelector('a.delete').addEventListener('click', sendDeleteItemRequest);
  
    return new_item;
  }

  function editOrder(orderId) {
    document.getElementById(`status-text-${orderId}`).style.display = 'none';
    document.getElementById(`status-form-${orderId}`).style.display = 'block';

    const actionsCell = document.querySelector(`button[onclick="editOrder(${orderId})"]`).parentElement;
    actionsCell.innerHTML = `
        <button type="submit" form="status-form-${orderId}" class="btn-primary">Save</button>
        <button type="button" class="btn-secondary" onclick="cancelEdit(${orderId})">Cancel</button>
    `;
  }

  function cancelEdit(orderId) {
    document.getElementById(`status-form-${orderId}`).style.display = 'none';
    document.getElementById(`status-text-${orderId}`).style.display = 'inline';

    const actionsCell = document.querySelector(`#status-form-${orderId}`).parentElement.nextElementSibling;
    actionsCell.innerHTML = `
        <button class="btn-warning" onclick="editOrder(${orderId})">Edit Order</button>
    `;
  }
  
  addEventListeners();

  
// hide and show add product forms in products/list.blade.php
document.getElementById('add-product-button')?.addEventListener('click', function () {
  let addProductModal = document.getElementById('add-product-modal');
  if (addProductModal.classList.contains('show')) {
      // Hide modal smoothly
      addProductModal.classList.remove('show');
      setTimeout(() => addProductModal.style.display = 'none', 300); // Delay to match transition duration
  } else {
      // Show modal smoothly
      addProductModal.style.display = 'block';
      setTimeout(() => addProductModal.classList.add('show'), 10); // Small delay to trigger transition
      // ensure product name uniqueness
      let debounceTimeout;
      const nameInput = document.getElementById('name');
      const quantityInput = document.getElementById('quantity');
      const ratingInput = document.getElementById('rating');
      const addButton = document.querySelector('#add-product-modal button[type="submit"]');

      function createErrorMessage(inputElement, errorMessageId, defaultMessage) {
          if (!document.getElementById(errorMessageId)) {
              const errorMessageElement = document.createElement('div');
              errorMessageElement.id = errorMessageId;
              errorMessageElement.style.color = 'red';
              errorMessageElement.style.marginTop = '5px';
              inputElement.parentNode.insertBefore(errorMessageElement, inputElement.nextSibling);
          }

          return document.getElementById(errorMessageId);
      }

      const nameErrorMessage = createErrorMessage(nameInput, 'name-error-message', 'Invalid name');
      const quantityErrorMessage = createErrorMessage(quantityInput, 'quantity-error-message', 'Invalid quantity');
      const ratingErrorMessage = createErrorMessage(ratingInput, 'rating-error-message', 'Invalid rating');

      function validateProductName() {
          const productName = nameInput.value;

          clearTimeout(debounceTimeout);
          debounceTimeout = setTimeout(() => {
              fetch(`/check-product-name?name=${encodeURIComponent(productName)}`)
                  .then(response => response.json())
                  .then(data => {
                      if (data.exists) {
                          nameErrorMessage.textContent = 'This product name already exists. Please choose another.';
                          nameInput.focus();
                          nameInput.dataset.valid = 'false';
                      } else {
                          nameErrorMessage.textContent = '';
                          nameInput.dataset.valid = 'true';
                      }
                      updateSubmitButtonState();
                  })
                  .catch(() => {
                      nameErrorMessage.textContent = 'An error occurred while validating the product name.';
                      nameInput.dataset.valid = 'false';
                      updateSubmitButtonState();
                  });
          }, 500);
      }

      function validateQuantity() {
          const quantityValue = quantityInput.value;

          if (quantityValue && parseInt(quantityValue, 10) > 0) {
              quantityErrorMessage.textContent = '';
              quantityInput.dataset.valid = 'true';
          } else {
              quantityErrorMessage.textContent = 'Quantity must be greater than 0.';
              quantityInput,focus();
              quantityInput.dataset.valid = 'false';
          }

          updateSubmitButtonState();
      }

      function validateRating() {
          const ratingValue = ratingInput.value;

          if (ratingValue && parseFloat(ratingValue) >= 1 && parseFloat(ratingValue) <= 5) {
              ratingErrorMessage.textContent = '';
              ratingInput.dataset.valid = 'true';
          } else {
              ratingErrorMessage.textContent = 'Rating must be between 1 and 5.';
              ratingInput.focus();
              ratingInput.dataset.valid = 'false';
          }

          updateSubmitButtonState();
      }

      function updateSubmitButtonState() {
          const isNameValid = nameInput.dataset.valid === 'true';
          const isQuantityValid = quantityInput.dataset.valid === 'true';
          const isRatingValid = ratingInput.dataset.valid === 'true';

          addButton.disabled = !(isNameValid && isQuantityValid && isRatingValid);
      }

      nameInput.addEventListener('input', validateProductName);
      quantityInput.addEventListener('input', validateQuantity);
      ratingInput.addEventListener('input', validateRating);

      updateSubmitButtonState();
  }
});

document.getElementById('close-modal')?.addEventListener('click', function () {
  let addProductModal = document.getElementById('add-product-modal');
  // Smoothly hide the modal
  addProductModal.classList.remove('show');
  setTimeout(() => addProductModal.style.display = 'none', 300); // Delay to match transition duration
});


// Categories
// hide and show add and edit forms 
document.getElementById('toggle-add-category')?.addEventListener('click', function() {
  let addCatForm = document.getElementById('add-form');
  addCatForm.style.display = (addCatForm.style.display === 'none' || addCatForm.style.display === '') ? 'block' : 'none';
});
document.querySelectorAll('.toggle-edit-category').forEach(function (button, index) {
  button.addEventListener('click', function () {
    let editForm = document.querySelectorAll('.edit-form')[index];
    editForm.style.display = (editForm.style.display === 'none' || editForm.style.display === '') ? 'block' : 'none';
  });
});

// User and Products
// Edit Profile
document.addEventListener("DOMContentLoaded", () => {
  const toggleButton = document.getElementById("toggle-edit-form");
  const cancelButton = document.getElementById("cancel-edit");
  const editForm = document.getElementById("edit-form");

  toggleButton.addEventListener("click", () => {
    if (editForm.classList.contains("show")) {
      editForm.classList.remove("show");
      setTimeout(() => {
        editForm.style.display = "none";
      }, 300); 
    } else {
      const buttonRect = toggleButton.getBoundingClientRect();
      editForm.style.display = "block";
      editForm.style.top = `${buttonRect.bottom + window.scrollY - 110}px`; 
      editForm.style.left = `${buttonRect.left}px`; 
      setTimeout(() => {
        editForm.classList.add("show");
      }, 10); 
    }
  });

  cancelButton.addEventListener("click", () => {
    editForm.classList.remove("show");
    setTimeout(() => {
      editForm.style.display = "none";
    }, 300); 
  });
});


// cart
document.addEventListener('DOMContentLoaded', () => {
  // Handle minus link click
  document.querySelectorAll('.minus-link').forEach(link => {
      link.addEventListener('click', (e) => {
          e.preventDefault();  
          const productId = link.getAttribute('data-product-id');
          const quantityDisplay = link.nextElementSibling; 
          const currentQuantity = parseInt(quantityDisplay.textContent);
          
          if (currentQuantity > 1) {  
              link.classList.add('clicked');
              updateQuantity(productId, currentQuantity - 1, quantityDisplay, link.closest('tr'));
          }
      });
  });

  // Handle plus link click
  document.querySelectorAll('.plus-link').forEach(link => {
      link.addEventListener('click', (e) => {
          e.preventDefault();  
          const productId = link.getAttribute('data-product-id');
          const quantityDisplay = link.previousElementSibling;  
          const currentQuantity = parseInt(quantityDisplay.textContent);
          
          link.classList.add('clicked');
          updateQuantity(productId, currentQuantity + 1, quantityDisplay, link.closest('tr'));
      });
  });

  // Function to update the quantity through AJAX and update the total
  const updateQuantity = (productId, newQuantity, quantityDisplay, row) => {
      fetch(`/cart/update/${productId}`, {
          method: 'PUT',
          headers: {
              'Content-Type': 'application/json',
              'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),  
          },
          body: JSON.stringify({ quantity: newQuantity }),
      })
      .then(response => response.json())
      .then(data => {
          if (data.success) {
              quantityDisplay.textContent = newQuantity;
              
              const priceCell = row.querySelector('.product-price');
              const price = parseFloat(priceCell.textContent.replace('$', '')); 
              const totalCell = row.querySelector('.product-total');
              
              const newTotal = (price * newQuantity).toFixed(2);
              totalCell.textContent = `$${newTotal}`;

              recalculateCartTotal();

              setTimeout(() => {
                  link.classList.remove('clicked');
              }, 100); 
          } else {
              alert(data.message);
              quantityDisplay.textContent = data.currentQuantity;
              console.error('Failed to update quantity');
          }
      })
      .catch(error => console.error('Error updating quantity:', error));
  };

  // Function to recalculate the cart total
  const recalculateCartTotal = () => {
    let cartTotal = 0;

    document.querySelectorAll('.product-total').forEach(totalCell => {
        const total = parseFloat(totalCell.textContent.replace('$', ''));
        cartTotal += total;
    });

    const cartTotalDisplay = document.querySelector('.cart-total-display');
    cartTotalDisplay.textContent = `$${cartTotal.toFixed(2)}`;
    cartTotalDisplay.classList.add('updated');
    setTimeout(() => cartTotalDisplay.classList.remove('updated'), 300);
};
});

// load the navigation bar with jQuery via ajax
$(document).ready(function () {
  if ($('.navbar').children().length === 0) {
      $.ajax({
          url: `{{ route('navbar') }}`, 
          type: 'GET',
          success: function (data) {
              $('.navbar').html(data); 
          },
          error: function () {
              console.error('Failed to load the navbar');
          }
      });
  }

  $(document).on('click', 'a', function (e) {
      e.preventDefault(); 

      var url = $(this).attr('href'); 
      $.ajax({
          url: url,
          type: 'GET',
          success: function (data) {
              $('#content-container').html(data); 
          },
          error: function () {
              console.error('Failed to load the page');
          }
      });
  });
});

document.addEventListener('DOMContentLoaded', function () {
  document.querySelectorAll('.add-to-wishlist').forEach(function (wishlistButton) {
      wishlistButton.addEventListener('click', function (event) {
          event.preventDefault();
          fetch(this.closest('form').action, {
              method: 'POST',
              headers: {
                  'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                  'Content-Type': 'application/json'
              }
          }).then(response => response.json())
          .then(data => {
              wishlistButton.style.display = 'none';
              if (!data.error) {
                  alert('Product added to the wishlist.');
              } else {
                  alert(data.error);
              }
          }).catch(error => {
              console.error('Error:', error);
              alert('An error occurred. Please try again.');
          });
      });
  });
});

document.addEventListener('DOMContentLoaded', function () {
  document.querySelectorAll('.add-to-cart').forEach(function (cartButton) {
      cartButton.addEventListener('click', function (event) {
          event.preventDefault();

          const form = this.closest('form');
          
          fetch(form.action, {
              method: 'POST',
              headers: {
                  'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                  'Content-Type': 'application/json',
                  'X-Requested-With': 'XMLHttpRequest'
              }
          })
          .then(response => response.json())
          .then(data => {
              if (!data.error) {
                  alert(data.success);
              } else {
                  alert(data.error);
              }
          }).catch(error => {
              console.error('Error:', error);
              alert('An error occurred. Please try again.');
          });
      });
  });
});

//filter
document.addEventListener("DOMContentLoaded", () => {
  const filterImage = document.querySelector(".filter-image");
  const filterBox = document.querySelector(".filter-box");

  filterImage.addEventListener("click", () => {
      if (filterBox.classList.contains("show")) {
          filterBox.classList.remove("show");
          setTimeout(() => filterBox.style.display = "none", 300); 
      } else {
          filterBox.style.display = "block";
          setTimeout(() => filterBox.classList.add("show"), 0);
      }
  });
});



  