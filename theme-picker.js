document.addEventListener('DOMContentLoaded', function () {
    // Colors
    const colors = {
        orange: '#eda049',
        pink: '#fdeff1',
        blue: '#a080c5',
        yellow: '#e5cd5e',
        green: '#436842',
        red: '#B35227',
        gray: '#8D8281'
    };

    // Create Palette UI
    const palette = document.createElement('div');
    palette.id = 'theme-picker';
    palette.style.cssText = `
        position: fixed;
        bottom: 20px;
        left: 20px;
        display: flex;
        gap: 8px;
        z-index: 9999;
    `;

    Object.keys(colors).forEach(key => {
        const btn = document.createElement('div');
        btn.style.cssText = `
            width: 24px;
            height: 24px;
            border-radius: 50%;
            background-color: ${colors[key]};
            cursor: pointer;
            border: 2px solid #fff;
            box-shadow: 0 2px 4px rgba(0,0,0,0.2);
            transition: transform 0.2s;
        `;

        btn.addEventListener('click', () => {
            document.documentElement.style.setProperty('--page-color', colors[key]);
        });

        btn.addEventListener('mouseenter', () => btn.style.transform = 'scale(1.2)');
        btn.addEventListener('mouseleave', () => btn.style.transform = 'scale(1)');

        palette.appendChild(btn);
    });

    document.body.appendChild(palette);
});
