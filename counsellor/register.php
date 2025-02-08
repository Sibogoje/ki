<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(135deg, #6a11cb, #2575fc);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            background: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 4px 20px rgba(0, 0, 0, 0.2);
            max-width: 500px;
            width: 100%;
            margin: auto;
        }
        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }
        .form-row {
            display: flex;
            gap: 15px;
            margin-bottom: 15px;
            justify-content: space-between;
        }
        .form-group {
            flex: 1;
            padding-right: 15px;
        }
        label {
            font-weight: 600;
            display: block;
            margin-bottom: 5px;
            color: #555;
        }
        input, select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
        }
        input[type="submit"] {
            width: 100%;
            background: #6a11cb;
            color: #fff;
            border: none;
            font-weight: 600;
            cursor: pointer;
            padding: 12px;
            border-radius: 5px;
            transition: background 0.3s ease;
        }
        input[type="submit"]:hover {
            background: #2575fc;
        }
        #login{
            background: green;
        }
        .message {
            text-align: center;
            padding: 10px;
            margin-top: 15px;
            display: none;
            color: red;
        }
        .success {
            color: green;
        }
        .error {
            color: red;
        }
        @media (max-width: 768px) {
            .form-row {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>KI Counselling App Registration</h2>
       
        <form id="registrationForm">
            <div class="form-row">
                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" id="name" name="name" required>
                </div>
                <div class="form-group">
                    <label for="surname">Surname</label>
                    <input type="text" id="surname" name="surname" required>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label for="region">Region</label>
                    <select id="region" name="region" required>
                        <option value="" disabled selected>Select your region</option>
                        <option value="Hhohho">Hhohho</option>
                        <option value="Manzini">Manzini</option>
                        <option value="Lubombo">Lubombo</option>
                        <option value="Shiselweni">Shiselweni</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="town">Town</label>
                    <input type="text" id="town" name="town" required>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="phone">Phone</label>
                    <input type="text" id="phone" name="phone" required>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" required>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>
                </div>
            </div>
             <div class="message" id="message"></div>
            
             <div class="form-row">
                 <div class="form-group"><input type="submit" value="Register" id="register"></div>
             </div>
              <div class="form-row">
                 <div class="form-group"><input type="submit" value="Go to Login" id="login"></div>
             </div>
        </form>
    </div>

   <script>
    $(document).ready(function() {
        // Hide login form initially
        $('#login').hide();

        // Handle form submission
        $('#registrationForm').submit(function(event) {
            event.preventDefault(); // Prevent default form submission

            $.ajax({
                url: 'register1.php', // Server-side script to process registration
                method: 'POST',
                data: $(this).serialize(), // Serialize form data for submission
                success: function(response) {
                    // Display success message
                    $('#message').html(response).addClass('success').fadeIn();
                    
                    // Hide the register form and show the login form
                    $('#register').hide();
                    $('#login').show();

                    // Automatically fade out the message after 10 seconds
                    setTimeout(function() {
                        $('#message').fadeOut();
                        window.location.href = 'index.php';
                    }, 3000);
                    
                    // Clear the form fields
                    $('#registrationForm')[0].reset();

                },
                error: function(xhr, status, error) {
                    // Handle errors
                    $('#message')
                        .html('An error occurred: ' + error)
                        .addClass('error')
                        .fadeIn();

                    // Automatically fade out the message after 3 seconds
                    setTimeout(function() {
                        $('#message').fadeOut();
                    }, 3000);
                }
            });
        });
    });
</script>

</body>
</html>
