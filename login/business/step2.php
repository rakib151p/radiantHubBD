<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Setup</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100">
    <div class="min-h-screen flex items-center justify-center">
        <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-md">
            <div>
            <a href="step1.php">
                    <img src="../../image/icon/left-arrow.png" alt="" style="width: 30px; height: 30px;">
                </a>
                <h2 class="text-xl font-bold text-center text-gray-700">Write Your Business Name</h2>
            </div>
            <p class="text-center text-gray-600 mb-8">This is your brand name of your shop</p>

            <form action="step3.php" method="POST">
                <div class="mb-4">
                    <label for="business-name" class="block text-gray-700 font-bold mb-2">Business name</label>
                    <input type="text" id="business-name" name="business-name"
                        class="border border-gray-300 p-2 rounded-lg w-full focus:outline-none focus:border-pink-600"
                        required>
                </div>
                <div class="mb-4">
                    <label for="business-title" class="block text-gray-700 font-bold mb-2">Business Title</label>
                    <input type="text" id="business-title" name="business-title"
                        class="border border-gray-300 p-2 rounded-lg w-full focus:outline-none focus:border-pink-600"
                        required>
                </div>
                <div class="mb-4">
                    <label for="website" class="block text-gray-700 font-bold mb-2">Website (if you have but not
                        necessary)</label>
                    <input type="url" id="website" name="website"
                        class="border border-gray-300 p-2 rounded-lg w-full focus:outline-none focus:border-pink-600">
                </div>
                <div class="mb-4">
                    <label for="info" class="block text-gray-700 font-bold mb-2">Your business info (Minimum 50 words
                        required*)</label>
                    <input type="textarea" id="info" name="business-info"
                        class="border border-gray-300 p-8 rounded-lg w-full focus:outline-none focus:border-pink-600">
                </div>
                <div class="mb-4">
                    <label for="gender" class="block text-gray-700 font-bold mb-2">For</label>
                    <select id="gender" name="gender"
                        class="border border-gray-300 p-2 rounded-lg w-full focus:outline-none focus:border-pink-600">
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                        <!-- <option value="other">Other</option> -->
                        <!-- <option value="prefer-not-to-say">Prefer not to say</option> -->
                    </select>
                </div>
                <button type="submit"
                    class="bg-pink-500 text-white p-2 w-full rounded-lg hover:bg-pink-700 transition duration-300">Continue</button>
            </form>
        </div>
    </div>
</body>

</html>
