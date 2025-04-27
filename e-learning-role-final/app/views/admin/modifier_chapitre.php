<link rel="stylesheet" href="/e-learning-role-final/public/style/admin-form.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<div class="admin-form-container">
    <h2>Modifier un chapitre</h2>
    <form method="POST" enctype="multipart/form-data">
        <div class="form-section">
            <label for="titre">Titre du chapitre</label>
            <input type="text" name="titre" id="titre" value="<?= htmlspecialchars($chapitre['titre']) ?>" required>
        </div>

        <div class="form-section">
            <label for="description">Description</label>
            <textarea name="description" id="description" required><?= htmlspecialchars($chapitre['description']) ?></textarea>
        </div>

        <!-- es fichiers PDF existants -->
        <?php if (!empty($chapitre['pdfs'])): ?>
            <div class="multi-files-section">
                <div class="section-title">
                    <h3><i class="fas fa-file-pdf"></i> Fichiers PDF existants</h3>
                </div>

                <div class="existing-files">
                    <?php foreach ($chapitre['pdfs'] as $pdf): ?>
                        <div class="file-card" id="pdf-<?= $pdf['id'] ?>">
                            <div class="file-card-inner">
                                <div class="file-icon">
                                    <i class="fas fa-file-pdf"></i>
                                </div>
                                <div class="file-info">
                                    <span class="file-name"><?= htmlspecialchars($pdf['pdf']) ?></span>
                                </div>
                                <div class="file-actions">
                                    <a href="/e-learning-role-final/public/pdfs/<?= $pdf['pdf'] ?>" target="_blank" class="file-view">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <button type="button" class="file-delete-btn" onclick="confirmDelete('pdf', <?= $pdf['id'] ?>)">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                    <input type="hidden" name="pdf_to_keep[<?= $pdf['id'] ?>]" id="keep-pdf-<?= $pdf['id'] ?>" value="1">
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>

        <!-- ajouter de nouveaux PDFs -->
        <div class="multi-files-section">
            <div class="section-title">
                <h3><i class="fas fa-file-pdf"></i> Ajouter des PDFs</h3>
            </div>

            <div id="pdf-container">
            </div>

            <button type="button" class="add-file-btn" onclick="addPdfField()">
                <i class="fas fa-plus-circle"></i> Ajouter un PDF
            </button>
        </div>

        <!-- les liens YouTube existants -->
        <?php
        $youtubeLinks = array_filter($chapitre['videos'], function ($v) {
            return $v['est_youtube'] == 1;
        });
        if (!empty($youtubeLinks)):
        ?>
            <div class="multi-files-section">
                <div class="section-title">
                    <h3><i class="fab fa-youtube"></i> Liens YouTube existants</h3>
                </div>

                <div class="existing-files">
                    <?php foreach ($youtubeLinks as $video): ?>
                        <div class="file-card" id="video-<?= $video['id'] ?>">
                            <div class="file-card-inner">
                                <div class="file-icon youtube-icon">
                                    <i class="fab fa-youtube"></i>
                                </div>
                                <div class="file-info">
                                    <span class="file-name"><?= htmlspecialchars($video['video']) ?></span>
                                </div>
                                <div class="file-actions">
                                    <a href="<?= $video['video'] ?>" target="_blank" class="file-view youtube-view">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <button type="button" class="file-delete-btn" onclick="confirmDelete('video', <?= $video['id'] ?>)">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                    <!-- Champ caché qui sera mis à jour par JS -->
                                    <input type="hidden" name="video_to_keep[<?= $video['id'] ?>]" id="keep-video-<?= $video['id'] ?>" value="1">
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>

        <!-- ajouter de nouveaux liens YouTube -->
        <div class="multi-files-section">
            <div class="section-title">
                <h3><i class="fab fa-youtube"></i> Ajouter des liens YouTube</h3>
            </div>

            <div id="youtube-container">
            </div>

            <button type="button" class="add-file-btn yt-btn" onclick="addYoutubeField()">
                <i class="fas fa-plus-circle"></i> Ajouter un lien YouTube
            </button>
        </div>

        <!-- les fichiers vidéo MP4 existants -->
        <?php
        $mp4Files = array_filter($chapitre['videos'], function ($v) {
            return $v['est_youtube'] == 0;
        });
        if (!empty($mp4Files)):
        ?>
            <div class="multi-files-section">
                <div class="section-title">
                    <h3><i class="fas fa-video"></i> Fichiers Vidéo MP4 existants</h3>
                </div>

                <div class="existing-files">
                    <?php foreach ($mp4Files as $video): ?>
                        <div class="file-card" id="video-<?= $video['id'] ?>">
                            <div class="file-card-inner">
                                <div class="file-icon video-icon">
                                    <i class="fas fa-video"></i>
                                </div>
                                <div class="file-info">
                                    <span class="file-name"><?= htmlspecialchars($video['video']) ?></span>
                                </div>
                                <div class="file-actions">
                                    <button type="button" class="file-delete-btn" onclick="confirmDelete('video', <?= $video['id'] ?>)">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                    <input type="hidden" name="video_to_keep[<?= $video['id'] ?>]" id="keep-video-<?= $video['id'] ?>" value="1">
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>

        <!-- ajouter de nouveaux fichiers vidéo MP4 -->
        <div class="multi-files-section">
            <div class="section-title">
                <h3><i class="fas fa-video"></i> Ajouter des fichiers vidéo MP4</h3>
            </div>

            <div id="video-container">
            </div>

            <button type="button" class="add-file-btn video-btn" onclick="addVideoField()">
                <i class="fas fa-plus-circle"></i> Ajouter une vidéo
            </button>
        </div>

        <!-- confirmation pour la suppression -->
        <div id="delete-modal" class="delete-modal">
            <div class="delete-modal-content">
                <h3>Confirmer la suppression</h3>
                <p>Êtes-vous sûr de vouloir supprimer ce fichier ?</p>
                <div class="delete-modal-buttons">
                    <button type="button" onclick="closeDeleteModal()">Annuler</button>
                    <button type="button" id="confirm-delete-btn" class="delete-btn">Supprimer</button>
                </div>
            </div>
        </div>

        <div class="action-buttons">
            <button type="submit" class="submit-btn">
                <i class="fas fa-check"></i> Enregistrer les modifications
            </button>
            <a href="/e-learning-role-final/public/cours/voir/<?= $cours_id ?>" class="cancel-btn">
                <i class="fas fa-times"></i> Annuler
            </a>
        </div>
    </form>
</div>

<link rel="stylesheet" href="/e-learning-role-final/public/style/chapitre-modifier.css">
<script src="/e-learning-role-final/public/JS/modif-chapitre.js"></script>