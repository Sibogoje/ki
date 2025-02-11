<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <style>
        body {
            background-color: #001f3f;
            color: #ffffff;
            font-family: Arial, sans-serif;
            display: flex;
            flex-direction: column;
            align-items: center;
            height: 100vh;
            margin: 0;
            padding : 30px;
        }
        header {
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 1000;
            background-color: black;
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
        header a {
            color: white;
            text-decoration: none;
            margin-right: auto;
            text-align: center;
            padding: 20px;
        }
        .container {
            text-align: center;
            background-color: #004080;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
            margin: 10px; /* Add margin for small screens */
            margin-top: 80px; /* Add margin to avoid overlap with header */
            width: 90%;
            max-width: 800px;
        }
        .container img {
            width: 100px;
            margin-bottom: 20px;
        }
        .container h1 {
            margin-bottom: 20px;
        }
        .form-group {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
        }
        .form-group .row {
            display: flex;
            width: 100%;
            justify-content: space-between;
            gap: 10px;
        }
        .form-group .row input[type="text"], .form-group .row input[type="password"], .form-group .row input[type="email"], .form-group .row select {
            width: 48%; /* Adjust width to fit two fields per row */
            padding: 10px;
            margin: 10px 1%; /* Adjust margin to fit two fields per row */
            border: none;
            border-radius: 5px;
        }
        .form-group.full-width {
            width: 100%;
        }
        .form-group.full-width input[type="text"], .form-group.full-width input[type="password"], .form-group full-width select {
            width: 100%;
        }
        .form-group input[type="submit"] {
            background-color:rgb(51, 186, 186);
            color:rgb(0, 0, 0);
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 100%; /* Make the button width equal to the card */
            font-weight : bold;
        }
        .form-group input[type="submit"]:hover {
            background-color: rgb(51, 186, 186);
        }
        .sponsors {
            margin-top: 20px;
        }
        .sponsors img {
            width: 50px;
            margin: 0 10px;
        }
        .links {
            margin-top: 20px;
        }
        .links a {
            color: #00a65a;
            text-decoration: none;
            margin: 0 10px;
        }
        .links a:hover {
            text-decoration: underline;
        }
        .error {
            color: #ff0000;
            margin-top: 10px;
        }
        @media (max-width: 600px) {
            .form-group .row input[type="text"], .form-group .row input[type="password"], .form-group .row input[type="email"], .form-group .row select {
                width: 100%; /* Full width for small screens */
                margin: 10px 0;
            }
        }
    </style>
</head>
<body>
    <?php
    session_start();
    ?>
 <header>
        <a href="../index.php" class="back-button py-2">Back</a>
        <h2>REGISTER</h2>
    </header>
    <div class="container">
<form action="register_process.php" method="POST">
    <?php if (isset($_SESSION['error'])): ?>
        <div class="error">
            <?php
            echo $_SESSION['error'];
            unset($_SESSION['error']);
            ?>
        </div>
    <?php endif; ?>
    <div class="form-group">
        <div class="row">
            <input type="text" name="surname" placeholder="Surname" required>
            <input type="text" name="name" placeholder="Name" required>
        </div>
        <div class="row">
            <input type="email" name="email" placeholder="Email Address">
            <input type="text" name="phone_number" placeholder="Phone Number" required>
        </div>
        <div class="row">
            <select name="gender" required>
                <option value="">Select Gender</option>
                <option value="male">Male</option>
                <option value="female">Female</option>
            </select>
           <select class="form-control" id="age" name="age">
                <option value="">Select Age</option>
                <option value="12-16">12-16</option>
                <option value="17-20">17-20</option>
                <option value="21-24">21-24</option>
                <option value="25-28">25-28</option>
                <option value="29-31">29-31</option>
                <option value="32-35">32-35</option>
                <option value="36-40">36-40</option>
                <option value="41-45">41-45</option>
                <option value="46-50">46-50</option>
                <option value="51-55">51-55</option>
                <option value="56-60">56-60</option>
            </select>
        </div>
        <div class="row">
            <select id="region" name="region" required onchange="updateConstituencies()">
                <option value="">Select Region</option>
                <option value="Hhohho">Hhohho</option>
                <option value="Lubombo">Lubombo</option>
                <option value="Manzini">Manzini</option>
                <option value="Shiselweni">Shiselweni</option>
            </select>
                        <select id="constituency" name="constituency" required>
                <option value="">Select Constituency</option>
            </select>
        </div>
        <div class="row">
             <input type="text" name="town" placeholder="Nearest Town" required>
             <select name="employment_status" required>
                <option value="">Employment Status</option>
                <option value="Employed">Employed</option>
                <option value="Not Employed">Not Employed</option>
            </select>
        </div>
        <div class="row">
            <select name="orphan">
                <option value="">Orphan Status</option>
                <option value="Orphaned">Orphaned</option>
                <option value="Not Orphaned">Not Orphaned</option>
            </select>
            <select name="disability">
                <option value="">Disability Status</option>
                <option value="Yes">Yes</option>
                <option value="No">No</option>
            </select>
        </div>
        <div class="row">
            <select name="marital" required>
                <option value="">Marital Status</option>
                <option value="single">Single</option>
                <option value="married">Married</option>
                <option value="divorced">Divorced</option>
                <option value="widowed">Widowed</option>
            </select>
            <select name="education">
                <option value="">Education Level</option>
                <option value="None">None</option>
                <option value="Primary School">Primary School</option>
                <option value="High School">High School</option>
                <option value="Tertiary">Tertiary</option>
            </select>
        </div>
        <div class="row">
            <input type="password" name="password" placeholder="Password" required>
        </div>
        <div class="form-group full-width">
            <input type="submit" value="REGISTER">
        </div>
    </div>

    <script>
        const constituencies = {
            "Manzini": [
                "Kukhanyeni", "Mtfongwaneni", "Mahlangatja", "Mangcongco", "Ludzeludze", "Emkhiweni",
                "Lobamba Lomdzala", "Ntondozi", "Lamgabhi", "Mafutseni", "Nhlambeni", "Ngwempisi",
                "Mhlambanyatsi", "Manzini North", "Manzini South", "Kwaluseni"
            ],
            "Shiselweni": [
                "Sigwe", "Ngudzeni", "Khubuta", "Mtsambama", "Gege", "Shiselweni II", "Sandleni",
                "Hosea", "Zombodze", "Shiselweni I", "Nkwene", "Maseyisini", "Matsanjeni", "Somntongo"
            ],
            "Lubombo": [
                "Sithobela", "Mpholonjeni", "Siphofaneni", "Nkilongo", "Matsanjeni North", "Dvokodvweni",
                "Lubuli", "Hlane", "Lomahasha", "Mhlume", "Lugongolweni"
            ],
            "Hhohho": [
                "Timphisini", "Ndzingeni", "Mhlangatane", "Ntfonjeni", "Pigg’s Peak", "Madlangampisi",
                "Lobamba", "Mbabane West", "Mbabane East", "Hhukwini", "Maphalaleni", "Motshane",
                "Nkhaba", "Mayiwane"
            ]
        };

        function updateConstituencies() {
            const region = document.getElementById("region").value;
            const constituencySelect = document.getElementById("constituency");

            // Clear existing options
            constituencySelect.innerHTML = '<option value="">Select Constituency</option>';

            if (region) {
                const regionConstituencies = constituencies[region];
                regionConstituencies.forEach(constituency => {
                    const option = document.createElement("option");
                    option.value = constituency;
                    option.text = constituency;
                    constituencySelect.appendChild(option);
                });
            }
        }
    </script>
</form>
        <div class="links">
            <a href="login-page.php">Already have an account? Login</a>
        </div>
        <div class="sponsors">
            <img src="../fnb foundation.png" alt="FNB" height="50px" width="100px">
            <img src="../icon.png" alt="KI" height="50px" width="100px">
            <img src="../ki.png" alt="KI">
        </div>
    </div>
</body>
</html>
