<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo isset($title) ? $title : "BabyBrands"; ?></title>
  
  <!-- Global CSS for header/footer -->
  <link rel="stylesheet" href="https://www.babybrandsgiftclub.com/nameGeneratorTool/template/style.css">
  
  <!-- Baby Name Generator CSS -->
  <link rel="stylesheet" href="https://www.babybrandsgiftclub.com/nameGeneratorTool/public/style.css">
  
  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

<header class="header-wrapper">
  <!-- 🔹 Desktop: Icons Row -->
  <div class="header-top-strip">
    <div class="top-strip-container">
      <ul class="contact-list-desktop">
        <li class="divider"></li>
        <li>
          <a href="https://www.babybrandsgiftclub.com/" class="selected">
            <img src="https://www.babybrandsgiftclub.com/wp-content/uploads/2024/11/parents-default.svg" class="default-img" alt="Parents">
            <img src="https://www.babybrandsgiftclub.com/wp-content/uploads/2024/11/parents-selected.svg" class="selected-img" alt="Parents">
          </a>
        </li>
        <li class="divider"></li>
        <li>
          <a href="https://www.babybrandsgiftclub.com/baby-brands-partners">
            <img src="https://www.babybrandsgiftclub.com/wp-content/uploads/2025/02/b_brands-svg.svg" class="default-img" alt="Brands">
            <img src="https://www.babybrandsgiftclub.com/wp-content/uploads/2025/02/b_brands-color-svg.svg" class="hover-img" alt="Brands">
            <img src="https://www.babybrandsgiftclub.com/wp-content/uploads/2025/02/b_brands-color-svg.svg" class="selected-img" alt="Brands">
          </a>
        </li>
        <li class="divider"></li>
        <li>
          <a href="https://www.babybrandsgiftclub.com/healthcare-center">
            <img src="https://www.babybrandsgiftclub.com/wp-content/uploads/2025/02/healthcare_centers.svg" class="default-img" alt="Healthcare">
            <img src="https://www.babybrandsgiftclub.com/wp-content/uploads/2025/02/healthcare-logo-color.svg" class="hover-img" alt="Healthcare">
            <img src="https://www.babybrandsgiftclub.com/wp-content/uploads/2025/02/healthcare-logo-color.svg" class="selected-img" alt="Healthcare">
          </a>
        </li>
        <li class="divider"></li>
      </ul>

      <!-- 🔹 Mobile Dropdown -->
      <div class="contact-list-mobile dropdown">
        <a class="btn btn-light dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
          <img src="https://www.babybrandsgiftclub.com/wp-content/uploads/2024/11/parents-selected.svg" alt="Parents">
        </a>
        <ul class="dropdown-menu">
          <li><a class="dropdown-item" href="https://www.babybrandsgiftclub.com/"><img src="https://www.babybrandsgiftclub.com/wp-content/uploads/2024/11/parents-hover.svg" alt="Parents"></a></li>
          <li><a class="dropdown-item" href="https://www.babybrandsgiftclub.com/baby-brands-partners/"><img src="https://www.babybrandsgiftclub.com/wp-content/uploads/2025/02/b_brands-svg.svg" alt="Brands"></a></li>
          <li><a class="dropdown-item" href="https://www.babybrandsgiftclub.com/healthcare-center"><img src="https://www.babybrandsgiftclub.com/wp-content/uploads/2025/02/healthcare_centers.svg" alt="Healthcare"></a></li>
        </ul>
      </div>
    </div>
  </div>

  <!-- 🔹 Main Header -->
  <div class="header-main-strip">
    <div class="brand-logo">
      <a href="https://www.babybrandsgiftclub.com/"><img src="https://www.babybrandsgiftclub.com/wp-content/uploads/2025/07/Logo_BBGC_Principal-Horizontal_-wihite-and-light-background-1-3.png" alt="BabyBrands Logo"></a>
    </div>

    <nav class="main-navigation">
      <ul>
        <li><a href="https://www.babybrandsgiftclub.com/about-us/">ABOUT US</a></li>
        <li><a href="https://www.babybrandsgiftclub.com/our-community/">OUR COMMUNITY</a></li>
        <li><a href="https://www.babybrandsgiftclub.com/resources/">RESOURCES</a></li>
        <li><a href="https://www.babybrandsgiftclub.com/offers/">DEALS</a></li>
      </ul>
    </nav>
    <a href="https://www.reg.babybrandsgiftclub.com/app/en/front/registration/step1/4" class="register-btn">REGISTER TO WIN</a>

    <div class="hamburger"><i class="fas fa-bars"></i></div>
  </div>
</header>

<!-- Mobile Menu -->
<div class="mobile-menu" id="mobileMenu">
  <div class="mobile-menu-header">
    <img src="https://www.babybrandsgiftclub.com/wp-content/uploads/2025/07/Logo_BBGC_Principal-Horizontal_-wihite-and-light-background-1-3.png" alt="BabyBrands Logo">
    <button class="close-btn" id="closeMenu">&times;</button>
  </div>
  <ul>
    <li><a href="https://www.babybrandsgiftclub.com/about-us/">About Us</a></li>
    <li><a href="https://www.babybrandsgiftclub.com/our-community/">Our Community</a></li>
    <li><a href="https://www.babybrandsgiftclub.com/resources/">Resources</a></li>
    <li><a href="https://www.babybrandsgiftclub.com/offers/">Deals</a></li>
  </ul>
  <a href="https://www.reg.babybrandsgiftclub.com/app/en/front/registration/step1/4" class="register-btn">Register To Win</a>
</div>

<!-- Dark Overlay -->
<div class="overlay" id="overlay"></div>
