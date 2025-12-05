(function () {
    // 1. Define Theme Colors
    const themeColors = [
        { name: 'トークライブ', var: '--color-orange', default: '#FF8C00' },
        { name: '桜の会', var: '--color-pink', default: '#ffb7c5' },
        { name: 'の巻', var: '--color-blue', default: '#a0d8ef' },
        { name: 'コイシキウチ', var: '--color-yellow', default: '#fff3b8' },
        { name: '配信', var: '--color-green', default: '#c5e1a5' },
        { name: 'グッズ', var: '--color-red', default: '#ff8a80' },
        { name: 'Blog', var: '--color-gray', default: '#e0e0e0' }
    ];

    // 2. Apply saved colors IMMEDIATELY
    function applySavedColors() {
        const savedColors = JSON.parse(localStorage.getItem('themeColors') || '{}');
        themeColors.forEach(color => {
            if (savedColors[color.var]) {
                document.documentElement.style.setProperty(color.var, savedColors[color.var]);
            }
        });
    }
    applySavedColors();

    // 3. Load Pickr Resources
    const pickrCss = document.createElement('link');
    pickrCss.rel = 'stylesheet';
    pickrCss.href = 'https://cdn.jsdelivr.net/npm/@simonwep/pickr/dist/themes/classic.min.css';
    document.head.appendChild(pickrCss);

    const pickrScript = document.createElement('script');
    pickrScript.src = 'https://cdn.jsdelivr.net/npm/@simonwep/pickr/dist/pickr.min.js';
    document.head.appendChild(pickrScript);

    // 4. Initialize UI after script load
    pickrScript.onload = () => {
        initThemePicker();
    };

    function initThemePicker() {
        // Create Toggle Button
        const btn = document.createElement('button');
        btn.innerHTML = '🎨';
        btn.style.cssText = `
            position: fixed;
            bottom: 20px;
            left: 20px;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: #333;
            color: white;
            border: none;
            font-size: 24px;
            cursor: pointer;
            z-index: 9999;
            box-shadow: 0 4px 10px rgba(0,0,0,0.3);
            transition: transform 0.2s;
        `;
        btn.onmouseover = () => btn.style.transform = 'scale(1.1)';
        btn.onmouseout = () => btn.style.transform = 'scale(1)';
        document.body.appendChild(btn);

        // Create Panel Container
        const panel = document.createElement('div');
        panel.style.cssText = `
            position: fixed;
            bottom: 80px;
            left: 20px;
            background: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.2);
            z-index: 9999;
            display: none;
            max-height: 80vh;
            overflow-y: auto;
            width: 250px;
        `;
        document.body.appendChild(panel);

        // Toggle Panel
        btn.onclick = (e) => {
            e.stopPropagation(); // Prevent immediate closing
            panel.style.display = panel.style.display === 'none' ? 'block' : 'none';
        };

        // Close on outside click
        document.addEventListener('click', (e) => {
            // Check if click is outside panel, outside button, AND outside any open Pickr popup
            if (panel.style.display === 'block' &&
                !panel.contains(e.target) &&
                e.target !== btn &&
                !e.target.closest('.pcr-app')) {
                panel.style.display = 'none';
            }
        });

        // Prevent clicks inside panel from closing it
        panel.onclick = (e) => {
            e.stopPropagation();
        };

        // Load saved colors for Pickr initialization
        const savedColors = JSON.parse(localStorage.getItem('themeColors') || '{}');

        // Create Pickers
        themeColors.forEach(color => {
            const wrapper = document.createElement('div');
            wrapper.style.marginBottom = '15px';

            const label = document.createElement('div');
            label.textContent = color.name;
            label.style.marginBottom = '5px';
            label.style.fontWeight = 'bold';
            label.style.fontSize = '14px';
            wrapper.appendChild(label);

            const pickerContainer = document.createElement('div');
            wrapper.appendChild(pickerContainer);
            panel.appendChild(wrapper);

            // Determine initial color for the picker
            const initialColor = savedColors[color.var] || getComputedStyle(document.documentElement).getPropertyValue(color.var).trim() || color.default;

            const pickr = Pickr.create({
                el: pickerContainer,
                theme: 'classic',
                default: initialColor,
                i18n: {
                    'btn:save': '色を適用する',
                    'btn:cancel': 'キャンセル',
                    'btn:clear': 'クリア'
                },
                components: {
                    preview: true,
                    opacity: true,
                    hue: true,
                    interaction: {
                        hex: true,
                        rgba: true,
                        input: true,
                        save: true
                    }
                }
            });

            pickr.on('save', (selectedColor, instance) => {
                const newColor = selectedColor.toHEXA().toString();
                document.documentElement.style.setProperty(color.var, newColor);

                // Save to localStorage
                const currentSaved = JSON.parse(localStorage.getItem('themeColors') || '{}');
                currentSaved[color.var] = newColor;
                localStorage.setItem('themeColors', JSON.stringify(currentSaved));

                // Close ONLY the picker, keep the panel open
                pickr.hide();
            });

            pickr.on('change', (colorObj) => {
                const hex = colorObj.toHEXA().toString();
                document.documentElement.style.setProperty(color.var, hex);
            });
        });

        // Reset Button
        const resetBtn = document.createElement('button');
        resetBtn.textContent = '初期カラーに戻す';
        resetBtn.style.cssText = `
            width: 100%;
            padding: 8px;
            margin-top: 10px;
            background: #f0f0f0;
            border: 1px solid #ccc;
            border-radius: 4px;
            cursor: pointer;
        `;
        resetBtn.onclick = () => {
            localStorage.removeItem('themeColors');
            location.reload();
        };
        panel.appendChild(resetBtn);
    }
})();
