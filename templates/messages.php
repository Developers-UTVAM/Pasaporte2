<?php if(isset($errors)): ?>
<?php foreach ($errors as $error): ?>
    <div class="alert alert-danger alert-dismissible fade show shadow-sm d-flex align-items-center" role="alert" style="border-radius: 12px; border-left: 5px solid #dc3545;">
        <i class="fa-solid fa-triangle-exclamation flex-shrink-0 me-2 fs-5"></i>
        <div>
            <?php echo htmlspecialchars($error); ?>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endforeach; ?>
<?php endif; ?>
