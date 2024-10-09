<?php
require '../mysql_connection.php';
session_start();
if (isset($_POST['submit'])) {
    $_SESSION['district'] = $district = $_POST['district'];
    $_SESSION['upazilla'] = $upazilla = $_POST['upazilla'];
    $_SESSION['area'] = $area = $_POST['area'];
    $_SESSION['latitude'] = $latitude = $_POST['latitude'];
    $_SESSION['longitude'] = $longitude = $_POST['longitude'];
    $customer_id = $_SESSION['customer_id'];
    $sql = "UPDATE `customer` SET `district`='$district', `upazilla`='$upazilla', `area`='$area', `latitude`='$latitude',`longitude`='$longitude' WHERE `customer_id`='$customer_id'"; // Use the appropriate WHERE condition
    if (mysqli_query($conn, $sql)) {
        // echo "<script>alert('Your details updated successfully.');</script>";
        echo "<script>
            window.onload = function() {
            alert('Successfully updated.');
            };
        </script>";
    } else {
        echo "<script>
            window.onload = function() {
            alert('Error updating record: " . mysqli_error($conn) . "');
            };
        </script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Addresses</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <style>
        body {
            background-color: #F4F4F4;
        }


        #navbar {
            z-index: 1;
            width: 100%;
            margin-bottom: 20px;
            background-color: #FFFFFF;
        }

        .text-gray-700 {
            font-weight: bolder;
        }

        .t {
            font-family: 'Times New Roman', Times, serif;
            font-size: 50px;
            color: #C71585;
        }

        #sidebar {
            flex-direction: column;
            justify-content: center;
            margin: 20px 0 0 30px;
            width: 300px;
        }


        #sidebar ul li a {
            text-decoration: none;
            line-height: 35px;
            font-size: larger;
            margin-left: 20px;
            margin-top: 40px;
            color: #4A5568;
            transition: color 0.3s;
        }

        #sidebar ul li a:hover {
            color: #C71585;
        }

        #sidebar ul li {
            text-decoration: none;
            list-style-type: none;
        }

        #mma {
            font-size: 27.9px;
            text-decoration: none;
            color: #C71585;

        }


        #undernavbar {
            display: flex;
            border-radius: 20px;
            margin-bottom: 80px;
            margin-top: 50px;
        }


        .card {
            background-color: white;
            border-radius: 10px;
            padding: 20px;
            /* margin-bottom: 10px; */
            margin-top: 78px;
            margin-left: 80px;
            height: 330px;
            width: 55%;
            /* box-shadow: rgba(0, 0, 0, 0.16) 0px 3px 6px, rgba(0, 0, 0, 0.23) 0px 3px 6px; */
        }

        .card h3 {
            font-size: 1.5rem;
            color: #C71585;
            margin-bottom: 15px;
            position: relative;
        }


        button {
            height: 35px;
            text-align: center;
        }

        #B {
            font-size: larger;
            font-weight: bolder;
        }

        #tittlemnm {
            font-size: x-large;
            color: #C71585;
            margin: 50px 0 0 340px;
        }

        #name {
            margin: 0 0 0 670px;
        }

        footer {
            height: 358px;
            background-color: #1F2937;
            width: 100vw;
        }

        .card h3 {
            font-size: 2rem;
            color: #C71585;
            font-weight: 900;
            position: relative;
            bottom: 93.8px;
            right: 20px;
        }

        .arrow {
            position: absolute;
            right: 100px;
        }

        .navs {
            background-color: #FFFFFF;
            width: 100vw;
            height: 65px;
            box-shadow: 1px 1px 1px 1px rgba(0, 0, 0, 0.1);
            display: flex;

        }

        .radiant {
            font-size: 42px;
            font-weight: 900;
            margin-left: 90px;
            color: #C71585;
        }

        .login_name {
            font-weight: 900;
        }

        .relation {
            margin-top: -6px;
            padding-left: 10px;
        }

        .together {
            display: flex;
            margin-top: 20px;
            margin-left: 600px;
        }

        li {
            font-size: 21px;
        }

        * {
            font-family: cursive;
        }

        .gri {
            position: relative;
            bottom: 70px;

        }

        #btn {
            position: relative;
            bottom: 70px;
            height: 45px;
        }
    </style>
</head>

<body>
    <div class="navs">
        <div class="radiant">
            <a href="../home.php">RadiantHub BD</a>
        </div>
        <div class="together">
            <div class="login">
                <a href="
            <?php
            if (!isset($_SESSION['type'])) {
                echo 'login/login.php';
            } else {
                if ($_SESSION['type'] === 'customer') {
                    echo 'My_profile.php';
                } else {
                    echo 'business_dashboard/saloon_dashboard.php';
                }
            }
            ?>" id="name" class="login_name">
                    <?php
                    if (!isset($_SESSION['type'])) {
                        echo 'Login';
                    } else {
                        if ($_SESSION['type'] === 'customer') {
                            echo $_SESSION['first_name'];
                        } else {
                            echo $_SESSION['shop_name'];
                        }
                    }
                    ?>
                </a>
            </div>
            <div class="relation">
                <button class="arrow_bottom" onclick="toggleDropdown()">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7">
                        </path>
                    </svg>
                </button>

                <!-- Dropdown Menu -->
                <div id="dropdownMenu"
                    class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg overflow-hidden z-20 hidden">
                    <a href="../home.php" class="text-2xl font-bold hover:text-pink-600"
                        style="font-size:16px;margin:0 0 0 15px;">Home</a><br>
                    <a href="My_profile.php" class="text-2xl font-bold hover:text-pink-600"
                        style="font-size:16px;margin:0 0 0 15px;">Profile</a><br>
                    <a href="addressofbooking.php" class="text-2xl font-bold hover:text-pink-600"
                        style="font-size:16px;margin:0 0 0 15px;">Book & address</a><br>
                    <a href="myreviews.php" class="text-2xl font-bold hover:text-pink-600"
                        style="font-size:16px;margin:0 0 0 15px;">Review</a><br>
                    <a href="mycancellations.php" class="text-2xl font-bold hover:text-pink-600"
                        style="font-size:16px;margin:0 0 0 15px;">Cancel products</a><br>
                    <?php
                    if (isset($_SESSION['type'])) {
                        echo '<a href="../logout.php" class="text-gray-700 hover:text-pink-600 " style="font-size:16px;margin:0 0 0 15px;">Logout</a>';
                    }
                    ?>
                </div>

            </div>

        </div>

    </div>

    <script>
        function toggleDropdown() {
            document.getElementById('dropdownMenu').classList.toggle('hidden');
        }
    </script>


    <!-- <h2 id="tittlemnm">Address of Booking</h2> -->
    <section id="undernavbar">
        <div id="sidebar">
            <a href="#" id="mma">Manage My Account</a>
            <ul>
            <li><a href="My_profile.php">My Profile</a></li>
                <li><a href="addressofbooking.php">Address of Booking</a></li>
                <li><a href="myreviews.php">My Reviews</a></li>
                <li><a href="message.php" id="mymessage">My Messages<?php if ($_SESSION['unseen'] > 0): ?>
                            <span class="unseen-count" style="color: red;">(<?php echo $_SESSION['unseen']; ?>)</span>
                        <?php endif; ?></a></li>
                <li><a href="mybooking.php" id="mma">My booking</a></li>
                <li><a href="mycancellations.php">My Cancellations</a></li>
                <li><a href="Notifications.php">My Notifications</a></li>
            </ul>
            <?php
            if (isset($_SESSION['type'])) {
                echo '<a href="../logout.php" class="text-gray-700 hover:text-pink-600 " style="font-size:30px;margin:0 0 0 20px; line-height:50px;">Logout</a>';
            }
            ?>
        </div>
        <!-- <div id="box1"> -->

        <div class="card">
            <h3>Address of Booking</h3>
            <form action="" method="POST">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6 gri">
                    <div>
                        <label for="district" class="font-semibold">District</label>
                        <!-- <input type="text" id="district" name="district" class="text-gray-600 w-full p-2 border rounded"
                            value="" required> -->
                        <select id="district" name="district"
                            class="border border-gray-300 p-2 rounded-lg w-full focus:outline-none focus:border-pink-600"
                            required>
                            <option disabled selected><?php echo $_SESSION['district']; ?></option>
                            <option value="Bagerhat">Bagerhat</option>
                            <option value="Bandarban">Bandarban</option>
                            <option value="Barguna">Barguna</option>
                            <option value="Barishal">Barishal</option>
                            <option value="Bhola">Bhola</option>
                            <option value="Bogura">Bogura</option>
                            <option value="Brahmanbaria">Brahmanbaria</option>
                            <option value="Chandpur">Chandpur</option>
                            <option value="Chattogram">Chattogram</option>
                            <option value="Chuadanga">Chuadanga</option>
                            <option value="Cox's Bazar">Cox_s_Bazar</option>
                            <option value="Cumilla">Cumilla</option>
                            <option value="Dhaka">Dhaka</option>
                            <option value="Dinajpur">Dinajpur</option>
                            <option value="Faridpur">Faridpur</option>
                            <option value="Feni">Feni</option>
                            <option value="Gaibandha">Gaibandha</option>
                            <option value="Gazipur">Gazipur</option>
                            <option value="Gopalganj">Gopalganj</option>
                            <option value="Habiganj">Habiganj</option>
                            <option value="Jamalpur">Jamalpur</option>
                            <option value="Jashore">Jashore</option>
                            <option value="Jhalokathi">Jhalokathi</option>
                            <option value="Jhenaidah">Jhenaidah</option>
                            <option value="Khagrachhari">Khagrachhari</option>
                            <option value="Khulna">Khulna</option>
                            <option value="Kishoreganj">Kishoreganj</option>
                            <option value="Kurigram">Kurigram</option>
                            <option value="Kushtia">Kushtia</option>
                            <option value="Lakshmipur">Lakshmipur</option>
                            <option value="Lalmonirhat">Lalmonirhat</option>
                            <option value="Madaripur">Madaripur</option>
                            <option value="Magura">Magura</option>
                            <option value="Manikganj">Manikganj</option>
                            <option value="Meherpur">Meherpur</option>
                            <option value="Moulvibazar">Moulvibazar</option>
                            <option value="Munshiganj">Munshiganj</option>
                            <option value="Mymensingh">Mymensingh</option>
                            <option value="Naogaon">Naogaon</option>
                            <option value="Narail">Narail</option>
                            <option value="Narayanganj">Narayanganj</option>
                            <option value="Narsingdi">Narsingdi</option>
                            <option value="Natore">Natore</option>
                            <option value="Netrokona">Netrokona</option>
                            <option value="Nilphamari">Nilphamari</option>
                            <option value="Noakhali">Noakhali</option>
                            <option value="Pabna">Pabna</option>
                            <option value="Panchagarh">Panchagarh</option>
                            <option value="Patuakhali">Patuakhali</option>
                            <option value="Pirojpur">Pirojpur</option>
                            <option value="Rajbari">Rajbari</option>
                            <option value="Rajshahi">Rajshahi</option>
                            <option value="Rangamati">Rangamati</option>
                            <option value="Rangpur">Rangpur</option>
                            <option value="Satkhira">Satkhira</option>
                            <option value="Shariatpur">Shariatpur</option>
                            <option value="Sherpur">Sherpur</option>
                            <option value="Sirajganj">Sirajganj</option>
                            <option value="Sunamganj">Sunamganj</option>
                            <option value="Sylhet">Sylhet</option>
                            <option value="Tangail">Tangail</option>
                            <option value="Thakurgaon">Thakurgaon</option>
                        </select>
                    </div>
                    <div>
                        <label for="upazilla" class="font-semibold">Upazilla</label>
                        <!-- <input type="text" id="upazilla" name="upazilla" class="text-gray-600 w-full p-2 border rounded"
                            value="" required> -->
                        <select id="upazilla" name="upazilla"
                            class="border border-gray-300 p-2 rounded-lg w-full focus:outline-none focus:border-pink-600"
                            required>
                            <option value="" disabled selected><?php echo $_SESSION['upazilla']; ?></option>
                        </select>
                    </div>
                    <div>
                        <label for="area" class="font-semibold">Area</label>
                        <input type="text" id="area" name="area" class="text-gray-600 w-full p-2 border rounded"
                            value="<?php echo $_SESSION['area']; ?>" required>
                    </div>
                    <div>
                        <label for="latitude" class="font-semibold">Latitude</label>
                        <input type="text" id="latitude" name="latitude" class="text-gray-600 w-full p-2 border rounded"
                            value="<?php echo $_SESSION['latitude']; ?>" required>
                    </div>
                    <div>
                        <label for="longitude" class="font-semibold">Longitude</label>
                        <input type="text" id="longitude" name="longitude"
                            class="text-gray-600 w-full p-2 border rounded"
                            value="<?php echo $_SESSION['longitude']; ?>" required>
                    </div>
                    <div>
                        <label for="longitude" class="font-semibold">&nbsp;</label>
                        <button type="button"
                            class="bg-pink-500 text-white p-2 w-full rounded-lg hover:bg-pink-700 transition duration-300"
                            onclick="getLocation()">Get My Location</button>
                    </div>
                </div>
                <div class="flex space-x-4">
                    <button name="submit" id="btn"
                        class="bg-pink-500 hover:bg-pink-700 text-white font-bold py-2 px-4 rounded">Keep
                        changes</button>
                </div>
            </form>
        </div>
        <!-- </div> -->

    </section>
    <!-- <footer class="bg-gray-800 text-white py-6">

        <div class="container mx-auto px-4">
            <div class="grid md:grid-cols-4 gap-8">
                
                <div>
                    <h3 class="text-lg font-semibold mb-4">Quick Links</h3>
                    <ul class="space-y-2">
                        <li><a href="#" class="hover:text-gray-300">Home</a></li>
                        <li><a href="#" class="hover:text-gray-300">About Us</a></li>
                        <li><a href="#" class="hover:text-gray-300">Services</a></li>
                        <li><a href="#" class="hover:text-gray-300">Book Now</a></li>
                        <li><a href="#" class="hover:text-gray-300">Locations</a></li>
                        <li><a href="#" class="hover:text-gray-300">Contact Us</a></li>
                    </ul>
                </div>
                
                <div>
                    <h3 class="text-lg font-semibold mb-4">Contact Information</h3>
                    <p class="text-gray-400">Madani Avenue</p>
                    <p class="text-gray-400">radienthub@admin.com</p>
                    <p class="text-gray-400">+8801794706582</p>
                </div>
                
                <div>
                    <h3 class="text-lg font-semibold mb-4">Follow Us</h3>
                    <ul class="space-y-2">
                        <li><a href="#" class="hover:text-gray-300">Facebook</a></li>
                        <li><a href="#" class="hover:text-gray-300">Instagram</a></li>
                        <li><a href="#" class="hover:text-gray-300">Twitter</a></li>
                    </ul>
                </div>
                
                <div>
                    <h3 class="text-lg font-semibold mb-4">Subscribe to Our Newsletter</h3>
                    <form action="#" class="flex">
                        <input type="email" placeholder="Enter your email"
                            class="p-2 w-full rounded-l-lg text-gray-800 bg-gray-700">
                    </form>
                </div>
            </div>
        </div>



    </footer> -->
</body>
<script>
    const citiesBydistrict = {
        Bagerhat: [
            "Bagerhat Sadar",
            "Chitalmari",
            "Fakirhat",
            "Kachua",
            "Mollahat",
            "Mongla",
            "Morrelganj",
            "Rampal",
            "Sarankhola",
        ],
        Bandarban: [
            "Bandarban Sadar",
            "Thanchi",
            "Ruma",
            "Rowangchhari",
            "Lama",
            "Ali Kadam",
            "Naikhongchhari",
        ],
        Barguna: [
            "Amtali",
            "Bamna",
            "Barguna Sadar",
            "Betagi",
            "Patharghata",
            "Taltali",
        ],
        Barishal: [
            "Agailjhara",
            "Babuganj",
            "Bakerganj",
            "Banaripara",
            "Gaurnadi",
            "Hizla",
            "Mehendiganj",
            "Muladi",
            "Wazirpur",
        ],
        Bhola: [
            "Bhola Sadar",
            "Burhanuddin",
            "Char Fasson",
            "Daulatkhan",
            "Lalmohan",
            "Manpura",
            "Tazumuddin",
        ],
        Bogura: [
            "Adamdighi",
            "Bogra Sadar",
            "Dhunat",
            "Dhupchanchia",
            "Gabtali",
            "Kahaloo",
            "Nandigram",
            "Sariakandi",
            "Shajahanpur",
            "Sherpur",
            "Shibganj",
            "Sonatala",
        ],
        Brahmanbaria: [
            "Akhaura",
            "Ashuganj",
            "Banchharampur",
            "Brahmanbaria Sadar",
            "Kasba",
            "Nabinagar",
            "Nasirnagar",
            "Sarail",
        ],
        Chandpur: [
            "Chandpur Sadar",
            "Faridganj",
            "Haimchar",
            "Haziganj",
            "Kachua",
            "Matlab Dakshin",
            "Matlab Uttar",
            "Shahrasti",
        ],
        Chattogram: [
            "Anwara",
            "Banshkhali",
            "Boalkhali",
            "Chandanaish",
            "Chattogram Sadar",
            "Fatikchhari",
            "Hathazari",
            "Lohagara",
            "Mirsharai",
            "Patiya",
            "Rangunia",
            "Raozan",
            "Sandwip",
            "Satkania",
            "Sitakunda",
        ],
        Chuadanga: ["Alamdanga", "Chuadanga Sadar", "Damurhuda", "Jibannagar"],
        Cox_S_Bazar: [
            "Chakaria",
            "CoxsBazarSadar",
            "Kutubdia",
            "Maheshkhali",
            "Pekua",
            "Ramu",
            "Teknaf",
            "Ukhiya",
        ],
        Cumilla: [
            "Barura",
            "Brahmanpara",
            "Burichong",
            "Chandina",
            "Chauddagram",
            "Daudkandi",
            "Debidwar",
            "Homna",
            "Laksam",
            "Monohorgonj",
            "Meghna",
            "Muradnagar",
            "Nangalkot",
            "Cumilla Sadar Dakshin",
            "Titas",
        ],
        Dhaka: ["Dhamrai", "Dohar", "Keraniganj", "Nawabganj", "Savar"],
        Dinajpur: [
            "Birampur",
            "Birganj",
            "Biral",
            "Bochaganj",
            "Chirirbandar",
            "Dinajpur Sadar",
            "Ghoraghat",
            "Hakimpur",
            "Kaharole",
            "Khansama",
            "Nawabganj",
            "Parbatipur",
        ],
        Faridpur: [
            "Alfadanga",
            "Bhanga",
            "Boalmari",
            "Charbhadrasan",
            "Faridpur Sadar",
            "Madhukhali",
            "Nagarkanda",
            "Sadarpur",
            "Saltha",
        ],
        Feni: [
            "Chhagalnaiya",
            "Daganbhuiyan",
            "Feni Sadar",
            "Parshuram",
            "Sonagazi",
            "Fulgazi",
        ],
        Gaibandha: [
            "Fulchhari",
            "Gaibandha Sadar",
            "Gobindaganj",
            "Palashbari",
            "Sadullapur",
            "Saghata",
            "Sundarganj",
        ],
        Gazipur: [
            "Gazipur Sadar",
            "Kaliakair",
            "Kaliganj",
            "Kapasia",
            "Sreepur",
        ],
        Gopalganj: [
            "Gopalganj Sadar",
            "Kashiani",
            "Kotalipara",
            "Muksudpur",
            "Tungipara",
        ],
        Habiganj: [
            "Ajmiriganj",
            "Bahubal",
            "Baniachong",
            "Chunarughat",
            "Habiganj Sadar",
            "Lakhai",
            "Madhabpur",
            "Nabiganj",
        ],
        Jamalpur: [
            "Baksiganj",
            "Dewanganj",
            "Islampur",
            "Jamalpur Sadar",
            "Madarganj",
            "Melandaha",
            "Sarishabari",
        ],
        Jashore: [
            "Abhaynagar",
            "Bagherpara",
            "Chaugachha",
            "Jashore Sadar",
            "Jhikargachha",
            "Keshabpur",
            "Manirampur",
            "Sharsha",
        ],
        Jhalokathi: ["Jhalokathi Sadar", "Kathalia", "Nalchity", "Rajapur"],
        Jhenaidah: [
            "Harinakunda",
            "Jhenaidah Sadar",
            "Kaliganj",
            "Kotchandpur",
            "Maheshpur",
            "Shailkupa",
        ],
        Khagrachhari: [
            "Dighinala",
            "Khagrachhari Sadar",
            "Lakshmichhari",
            "Mahalchhari",
            "Manikchhari",
            "Matiranga",
            "Panchhari",
            "Ramgarh",
        ],
        Khulna: [
            "Batiaghata",
            "Dacope",
            "Dumuria",
            "Dighalia",
            "Koyra",
            "Paikgachha",
            "Phultala",
            "Rupsha",
            "Terokhada",
        ],
        Kishoreganj: [
            "Austagram",
            "Bajitpur",
            "Bhairab",
            "Hossainpur",
            "Itna",
            "Karimganj",
            "Katiadi",
            "Kishoreganj Sadar",
            "Kuliarchar",
            "Mithamain",
            "Nikli",
            "Pakundia",
            "Tarail",
        ],
        Kurigram: [
            "Bhurungamari",
            "Char Rajibpur",
            "Chilmari",
            "Kurigram Sadar",
            "Nageshwari",
            "Phulbari",
            "Rajarhat",
            "Raomari",
            "Ulipur",
        ],
        Kushtia: [
            "Bheramara",
            "Daulatpur",
            "Khoksa",
            "Kumarkhali",
            "Kushtia Sadar",
            "Mirpur",
        ],
        Lakshmipur: [
            "Lakshmipur Sadar",
            "Raipur",
            "Ramganj",
            "Ramgati",
            "Kamalnagar",
        ],
        Lalmonirhat: [
            "Aditmari",
            "Hatibandha",
            "Kaliganj",
            "Lalmonirhat Sadar",
            "Patgram",
        ],
        Madaripur: ["Rajoir", "Madaripur Sadar", "Kalkini", "Shibchar"],
        Magura: ["Magura Sadar", "Mohammadpur", "Shalikha", "Sreepur"],
        Manikganj: [
            "Daulatpur",
            "Ghior",
            "Harirampur",
            "Manikganj Sadar",
            "Saturia",
            "Shivalaya",
            "Singair",
        ],
        Meherpur: ["Gangni", "Meherpur Sadar", "Mujibnagar"],
        Moulvibazar: [
            "Barlekha",
            "Juri",
            "Kamalganj",
            "Kulaura",
            "Moulvibazar Sadar",
            "Rajnagar",
            "Sreemangal",
        ],
        Munshiganj: [
            "Gazaria",
            "Lohajang",
            "Munshiganj Sadar",
            "Sirajdikhan",
            "Sreenagar",
            "Tongibari",
        ],
        Mymensingh: [
            "Bhaluka",
            "Dhobaura",
            "Fulbaria",
            "Gaffargaon",
            "Gauripur",
            "Haluaghat",
            "Ishwarganj",
            "Mymensingh Sadar",
            "Muktagachha",
            "Nandail",
            "Phulpur",
            "Trishal",
        ],
        Naogaon: [
            "Atrai",
            "Badalgachhi",
            "Dhamoirhat",
            "Manda",
            "Mohadevpur",
            "Naogaon Sadar",
            "Niamatpur",
            "Patnitala",
            "Porsha",
            "Raninagar",
            "Sapahar",
        ],
        Narail: ["Kalia", "Lohagara", "Narail Sadar"],
        Narayanganj: [
            "Araihazar",
            "Bandar",
            "Narayanganj Sadar",
            "Rupganj",
            "Sonargaon",
        ],
        Narsingdi: [
            "Belabo",
            "Monohardi",
            "Narsingdi Sadar",
            "Palash",
            "Raipura",
            "Shibpur",
        ],
        Natore: [
            "Bagatipara",
            "Baraigram",
            "Gurudaspur",
            "Lalpur",
            "Natore Sadar",
            "Singra",
        ],
        Netrokona: [
            "Atpara",
            "Barhatta",
            "Durgapur",
            "Khaliajuri",
            "Kalmakanda",
            "Kendua",
            "Madan",
            "Mohanganj",
            "Netrokona Sadar",
            "Purbadhala",
        ],
        Nilphamari: [
            "Dimla",
            "Domar",
            "Jaldhaka",
            "Kishoreganj",
            "Nilphamari Sadar",
            "Saidpur",
        ],
        Noakhali: [
            "Begumganj",
            "Chatkhil",
            "Companiganj",
            "Hatia",
            "Lakshmipur Sadar",
            "Noakhali Sadar",
            "Senbagh",
            "Subarnachar",
        ],
        Pabna: [
            "Atgharia",
            "Bera",
            "Bhangura",
            "Chatmohar",
            "Faridpur",
            "Ishwardi",
            "Pabna Sadar",
            "Santhia",
            "Sujanagar",
        ],
        Panchagarh: [
            "Atwari",
            "Boda",
            "Debiganj",
            "Panchagarh Sadar",
            "Tetulia",
        ],
        Patuakhali: [
            "Bauphal",
            "Dashmina",
            "Dumki",
            "Galachipa",
            "Kalapara",
            "Mirzaganj",
            "Patuakhali Sadar",
            "Rangabali",
        ],
        Pirojpur: [
            "Bhandaria",
            "Kawkhali",
            "Mathbaria",
            "Nazirpur",
            "Nesarabad (Swarupkathi)",
            "Pirojpur Sadar",
            "Zianagar",
        ],
        Rajbari: ["Baliakandi", "Goalandaghat", "Pangsha", "Rajbari Sadar"],
        Rajshahi: [
            "Bagha",
            "Bagmara",
            "Charghat",
            "Durgapur",
            "Godagari",
            "Mohanpur",
            "Paba",
            "Puthia",
            "Tanore",
        ],
        Rangamati: [
            "Baghaichhari",
            "Barkal",
            "Kawkhali (Betbunia)",
            "Belaichhari",
            "Juraichhari",
            "Kaptai",
            "Langadu",
            "Naniarchar",
            "Rajasthali",
            "Rangamati Sadar",
        ],
        Rangpur: [
            "Badarganj",
            "Gangachhara",
            "Kaunia",
            "Rangpur Sadar",
            "Mithapukur",
            "Pirgachha",
            "Pirganj",
            "Taraganj",
        ],
        Satkhira: [
            "Assasuni",
            "Debhata",
            "Kalaroa",
            "Kaliganj",
            "Satkhira Sadar",
            "Shyamnagar",
            "Tala",
        ],
        Shariatpur: [
            "Bhedarganj",
            "Damudya",
            "Gosairhat",
            "Naria",
            "Shariatpur Sadar",
            "Zajira",
        ],
        Sherpur: [
            "Jhenaigati",
            "Nakla",
            "Nalitabari",
            "Sherpur Sadar",
            "Sreebardi",
        ],
        Sirajganj: [
            "Belkuchi",
            "Chauhali",
            "Kamarkhanda",
            "Kazipur",
            "Raiganj",
            "Shahjadpur",
            "Sirajganj Sadar",
            "Tarash",
            "Ullahpara",
        ],
        Sunamganj: [
            "Bishwamvarpur",
            "Chhatak",
            "Dakshin Sunamganj",
            "Derai",
            "Dharampasha",
            "Dowarabazar",
            "Jagannathpur",
            "Jamalganj",
            "Sullah",
            "Sunamganj Sadar",
            "Tahirpur",
        ],
        Sylhet: [
            "Balaganj",
            "Beanibazar",
            "Bishwanath",
            "Companiganj",
            "Dakshin Surma",
            "Fenchuganj",
            "Golapganj",
            "Gowainghat",
            "Jaintiapur",
            "Kanaighat",
            "Sylhet Sadar",
            "Zakiganj",
        ],
        Tangail: [
            "Basail",
            "Bhuapur",
            "Delduar",
            "Dhanbari",
            "Ghatail",
            "Gopalpur",
            "Kalihati",
            "Madhupur",
            "Mirzapur",
            "Nagarpur",
            "Sakhipur",
            "Tangail Sadar",
        ],
        Thakurgaon: [
            "Baliadangi",
            "Haripur",
            "Pirganj",
            "Ranisankail",
            "Thakurgaon Sadar",
        ],
    };

    document
        .getElementById("district")
        .addEventListener("change", function() {
            const district = this.value;
            const upazillaSelect = document.getElementById("upazilla");
            upazillaSelect.innerHTML =
                '<option value="" disabled selected>Select upazilla</option>';

            if (district) {
                const cities = citiesBydistrict[district];
                cities.forEach((upazilla) => {
                    const option = document.createElement("option");
                    option.value = upazilla;
                    option.textContent = upazilla;
                    upazillaSelect.appendChild(option);
                });
            }
        });

    function getLocation() {
        // Check if Geolocation is supported
        if (navigator.geolocation) {
            // Get the current position
            navigator.geolocation.getCurrentPosition(showPosition, showError);
        } else {
            document.getElementById("location").innerHTML = "Geolocation is not supported by this browser.";
        }
    }

    function showPosition(position) {
        const latitude = position.coords.latitude;
        const longitude = position.coords.longitude;
        document.getElementById("latitude").value = latitude;
        document.getElementById("longitude").value = longitude;
    }

    function showError(error) {
        // Handle errors
        switch (error.code) {
            case error.PERMISSION_DENIED:
                document.getElementById("location").innerHTML = "User denied the request for Geolocation.";
                break;
            case error.POSITION_UNAVAILABLE:
                document.getElementById("location").innerHTML = "Location information is unavailable.";
                break;
            case error.TIMEOUT:
                document.getElementById("location").innerHTML = "The request to get user location timed out.";
                break;
            case error.UNKNOWN_ERROR:
                document.getElementById("location").innerHTML = "An unknown error occurred.";
                break;
        }
    }
</script>

</html>