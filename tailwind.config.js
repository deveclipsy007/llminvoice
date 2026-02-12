/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        './templates/**/*.php',
        './src/**/*.php',
        './public/js/**/*.js',
    ],
    theme: {
        extend: {
            colors: {
                dark: {
                    DEFAULT: '#050505',
                    surface: '#111111',
                    hover: '#1a1a1a',
                },
                lime: {
                    DEFAULT: '#C8FF00',
                    dim: 'rgba(200, 255, 0, 0.1)',
                    glow: 'rgba(200, 255, 0, 0.3)',
                },
                danger: '#FF4B4B',
                'text-secondary': '#888888',
                'text-body': '#D1D1D1',
                sage: '#F2F4F1',
            },
            fontFamily: {
                sans: ['"Plus Jakarta Sans"', 'system-ui', 'sans-serif'],
            },
            borderRadius: {
                glass: '24px',
            },
            backdropBlur: {
                glass: '16px',
            },
            letterSpacing: {
                tight: '-0.02em',
            },
            animation: {
                'pulse-lime': 'pulseLime 2s ease-in-out infinite',
                'fade-in': 'fadeIn 0.3s ease-out',
                'slide-up': 'slideUp 0.3s ease-out',
                'orb-spin': 'orbSpin 3s linear infinite',
            },
            keyframes: {
                pulseLime: {
                    '0%, 100%': { boxShadow: '0 0 0 0 rgba(200, 255, 0, 0.4)' },
                    '50%': { boxShadow: '0 0 20px 10px rgba(200, 255, 0, 0.1)' },
                },
                fadeIn: {
                    from: { opacity: '0' },
                    to: { opacity: '1' },
                },
                slideUp: {
                    from: { opacity: '0', transform: 'translateY(10px)' },
                    to: { opacity: '1', transform: 'translateY(0)' },
                },
                orbSpin: {
                    from: { transform: 'rotate(0deg)' },
                    to: { transform: 'rotate(360deg)' },
                },
            },
        },
    },
    plugins: [
        require('@tailwindcss/forms'),
        require('@tailwindcss/typography'),
    ],
};
