<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Contact Support</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link href="assets/img/favicon.png" rel="icon">
  <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link href="https://fonts.gstatic.com" rel="preconnect">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
  <link href="assets/vendor/quill/quill.snow.css" rel="stylesheet">
  <link href="assets/vendor/quill/quill.bubble.css" rel="stylesheet">
  <link href="assets/vendor/remixicon/remixicon.css" rel="stylesheet">
  <link href="assets/vendor/simple-datatables/style.css" rel="stylesheet">

  <!-- Template Main CSS File -->
  <link href="assets/css/style.css" rel="stylesheet">

</head>

<body>

  <!-- ======= Header ======= -->
  <?php include 'header.php'; ?>

  <!-- ======= Main Content ======= -->
  <main id="main" class="main">
    <div class="pagetitle">
      <h1>Contact Support</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.php">Home</a></li>
          <li class="breadcrumb-item active">Contact Support</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <section class="section contact">
      <div class="row gy-4">
        <div class="col-lg-6">
          <div class="card p-4">
            <h5 class="card-title">Get in Touch</h5>
            <form action="forms/contact.php" method="post" class="php-email-form">
              <div class="row gy-4">
                <div class="col-md-12">
                  <input type="text" name="name" class="form-control" placeholder="Your Name" required>
                </div>
                <div class="col-md-12">
                  <input type="email" name="email" class="form-control" placeholder="Your Email" required>
                </div>
                <div class="col-md-12">
                  <input type="text" name="subject" class="form-control" placeholder="Subject" required>
                </div>
                <div class="col-md-12">
                  <textarea name="message" class="form-control" rows="6" placeholder="Message" required></textarea>
                </div>
                <div class="col-md-12 text-center">
                  <button type="submit" class="btn btn-primary">Send Message</button>
                </div>
              </div>
            </form>
          </div>
        </div>

        <div class="col-lg-6">
          <div class="info-box card p-4">
            <h5 class="card-title">Contact Details</h5>
            <p>If you have any questions or need further assistance, feel free to reach out to us via the following contact details:</p>
            <div class="d-flex align-items-center">
              <i class="bi bi-envelope"></i>
              <p class="ps-2">siboniso@Kwakha Indvodza.net</p>
            </div>
            <div class="d-flex align-items-center mt-3">
              <i class="bi bi-phone"></i>
              <p class="ps-2">+268 76547494</p>
            </div>
            <div class="d-flex align-items-center mt-3">
              <i class="bi bi-geo-alt"></i>
              <p class="ps-2"> Manzini, Eswatini</p>
            </div>
          </div>
        </div>
      </div>
    </section>
  </main><!-- End Main Content -->

 <?php 
  include('footer.php');
  ?>

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- Vendor JS Files -->
  <script src="assets/vendor/apexcharts/apexcharts.min.js"></script>
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/chart.js/chart.umd.js"></script>
  <script src="assets/vendor/echarts/echarts.min.js"></script>
  <script src="assets/vendor/quill/quill.js"></script>
  <script src="assets/vendor/simple-datatables/simple-datatables.js"></script>
  <script src="assets/vendor/tinymce/tinymce.min.js"></script>
  <script src="assets/vendor/php-email-form/validate.js"></script>

  <!-- Template Main JS File -->
  <script src="assets/js/main.js"></script>

</body>

</html>
