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
  <style>
    @import url("https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap");

* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

body {
  font-family: "Poppins", sans-serif;
  background-image: linear-gradient(120deg, #ccbcfa 0%, #9485c0 70%);
  min-height: 100vh;
  color: #333;
  line-height: 1.6;
}

.header {
  background: rgba(255, 255, 255, 0.95);
  backdrop-filter: blur(10px);
  padding: 1.2rem 2rem;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
  position: sticky;
  top: 0;
  z-index: 100;
}

.header-content {
  max-width: 1400px;
  margin: 0 auto;
  display: flex;
  justify-content: space-between;
  align-items: center;
  gap: 1rem;
}

.header h1 {
  font-size: 1.5rem;
  color: #8b7ab8;
  font-weight: 600;
}

.header .scientific-name {
  font-style: italic;
  color: #666;
  font-size: 0.9rem;
  margin-left: 0.5rem;
}

.back-btn {
  background-image: linear-gradient(-45deg, #9d79ff 0%, #7658d6 70%);
  border: none;
  border-radius: 10px;
  color: white;
  padding: 0.7rem 1.3rem;
  font-size: 0.95rem;
  font-weight: 500;
  cursor: pointer;
  display: flex;
  align-items: center;
  gap: 0.5rem;
  transition: all 0.3s ease;
}

.back-btn:hover {
  background-image: linear-gradient(120deg, #9d79ff 0%, #7658d6 70%);
  transform: translateY(-2px);
}

.container {
  max-width: 1400px;
  margin: 0 auto;
  padding: 2rem;
}

.tooltip {
  position: relative;
  cursor: help;
  border-bottom: 1px dotted #8b7ab8;
}

.tooltip::after {
  content: attr(data-tooltip);
  position: absolute;
  bottom: 100%;
  left: 50%;
  transform: translateX(-50%) translateY(-8px);
  background: rgba(118, 88, 214, 0.95);
  color: white;
  padding: 8px 12px;
  border-radius: 8px;
  font-size: 0.85rem;
  white-space: nowrap;
  opacity: 0;
  pointer-events: none;
  transition: opacity 0.3s ease, transform 0.3s ease;
  z-index: 1000;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
}

.tooltip:hover::after {
  opacity: 1;
  transform: translateX(-50%) translateY(-12px);
}

.section-card {
  background: white;
  border-radius: 20px;
  padding: 2.5rem;
  margin-bottom: 2rem;
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
  transition: all 0.3s ease;
}

.section-card:hover {
  box-shadow: 0 6px 20px rgba(0, 0, 0, 0.12);
}

.section-title {
  font-size: 1.8rem;
  color: #8b7ab8;
  margin-bottom: 1.5rem;
  font-weight: 600;
  display: flex;
  align-items: center;
  gap: 0.8rem;
}

.section-title i {
  color: #7658d6;
}

.carousel-container {
  position: relative;
  max-width: 600px;
  margin: 0 auto;
  overflow: hidden;
  border-radius: 16px;
}

.carousel-track {
  display: flex;
  transition: transform 0.5s ease;
}

.carousel-slide {
  min-width: 100%;
  height: 350px;
}

.carousel-slide img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.carousel-btn {
  position: absolute;
  top: 50%;
  transform: translateY(-50%);
  background: rgba(255, 255, 255, 0.9);
  border: none;
  border-radius: 50%;
  width: 45px;
  height: 45px;
  font-size: 1.2rem;
  cursor: pointer;
  transition: all 0.3s ease;
  z-index: 10;
  color: #7658d6;
}

.carousel-btn:hover {
  background: white;
  transform: translateY(-50%) scale(1.1);
}

.carousel-btn.prev {
  left: 15px;
}

.carousel-btn.next {
  right: 15px;
}

.carousel-indicators {
  display: flex;
  justify-content: center;
  gap: 8px;
  margin-top: 1rem;
}

.indicator {
  width: 10px;
  height: 10px;
  border-radius: 50%;
  background: #ddd;
  cursor: pointer;
  transition: all 0.3s ease;
}

.indicator.active {
  background: #7658d6;
  width: 30px;
  border-radius: 5px;
}

.info-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: 1rem;
  margin-bottom: 2rem;
}

.info-item {
  background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
  border-radius: 12px;
  padding: 1rem 1.2rem;
  border-left: 4px solid #7658d6;
  transition: all 0.3s ease;
  box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
}

.info-item:hover {
  transform: translateY(-3px);
  box-shadow: 0 6px 15px rgba(118, 88, 214, 0.12);
}

.info-item .label {
  font-size: 0.7rem;
  color: #7658d6;
  text-transform: uppercase;
  letter-spacing: 0.8px;
  margin-bottom: 0.4rem;
  font-weight: 600;
  display: block;
}

.info-item .value {
  font-size: 1rem;
  color: #2c3e50;
  font-weight: 500;
  line-height: 1.4;
}

.conservation-badge {
  display: inline-flex;
  align-items: center;
  gap: 0.5rem;
  padding: 0.6rem 1.2rem;
  background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
  color: white;
  border-radius: 20px;
  font-weight: 600;
  font-size: 0.9rem;
  margin-bottom: 1.5rem;
}

.lifecycle-timeline {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
  gap: 1.5rem;
  margin-top: 1.5rem;
}

.lifecycle-stage {
  background: linear-gradient(135deg, #f5f7fa 0%, #e8ecf1 100%);
  border-radius: 16px;
  padding: 1.8rem;
  text-align: center;
  transition: all 0.3s ease;
  position: relative;
  border-top: 4px solid #7658d6;
}

.lifecycle-stage:hover {
  transform: translateY(-8px);
  box-shadow: 0 10px 25px rgba(0, 0, 0, 0.12);
}

.lifecycle-stage .stage-icon {
  font-size: 3rem;
  margin-bottom: 0.8rem;
}

.lifecycle-stage h3 {
  font-size: 1.3rem;
  color: #8b7ab8;
  margin-bottom: 0.8rem;
  font-weight: 600;
}

.lifecycle-stage p {
  color: #555;
  font-size: 0.95rem;
  line-height: 1.6;
}

.article-content {
  font-size: 1.05rem;
  color: #444;
  line-height: 1.8;
}

.article-content h3 {
  font-size: 1.4rem;
  color: #8b7ab8;
  margin-top: 2rem;
  margin-bottom: 1rem;
  font-weight: 600;
}

.article-content p {
  margin-bottom: 1.3rem;
  text-align: justify;
}

.highlight-box {
  background: linear-gradient(135deg, #fff4e6 0%, #ffe8cc 100%);
  border-left: 4px solid #ff9800;
  padding: 1.3rem;
  border-radius: 10px;
  margin: 1.8rem 0;
}

.highlight-box strong {
  color: #e65100;
}

.curiosity-grid {
  display: grid;
  gap: 1rem;
  margin-top: 1.5rem;
}

.curiosity-item {
  background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
  border-radius: 12px;
  padding: 1rem 1.2rem;
  display: flex;
  gap: 1rem;
  align-items: flex-start;
  transition: all 0.3s ease;
  border-left: 4px solid #7658d6;
  box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
}

.curiosity-item:hover {
  transform: translateX(6px);
  box-shadow: 0 4px 12px rgba(118, 88, 214, 0.1);
}

.curiosity-item .icon {
  font-size: 1.5rem;
  color: #7658d6;
  flex-shrink: 0;
  width: auto;
  height: auto;
  display: flex;
  align-items: center;
  justify-content: center;
}

.curiosity-item p {
  color: #2c3e50;
  font-size: 0.95rem;
  line-height: 1.5;
  font-weight: 400;
  margin: 0;
}

.quiz-container {
  margin-top: 1.5rem;
}

.quiz-question {
  background: linear-gradient(-45deg, #9d79ff 0%, #7658d6 70%);
  color: white;
  padding: 1.8rem;
  border-radius: 12px;
  font-size: 1.2rem;
  font-weight: 500;
  margin-bottom: 1.5rem;
  text-align: center;
}

/* Melhorando estilos dos botões do quiz com reset completo */
.quiz-options {
  display: grid;
  gap: 1rem;
  margin-top: 1.5rem;
}

.quiz-option {
  /* Reset de estilos nativos */
  appearance: none;
  -webkit-appearance: none;
  -moz-appearance: none;

  /* Layout */
  display: block;
  width: 100%;

  /* Espaçamento */
  padding: 1.5rem 1.8rem;
  margin: 0;

  /* Visual */
  background: #f8f9fa;
  border: 2px solid #dee2e6;
  border-radius: 12px;

  /* Tipografia */
  font-family: "Poppins", sans-serif;
  font-size: 1.05rem;
  font-weight: 500;
  color: #333;
  text-align: left;
  line-height: 1.5;

  /* Interação */
  cursor: pointer;
  transition: all 0.3s ease;

  /* Sombra sutil */
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
}

.quiz-option:hover:not(:disabled) {
  background: #e9ecef;
  border-color: #7658d6;
  transform: translateX(8px);
  box-shadow: 0 4px 12px rgba(118, 88, 214, 0.15);
}

.quiz-option:active:not(:disabled) {
  transform: translateX(8px) scale(0.98);
}

.quiz-option.correct {
  background: #d4edda;
  border-color: #28a745;
  color: #155724;
  font-weight: 600;
  box-shadow: 0 4px 12px rgba(40, 167, 69, 0.2);
}

.quiz-option.incorrect {
  background: #f8d7da;
  border-color: #dc3545;
  color: #721c24;
  font-weight: 600;
  box-shadow: 0 4px 12px rgba(220, 53, 69, 0.2);
}

.quiz-option:disabled {
  cursor: not-allowed;
  opacity: 0.7;
}

.quiz-feedback {
  margin-top: 1.5rem;
  padding: 1.5rem;
  border-radius: 12px;
  font-size: 1.1rem;
  font-weight: 500;
  text-align: center;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
}

.quiz-feedback.correct {
  background: #d4edda;
  color: #155724;
  border: 2px solid #28a745;
}

.quiz-feedback.incorrect {
  background: #f8d7da;
  color: #721c24;
  border: 2px solid #dc3545;
}

.quiz-result {
  background: linear-gradient(-45deg, #9d79ff 0%, #7658d6 70%);
  color: white;
  padding: 3rem 2rem;
  border-radius: 16px;
  text-align: center;
  box-shadow: 0 8px 24px rgba(118, 88, 214, 0.3);
}

.quiz-result h3 {
  font-size: 2rem;
  margin-bottom: 1rem;
  font-weight: 600;
}

.quiz-result .score {
  font-size: 3rem;
  font-weight: 700;
  margin: 1.5rem 0;
  text-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
}

.quiz-result p {
  font-size: 1.1rem;
  margin-bottom: 1.5rem;
  opacity: 0.95;
}

.restart-btn {
  /* Reset de estilos nativos */
  appearance: none;
  -webkit-appearance: none;
  -moz-appearance: none;

  /* Visual */
  background: white;
  color: #7658d6;
  border: none;
  border-radius: 12px;

  /* Espaçamento */
  padding: 1rem 2.5rem;
  margin-top: 1.5rem;

  /* Tipografia */
  font-family: "Poppins", sans-serif;
  font-size: 1.05rem;
  font-weight: 600;

  /* Interação */
  cursor: pointer;
  transition: all 0.3s ease;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

.restart-btn:hover {
  transform: translateY(-2px) scale(1.05);
  box-shadow: 0 6px 20px rgba(0, 0, 0, 0.25);
  background: #f8f9fa;
}

.restart-btn:active {
  transform: translateY(0) scale(1.02);
}

.back-to-top {
  position: fixed;
  bottom: 2rem;
  right: 2rem;
  background-image: linear-gradient(-45deg, #9d79ff 0%, #7658d6 70%);
  color: white;
  border: none;
  border-radius: 50%;
  width: 55px;
  height: 55px;
  font-size: 1.4rem;
  cursor: pointer;
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
  transition: all 0.3s ease;
  opacity: 0;
  visibility: hidden;
  z-index: 99;
}

.back-to-top.visible {
  opacity: 1;
  visibility: visible;
}

.back-to-top:hover {
  transform: translateY(-3px);
  box-shadow: 0 6px 20px rgba(0, 0, 0, 0.3);
}

@media (max-width: 1024px) {
  .lifecycle-timeline {
    grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
  }
}

@media (max-width: 768px) {
  .container {
    padding: 1rem;
  }

  .header-content {
    flex-direction: column;
    text-align: center;
  }

  .header h1 {
    font-size: 1.3rem;
  }

  .section-card {
    padding: 1.8rem 1.3rem;
  }

  .section-title {
    font-size: 1.5rem;
  }

  .info-grid {
    grid-template-columns: 1fr;
  }

  .lifecycle-timeline {
    grid-template-columns: 1fr;
  }

  .carousel-slide {
    height: 250px;
  }

  .back-to-top {
    width: 45px;
    height: 45px;
    bottom: 1rem;
    right: 1rem;
  }
}

@keyframes fadeIn {
  from {
    opacity: 0;
    transform: translateY(20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.fade-in {
  animation: fadeIn 0.5s ease-out;
}

  </style>
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
