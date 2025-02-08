<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>News Updates - Kwakha Indvodza</title>
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
        }
        header h1 a {
            color: green !important; /* Header text color is green */
        }
        main {
            margin-top: 70px; /* Adjust this value to ensure content appears below the header */
            width: 100%; /* Make the main container full width */
            color: black; /* Keep content text color black */
        }
        .form-label {
            font-weight: bold;
        }

        .main2 {
            margin-top: 120px; /* Adjust this value to ensure content appears below the header */
            margin-bottom: 140px; /* Add margin to ensure content is not hidden behind the footer */
            width: 100%; /* Make the main container full width */
            color: black; /* Keep content text color black */
            padding-top: 100px; /* Add padding to the top of the main container */
            padding-bottom: 100px; /* Add padding to the bottom of the main container */
        }
    </style>
</head>
<body>
 <header>
        <h2><a href="../index.php" class="text-white text-decoration-none">&#x21A9;</a> A SAFE SPACE FOR YOU</h2>
    </header>

    <main2 class="main2 container-fluid mt-2" style="padding-bottom: 30px">
        <div class="row" id="newsContainer">
            <!-- News updates will be dynamically inserted here -->
        </div>
    </main2>

    <footer class="bg-dark text-white text-center py-3">
    <img src="../fnb foundation.png" alt="Image 3" class="mx-2" style="width: 64px; height: 45px;">
    <img src="../icon.png" alt="Image 2" class="mx-2" style="width: 45px; height: 45px;">    
    <img src="../ki.png" alt="Image 1" class="mx-2" style="width: 45px; height: 45px;">
    </footer>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            fetch('fetch_news_updates.php')
                .then(response => response.json())
                .then(data => {
                    const newsContainer = document.getElementById('newsContainer');
                    data.forEach(news => {
                        const newsCard = document.createElement('div');
                        newsCard.className = 'col-md-12 mb-4';
                        newsCard.innerHTML = `
                            <div class="card">
                                <img src="${news.image_url}" class="card-img-top" alt="${news.title}">
                                <div class="card-body">
                                    <h5 class="card-title">${news.title}</h5>
                                    <p class="card-text">${news.short_description}</p>
                                    <a href="news_detail.php?id=${news.id}" class="btn btn-primary">Read More</a>
                                </div>
                            </div>
                        `;
                        newsContainer.appendChild(newsCard);
                    });
                })
                .catch(error => console.error('Error fetching news updates:', error));
        });
    </script>
</body>
</html>
