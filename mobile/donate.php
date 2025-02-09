<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Donate to Kwakha Indvodza</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="../styles.css">
    <link rel="manifest" href="../manifest.json">
    <link rel="icon" href="../icon.png" type="image/png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script>
        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.register('../service-worker.js')
                .then((registration) => {
                    console.log('Service Worker registered with scope:', registration.scope);
                })
                .catch((error) => {
                    console.log('Service Worker registration failed:', error);
                });
        }
    </script>
    <style>
        header {
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 1000;
            background-color: black; /* Header background color is black */
            display: flex;
            align-items: center;
            padding: 10px;
            color: green;
        }
        .back-button {
            color: green;
            text-decoration: none;
            display: flex;
            align-items: center;
        }
        .back-button::before {
            content: '<';
            font-weight: bold;
            margin-right: 5px;
        }
        header h2 {
            margin: 0;
            font-size: 16px;
            color: green;
            flex-grow: 1;
            text-align: center;
        }
        .main2 {
            margin-top: 120px; /* Adjust this value to ensure content appears below the header */
            margin-bottom: 100px; /* Add margin to ensure content is not hidden behind the footer */
            width: 100%; /* Make the main container full width */
            color: black; /* Keep content text color black */
            padding-top: 100px; /* Add padding to the top of the main container */
        }
        .form-label {
            font-weight: bold;
        }
        .btn {
            background-color:rgb(27, 4, 129); /* Button background color is green */
            border-color: green; /* Button border color is green */
        }
    </style>
</head>
<body>
<header>
        <a href="../index.php" class="back-button py-2">Back</a>
        <h2>A SAFE SPACE FOR YOU</h2>
    </header>

    <main2 class="main2 container-fluid mt-2" style="padding-bottom: 30px">
        <p class="text-center">Your support makes a difference. Please complete the form below to make your donation.</p>
        <form id="donationForm" action="process_donation.php" method="POST">
            <!-- Donor Information -->
            <div class="mb-3">
                <label for="donorName" class="form-label">Full Name</label>
                <input type="text" class="form-control" id="donorName" name="donor_name" placeholder="Enter your full name" required>
            </div>
            <div class="mb-3">
                <label for="donorPhone" class="form-label">Phone Number</label>
                <input type="tel" class="form-control" id="donorPhone" name="donor_phone" placeholder="Enter your phone number" required>
            </div>
            <div class="mb-3">
                <label for="donorEmail" class="form-label">Email Address</label>
                <input type="email" class="form-control" id="donorEmail" name="donor_email" placeholder="Enter your email address">
            </div>

            <!-- Donation Details -->
            <div class="mb-3">
                <label for="donationAmount" class="form-label">Donation Amount (SZL)</label>
                <input type="number" step="0.01" class="form-control" id="donationAmount" name="donation_amount" placeholder="Enter the donation amount" required>
            </div>
            <div class="mb-3">
                <label for="otherDonations" class="form-label">Other Donations (Optional)</label>
                <textarea class="form-control" id="otherDonations" name="other_donations" placeholder="Mention other donations or comments"></textarea>
            </div>

            <!-- Pledge and Reminder Options -->
            <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" id="isPledge" name="is_pledge">
                <label for="isPledge" class="form-check-label">This is a pledge</label>
            </div>
            <div class="mb-3">
                <label for="pledgeDate" class="form-label">Pledge Date</label>
                <input type="date" class="form-control" id="pledgeDate" name="pledge_date" disabled>
            </div>
            <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" id="reminderCheck" name="reminder_check">
                <label for="reminderCheck" class="form-check-label">Send me a reminder</label>
            </div>
            <div class="mb-3">
                <label for="reminderDate" class="form-label">Reminder Date</label>
                <input type="date" class="form-control" id="reminderDate" name="reminder_date" disabled>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="btn btn-primary w-100">Submit Donation Pledge</button>
        </form>
    </main2>

<?php include "../footer.php"; ?>

    <script>
        document.getElementById('isPledge').addEventListener('change', function () {
            document.getElementById('pledgeDate').disabled = !this.checked;
        });

        document.getElementById('reminderCheck').addEventListener('change', function () {
            document.getElementById('reminderDate').disabled = !this.checked;
        });
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>
</html>
