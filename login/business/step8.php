<?php
require 'mysql_connection.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // // $shop_id=104;
    // // $barber_id = $_SESSION['barber_id'];  // Assuming barber_id is stored in session
    // $schedule = $_POST['schedule'];

    // // Validate schedule for overlapping slots
    // foreach ($schedule as $day => $slots) {
    //     usort($slots, function ($a, $b) {
    //         return strtotime($a['start']) - strtotime($b['start']);
    //     });

    //     for ($i = 0; $i < count($slots) - 1; $i++) {
    //         if (strtotime($slots[$i]['end']) > strtotime($slots[$i + 1]['start'])) {
    //             echo "Error: Overlapping time slots detected on $day.";
    //             exit;
    //         }
    //     }
    // }
    // header("Location: submit.php");
    // exit();

    // Clear existing schedule for the barber
    // $delete_sql = "DELETE FROM barber_schedule WHERE shop_id = $shop_id";
    // mysqli_query($conn, $delete_sql);

    // Insert new schedule
    // $_SESSION['schedule']=$schedule;
    // foreach ($schedule as $day => $slots) {
    //     foreach ($slots as $index => $slot) {
    //         $start_time = $slot['start'];
    //         $end_time = $slot['end'];
    //         $insert_sql = "INSERT INTO barber_schedule (shop_id, day_of_week, start_time, end_time) VALUES ($shop_id, '$day', '$start_time', '$end_time')";
    //         mysqli_query($conn, $insert_sql);
    //     }
    // }
    // echo "Schedule updated successfully!";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Barber Shop Schedule</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100 p-6">
    <div class="container mx-auto">
        <form action="submit.php" method="POST" class="bg-white p-6 rounded shadow-md">
            <h2 class="text-2xl font-bold text-center mb-4">Set Your Schedule</h2>
            <div id="schedule">
                <?php
                $days_of_week = ['Saturday', 'Sunday','Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];
                foreach ($days_of_week as $day): ?>
                    <div class="day mb-4">
                        <label class="block font-bold mb-2"><?= $day ?></label>
                        <div class="slots">
                            <div class="slot mb-2 flex">
                                <input type="time" name="schedule[<?= $day ?>][0][start]"
                                    class="block w-1/2 p-2 border rounded mb-2 mr-2" required>
                                <input type="time" name="schedule[<?= $day ?>][0][end]"
                                    class="block w-1/2 p-2 border rounded mb-2" required>
                            </div>
                        </div>
                        <button type="button" class="add-slot bg-pink-500 text-white p-2 rounded mt-2">Add Slot</button>
                    </div>
                <?php endforeach; ?>
            </div>
            <button type="submit"
                class="bg-pink-500 text-white p-2 w-full rounded-lg hover:bg-pink-700 transition duration-300 mt-6">Submit</button>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.add-slot').forEach(button => {
                button.addEventListener('click', function () {
                    const dayDiv = this.closest('.day');
                    const slotsDiv = dayDiv.querySelector('.slots');
                    const slotIndex = slotsDiv.querySelectorAll('.slot').length;

                    const slotDiv = document.createElement('div');
                    slotDiv.className = 'slot mb-2 flex';
                    slotDiv.innerHTML = `
                        <input type="time" name="schedule[${dayDiv.querySelector('label').textContent}][${slotIndex}][start]"
                            class="block w-1/2 p-2 border rounded mb-2 mr-2">
                        <input type="time" name="schedule[${dayDiv.querySelector('label').textContent}][${slotIndex}][end]"
                            class="block w-1/2 p-2 border rounded mb-2">
                    `;
                    slotsDiv.appendChild(slotDiv);
                });
            });

            document.querySelector('form').addEventListener('submit', function (e) {
                const schedule = document.querySelectorAll('.day');
                let valid = true;

                schedule.forEach(day => {
                    const slots = day.querySelectorAll('.slot');
                    let times = [];

                    slots.forEach(slot => {
                        const start = slot.querySelector('input[type="time"]').value;
                        const end = slot.querySelectorAll('input[type="time"]')[1].value;
                        if (start && end) {
                            times.push({ start: new Date(`1970-01-01T${start}:00`), end: new Date(`1970-01-01T${end}:00`) });
                        }
                    });

                    times.sort((a, b) => a.start - b.start);

                    for (let i = 0; i < times.length - 1; i++) {
                        if (times[i].end > times[i + 1].start) {
                            valid = false;
                            alert(`Overlapping time slots detected on ${day.querySelector('label').textContent}.`);
                            e.preventDefault();
                            return;
                        }
                    }
                });

                if (!valid) {
                    e.preventDefault();
                }
            });
        });
    </script>

</body>

</html>
