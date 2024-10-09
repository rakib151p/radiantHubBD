<?php
session_start();
require '../mysql_connection.php';
// Check if the data was posted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $shop_id = $_POST['shop_id'];
    if (!isset($_SESSION['customer_id'])) {
        // header("Location: dashboard.php?shop_id=" . urlencode($shop_id));
        header("Location: ../login/select_login.php?profile-type=customer");
        exit();
    }
    $item_id = $_POST['item_id'];
    $item_description = $_POST['item_description'];
    $item_price = $_POST['item_price'];
} else {
    header("Location: ../home.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Select Professional</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        .professional-card {
            background-color: white;
            width: 100%;
            cursor: pointer;
        }

        .professional-card.selected {
            border-color: pink;
            box-shadow: 0 0 10px 10px pink;
        }

        @media (min-width: 1080px) {
            .professional-card {
                width: calc(33.33% - 1rem);
            }
        }

        @media (min-width: 1900px) {
            .professional-card {
                width: calc(25% - 1rem);
            }
        }
    </style>
</head>

<body class="bg-gray-100">
    <div class="max-w-screen-xl mx-auto p-3">
        <div class="flex flex-col lg:flex-row lg:space-x-6">
            <div class="lg:w-5/6">
                <div class="flex items-center space-x-4 mb-8">
                    <a href="dashboard.php?shop_id=<?php echo htmlspecialchars($shop_id); ?>">
                        <img src="../image/icon/left-arrow.png" alt="Left Arrow" class="w-8 h-8">
                    </a>
                    <h2 class="text-4xl font-bold text-gray-800">Select Your Desired Barber</h2>
                </div>

                <div class="flex flex-wrap -mx-2" id="worker-selection">
                    <?php
                    $sql_workers = "SELECT * FROM shop_worker JOIN worker_expertise ON shop_worker.worker_id = worker_expertise.worker_id WHERE shop_worker.shop_id = '$shop_id' AND worker_expertise.item_id='$item_id'";
                    $result_workers = mysqli_query($conn, $sql_workers);

                    if (mysqli_num_rows($result_workers) > 0) {
                        $workers = mysqli_fetch_all($result_workers, MYSQLI_ASSOC);
                        foreach ($workers as $worker) {
                            echo '
                            <div class="professional-card border p-4 mx-2 mb-4 rounded-lg text-center" data-worker-id="' . htmlspecialchars($worker["worker_id"]) . '">
                                <img class="mx-auto rounded-full h-16 w-16 mb-2" src="image/worker/' . htmlspecialchars($worker["worker_picture"]) . '" alt="' . htmlspecialchars($worker["worker_name"]) . '">
                                <p class="text-gray-700 font-semibold">' . htmlspecialchars($worker["worker_name"]) . '</p>
                                <p class="text-yellow-500 text-sm">⭐ ' . htmlspecialchars($worker["rating"]) . '</p>
                            </div>';
                        }
                    } else {
                        echo '<p>No workers found for this shop.</p>';
                    }
                    ?>
                </div>
            </div>

            <div class="lg:w-2/6 bg-white p-6 shadow-md rounded-lg mt-6 lg:mt-0">
                <?php
                $sql_shop = "SELECT * FROM barber_shop WHERE shop_id = $shop_id";
                $result_shop = mysqli_query($conn, $sql_shop);

                if ($result_shop && mysqli_num_rows($result_shop) > 0) {
                    $shop = mysqli_fetch_assoc($result_shop);
                    echo '<h1 class="text-4xl font-bold mb-4">' . htmlspecialchars($shop['shop_name']) . "</h1>";
                    ?>
                    <p class="text-gray-700 ">
                        <?php echo $shop['shop_rating'] . '⭐ (' . $shop['shop_customer_count'] . ' reviews)'; ?>
                    </p>
                    <p class="text-gray-500 text-2xl"><?php echo $shop['shop_city']; ?></p>
                    <p class="text-gray-500">
                        <?php echo $shop['shop_area'] . ',' . $shop['shop_landmark_1'] . ',' . $shop['shop_landmark_2']; ?>
                    </p>
                    <hr class="my-4">

                    <p class="text-gray-700 font-semibold">
                        <?php
                        $sql_item = "SELECT item_name FROM item_table WHERE item_id = $item_id";
                        $result_item = mysqli_query($conn, $sql_item);

                        if ($result_item && mysqli_num_rows($result_item) > 0) {
                            $item = mysqli_fetch_assoc($result_item);
                            echo '<h3 class="text-lg text-2xl font-bold mb-4">' . htmlspecialchars($item['item_name']) . '</h3>';
                        }
                        ?>
                    </p>
                    <p class="text-gray-500">Time: 1 hour(aprx) &bull; <?php echo $item_description; ?></p>
                    <p class="text-gray-700 font-bold mt-4">BDT: <?php echo $item_price; ?>TK</p>

                    <hr class="my-4">
                    <p class="text-lg font-bold">Total: <span class="text-green-600">BDT:
                            <?php echo $item_price; ?>TK</span></p>
                <?php } ?>

                <form id="worker-selection-form" action="book_step_2.php" method="POST" class="hidden">
                    <input type="hidden" name="shop_id" value="<?php echo $shop_id; ?>">
                    <input type="hidden" name="item_id" value="<?php echo $item_id; ?>">
                    <input type="hidden" name="worker_id" id="worker-id-input" value="<?php echo $worker_id; ?>">
                    <input type="hidden" name="item_price" value="<?php echo $item_price; ?>">
                    <input type="hidden" name="item_description" value="<?php echo $item_description; ?>">
                </form>

                <button id="continue-button" disabled
                    class="w-full bg-pink-400 text-white py-2 mt-4 rounded-lg transition duration-150 hover:bg-pink-700">Continue</button>
            </div>
        </div>
    </div>

    <script>
        const cards = document.querySelectorAll('.professional-card');
        const continueButton = document.getElementById('continue-button');
        const workerIdInput = document.getElementById('worker-id-input');
        let selectedCard = null;

        cards.forEach(card => {
            card.addEventListener('click', () => {
                // Remove selection from previous card
                if (selectedCard) {
                    selectedCard.classList.remove('selected');
                }

                // Mark the clicked card as selected
                selectedCard = card;
                selectedCard.classList.add('selected');

                // Enable the continue button
                continueButton.disabled = false;
                continueButton.classList.remove('bg-gray-400');
                continueButton.classList.add('bg-black');

                // Set the worker_id in the hidden form
                workerIdInput.value = card.getAttribute('data-worker-id');
            });
        });

        continueButton.addEventListener('click', () => {
            if (selectedCard) {
                document.getElementById('worker-selection-form').submit();
            }
        });
    </script>
</body>

</html>