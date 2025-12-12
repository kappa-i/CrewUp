/*Animation Header*/

document.addEventListener('DOMContentLoaded', () => {
    const nav = document.querySelector('.nav');
    if (!nav) return;
  
    const apply = () => {
      if (window.scrollY <= 1) {
        nav.style.background = 'rgba(255, 255, 255, 0)';
        nav.style.border = '1px solid rgba(255, 255, 255, 0)';
      } else {
        nav.style.background = 'rgba(255, 255, 255, 0.120)';
        nav.style.border = '1px solid rgba(255, 255, 255, 0.1)';
        nav.style.boxShadow = '0 4px 20px rgba(0, 0, 0, 0.25)';
      }
    };
  
    apply();
    window.addEventListener('scroll', apply, { passive: true });
  });
  