document.addEventListener('DOMContentLoaded', () => {
    const oldBg = document.querySelector('.bg-animation');
    if (oldBg) oldBg.remove();

    const canvas = document.createElement('canvas');
    canvas.id = 'bgCanvas';
    canvas.style.position = 'fixed';
    canvas.style.top = '0';
    canvas.style.left = '0';
    canvas.style.width = '100vw';
    canvas.style.height = '100vh';
    canvas.style.zIndex = '-1';
    canvas.style.pointerEvents = 'none';
    document.body.appendChild(canvas);

    const ctx = canvas.getContext('2d');
    let width = canvas.width = window.innerWidth;
    let height = canvas.height = window.innerHeight;

    window.addEventListener('resize', () => {
        width = canvas.width = window.innerWidth;
        height = canvas.height = window.innerHeight;
    });

    const particles = [];
    const colors = ['#ff0033', '#ff0066', '#ff3333', '#cc0000']; 
    // Adding 'D' as requested by user
    const shapes = ['❤', '✿', '❀', '♥', 'D'];

    for (let i = 0; i < 50; i++) {
        particles.push({
            x: Math.random() * width,
            y: Math.random() * height,
            vx: (Math.random() - 0.5) * 1.5,
            vy: (Math.random() - 0.5) * 2.0 - 0.5, // drifting upwards slightly faster
            size: Math.random() * 20 + 15, // Increased size: between 15 and 35
            color: colors[Math.floor(Math.random() * colors.length)],
            shape: shapes[Math.floor(Math.random() * shapes.length)],
            alpha: Math.random() * 0.5 + 0.4, // Increased opacity for brighter effect
            rotation: Math.random() * Math.PI * 2,
            rotationSpeed: (Math.random() - 0.5) * 0.03
        });
    }

    function animate() {
        ctx.clearRect(0, 0, width, height);
        
        particles.forEach(p => {
            p.x += p.vx;
            p.y += p.vy;
            p.rotation += p.rotationSpeed;

            // Wrap around edges smoothly
            if (p.x < -40) p.x = width + 40;
            if (p.x > width + 40) p.x = -40;
            if (p.y < -40) p.y = height + 40;
            if (p.y > height + 40) p.y = -40;

            ctx.save();
            ctx.translate(p.x, p.y);
            ctx.rotate(p.rotation);
            
            // Stronger glowing effect
            ctx.shadowBlur = 25;
            ctx.shadowColor = p.color;

            
            ctx.globalAlpha = p.alpha;
            ctx.fillStyle = p.color;
            ctx.font = `${p.size}px Arial`;
            ctx.textAlign = 'center';
            ctx.textBaseline = 'middle';
            
            // Draw the text symbol (heart or flower)
            ctx.fillText(p.shape, 0, 0);
            
            ctx.restore();
        });

        requestAnimationFrame(animate);
    }
    
    animate();
});
