<div class="col-md-9">
    <!-- Breadcrumb -->
    <small>
        <ol class="breadcrumb">
            <li class="breadcrumb-item active">Boards</li>
        </ol>
    </small>

    <!-- Page Heading -->
    <h3 class="fw-bold text-primary mb-3">Select a Board</h3>

    <!-- Board Cards -->
    <div class="row">
        <?php foreach ($boards as $board): ?>
            <div class="col-md-4 mb-4">
                <a href="<?= base_url('home/board/' . $board->id) ?>" class="text-decoration-none" aria-label="Go to <?= htmlspecialchars($board->name); ?>">
                    <div class="card board-card">
                        <div class="card-body text-center">
                            <h4 class="card-title text-primary fw-bold mb-3">
                                <?= htmlspecialchars($board->name); ?>
                            </h4>
                            <p class="card-text text-muted">
                                <?= !empty($board->description) ? htmlspecialchars(substr($board->description, 0, 50)) . '...' : 'No description available'; ?>
                            </p>
                        </div>
                    </div>
                </a>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<!-- Optional: Add custom CSS for hover effects -->
<style>
    .board-card {
        border-radius: 8px;
        overflow: hidden;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        background: #f8f9fa;
        border: 1px solid #e9ecef;
    }

    .board-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }

    .card-body {
        padding: 20px;
    }
</style>