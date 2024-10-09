<?php
session_start(); // Start or resume the session
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $_SESSION['district'] = $_POST['district'];
    $_SESSION['upazilla'] = $_POST['upazilla'];
    $_SESSION['area'] = $_POST['area'];
    $_SESSION['landmarks'] = $_POST['landmarks'];
    $_SESSION['latitude'] = $_POST['latitude'];
    $_SESSION['longitude'] = $_POST['longitude'];
    header('Location: submit.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Your Address</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet" />


    <style>
        #map {
            height: 400px;
            width: 100%;
        }
    </style>
</head>

<body class="bg-gray-100">
    <div class="min-h-screen flex items-center justify-center">
        <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-2xl">
            <div class="flex justify-between items-center mb-4">
                <a href="step1.php" class="bg-pink-500 hover:bg-pink-700 text-white font-bold py-2 px-4 rounded">
                    &larr;
                </a>
                <h2 class="text-xl font-bold items-center text-gray-700">Your Address&nbsp;</h2>
                <div></div> <!-- Empty div to balance the flex spacing -->
            </div>
            <form action="" method="POST" id="shopAddressForm">
                <div class="mb-4">
                    <label for="district" class="block text-gray-700 font-bold mb-2">Select Your District *</label>
                    <select id="district" name="district"
                        class="border border-gray-300 p-2 rounded-lg w-full focus:outline-none focus:border-pink-600"
                        required>
                        <option disabled selected>Select District</option>
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
                <div class="mb-4">
                    <label for="upazilla" class="block text-gray-700 font-bold mb-2">Select your upazilla *</label>
                    <select id="upazilla" name="upazilla"
                        class="border border-gray-300 p-2 rounded-lg w-full focus:outline-none focus:border-pink-600"
                        required>
                        <option value="" disabled selected>Select Your Upazilla</option>
                    </select>
                </div>
                <div class="mb-4">
                    <label for="area" class="block text-gray-700 font-bold mb-2">Your Area *</label>
                    <input type="text" id="area" name="area"
                        class="border border-gray-300 p-2 rounded-lg w-full focus:outline-none focus:border-pink-600"
                        required />
                </div>

                <div class="mb-4">
                    <table>
                        <tr>
                            <td>
                                <label for="latitude" class="block text-gray-700 font-bold mb-2">Latitude *</label>
                            </td>
                            <td>
                                <input type="text" id="latitude" name="latitude"
                                    class="border border-gray-300 p-2 rounded-lg w-full focus:outline-none focus:border-pink-600 mb-2"
                                    required />
                            </td>
                            <td>&nbsp;</td>
                            <td>
                                <!-- </div>
                  <div class="mb-4"> -->
                                <label for="longitude" class="block text-gray-700 font-bold mb-2">Longitude *</label>
                            </td>
                            <td>
                                <input type="text" id="longitude" name="longitude"
                                    class="border border-gray-300 p-2 rounded-lg w-full focus:outline-none focus:border-pink-600 mb-2"
                                    required placeholder="" />
                            </td>
                            <td>
                                <button type="button"
                                    class="bg-pink-500 text-white p-2 w-full rounded-lg hover:bg-pink-700 transition duration-300"
                                    onclick="getLocation()">Get My Location</button>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="5" style="color:red">Note: Collect your latitude and longitude by clicking get
                                my location*
                            </td>
                        </tr>
                    </table>
                </div>

                <button type="submit"
                    class="bg-pink-500 text-white p-2 w-full rounded-lg hover:bg-pink-700 transition duration-300">
                    Submit
                </button>
            </form>
        </div>
    </div>

    <script>
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
            Dhaka: ["Adabor","Badda","Cantonmant","Vatara","uttara","Dhamrai", "Dohar", "Keraniganj", "Nawabganj", "Savar"],
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
            .addEventListener("change", function () {
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

        // Initialize and add the map
        function initMap() {
            const map = new google.maps.Map(document.getElementById("map"), {
                center: { lat: 23.8103, lng: 90.4125 },
                zoom: 8,
            });

            const marker = new google.maps.Marker({
                position: { lat: 23.8103, lng: 90.4125 },
                map: map,
                draggable: true,
            });

            google.maps.event.addListener(marker, "position_changed", function () {
                const lat = marker.getPosition().lat();
                const lng = marker.getPosition().lng();
                document.getElementById("latitude").value = lat;
                document.getElementById("longitude").value = lng;
            });

            google.maps.event.addListener(map, "click", function (event) {
                marker.setPosition(event.latLng);
            });
        }
    </script>
    <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&callback=initMap" async defer></script>
</body>

</html>