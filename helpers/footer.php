<?php
/**
 * Footer reutilizable para toda la aplicación
 * Incluir con: <?php include('helpers/footer.php'); ?>
 */

$appVersion = '1.0.0';
$appYear = date('Y');
?>
<footer class="app-footer">
    <div class="footer-content">
        <span class="footer-text">
            © <?php echo $appYear; ?> UNIAJCVirtual - Informes Moodle
        </span>
        <span class="footer-version">
            v<?php echo $appVersion; ?>
        </span>
    </div>
</footer>

<style>
.app-footer {
    background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
    color: #a0a0a0;
    padding: 12px 20px;
    text-align: center;
    font-size: 13px;
    border-top: 1px solid #2a2a4a;
    margin-top: auto;
}

.footer-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
    max-width: 1200px;
    margin: 0 auto;
}

.footer-text {
    font-weight: 400;
}

.footer-version {
    background: #2a2a4a;
    padding: 4px 10px;
    border-radius: 12px;
    font-family: 'Consolas', 'Monaco', monospace;
    font-size: 11px;
    color: #6c9bcf;
}

@media (max-width: 576px) {
    .footer-content {
        flex-direction: column;
        gap: 8px;
    }
}
</style>
