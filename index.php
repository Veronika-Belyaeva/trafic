<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Noto+Sans:wght@100;200;400;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="css/normalize.css">
  <link rel="stylesheet" href="css/style.css">
  <title>Билетики</title>
</head>
<body class="body-main">
  <nav class="navbar">
    <a href="./" class="header-logo">
      <svg width="33" height="39" class="logo">
        <use href="image/sprite.svg#logo"></use>
      </svg> 
      <!-- <img src="image/logo.png" alt="Логотип" class="logo"> -->
    </a>
    <ul class="navbar-list">
      <li class="navbar-item">
        <a href="#about" class="header-link">О нас</a>
      </li>
      <li class="navbar-item">
        <a href="#quiz" class="header-link">Квизы</a>
      </li>
    </ul>
    <a href="/registration.php" class="button-link">
      <button class="button navbar-button">
        <span class="button-text">Зарегистрироваться</span>
      </button>
    </a>

  </nav>
  <header class="header header-image">
    <div class="container">
      <div class="header-content">
        <div class="seporator"></div>
        <h1 class="header-title">
          TRAFFIC LAWS
        </h1>
        <p class="header-text">
          Наш сайт — ваш источник информации о Правилах Дорожного Движения и возможность проверить свои знания через тесты и квизы. Обсудите интересующие вас вопросы на форуме или свяжитесь с нами для получения поддержки.
        </p>

      </div>
    </div>
  </header>

  <section id="about" class="section about">
    <img src="image/plan.png" alt="Car" class="about-car-photo">
    <div class="container">
      <div class="about-content-wrapper">
        <div class="about-content">
          <div class="seporator"></div>
          <h2 class="section-title">О нас</h2>
          <p class="about-text">
            Мы предлагаем интерактивную форму прохождения билетов по правилам дорожного движения. Также можно пройти тесты по отдельным темам, над которыми  вы бы хотели потренироваться. К тому же будет вестись статистика ваших прохождений!</p>
        </div>
      </div>
    </div>
  </section>

  
  <section id="quiz" class="section grey-section section-school">
    <div class="container quiz">
      <div class="wrapper-school">
        <div class="seporator"></div>
        <h2 class="section-title">Квиз</h2>
        <p class="quiz-text">
          Наши квизы основаны на официальных билетах Правил Дорожного Движения, что позволяет пользователям проверить свои знания в интерактивной форме. Каждый квиз содержит вопросы, аналогичные тем, которые могут встретиться на экзамене по вождению, и предлагает выбрать правильные ответы из предложенных вариантов
        </p>
        <ul class="quiz-list">
          <li class="quiz-item">
            <svg width="42" height="34" viewBox="0 0 47 39" fill="none" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
              <rect width="47" height="39" fill="url(#pattern0_65_44)"/>
              <defs>
              <pattern id="pattern0_65_44" patternContentUnits="objectBoundingBox" width="1" height="1">
              <use xlink:href="#image0_65_44" transform="matrix(0.00921986 0 0 0.0111111 0.0851064 0)"/>
              </pattern>
              <image id="image0_65_44" width="90" height="90" xlink:href="data:image/png;base64,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"/>
              </defs>
              </svg>
              <p class="quiz-item-text">Основные дорожные знаки</p>
          </li>
          <li class="quiz-item">
            <svg width="42" height="34" viewBox="0 0 48 39" fill="none" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
              <rect width="48" height="39" fill="url(#pattern0_65_47)"/>
              <defs>
              <pattern id="pattern0_65_47" patternContentUnits="objectBoundingBox" width="1" height="1">
              <use xlink:href="#image0_65_47" transform="matrix(0.00902778 0 0 0.0111111 0.09375 0)"/>
              </pattern>
              <image id="image0_65_47" width="90" height="90" xlink:href="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAFoAAABaCAYAAAA4qEECAAAACXBIWXMAAAsTAAALEwEAmpwYAAADBElEQVR4nO2cv49MURTHLwWCDhWhUbBh55wZEjQbETHnzASN/0HhRxQqkk00RKfQbKETiahIJMzc8xA/E7E0GioJiYawm9Dw5G6ySGQyz769787Ofj/JaTd7P3v2++57573rHAAAAAAAAAAAAABw2djYChM6acLPTHnalPMhrWkv9NSUTtxubl5e6Z8+a27fYEqvBkBCXmkJvwxrr66TF6Nk/SO7ks6eiYvUi9W05ZWPVyB6JpMXuWh6El20V55KvVBLLpqnootOvUgbkIJoheg8dReiozW9OESHppeKjFaITt5xho7m5JIQHZpe4PBmtNDzMr9P7tySB01ad095q7XqB0z4oheehGidX9G96LR5t1e65ZV/oqM1nuhZTOmQKX1BdEhc0YGsNbrNlD8ioyvAK+0x5e+4GFaA19ppiK5oDOeV3mF714Ob7cZKL/zpn1J6Y8pXrcn7XEHCNBuie3Bn/+iqAnImwp7a9cErb4LocqLDiOmUK4AXeo07w1Ki6UOhrha6BtElRIfK2o0trg9e+BJElxTd1dpe1wevfBaiy3Z0s77L9cGEz0N0GdHCPzqHd65xfTClKxBdrqPvugJ4pS5Ez1G0F/oaHh4V+Vle6RtEz0G0V37RbTfqrgC+xQdxw9KD60dGlnmhC3+XKZ/rCh/LhHe4/8ALdyA6MplyE0/vIpO1G2tN6S1ER388yg8xYYlIp93YaMKPMDOUODPD8J1JV/ioF/6MKbjO7xR8fNwttVa9YcpnTOh9zHc7XGwGZXvnfxdf9ko3TOh+mLrElDv0orOxkdVVCYRoTS8XHa0QjeiYK8hoxsXQkNGMXQeig7HrmAX7aNywxCP1jYINSEVUDNEG0YyOTv0vbogOTi4LGa3pReJiqINR2HUoROepu3BBdTSOY+M8fPIcPzpwwGBeyQGD8/Wdni3gCi9XRhcdXkwJB6GmXqwlqnAWSHj9wVV2rPEilO2FJ7ut2npXJaGzw6mzIa+G+QLpw9qEHoe4qKyTAQAAAAAAAAAAANxg8wshzDkkxyorfQAAAABJRU5ErkJggg=="/>
              </defs>
              </svg>
              <p class="quiz-item-text">Правила парковки</p>
          </li>
          <li class="quiz-item">
            <svg width="42" height="34" viewBox="0 0 42 48" fill="none" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
              <rect width="42" height="48" fill="url(#pattern0_65_46)"/>
              <defs>
              <pattern id="pattern0_65_46" patternContentUnits="objectBoundingBox" width="1" height="1">
              <use xlink:href="#image0_65_46" transform="matrix(0.0111111 0 0 0.00972222 0 0.0625)"/>
              </pattern>
              <image id="image0_65_46" width="90" height="90" xlink:href="data:image/png;base64,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"/>
              </defs>
              </svg>
              <p class="quiz-item-text">Правила обгона и объезда</p>
          </li>
          <li class="quiz-item">
            <svg width="42" height="34" viewBox="0 0 47 41" fill="none" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
              <rect width="47" height="41" fill="url(#pattern0_65_51)"/>
              <defs>
              <pattern id="pattern0_65_51" patternContentUnits="objectBoundingBox" width="1" height="1">
              <use xlink:href="#image0_65_51" transform="matrix(0.00969267 0 0 0.0111111 0.0638298 0)"/>
              </pattern>
              <image id="image0_65_51" width="90" height="90" xlink:href="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAFoAAABaCAYAAAA4qEECAAAACXBIWXMAAAsTAAALEwEAmpwYAAADf0lEQVR4nO2cO2wTQRCGBwQF0PCogBYokMjNxELiUaQhJrOOSChooCW0UEORBmhBARQILdQgKhJ7xyAgFRK0hBboSGgCEpFitEkbK7njvN69nU+60t6bT3Or893vAVAURVEURVEURVE2ptXIDouhzmZHl4/DVj47d4EOQeq0GoMXey26zdk4pI4Yuttr0dbQHUgdy9TstWgxNAcp0wHYZpkWe97RTEtuLUiVJg8c22JH/m9Hd9408CikioxkV3yJtoyXIVWsofu+RAvjPUgVYfrgraMNvYcUaQ8N7bCMv/2Jxj8fa7WdkBptk+GWL/sytg4nu4EZpIblbMK36DbjVUgNMTTjW7QwPoHUEKbP3rcOpk+QEvOXTu2yBv96F21wxa0NqSCcnc11yZe1dazLPgOp0BoZvNEv0cJ4HVJBDD3vX0fTM0gFy7TQt4429AVSoD2W7bWGVvvY0avvGif2QdVpMQ4X6MIyO7rTbuA5v1UriqIoilIOk5OwvYpvmSd91PVqtLbbMj0Sg7+K3EKldFimJWvwYaEHVpbxcb8LkMgO5yz3JZPnfZ4etO6Acdm5U9EmMNG6dVBB2Thd6C2IGHzgNnrdGmiTvZkWhXGqZ29vui0MkSOh1RXcCVW1ruBOqKp1bbR/u/0KIseGVpf7NbTBLc4URI4Nra61fIb7Bcm4vHYYnK5CbmI+1Lrcg5duD1+ao3S6jNvDbmuX8d3uHPPWVYkMh3gWXYmsR5EMh3gWXYmsR5EMh/ju6NizHkUzHOK/o+POehTNcIj/jo476yGGbsUiWhhvQqxYppexiLaGXkCsCOP3WEQL0zeIkbfnawdLk2A8iI51roebkRGbaDE4BrHhZmTEJtoy3obYcDMyYhMthmYhJtyDGDH0MzbRlmkxmodIDjcbo+RO63Rbq+x12nU6AlWcwyGBiY5qrkeeORwSmOio5nrkmcMhgYmOZq5H3jkcEpzoSOZ65J3DIYGJjmauhzQGr0UvmrMJCB1r6GnsosXQDFRtDocEKDr4uR5rf8cwuBK9aIMrs8MDe6BKczgkQNHBz/UoK8MhAYgOOutRVoZDAhAddNbDGvxaGdFMCxAir+vH95eV4ZAQRBtabY6fPAChYUeyeq+Kln7s0YY6LpcCVc5wSCCig8x6lJnhkEBEB5n1KDPDIcGIxh9+LSqKoiiKoiiKooB//gFGLc3moxrELwAAAABJRU5ErkJggg=="/>
              </defs>
              </svg>
              <p class="quiz-item-text">Движение по полосам</p>
          </li>
        </ul>
        <p class="quiz-text">
          Для того чтобы начать проходить квизы, нужно зарегистрироваться. Это нужно для вас, чтобы вы смогли смотреть на ваши результаты
        </p>
        <a href="/registration.php" class="">
          <button class="button navbar-button">
            <span class="button-text">Зарегистрироваться</span>
          </button>
        </a>
 
      </div>
    </div>
    <img src="image/schoolavto.png" alt="schoolavto" class="school-photo">
  </section>
  <footer class="footer">
    <div class="container">
      <div class="footer-top">
        <svg class="logo-svg footer-logo">
          <use href="img/sprite.svg#logo"></use>
        </svg>  
        <a href="tel:+74996861014" class="footer-phone">+7 (499) 686-10-14</a>
        <div class="footer-info">
          <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M12.625 19.6875C12.3438 20.125 11.6875 20.125 11.4062 19.6875C6.84375 13.125 6 12.4375 6 10C6 6.6875 8.6875 4 12 4C15.3438 4 18 6.6875 18 10C18 12.4375 17.1875 13.125 12.625 19.6875ZM12 12.5C13.4062 12.5 14.5 11.4062 14.5 10C14.5 8.625 13.4062 7.5 12 7.5C10.625 7.5 9.5 8.625 9.5 10C9.5 11.4062 10.625 12.5 12 12.5Z" fill="#5C8EE6"/>
            </svg>            
          <address class="footer-info-address">г. Мосвка, Холодильный пер. 4к1с8</address>
        </div>
        <div class="footer-info">
          <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M4.3125 9.96875C5.03125 10.5312 5.9375 11.2188 9.125 13.5312C9.75 14 10.9062 15.0312 12 15.0312C13.125 15.0312 14.25 14 14.9062 13.5312C18.0938 11.2188 19 10.5312 19.7188 9.96875C19.8438 9.875 20 9.96875 20 10.125V16.5C20 17.3438 19.3438 18 18.5 18H5.5C4.6875 18 4 17.3438 4 16.5V10.125C4 9.96875 4.1875 9.875 4.3125 9.96875ZM12 14C11.2812 14.0312 10.25 13.0938 9.71875 12.7188C5.5625 9.71875 5.25 9.4375 4.3125 8.6875C4.125 8.5625 4 8.34375 4 8.09375V7.5C4 6.6875 4.6875 6 5.5 6H18.5C19.3438 6 20 6.6875 20 7.5V8.09375C20 8.34375 19.9062 8.5625 19.7188 8.6875C18.7812 9.4375 18.4688 9.71875 14.3125 12.7188C13.7812 13.0938 12.75 14.0312 12 14Z" fill="#5C8EE6"/>
            </svg>
             
          <a href="mailto:a.dragunov@tdaliance.ru" class="footer-info-email">a.dragunov@tdaliance.ru</a>
        </div>
        <div class="footer-social">
          <a href="#" class="footer-social-link">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path d="M21.4439 16.7569C20.6455 15.32 18.5889 13.5935 17.7398 12.8422C17.5074 12.6365 17.4826 12.2806 17.694 12.0531C19.3144 10.3119 20.6104 8.31123 21.0565 7.1023C21.2533 6.56826 20.8459 6.004 20.2719 6.004H18.6044C18.0548 6.004 17.7331 6.20127 17.5799 6.51538C16.2332 9.27491 15.078 10.4692 14.2694 11.1672C13.8167 11.5581 13.1107 11.2343 13.1107 10.6393C13.1107 9.49306 13.1107 8.01133 13.1107 6.96057C13.1107 6.45096 12.6939 6.03865 12.1799 6.03865L9.13379 6.004C8.75036 6.004 8.53132 6.43808 8.76147 6.74242L9.26441 7.4644C9.45368 7.71454 9.55587 8.01888 9.55587 8.33122L9.55321 11.5826C9.55321 12.1482 8.86766 12.4245 8.46068 12.0282C7.08381 10.6873 5.88909 7.94913 5.45901 6.63979C5.33461 6.2608 4.97962 6.00489 4.57709 6.004L2.93452 6C2.31828 6 1.86777 6.58425 2.03527 7.1725C3.5361 12.4405 6.61552 17.4522 12.1035 17.9956C12.6442 18.0489 13.1107 17.6135 13.1107 17.0745V15.3658C13.1107 14.8757 13.495 14.4545 13.9891 14.4421C14.0064 14.4416 14.0237 14.4416 14.041 14.4416C15.4926 14.4416 17.1182 16.5543 17.6869 17.5424C17.8504 17.8267 18.1561 18 18.4867 18H20.6962C21.3408 18 21.7545 17.3162 21.4439 16.7569Z" fill="#5C8EE6"/>
              </svg>               
          </a>
          <a href="#" class="footer-social-link">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path d="M8.44444 4C5.99378 4 4 5.99378 4 8.44444V15.5556C4 18.0062 5.99378 20 8.44444 20H15.5556C18.0062 20 20 18.0062 20 15.5556V8.44444C20 5.99378 18.0062 4 15.5556 4H8.44444ZM8.44444 5.77778H15.5556C17.0258 5.77778 18.2222 6.97422 18.2222 8.44444V15.5556C18.2222 17.0258 17.0258 18.2222 15.5556 18.2222H8.44444C6.97422 18.2222 5.77778 17.0258 5.77778 15.5556V8.44444C5.77778 6.97422 6.97422 5.77778 8.44444 5.77778ZM16.4444 6.66667C16.2087 6.66667 15.9826 6.76032 15.8159 6.92702C15.6492 7.09372 15.5556 7.31981 15.5556 7.55556C15.5556 7.7913 15.6492 8.0174 15.8159 8.18409C15.9826 8.35079 16.2087 8.44444 16.4444 8.44444C16.6802 8.44444 16.9063 8.35079 17.073 8.18409C17.2397 8.0174 17.3333 7.7913 17.3333 7.55556C17.3333 7.31981 17.2397 7.09372 17.073 6.92702C16.9063 6.76032 16.6802 6.66667 16.4444 6.66667ZM12 7.55556C9.54933 7.55556 7.55556 9.54933 7.55556 12C7.55556 14.4507 9.54933 16.4444 12 16.4444C14.4507 16.4444 16.4444 14.4507 16.4444 12C16.4444 9.54933 14.4507 7.55556 12 7.55556ZM12 9.33333C13.4702 9.33333 14.6667 10.5298 14.6667 12C14.6667 13.4702 13.4702 14.6667 12 14.6667C10.5298 14.6667 9.33333 13.4702 9.33333 12C9.33333 10.5298 10.5298 9.33333 12 9.33333Z" fill="#5C8EE6"/>
              </svg>  
                          
          </a>
        </div>
        <!-- /.footer-social -->
      </div>
      <!-- /.footer-top -->
    </div>
    </div>
    <!-- /.container -->
  </footer>
  
      </form>
    </div>
  </div>
  <script src="js/main.js"></script>
</body>
</html>