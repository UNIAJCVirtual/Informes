// SweetAlert2 - Modal de bienvenida
document.addEventListener('DOMContentLoaded', function() {
    // Verificar que SweetAlert2 esté disponible
    if (typeof Swal === 'undefined') {
        console.error('SweetAlert2 no está cargado');
        return;
    }
    
    // Agregar estilos personalizados
    const style = document.createElement('style');
    style.textContent = `
        .swal2-popup.swal-welcome {
            border-radius: 20px !important;
            padding: 2rem !important;
            z-index: 10000 !important;
        }
        .swal2-container {
            z-index: 9999 !important;
        }
        @keyframes swal-float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-8px); }
        }
        .swal-welcome .swal2-image {
            animation: swal-float 3s ease-in-out infinite;
        }
    `;
    document.head.appendChild(style);
    
    // Detectar tema
    const isDark = document.documentElement.getAttribute('data-theme') === 'dark' || 
                   (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches && 
                    !document.documentElement.getAttribute('data-theme'));
    
    Swal.fire({
        title: '¡Bienvenido!',
        html: `
            <p style="color: ${isDark ? '#94a3b8' : '#64748b'}; font-size: 0.95rem; line-height: 1.6; margin-top: 0.5rem;">
                Gestor de informes de cualificación para profesores del departamento 
                <strong style="color: #0066ff;">UNIAJC Virtual</strong>
            </p>
            <div style="display: flex; gap: 12px; justify-content: center; margin-top: 1.5rem; flex-wrap: wrap;">
                <span style="display: inline-flex; align-items: center; gap: 6px; padding: 6px 12px; background: rgba(0, 102, 255, 0.1); border-radius: 20px; font-size: 0.75rem; color: #0066ff;">
                    📋 Reportes
                </span>
                <span style="display: inline-flex; align-items: center; gap: 6px; padding: 6px 12px; background: rgba(16, 185, 129, 0.1); border-radius: 20px; font-size: 0.75rem; color: #10b981;">
                    📊 Estadísticas
                </span>
                <span style="display: inline-flex; align-items: center; gap: 6px; padding: 6px 12px; background: rgba(139, 92, 246, 0.1); border-radius: 20px; font-size: 0.75rem; color: #8b5cf6;">
                    💻 Consultas SQL
                </span>
            </div>
        `,
        imageUrl: 'resources/img/uvi-blue-transparent.png',
        imageWidth: 260,
        imageHeight: 160,
        imageAlt: 'UNIAJC Virtual',
        background: isDark ? '#1e293b' : '#ffffff',
        color: isDark ? '#f1f5f9' : '#1e293b',
        backdrop: 'rgba(0,0,0,0.5)',
        timer: 6000,
        timerProgressBar: true,
        showConfirmButton: false,
        showCloseButton: true,
        allowOutsideClick: true,
        allowEscapeKey: true,
        customClass: {
            popup: 'swal-welcome'
        }
    });
});
