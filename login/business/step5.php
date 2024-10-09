<?php
require 'mysql_connection.php';
session_start();
$_SESSION['$services'] = [];
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $_SESSION['services'] = $_POST['services'] ?? [];

}

$temp = $services = $_POST['services'] ?? [];
$other_service = $_POST['other_service'] ?? '';

$items_list = [];  // To store items for each service

foreach ($temp as $key) {
    $items_list[$key] = [];
    $sql = "SELECT b.item_name FROM `service_table` as `a` JOIN `item_table` as `b` ON a.service_id = b.service_id WHERE a.service_name='$key'";
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $items_list[$key][] = $row['item_name'];
        }
    }
}

if ($other_service) {
    $items_list[$other_service] = [];
    $sql = "SELECT b.item_name FROM `service_table` as `a` JOIN `item_table` as `b` ON a.id = b.service_id WHERE a.service_name='$other_service'";
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $items_list[$other_service][] = $row['item_name'];
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Service Selection</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100 p-6">
    <div class="container mx-auto">
        <form action="step6.php" method="POST" class="bg-white p-6 rounded shadow-md">
            <a href="step4.php" class="bg-pink-500 hover:bg-pink-700 text-white font-bold py-2 px-4 rounded">
                &larr;
            </a>
            <h2 class="text-2xl font-bold text-center mb-4">Add Items to Services</h2>

            <div id="services">
                <?php foreach ($services as $index => $service): ?>
                    <div class="service mb-4">
                        <label class="block font-bold mb-2">Service: <?= htmlspecialchars($service) ?></label>
                        <label class="block font-bold mb-2">Items</label>
                        <div class="items">
                            <div class="item mb-2 flex">
                                <select name="items[<?= $index ?>][name][]"
                                    class="block w-1/2 p-2 border rounded mb-2 mr-2 item-select">
                                    <option value="">Select an item</option>
                                    <?php foreach ($items_list[$service] as $item): ?>
                                        <option value="<?= htmlspecialchars($item) ?>"><?= htmlspecialchars($item) ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <input type="number" name="items[<?= $index ?>][price][]"
                                    class="block w-1/2 p-2 border rounded mb-2 item-price" placeholder="Enter price">
                            </div>
                        </div>
                        <button type="button" class="add-item bg-pink-500 text-white p-2 rounded mt-2">Add Item</button>
                    </div>
                <?php endforeach; ?>
                <?php if ($other_service): ?>
                    <div class="service mb-4">
                        <label class="block font-bold mb-2">Service: <?= htmlspecialchars($other_service) ?></label>
                        <label class="block font-bold mb-2">Items</label>
                        <div class="items">
                            <div class="item mb-2 flex">
                                <select name="other[<?= $index ?>][name][]"
                                    class="block w-1/2 p-2 border rounded mb-2 mr-2 item-select">
                                    <option value="">Select an item</option>
                                    <?php foreach ($items_list[$other_service] as $item): ?>
                                        <option value="<?= htmlspecialchars($item) ?>"><?= htmlspecialchars($item) ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <input type="number" name="other[<?= $index ?>][price][]"
                                    class="block w-1/2 p-2 border rounded mb-2 item-price" placeholder="Enter price">
                            </div>
                        </div>
                        <button type="button" class="add-item bg-pink-600 text-white p-2 rounded mt-2">Add Item</button>
                    </div>
                <?php endif; ?>
            </div>
            <button type="submit"
                class="bg-pink-500 text-white p-2 w-full rounded-lg hover:bg-pink-700 transition duration-300 mt-6">Continue</button>
        </form>
    </div>

    <script>
        document.addEventListener('click', function (e) {
            if (e.target && e.target.classList.contains('add-item')) {
                const serviceDiv = e.target.closest('.service');
                const itemsDiv = serviceDiv.querySelector('.items');
                const lastItemSelect = itemsDiv.querySelector('.item:last-child .item-select');
                const lastItemPrice = itemsDiv.querySelector('.item:last-child .item-price');

                // Check if the last item has been selected and priced
                if (lastItemSelect.value === '' || lastItemPrice.value === '') {
                    alert('Please select an item and enter its price before adding a new item.');
                    return;
                }

                // Check for duplicate items
                const itemSelects = Array.from(itemsDiv.querySelectorAll('.item-select'));
                const selectedValues = itemSelects.map(select => select.value);
                if (new Set(selectedValues).size !== selectedValues.length) {
                    alert('An item has already been selected. Please choose a different item.');
                    return;
                }

                const itemIndex = itemsDiv.querySelectorAll('.item').length;
                const itemDiv = document.createElement('div');
                itemDiv.className = 'item mb-2 flex';
                itemDiv.innerHTML = `
                    <select name="${serviceDiv.querySelector('select').name.replace(/\[\d+\]\[\]/, '[' + itemIndex + '][]')}" class="block w-1/2 p-2 border rounded mb-2 mr-2 item-select">
                        <option value="">Select an item</option>
                        ${Array.from(itemsDiv.querySelector('.item-select').options).map(option => `<option value="${option.value}">${option.text}</option>`).join('')}
                    </select>
                    <input type="number" name="${serviceDiv.querySelector('input').name.replace(/\[\d+\]\[\]/, '[' + itemIndex + '][]')}" class="block w-1/2 p-2 border rounded mb-2 item-price" placeholder="Enter price">
                `;
                itemsDiv.appendChild(itemDiv);
            }
        });

        document.addEventListener('DOMContentLoaded', function () {
            document.querySelector('form').addEventListener('submit', function (e) {
                const services = document.querySelectorAll('.service');
                for (let i = 0; i < services.length; i++) {
                    const itemsSelect = services[i].querySelectorAll('.item-select');
                    let hasSelectedItem = false;
                    itemsSelect.forEach(function (select) {
                        if (select.value !== '') {
                            hasSelectedItem = true;
                        }
                    });
                    if (!hasSelectedItem) {
                        e.preventDefault(); // Prevent form submission
                        alert(`Please select at least one item for ${services[i].querySelector('.font-bold').textContent}.`);
                        return;
                    }
                }
            });
        });

        document.addEventListener('DOMContentLoaded', function () {
            document.querySelector('form').addEventListener('submit', function (e) {
                const services = document.querySelectorAll('.service');
                for (let i = 0; i < services.length; i++) {
                    const itemsDiv = services[i].querySelector('.items');
                    const itemSelects = itemsDiv.querySelectorAll('.item-select');
                    let valid = true;

                    itemSelects.forEach(function (select) {
                        const priceInput = select.nextElementSibling;
                        if (select.value !== '' && priceInput.value === '') {
                            valid = false;
                            alert(`Please enter a price for the selected item in ${services[i].querySelector('.font-bold').textContent}.`);
                            e.preventDefault(); // Prevent form submission
                            return;
                        }
                    });

                    if (!valid) {
                        return; // Exit the loop if invalid
                    }
                }
            });
        });
    </script>
</body>

</html>