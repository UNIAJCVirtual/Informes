<?php
/**
 * Footer reutilizable para toda la aplicación
 * Incluir con: <?php include('helpers/footer.php'); ?>
 * Compatible con el sistema de temas (theme.css)
 */

$appVersion = '1.1.0';
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
    background: var(--sidebar-bg, #0f172a);
    color: var(--text-muted, #94a3b8);
    padding: var(--spacing-md, 1rem) var(--spacing-lg, 1.5rem);
    text-align: center;
    font-size: 13px;
    border-top: 1px solid #2a2a4a;
    font-size: 0.8125rem;
    border-top: 1px solid var(--border-light, #334155);
    margin-top: auto;
    transition: background-color 0.25s ease, color 0.25s ease;
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
    background: var(--surface-elevated, #334155);
    padding: 4px 12px;
    border-radius: var(--radius-full, 9999px);
    font-family: var(--font-mono, 'JetBrains Mono', monospace);
    font-size: 0.6875rem;
    color: var(--color-primary, #3b82f6);
    font-weight: 500;
}

@media (max-width: 576px) {
    .footer-content {
        flex-direction: column;
        gap: 8px;
        gap: var(--spacing-sm, 0.5rem);
    }
}
</style>
