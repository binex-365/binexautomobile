exported();
function exported() {
  const sidy = document.querySelector('.sidy');
  const content = document.querySelector('.all');
  const ham = document.querySelector('.ham-div');
  const cancel = document.querySelector('.cancel');
  const body = document.querySelector('body');

  ham.addEventListener('click', () => {
    content.classList.add('active');
    sidy.classList.add('active');
    content.style.pointerEvents="none";
    content.classList.add('opaci');
    body.style.overflowX='hidden';
    body.style.overflowY='hidden';
  });

  cancel.addEventListener('click', () => {
    content.classList.remove('active');
    sidy.classList.remove('active');
    content.style.pointerEvents="all";
    content.classList.remove('opaci');
    body.style.overflowX='hidden';
    body.style.overflowY='scroll';
  });
}

document.addEventListener('DOMContentLoaded', function() {
    setTimeout(function() {
        document.body.classList.add('loaded');
    }, 200);
});

// Optional: Smooth scroll for contact link
document.querySelectorAll('.contact-link').forEach(link => {
    link.addEventListener('click', function(e) {
        const href = this.getAttribute('href');
        if (href && href.startsWith('#')) {
            e.preventDefault();
            document.querySelector(href).scrollIntoView({ behavior: 'smooth' });
        }
    });
});