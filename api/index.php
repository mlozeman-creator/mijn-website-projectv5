<?php
// 1. Data inladen uit de JSON database
$jsonData = file_get_contents(__DIR__ . '/../data/content.json');
$data = json_decode($jsonData, true);

// 2. Beheer van de status (Pagina, Artikel en Categorie-filter)
$page = $_GET['page'] ?? 'home';
$articleSlug = $_GET['article'] ?? null;
$filter = $_GET['filter'] ?? 'alles';

// 3. Dynamische Filter Logica voor de '11'
$filteredArticles = $data['articles'];
if ($filter !== 'alles') {
    $filteredArticles = array_filter($data['articles'], function($a) use ($filter) {
        return strtolower($a['category']) === strtolower($filter);
    });
}

// 4. Automatische generatie van categorie-knoppen op basis van data
$categories = array_unique(array_column($data['articles'], 'category'));

// 5. Helper functie voor de leestijd-indicator
function berekenLeestijd($tekst) {
    $woorden = str_word_count($tekst);
    return ceil($woorden / 200); // Gemiddelde leessnelheid
}
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WEB.EDU | Modern Webonderwijs & Techniek</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;800&display=swap" rel="stylesheet">
    <style>
        :root { --accent: #6366f1; --dark: #0f172a; }
        body { background-color: #f8fafc; font-family: 'Plus Jakarta Sans', sans-serif; color: #334155; }
        .navbar { background: rgba(255,255,255,0.85); backdrop-filter: blur(15px); border-bottom: 1px solid #e2e8f0; }
        .hero { 
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%); 
            color: white; padding: 160px 0 120px; clip-path: polygon(0 0, 100% 0, 100% 90%, 0% 100%);
        }
        .filter-btn { border-radius: 50px; padding: 8px 24px; transition: 0.3s; border: 1px solid #e2e8f0; background: white; text-decoration: none; color: #64748b; font-weight: 700; }
        .filter-btn.active { background: var(--accent); color: white; border-color: var(--accent); box-shadow: 0 10px 15px rgba(99, 102, 241, 0.2); }
        .card-blog { border: none; border-radius: 28px; transition: all 0.4s ease; background: white; overflow: hidden; border: 1px solid #f1f5f9; }
        .card-blog:hover { transform: translateY(-12px); box-shadow: 0 30px 60px rgba(0,0,0,0.08); }
        .card-img-custom { height: 200px; object-fit: cover; }
        .btn-elite { background: var(--dark); color: white; border-radius: 50px; padding: 12px 25px; font-weight: 700; transition: 0.3s; border: none; width: 100%; }
        .btn-elite:hover { background: var(--accent); transform: scale(1.05); color: white; }
        .code-block { background: #1e293b; color: #38bdf8; padding: 25px; border-radius: 16px; font-family: 'Courier New', monospace; font-size: 0.9rem; }
        .tech-card { border-left: 5px solid var(--accent); background: white; }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg fixed-top shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-extrabold text-dark fs-3" href="?page=home">WEB<span class="text-primary">.EDU</span></a>
        <div class="navbar-nav ms-auto">
            <a class="nav-link fw-bold px-3" href="?page=home">Artikelen</a>
            <a class="nav-link fw-bold px-3" href="?page=tech">De Techniek</a>
            <a class="nav-link fw-bold px-3" href="?page=author">De Auteur</a>
        </div>
    </div>
</nav>

<?php if ($page === 'home' && !$articleSlug): ?>
<header class="hero text-center text-white">
    <div class="container">
        <span class="badge bg-white text-primary px-4 py-2 mb-4 shadow-lg rounded-pill fw-bold">Vibe Coding Elite v2.0</span>
        <h1 class="display-2 fw-extrabold mb-4">Leren. Bouwen. Publuceren.</h1>
        <p class="lead opacity-90 mx-auto fs-4" style="max-width: 700px;">Een technisch meesterwerk over modern webdesign en cloud-architectuur.</p>
    </div>
</header>

<section class="container mb-5 text-center">
    <div class="d-flex justify-content-center gap-2 flex-wrap" style="margin-top: -30px;">
        <a href="?page=home&filter=alles" class="filter-btn <?php echo $filter === 'alles' ? 'active' : ''; ?>">Alles tonen</a>
        <?php foreach($categories as $cat): ?>
            <a href="?page=home&filter=<?php echo $cat; ?>" class="filter-btn <?php echo strtolower($filter) === strtolower($cat) ? 'active' : ''; ?>">
                <?php echo $cat; ?>
            </a>
        <?php endforeach; ?>
    </div>
</section>
<?php endif; ?>

<div class="container" style="margin-top: <?php echo ($page === 'home' && !$articleSlug) ? '20px' : '140px'; ?>;">
    
    <?php if ($page === 'home' && !$articleSlug): ?>
        <div class="row g-4 mb-5">
            <?php foreach ($filteredArticles as $post): ?>
            <div class="col-md-6 col-lg-4">
                <div class="card card-blog h-100 shadow-sm text-dark">
                    <img src="<?php echo $post['image']; ?>" class="card-img-custom" alt="Visuele weergave">
                    <div class="card-body p-4 d-flex flex-column">
                        <div class="d-flex justify-content-between mb-3">
                            <span class="badge bg-light text-primary border rounded-pill px-3"><?php echo $post['category']; ?></span>
                            <small class="text-muted fw-bold">⏱ <?php echo berekenLeestijd($post['content']); ?> min leestijd</small>
                        </div>
                        <h3 class="fw-bold mb-3"><?php echo $post['title']; ?></h3>
                        <p class="text-muted mb-4"><?php echo substr($post['content'], 0, 110); ?>...</p>
                        <a href="?page=home&article=<?php echo $post['slug']; ?>" class="mt-auto btn btn-elite">Lees Artikel</a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

    <?php elseif ($page === 'tech'): ?>
        <div class="row justify-content-center mb-5">
            <div class="col-lg-10">
                <h1 class="fw-extrabold mb-5 display-4 text-dark text-center">De Technische Architectuur</h1>
                <div class="row g-4 text-dark">
                    <div class="col-md-8">
                        <div class="p-5 rounded-4 shadow-sm tech-card h-100">
                            <h4 class="fw-bold mb-4 text-primary">Serverless Deployment</h4>
                            <p class="fs-5">Dit project draait op <strong>Vercel Edge</strong>. Via een CI/CD pijplijn worden wijzigingen in GitHub direct naar de cloud gepushed en uitgerold.</p>
                            
                            <div class="code-block mt-4 text-info">
                                # De volledige Vibe Coding workflow:<br>
                                git add . && git commit -m "Elite Update" && git push origin main
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="p-5 rounded-4 shadow-sm tech-card h-100">
                            <h4 class="fw-bold mb-4 text-primary">Systeem Status</h4>
                            <ul class="list-unstyled fw-bold text-muted fs-5">
                                <li class="mb-3">🔥 Status: Operationeel</li>
                                <li class="mb-3">📦 Database: JSON 2.0</li>
                                <li class="mb-3">🚀 Runtime: PHP 8.x</li>
                                <li class="mb-3">✨ UI: Bootstrap + Glass</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    <?php elseif ($articleSlug): ?>
        <?php 
        $post = null;
        foreach($data['articles'] as $a) { if($a['slug'] === $articleSlug) { $post = $a; break; } }
        if ($post): ?>
            <div class="row justify-content-center mb-5 text-dark">
                <div class="col-lg-9">
                    <a href="?page=home" class="btn btn-link text-decoration-none fw-bold text-muted mb-4 p-0">← TERUG NAAR OVERZICHT</a>
                    <div class="bg-white p-5 rounded-5 shadow-lg border">
                        <img src="<?php echo $post['image']; ?>" class="w-100 rounded-4 mb-5 shadow-sm" style="height: 400px; object-fit: cover;">
                        <h1 class="display-4 fw-extrabold mb-4"><?php echo $post['title']; ?></h1>
                        <p class="text-primary fw-bold mb-4">Geschatte leestijd: <?php echo berekenLeestijd($post['content']); ?> minuten</p>
                        <div class="lh-lg fs-5 text-secondary" style="white-space: pre-line;">
                            <?php echo $post['content']; ?>
                        </div>
                        <div class="mt-5 pt-4 border-top">
                            <a href="?page=home" class="btn btn-dark rounded-pill px-5 py-3 fw-bold shadow">Vorige Pagina</a>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>

    <?php elseif ($page === 'author'): ?>
        <div class="row justify-content-center text-center text-dark">
            <div class="col-md-6 bg-white p-5 rounded-5 shadow-lg border">
                <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($data['author']['name']); ?>&size=160&background=6366f1&color=fff" class="rounded-circle mb-4 border border-5 border-white shadow">
                <h2 class="fw-extrabold text-dark display-5"><?php echo $data['author']['name']; ?></h2>
                <p class="text-primary fw-bold fs-5">Senior Developer & Vibe Architect</p>
                <p class="text-muted fs-5 mb-5 px-3"><?php echo $data['author']['bio']; ?></p>
                <div class="d-grid gap-2 d-md-block">
                    <a href="mailto:<?php echo $data['author']['email']; ?>" class="btn btn-primary px-5 py-3 rounded-pill fw-bold shadow-sm me-md-2">Contact</a>
                    <a href="<?php echo $data['author']['github_url']; ?>" class="btn btn-outline-dark px-5 py-3 rounded-pill fw-bold shadow-sm">GitHub</a>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<footer class="py-5 text-center mt-5 bg-dark text-white">
    <div class="container text-center">
        <h5 class="fw-extrabold mb-1">WEB.EDU ELITE</h5>
        <p class="opacity-50 small mb-0">Meesterproef MBO-4 Software Development © 2026</p>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
