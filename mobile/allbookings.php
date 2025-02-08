<?php 
$phone = $_GET['phone'];

//echo $phone;
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking List</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border: 1px solid #ddd;
        }
        th {
            background-color: #f4f4f4;
        }
        tr:hover {
            background-color: #f1f1f1;
            cursor: pointer;
        }
        .modal {
            display: none;
            position: fixed;
            z-index: 10;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 80%;
            background-color: white;
            border: 1px solid #ccc;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            padding: 20px;
            overflow-y: auto;
        }
        .modal-header {
            font-size: 18px;
            margin-bottom: 10px;
        }
        .modal-close {
            float: right;
            font-size: 20px;
            cursor: pointer;
        }
        .modal-body {
            line-height: 1.6;
        }
        .overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 5;
        }
    </style>
</head>
<body>

    <table id="bookingTable">
        <thead>
            <tr>
                <th>Booking Date</th>
                <th>Status</th>
                <th>Mode</th>
            </tr>
        </thead>
        <tbody>
            <!-- Rows will be populated dynamically -->
        </tbody>
    </table>

    <div class="overlay" id="modalOverlay"></div>

    <div class="modal" id="bookingModal">
        <div class="modal-header">
            Booking Details
            <span class="modal-close" id="modalClose">&times;</span>
        </div>
        <div class="modal-body" id="modalBody">
            <!-- Details will be populated dynamically -->
        </div>
    </div>
    
<?php 
$phone = $_GET['phone'];
?>

<script>
    // Pass the phone number from PHP to JavaScript
    const phone = "<?php echo $phone; ?>";

    // Fetch data from PHP script
    async function fetchBookings() {
        try {
            const response = await fetch(`fetch_bookings.php?phone=${encodeURIComponent(phone)}`); // Use template literal for the URL
            if (!response.ok) {
                throw new Error('Failed to fetch booking data');
            }
            const bookings = await response.json();
            populateTable(bookings);
        } catch (error) {
            console.error(error);
            alert('Error loading bookings. Please try again later.');
        }
    }

    // Populate the table with data
    function populateTable(bookings) {
        const tableBody = document.querySelector("#bookingTable tbody");
        tableBody.innerHTML = ""; // Clear existing rows

        bookings.forEach((booking) => {
            const row = document.createElement("tr");
            row.innerHTML = `
                <td>${booking.booking_date}</td>
                <td>${booking.STATUS}</td>
                <td>${booking.MODE}</td>
            `;
            row.addEventListener("click", () => openModal(booking));
            tableBody.appendChild(row);
        });
    }

    // Open modal with booking details
    function openModal(booking) {
        const modalBody = document.getElementById("modalBody");
        modalBody.innerHTML = `
            <p><strong>Booking Date:</strong> ${booking.booking_date}</p>
            <p><strong>Status:</strong> ${booking.STATUS}</p>
            <p><strong>Mode:</strong> ${booking.MODE}</p>
            <p><strong>Counselor Surname:</strong> ${booking.counselor_surname}</p>
            <p><strong>Counselor Name:</strong> ${booking.counselor_name}</p>
            <p><strong>Cancellation Reason:</strong> ${booking.cancellation_reason || "N/A"}</p>
            <p><strong>Last Modified At:</strong> ${booking.last_modified_at}</p>
            <p><strong>Approved by Counselor:</strong> ${booking.approved_by_counselor ? "Yes" : "No"}</p>
        `;
        document.getElementById("bookingModal").style.display = "block";
        document.getElementById("modalOverlay").style.display = "block";
    }

    // Close modal
    const modalClose = document.getElementById("modalClose");
    const modalOverlay = document.getElementById("modalOverlay");

    modalClose.addEventListener("click", closeModal);
    modalOverlay.addEventListener("click", closeModal);

    function closeModal() {
        document.getElementById("bookingModal").style.display = "none";
        document.getElementById("modalOverlay").style.display = "none";
    }

    // Load bookings on page load
    fetchBookings();
</script>

</body>
</html>
