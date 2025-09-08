

// Function to populate products
function populateProducts(url, containerId){
  fetch(url)
    .then(res => res.json())
    .then(data => {
      if(!data.success) return console.error("Error fetching products");
      const container = document.getElementById(containerId);
      container.innerHTML = '';
      data.products.forEach(item => {
        const card = document.createElement('div');
        card.className = 'product-card';
        card.setAttribute('data-bs-toggle','modal');
        card.setAttribute('data-bs-target','#productModal');

        card.addEventListener('click', ()=> {
          document.getElementById('modalImage').src = item.image_url;
          document.getElementById('modalTitle').innerText = item.name;
          document.getElementById('modalPrice').innerText = `Rs ${item.price}`;
          document.getElementById('modalColors').innerText = `Colors: ${item.colors.join(', ')}`;
          document.getElementById('modalSizes').innerText = `Sizes: ${item.sizes.join(', ')}`;

          const colorSelect = document.getElementById('colorSelect');
          colorSelect.innerHTML = '';
          item.colors.forEach(c => { 
              const opt = document.createElement('option'); 
              opt.value = c; 
              opt.innerText = c; 
              colorSelect.appendChild(opt); 
          });

          const sizeSelect = document.getElementById('sizeSelect');
          sizeSelect.innerHTML = '';
          item.sizes.forEach(s => { 
              const opt = document.createElement('option'); 
              opt.value = s; 
              opt.innerText = s; 
              sizeSelect.appendChild(opt); 
          });
        });

        card.innerHTML = `
          <img src="${item.image_url}" alt="${item.name}">
          <p class="fw-bold">${item.name}</p>
          <p class="text-success fw-semibold">Rs ${item.price}</p>`;
        container.appendChild(card);
      });
    }).catch(err=>console.error(err));
}


// Populate Top Sellers and Latest Arrivals
// Top Sellers;

// Latest Arrivals
populateProducts('get_latest_arrivals.php', 'latest-arrivals');


