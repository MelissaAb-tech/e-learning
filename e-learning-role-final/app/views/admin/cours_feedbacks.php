<link rel="stylesheet" href="/e-learning-role-final/public/style/admin-dashboard.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

<style>
    .feedback-container {
        max-width: 1000px;
        margin: 40px auto;
        padding: 0 20px;
    }

    .feedback-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
    }

    .feedback-list {
        background: white;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    }

    .feedback-item {
        padding: 20px;
        border-bottom: 1px solid #eee;
    }

    .feedback-item:last-child {
        border-bottom: none;
    }

    .feedback-meta {
        display: flex;
        justify-content: space-between;
        margin-bottom: 10px;
    }

    .feedback-user {
        font-weight: bold;
        color: #2c3e50;
    }

    .feedback-date {
        color: #95a5a6;
        font-size: 14px;
    }

    .feedback-rating {
        display: flex;
        align-items: center;
        margin-bottom: 15px;
    }

    .rating-stars {
        color: #f39c12;
        margin-right: 5px;
    }

    .feedback-text {
        padding: 10px;
        background-color: #f9f9f9;
        border-radius: 5px;
        color: #333;
        line-height: 1.5;
    }

    .btn-back {
        background-color: #3B82F6;
        color: white;
        padding: 10px 16px;
        border-radius: 6px;
        text-decoration: none;
        font-weight: 500;
        transition: background 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .btn-back:hover {
        background-color: #2563EB;
    }

    .no-feedback {
        text-align: center;
        padding: 40px 20px;
        color: #7f8c8d;
        font-size: 18px;
    }

    .course-info {
        margin-bottom: 20px;
        background-color: #f8f9fa;
        padding: 15px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        gap: 20px;
    }

    .course-image {
        width: 100px;
        height: 70px;
        object-fit: cover;
        border-radius: 5px;
    }

    .course-details h3 {
        margin: 0 0 5px 0;
        font-size: 20px;
        color: #2c3e50;
    }

    .course-details p {
        margin: 0;
        color: #7f8c8d;
        font-size: 14px;
    }

    .average-rating {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-top: 5px;
    }

    .average-rating .rating-stars {
        font-size: 18px;
    }
</style>

<div class="admin-header">
    <a href="/e-learning-role-final/public/logout" class="logout-button">
        <i class="fas fa-sign-out-alt"></i> Déconnexion
    </a>
    <h1>Avis des étudiants sur le cours</h1>
</div>

<div class="feedback-container">
    <div class="feedback-header">
        <a href="/e-learning-role-final/public/cours/voir/<?= $cours['id'] ?>" class="btn-back">
            <i class="fas fa-arrow-left"></i> Retour au cours
        </a>
    </div>

    <div class="course-info">
        <img src="/e-learning-role-final/public/images/<?= $cours['image'] ?>" alt="<?= htmlspecialchars($cours['nom']) ?>" class="course-image">
        <div class="course-details">
            <h3><?= htmlspecialchars($cours['nom']) ?></h3>
            <p>Professeur : <?= htmlspecialchars($cours['professeur']) ?></p>
            <div class="average-rating">
                <span class="rating-stars">
                    <?php for ($i = 1; $i <= 5; $i++): ?>
                        <?php if ($i <= round($moyenne_notes)): ?>
                            <i class="fas fa-star"></i>
                        <?php else: ?>
                            <i class="far fa-star"></i>
                        <?php endif; ?>
                    <?php endfor; ?>
                </span>
                <span><?= number_format($moyenne_notes, 1) ?>/5 (<?= count($feedbacks) ?> avis)</span>
            </div>
        </div>
    </div>

    <div class="feedback-list">
        <?php if (empty($feedbacks)): ?>
            <div class="no-feedback">
                <i class="fas fa-comment-slash" style="font-size: 48px; margin-bottom: 20px; display: block;"></i>
                <p>Aucun feedback n'a encore été soumis par les étudiants pour ce cours.</p>
            </div>
        <?php else: ?>
            <?php foreach ($feedbacks as $feedback): ?>
                <div class="feedback-item">
                    <div class="feedback-meta">
                        <div class="feedback-user">
                            <?= htmlspecialchars($feedback['prenom'] . ' ' . $feedback['nom']) ?>
                            <span style="font-weight: normal; font-size: 14px; color: #7f8c8d;">
                                (<?= htmlspecialchars($feedback['email']) ?>)
                            </span>
                        </div>
                        <div class="feedback-date">
                            <?= date('d/m/Y à H:i', strtotime($feedback['date_feedback'])) ?>
                        </div>
                    </div>

                    <div class="feedback-rating">
                        <div class="rating-stars">
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                <?php if ($i <= $feedback['note']): ?>
                                    <i class="fas fa-star"></i>
                                <?php else: ?>
                                    <i class="far fa-star"></i>
                                <?php endif; ?>
                            <?php endfor; ?>
                        </div>
                        <span style="color: #7f8c8d;">(<?= $feedback['note'] ?>/5)</span>
                    </div>

                    <div class="feedback-text">
                        <?= nl2br(htmlspecialchars($feedback['commentaire'])) ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>