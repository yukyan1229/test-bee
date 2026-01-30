document.addEventListener('DOMContentLoaded', function () {
    // Colors
    const colors = {
        orange: '#FF9F43',
        pink: '#FF9FF3',
        blue: '#54A0FF',
        yellow: '#FDCB6E',
        green: '#78E08F',
        red: '#FF6B6B',
        gray: '#34495e',
        dark: '#2d3436'
    };

    // Create Palette UI
    const palette = document.createElement('div');
    palette.className = 'theme-palette';
    palette.style.cssText = `
        position: fixed;
        bottom: 20px;
        left: 20px;
        display: flex;
        gap: 10px;
        background: rgba(255, 255, 255, 0.9);
        padding: 10px;
        border-radius: 30px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        z-index: 1000;
        backdrop-filter: blur(5px);
    `;

    Object.keys(colors).forEach(key => {
        const btn = document.createElement('div');
        btn.className = 'theme-color-btn';
        btn.style.cssText = `
            width: 20px;
            height: 20px;
            border-radius: 50%;
            background-color: ${colors[key]};
            cursor: pointer;
            transition: transform 0.2s;
            border: 2px solid white;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        `;

        btn.addEventListener('click', () => {
            document.documentElement.style.setProperty('--page-color', colors[key]);
            // If there are other variables or elements to update, do it here
        });

        btn.addEventListener('mouseenter', () => btn.style.transform = 'scale(1.2)');
        btn.addEventListener('mouseleave', () => btn.style.transform = 'scale(1)');

        palette.appendChild(btn);
    });

    document.body.appendChild(palette);
});
