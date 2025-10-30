<?php
session_start();
include('conecta.php');

$especie = null;
$galeria = [];

if (isset($_GET['id'])) {
    $id = mysqli_real_escape_string($conn, $_GET['id']);
    
    // Busca os dados da espécie
    $sql = "SELECT * FROM especie WHERE id = '$id' LIMIT 1";
    $query = mysqli_query($conn, $sql);

    if (mysqli_num_rows($query) > 0) {
        $especie = mysqli_fetch_assoc($query);

        // Busca imagens da galeria relacionadas a essa espécie
        $sql_galeria = "SELECT * FROM galeria_imagens WHERE id_especie = '$id'";
        $result_galeria = mysqli_query($conn, $sql_galeria);

        if (mysqli_num_rows($result_galeria) > 0) {
            $galeria = mysqli_fetch_all($result_galeria, MYSQLI_ASSOC);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= $especie ? htmlspecialchars($especie['nome_comum']) : 'Detalhes da Espécie' ?></title>
  <link rel="stylesheet" href="../css/especie_detalhe.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
</head>
<body>
  <?php if ($especie): ?>
  <header class="header">
    <div class="header-content">
      <div>
        <h1>
          <i class="fas fa-butterfly"></i> 
          <span class="tooltip" data-tooltip="Nome comum da espécie"><?= htmlspecialchars($especie['nome_comum']) ?></span>
          <span class="scientific-name tooltip" data-tooltip="Nome científico da espécie"><?= htmlspecialchars($especie['nome_cientifico']) ?></span>
        </h1>
      </div>
      <button class="back-btn" onclick="window.location.href='especie.php'">
        <i class="fas fa-arrow-left"></i> Voltar ao Catálogo
      </button>
    </div>
  </header>

  <div class="container">
    <section class="section-card fade-in">
      <span class="conservation-badge">
        <i class="fas fa-leaf"></i> 
        <span class="tooltip" data-tooltip="Status de conservação segundo IUCN"><?= htmlspecialchars($especie['status_conservacao']) ?></span>
      </span>
      
      <div class="info-grid">
        <div class="info-item">
          <div class="label tooltip" data-tooltip="Ordem taxonômica">Ordem</div>
          <div class="value"><?= htmlspecialchars($especie['ordem']) ?></div>
        </div>
        <div class="info-item">
          <div class="label tooltip" data-tooltip="Família taxonômica">Família</div>
          <div class="value"><?= htmlspecialchars($especie['familia']) ?></div>
        </div>
        <div class="info-item">
          <div class="label tooltip" data-tooltip="Distância entre as pontas das asas">Envergadura</div>
          <div class="value"><?= htmlspecialchars($especie['envergadura_alas']) ?></div>
        </div>
        <div class="info-item">
          <div class="label tooltip" data-tooltip="Ambiente natural onde vive">Habitat</div>
          <div class="value"><?= htmlspecialchars($especie['habitat']) ?></div>
        </div>
        <div class="info-item">
          <div class="label tooltip" data-tooltip="Região geográfica">Distribuição</div>
          <div class="value"><?= htmlspecialchars($especie['distribuicao_geografica']) ?></div>
        </div>
        <div class="info-item">
          <div class="label tooltip" data-tooltip="Dieta principal da espécie">Alimentação</div>
          <div class="value"><?= htmlspecialchars($especie['alimentacao']) ?></div>
        </div>
      </div>
    </section>

    <?php if (!empty($especie['imagem']) || !empty($galeria)): ?>
    <section class="section-card fade-in">
      <h2 class="section-title">
        <i class="fas fa-images"></i> 
        <span class="tooltip" data-tooltip="Imagens da espécie em diferentes ângulos">Imagens</span>
      </h2>
      <div class="carousel-container">
        <div class="carousel-track" id="carouselTrack">
          <?php if (!empty($especie['imagem'])): ?>
          <div class="carousel-slide">
            <img src="img/<?= htmlspecialchars($especie['imagem']) ?>" alt="<?= htmlspecialchars($especie['nome_comum']) ?>">
          </div>
          <?php endif; ?>
          
          <?php foreach ($galeria as $img): ?>
          <div class="carousel-slide">
            <img src="<?= htmlspecialchars($img['imagem']) ?>" alt="<?= htmlspecialchars($img['descricao']) ?>">
          </div>
          <?php endforeach; ?>
        </div>
        <button class="carousel-btn prev" onclick="moveCarousel(-1)">
          <i class="fas fa-chevron-left"></i>
        </button>
        <button class="carousel-btn next" onclick="moveCarousel(1)">
          <i class="fas fa-chevron-right"></i>
        </button>
      </div>
      <div class="carousel-indicators" id="carouselIndicators"></div>
    </section>
    <?php endif; ?>

    <?php if (!empty($especie['ciclo_vida'])): ?>
    <section class="section-card fade-in">
      <h2 class="section-title">
        <i class="fas fa-sync-alt"></i> 
        <span class="tooltip" data-tooltip="Estágios de desenvolvimento da borboleta">Ciclo de Vida</span>
      </h2>
      <div class="lifecycle-timeline">
        <div class="lifecycle-stage">
          <div class="stage-icon">🥚</div>
          <h3 class="tooltip" data-tooltip="Primeira fase do desenvolvimento">Ovo</h3>
          <p><?= nl2br(htmlspecialchars($especie['ciclo_vida'])) ?></p>
        </div>
        <div class="lifecycle-stage">
          <div class="stage-icon">🐛</div>
          <h3 class="tooltip" data-tooltip="Segunda fase do desenvolvimento">Lagarta</h3>
          <p>Fase de alimentação e crescimento intenso.</p>
        </div>
        <div class="lifecycle-stage">
          <div class="stage-icon">🏺</div>
          <h3 class="tooltip" data-tooltip="Terceira fase do desenvolvimento">Crisálida</h3>
          <p>Metamorfose completa ocorre nesta fase.</p>
        </div>
        <div class="lifecycle-stage">
          <div class="stage-icon">🦋</div>
          <h3 class="tooltip" data-tooltip="Fase adulta final">Adulto</h3>
          <p>Borboleta adulta dedicando-se à reprodução.</p>
        </div>
      </div>
    </section>
    <?php endif; ?>

    <?php if (!empty($especie['descricao']) || !empty($especie['comportamento'])): ?>
    <section class="section-card fade-in">
      <h2 class="section-title">
        <i class="fas fa-book-open"></i> 
        <span class="tooltip" data-tooltip="Informações detalhadas sobre a espécie">Sobre a Espécie</span>
      </h2>
      <div class="article-content">
        <?php if (!empty($especie['descricao'])): ?>
        <p><?= nl2br(htmlspecialchars($especie['descricao'])) ?></p>
        <?php endif; ?>

        <?php if (!empty($especie['comportamento'])): ?>
        <h3 class="tooltip" data-tooltip="Padrões comportamentais da espécie">Comportamento</h3>
        <p><?= nl2br(htmlspecialchars($especie['comportamento'])) ?></p>
        <?php endif; ?>

        <?php if (!empty($especie['habitat'])): ?>
        <div class="highlight-box">
          <strong>Habitat:</strong> <?= htmlspecialchars($especie['habitat']) ?>
        </div>
        <?php endif; ?>
      </div>
    </section>
    <?php endif; ?>

    <section class="section-card fade-in">
      <h2 class="section-title">
        <i class="fas fa-lightbulb"></i> 
        <span class="tooltip" data-tooltip="Fatos interessantes sobre a espécie">Curiosidades</span>
      </h2>
      <div class="curiosity-grid">
        <div class="curiosity-item">
          <div class="icon"><i class="fas fa-map-marker-alt"></i></div>
          <p><strong>Distribuição:</strong> <?= htmlspecialchars($especie['distribuicao_geografica']) ?></p>
        </div>
        <div class="curiosity-item">
          <div class="icon"><i class="fas fa-utensils"></i></div>
          <p><strong>Alimentação:</strong> <?= htmlspecialchars($especie['alimentacao']) ?></p>
        </div>
        <div class="curiosity-item">
          <div class="icon"><i class="fas fa-ruler-horizontal"></i></div>
          <p><strong>Envergadura:</strong> <?= htmlspecialchars($especie['envergadura_alas']) ?></p>
        </div>
        <div class="curiosity-item">
          <div class="icon"><i class="fas fa-tree"></i></div>
          <p><strong>Habitat:</strong> <?= htmlspecialchars($especie['habitat']) ?></p>
        </div>
        <div class="curiosity-item">
          <div class="icon"><i class="fas fa-shield-alt"></i></div>
          <p><strong>Conservação:</strong> <?= htmlspecialchars($especie['status_conservacao']) ?></p>
        </div>
        <div class="curiosity-item">
          <div class="icon"><i class="fas fa-dna"></i></div>
          <p><strong>Família:</strong> <?= htmlspecialchars($especie['familia']) ?></p>
        </div>
      </div>
    </section>

    <section class="section-card fade-in">
      <h2 class="section-title">
        <i class="fas fa-question-circle"></i> 
        <span class="tooltip" data-tooltip="Teste seus conhecimentos sobre o conteúdo">Avaliação de Conhecimento</span>
      </h2>
      <div class="quiz-container" id="quiz-container">
        
      </div>
    </section>
  </div>

  <button class="back-to-top" id="backToTop">
    <i class="fas fa-arrow-up"></i>
  </button>

  <script>
    // Carousel
    let currentSlide = 0;
    const slides = document.querySelectorAll('.carousel-slide');
    const totalSlides = slides.length;

    function createIndicators() {
      const indicatorsContainer = document.getElementById('carouselIndicators');
      for (let i = 0; i < totalSlides; i++) {
        const indicator = document.createElement('div');
        indicator.className = 'indicator';
        if (i === 0) indicator.classList.add('active');
        indicator.onclick = () => goToSlide(i);
        indicatorsContainer.appendChild(indicator);
      }
    }

    function updateCarousel() {
      const track = document.getElementById('carouselTrack');
      track.style.transform = `translateX(-${currentSlide * 100}%)`;
      
      document.querySelectorAll('.indicator').forEach((indicator, index) => {
        indicator.classList.toggle('active', index === currentSlide);
      });
    }

    function moveCarousel(direction) {
      currentSlide += direction;
      if (currentSlide < 0) currentSlide = totalSlides - 1;
      if (currentSlide >= totalSlides) currentSlide = 0;
      updateCarousel();
    }

    function goToSlide(index) {
      currentSlide = index;
      updateCarousel();
    }

    if (totalSlides > 0) {
      setInterval(() => {
        moveCarousel(1);
      }, 5000);
      createIndicators();
    }

    // Quiz
    const quizData = [
      {
        question: "Qual é a família taxonômica desta espécie?",
        options: [
          "<?= htmlspecialchars($especie['familia']) ?>",
          "Papilionidae",
          "Pieridae",
          "Lycaenidae"
        ],
        correct: 0
      },
      {
        question: "Qual é o habitat principal desta borboleta?",
        options: [
          "Desertos",
          "<?= htmlspecialchars($especie['habitat']) ?>",
          "Tundra",
          "Savanas"
        ],
        correct: 1
      },
      {
        question: "Qual é a envergadura das asas?",
        options: [
          "5-8 cm",
          "<?= htmlspecialchars($especie['envergadura_alas']) ?>",
          "25-30 cm",
          "35-40 cm"
        ],
        correct: 1
      },
      {
        question: "Do que esta espécie se alimenta?",
        options: [
          "Apenas folhas",
          "Apenas pólen",
          "<?= htmlspecialchars($especie['alimentacao']) ?>",
          "Pequenos insetos"
        ],
        correct: 2
      },
      {
        question: "Qual é o status de conservação?",
        options: [
          "Extinta",
          "Criticamente em perigo",
          "<?= htmlspecialchars($especie['status_conservacao']) ?>",
          "Vulnerável"
        ],
        correct: 2
      }
    ];

    let currentQuestion = 0;
    let score = 0;
    let answered = false;

    function loadQuiz() {
      const container = document.getElementById('quiz-container');
      
      if (currentQuestion >= quizData.length) {
        showResults();
        return;
      }

      const question = quizData[currentQuestion];
      answered = false;

      container.innerHTML = `
        <div class="quiz-question">
          <strong>Questão ${currentQuestion + 1} de ${quizData.length}</strong><br>
          ${question.question}
        </div>
        <div class="quiz-options">
          ${question.options.map((option, index) => `
            <button class="quiz-option" onclick="checkAnswer(${index})">
              ${option}
            </button>
          `).join('')}
        </div>
        <div id="quiz-feedback"></div>
      `;
    }

    function checkAnswer(selected) {
      if (answered) return;
      answered = true;

      const question = quizData[currentQuestion];
      const options = document.querySelectorAll('.quiz-option');
      const feedback = document.getElementById('quiz-feedback');

      options.forEach((option, index) => {
        option.disabled = true;
        if (index === question.correct) {
          option.classList.add('correct');
        }
        if (index === selected && index !== question.correct) {
          option.classList.add('incorrect');
        }
      });

      if (selected === question.correct) {
        score++;
        feedback.innerHTML = '<div class="quiz-feedback correct"><i class="fas fa-check-circle"></i> Correto!</div>';
      } else {
        feedback.innerHTML = '<div class="quiz-feedback incorrect"><i class="fas fa-times-circle"></i> Incorreto. A resposta correta é: ' + question.options[question.correct] + '</div>';
      }

      setTimeout(() => {
        currentQuestion++;
        loadQuiz();
      }, 2500);
    }

    function showResults() {
      const container = document.getElementById('quiz-container');
      const percentage = Math.round((score / quizData.length) * 100);
      
      let message = '';
      let emoji = '';
      
      if (percentage === 100) {
        message = 'Excelente! Você domina o conteúdo sobre esta espécie.';
        emoji = '🏆';
      } else if (percentage >= 80) {
        message = 'Muito bom! Você compreendeu bem as informações apresentadas.';
        emoji = '🌟';
      } else if (percentage >= 60) {
        message = 'Bom desempenho! Revise o conteúdo para aprofundar seus conhecimentos.';
        emoji = '📚';
      } else {
        message = 'Recomenda-se revisar o conteúdo da página antes de tentar novamente.';
        emoji = '🔍';
      }

      container.innerHTML = `
        <div class="quiz-result">
          <div style="font-size: 3.5rem; margin-bottom: 1rem;">${emoji}</div>
          <h3>Avaliação Concluída</h3>
          <div class="score">${score} / ${quizData.length}</div>
          <p style="font-size: 1.15rem; margin-top: 1rem;">${percentage}% de acertos</p>
          <p style="font-size: 1.05rem; margin-top: 0.5rem;">${message}</p>
          <button class="restart-btn" onclick="restartQuiz()">
            <i class="fas fa-redo"></i> Refazer Avaliação
          </button>
        </div>
      `;
    }

    function restartQuiz() {
      currentQuestion = 0;
      score = 0;
      answered = false;
      loadQuiz();
    }

    // Back to Top
    const backToTop = document.getElementById('backToTop');

    window.addEventListener('scroll', () => {
      if (window.scrollY > 300) {
        backToTop.classList.add('visible');
      } else {
        backToTop.classList.remove('visible');
      }
    });

    backToTop.addEventListener('click', () => {
      window.scrollTo({
        top: 0,
        behavior: 'smooth'
      });
    });

    // Fade-in animations
    const observerOptions = {
      threshold: 0.1,
      rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.style.opacity = '1';
          entry.target.style.transform = 'translateY(0)';
        }
      });
    }, observerOptions);

    document.querySelectorAll('.fade-in').forEach(el => {
      el.style.opacity = '0';
      el.style.transform = 'translateY(20px)';
      el.style.transition = 'opacity 0.5s ease-out, transform 0.5s ease-out';
      observer.observe(el);
    });

    // Inicializar Quiz
    loadQuiz();
  </script>

  <?php else: ?>
  <div style="min-height: 100vh; display: flex; align-items: center; justify-content: center;">
    <div style="background: white; padding: 3rem; border-radius: 20px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); text-align: center;">
      <i class="fas fa-exclamation-triangle" style="font-size: 3rem; color: #ff9800; margin-bottom: 1rem;"></i>
      <h2 style="color: #8b7ab8; margin-bottom: 1rem;">Espécie não encontrada</h2>
      <p style="color: #666; margin-bottom: 2rem;">A espécie solicitada não existe no catálogo.</p>
      <button class="back-btn" onclick="window.location.href='especie.php'">
        <i class="fas fa-arrow-left"></i> Voltar ao Catálogo
      </button>
    </div>
  </div>
  <?php endif; ?>
</body>
</html>
