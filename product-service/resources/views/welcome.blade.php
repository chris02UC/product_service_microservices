<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Service - Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script> </head>
<body class="bg-gray-100 p-8">

    <div class="max-w-6xl mx-auto">
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-bold text-gray-800">🛍️ Product Microservice</h1>
            <div class="flex gap-2">
                <input type="text" id="searchInput" placeholder="Search products..." class="px-4 py-2 border rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 outline-none">
                <button onclick="fetchProducts()" class="bg-blue-600 text-white px-4 py-2 rounded-lg shadow hover:bg-blue-700">Search</button>
            </div>
        </div>

        <div id="productGrid" class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <p class="text-gray-500">Loading products from database...</p>
        </div>
    </div>

    <script>
        // Run this function immediately when the page loads
        document.addEventListener('DOMContentLoaded', fetchProducts);

        async function fetchProducts() {
            const searchBox = document.getElementById('searchInput').value;
            const grid = document.getElementById('productGrid');
            
            // Call the API we built earlier!
            let apiUrl = '/api/products';
            if (searchBox) apiUrl += `?search=${searchBox}`;

            try {
                const response = await fetch(apiUrl);
                const result = await response.json();
                
                grid.innerHTML = ''; // Clear loading text

                if (result.data.length === 0) {
                    grid.innerHTML = '<p class="text-red-500">No products found.</p>';
                    return;
                }

                // Loop through the data and create HTML cards
                result.data.forEach(product => {
                    const card = `
                        <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition-shadow">
                            <img src="${product.item_image}" alt="${product.name}" class="w-full h-48 object-cover">
                            <div class="p-5">
                                <span class="text-xs font-semibold text-blue-600 uppercase tracking-wider">${product.category.name}</span>
                                <h2 class="text-xl font-bold text-gray-900 mt-1">${product.name}</h2>
                                <p class="text-sm text-gray-500 mt-2 line-clamp-2">${product.description}</p>
                                
                                <div class="mt-4 flex justify-between items-center">
                                    <span class="text-lg font-bold text-green-600">Rp ${Number(product.price).toLocaleString('id-ID')}</span>
                                    <span class="text-sm text-gray-500 bg-gray-200 px-2 py-1 rounded">Stock: ${product.stock}</span>
                                </div>
                                
                                <div class="mt-4 pt-4 border-t border-gray-100 flex justify-between text-xs text-gray-400">
                                    <span>🏪 ${product.shop.name}</span>
                                    <span>⚖️ ${product.weight}g</span>
                                </div>
                            </div>
                        </div>
                    `;
                    grid.innerHTML += card;
                });
            } catch (error) {
                console.error("Error fetching products:", error);
                grid.innerHTML = '<p class="text-red-500">Error connecting to API. Is the server running?</p>';
            }
        }
    </script>
</body>
</html>