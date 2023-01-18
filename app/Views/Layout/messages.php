<?php if (!empty($_SESSION['messages'])) : ?>
    <?php foreach ($_SESSION['messages'] as $status => $message) : ?>
        <div class="container mt-4">
            <div class="alert alert-<?= $status == 'success'  ? $status : 'danger'; ?>" role="alert">
                <?php
                echo $message;
                unset($_SESSION['messages'][$status]);
                ?>
            </div>
        </div>
    <?php endforeach; ?>
<?php endif; ?>