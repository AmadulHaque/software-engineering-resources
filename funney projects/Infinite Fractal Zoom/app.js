class FractalExplorer {
    constructor(canvasId) {
        this.canvas = document.getElementById(canvasId);
        this.ctx = this.canvas.getContext('2d');
        this.width = this.canvas.width = window.innerWidth;
        this.height = this.canvas.height = window.innerHeight;
        
        // Mandelbrot parameters
        this.centerX = -0.5;
        this.centerY = 0;
        this.scale = 2.5;
        this.maxIterations = 100;
        
        // Interaction state
        this.isDragging = false;
        this.lastX = 0;
        this.lastY = 0;
        
        // Color scheme
        this.colorScheme = 'rainbow';
        
        this.init();
    }
    
    init() {
        this.render();
        
        // Event listeners
        this.canvas.addEventListener('mousedown', (e) => {
            this.isDragging = true;
            this.lastX = e.clientX;
            this.lastY = e.clientY;
        });
        
        window.addEventListener('mouseup', () => {
            this.isDragging = false;
        });
        
        window.addEventListener('mousemove', (e) => {
            if (this.isDragging) {
                const dx = e.clientX - this.lastX;
                const dy = e.clientY - this.lastY;
                
                this.centerX -= dx * this.scale / this.width;
                this.centerY -= dy * this.scale / this.height;
                
                this.lastX = e.clientX;
                this.lastY = e.clientY;
                
                this.render();
            }
        });
        
        window.addEventListener('wheel', (e) => {
            e.preventDefault();
            const zoomFactor = e.deltaY > 0 ? 1.1 : 0.9;
            this.scale *= zoomFactor;
            this.maxIterations = Math.min(500, Math.max(50, Math.floor(100 * Math.log(this.scale + 1))));
            this.render();
            document.getElementById('zoom').textContent = `${Math.round(this.scale/2.5*100)/100}x`;
        });
        
        document.getElementById('reset').addEventListener('click', () => {
            this.centerX = -0.5;
            this.centerY = 0;
            this.scale = 2.5;
            this.maxIterations = 100;
            this.render();
        });
        
        document.getElementById('colorScheme').addEventListener('change', (e) => {
            this.colorScheme = e.target.value;
            this.render();
        });
        
        this.canvas.addEventListener('mousemove', (e) => {
            const x = this.centerX + (e.clientX - this.width/2) * this.scale / this.width;
            const y = this.centerY + (e.clientY - this.height/2) * this.scale / this.height;
            document.getElementById('coords').textContent = `${x.toFixed(10)}, ${y.toFixed(10)}`;
        });
    }
    
    render() {
        const imageData = this.ctx.createImageData(this.width, this.height);
        const data = imageData.data;
        
        for (let x = 0; x < this.width; x++) {
            for (let y = 0; y < this.height; y++) {
                const cx = this.centerX + (x - this.width/2) * this.scale / this.width;
                const cy = this.centerY + (y - this.height/2) * this.scale / this.height;
                
                let zx = 0;
                let zy = 0;
                let iter = 0;
                
                while (zx * zx + zy * zy < 4 && iter < this.maxIterations) {
                    const tmp = zx * zx - zy * zy + cx;
                    zy = 2 * zx * zy + cy;
                    zx = tmp;
                    iter++;
                }
                
                const idx = (y * this.width + x) * 4;
                this.colorPixel(data, idx, iter);
            }
        }
        
        this.ctx.putImageData(imageData, 0, 0);
    }
    
    colorPixel(data, idx, iter) {
        if (iter === this.maxIterations) {
            data[idx] = 0;       // R
            data[idx+1] = 0;    // G
            data[idx+2] = 0;    // B
        } else {
            const norm = iter / this.maxIterations;
            
            switch (this.colorScheme) {
                case 'rainbow':
                    data[idx] = Math.floor(255 * (0.5 + 0.5 * Math.sin(norm * 20)));
                    data[idx+1] = Math.floor(255 * (0.5 + 0.5 * Math.sin(norm * 20 + 2)));
                    data[idx+2] = Math.floor(255 * (0.5 + 0.5 * Math.sin(norm * 20 + 4)));
                    break;
                case 'fire':
                    data[idx] = Math.floor(255 * norm);
                    data[idx+1] = Math.floor(255 * norm * 0.5);
                    data[idx+2] = Math.floor(255 * norm * norm);
                    break;
                case 'grayscale':
                    const val = Math.floor(255 * norm);
                    data[idx] = val;
                    data[idx+1] = val;
                    data[idx+2] = val;
                    break;
            }
        }
        data[idx+3] = 255; // Alpha
    }
}

// Initialize when loaded
window.addEventListener('load', () => {
    new FractalExplorer('fractalCanvas');
});