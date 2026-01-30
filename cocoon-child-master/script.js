document.addEventListener('DOMContentLoaded', () => {
    document.body.classList.add('loaded');

    // Setup Transition Overlay
    const overlay = document.createElement('div');
    overlay.id = 'transition-overlay';
    document.body.appendChild(overlay);

    // Intercept Links & Open External in New Tab
    let externalLinkCount = 0;
    document.querySelectorAll('a').forEach(link => {
        // External Link Handling
        if (link.hostname && link.hostname !== window.location.hostname) {
            link.setAttribute('target', '_blank');
            link.setAttribute('rel', 'noopener noreferrer');

            externalLinkCount++;
            // Analytics: Set distinguishable title if missing
            if (!link.getAttribute('title')) {
                link.setAttribute('title', `${link.href}_${externalLinkCount}`);
            }
        }

        link.addEventListener('click', (e) => {
            const href = link.getAttribute('href');

            // Skip if external or hash link or same page
            if (!href || href.startsWith('http') || href.startsWith('#') || href === window.location.pathname) {
                return;
            }

            e.preventDefault();

            // Special case for LIVE link (index.html) - Fade Transition
            if (href === 'index.html' || href.endsWith('/index.html')) {
                let fadeOverlay = document.querySelector('.fade-overlay');
                if (!fadeOverlay) {
                    fadeOverlay = document.createElement('div');
                    fadeOverlay.classList.add('fade-overlay');
                    document.body.appendChild(fadeOverlay);
                }

                // Trigger reflow
                fadeOverlay.offsetHeight;

                fadeOverlay.classList.add('active');

                setTimeout(() => {
                    window.location.href = href;
                }, 500); // Match CSS transition time
                return;
            }

            // Standard Circle Transition
            // Get click coordinates
            let x = e.clientX;
            let y = e.clientY;
            let color = 'var(--color-orange)';
            let startScale = 0;

            // Check if a circle button, orbit item, or central circle was clicked
            const targetBtn = link.closest('.circle-btn') || link.closest('.orbit-item') || link.closest('.central-circle');
            if (targetBtn) {
                const rect = targetBtn.getBoundingClientRect();
                x = rect.left + rect.width / 2;
                y = rect.top + rect.height / 2;
                // Use getComputedStyle to ensure we get the actual color even if set via CSS class
                color = window.getComputedStyle(targetBtn).backgroundColor;
                startScale = 1; // Start from button size
            }

            // Create transition circle
            const circle = document.createElement('div');
            circle.classList.add('transition-circle');

            // Set initial position and size
            circle.style.width = '60px';
            circle.style.height = '60px';
            circle.style.left = `${x}px`;
            circle.style.top = `${y}px`;
            circle.style.backgroundColor = color;
            circle.style.transform = `translate(-50%, -50%) scale(${startScale})`;

            overlay.appendChild(circle);

            // Trigger animation
            requestAnimationFrame(() => {
                // Calculate required scale to cover screen
                // Max distance from center to corner
                const maxDist = Math.max(x, window.innerWidth - x) ** 2 + Math.max(y, window.innerHeight - y) ** 2;
                const radius = Math.sqrt(maxDist);
                const targetScale = (radius * 2) / 60 + 5; // +5 for safety margin

                circle.style.transform = `translate(-50%, -50%) scale(${targetScale})`;
            });

            // Navigate after animation
            setTimeout(() => {
                window.location.href = href;
            }, 600);
            // Match CSS transition time slightly less to ensure coverage
        });
        // Modal Toggle Logic
        const trigger = document.querySelector('.orbit-trigger');
        const modal = document.querySelector('.orbit-modal');

        if (trigger && modal) {
            trigger.addEventListener('click', () => {
                modal.classList.add('active');
            });

            modal.addEventListener('click', (e) => {
                // Close if clicking outside the orbit container (or explicitly on modal bg)
                if (e.target === modal || e.target.classList.contains('orbit-modal-close')) {
                    modal.classList.remove('active');
                }
            });
        }
    });
});
