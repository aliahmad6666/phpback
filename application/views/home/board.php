<div class="col-md-9">
    <!-- Breadcrumb -->
    <small>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= base_url('home') ?>">Boards</a></li>
            <li class="breadcrumb-item active"><?= htmlspecialchars($board->name); ?></li>
        </ol>
    </small>

    <!-- Board Title and Description -->
    <h3 class="fw-bold text-secondary"><?= htmlspecialchars($board->name); ?></h3>
    <p class="text-muted"><?= htmlspecialchars($board->description); ?></p>

    <!-- Welcome Alert -->
    <div class="alert alert-light border shadow-sm mb-4">
        <h6 class="fw-bold mb-1 text-primary"><?= htmlspecialchars($welcomeTitle); ?></h6>
        <p class="mb-0 text-dark"><?= htmlspecialchars($welcomeDescription); ?></p>
    </div>

    <!-- Idea Cards -->
    <div class="row">
        <?php
        // Define idea categories and their respective styles
        $ideaCategories = [
            'completed' => [
                'icon' => 'fas fa-check-circle',
                'header_class' => 'bg-info-subtle',
                'badge_class' => 'bg-info-subtle text-dark',
                'lang_key' => 'last_completed_ideas',
                'status_key' => 'idea_completed',
            ],
            'planned' => [
                'icon' => 'fas fa-calendar-alt',
                'header_class' => 'bg-warning-subtle',
                'badge_class' => 'bg-warning-subtle text-dark',
                'lang_key' => 'last_planned_ideas',
                'status_key' => 'idea_planned',
            ],
            'started' => [
                'icon' => 'fas fa-play-circle',
                'header_class' => 'bg-success-subtle',
                'badge_class' => 'bg-success-subtle text-dark',
                'lang_key' => 'last_started_ideas',
                'status_key' => 'idea_started',
            ],
            'considered' => [
                'icon' => 'fas fa-lightbulb',
                'header_class' => 'bg-secondary-subtle',
                'badge_class' => 'bg-secondary-subtle text-dark',
                'lang_key' => 'last_considered_ideas',
                'status_key' => 'idea_considered',
            ],
        ];

        // Loop through each category and render the card
        foreach ($ideaCategories as $category => $props):
                ?>
                <div class="col-md-6 mb-4">
                    <div class="card border-0 shadow-sm">
                        <!-- Card Header -->
                        <div class="card-header text-dark fw-bold <?= $props['header_class']; ?>">
                            <i class="<?= $props['icon']; ?>"></i> <?= $lang[$props['lang_key']]; ?>
                        </div>

                        <!-- Card Body -->
                        <div class="card-body p-3 bg-white">
                            <ul class="list-group">
                                <?php foreach ($ideas[$category] as $idea): ?>
                                    <li class="list-group-item border-0">
                                        <span class="badge <?= $props['badge_class']; ?> me-2"><?= $lang[$props['status_key']]; ?></span>
                                        <a href="<?= $idea->url; ?>" class="text-primary text-decoration-none"><?= htmlspecialchars($idea->title); ?></a>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                </div>
            <?php
        endforeach;
        ?>
    </div>
</div>