<link rel="stylesheet" href="/e-learning-role-final/public/style/admin-dashboard.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<link rel="stylesheet" href="/e-learning-role-final/public/style/feedback.css">


<div class="admin-header">
    <a href="#" class="logout-button" onclick="openLogoutModal(); return false;">
        <i class="fas fa-sign-out-alt"></i> Déconnexion
    </a>
    <h1>Feedback des étudiants</h1>
</div>

<div class="feedback-container">
    <div class="feedback-header">
        <a href="/e-learning-role-final/public/admin/dashboard" class="btn-back">
            <i class="fas fa-arrow-left"></i> Retour au dashboard
        </a>
    </div>

    <div class="feedback-list">
        <?php if (empty($feedbacks)): ?>
            <div class="no-feedback">
                <i class="fas fa-comment-slash" style="font-size: 48px; margin-bottom: 20px; display: block;"></i>
                <p>Aucun feedback n'a encore été soumis par les étudiants.</p>
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
<!-- Modal de confirmation déconnexion -->
<div id="logoutModal" class="modal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%;
     background-color: rgba(0, 0, 0, 0.5); z-index: 9999; justify-content: center; align-items: center;">
    <div class="modal-content" style="background-color: white; padding: 25px; border-radius: 8px; width: 90%; max-width: 400px; text-align: center;">
        <h3 style="margin-bottom: 15px;">Déconnexion</h3>
        <p style="margin-bottom: 20px;">Êtes-vous sûr de vouloir vous déconnecter ?</p>
        <div style="display: flex; justify-content: center; gap: 10px;">
            <button onclick="closeLogoutModal()" style="padding: 8px 16px; background-color: #6c757d; color: white; border: none; border-radius: 5px;">Annuler</button>
            <a href="/e-learning-role-final/public/logout" style="padding: 8px 16px; background-color: #EF4444; color: white; text-decoration: none; border-radius: 5px;">Se déconnecter</a>
        </div>
    </div>
</div>
<script>
    function openLogoutModal() {
        document.getElementById('logoutModal').style.display = 'flex';
    }

    function closeLogoutModal() {
        document.getElementById('logoutModal').style.display = 'none';
    }

    window.onclick = function(event) {
        const modal = document.getElementById('logoutModal');
        if (event.target === modal) {
            closeLogoutModal();
        }
    };
</script>