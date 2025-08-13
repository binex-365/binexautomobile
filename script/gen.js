// Fade-in animation for viewer count
function fadeInViewerCount() {
    const viewerCount = document.getElementById('viewer-count');
    if (viewerCount) {
        viewerCount.style.opacity = 0;
        viewerCount.style.transition = 'opacity 1s';
        setTimeout(() => {
            viewerCount.style.opacity = 1;
        }, 100);
    }
}

// Fade-in animation for all sections and sidebar
function fadeInSections() {
    const sections = document.querySelectorAll('main section, .sidebar');
    sections.forEach((section, idx) => {
        section.style.opacity = 0;
        section.style.transform = 'translateY(40px)';
        section.style.transition = 'opacity 1s, transform 1s';
        setTimeout(() => {
            section.style.opacity = 1;
            section.style.transform = 'translateY(0)';
        }, 300 + idx * 150); // staggered effect
    });
}

// Viewer count logic
function updateViewerCount() {
    let count = localStorage.getItem('genkada_viewers');
    if (!count) count = 0;
    count = parseInt(count, 10);

    let counted = sessionStorage.getItem('genkada_counted');
    if (!counted) {
        function increment() {
            if (!sessionStorage.getItem('genkada_counted')) {
                count++;
                localStorage.setItem('genkada_viewers', count);
                sessionStorage.setItem('genkada_counted', 'yes');
                document.getElementById('viewer-count').textContent = count;
                fadeInViewerCount();
            }
        }
        if (window.innerWidth <= 800) {
            window.addEventListener('scroll', increment, { once: true });
        } else {
            window.addEventListener('scroll', increment, { once: true });
            document.body.addEventListener('mouseenter', increment, { once: true });
        }
    }
    document.getElementById('viewer-count').textContent = count;
    fadeInViewerCount();
}

document.addEventListener('DOMContentLoaded', () => {
    updateViewerCount();
    fadeInSections();
});

// Hero image carousel with swipe animation
document.addEventListener("DOMContentLoaded", function () {
    const carImages = [
        "assets/land-cr.png",
        "assets/car-i.jpeg",
        "assets/benz.png",
        "assets/lex.jpeg",
        "assets/cra.jpeg"
    ];
    let current = 0;
    const heroImg = document.querySelector('.hero-img img');
    if (!heroImg) return;

    // Set up for swipe animation
    heroImg.style.transition = "opacity 0.7s cubic-bezier(.4,0,.2,1), transform 0.7s cubic-bezier(.4,0,.2,1)";

    setInterval(() => {
        // Swipe out to left
        heroImg.style.opacity = 0;
        heroImg.style.transform = "translateX(-80px) scale(0.96)";
        setTimeout(() => {
            current = (current + 1) % carImages.length;
            heroImg.src = carImages[current];
            // Instantly move image to right (pre-swipe-in)
            heroImg.style.transition = "none";
            heroImg.style.transform = "translateX(80px) scale(0.96)";
            // Force reflow to apply the transform instantly
            void heroImg.offsetWidth;
            // Animate swipe in from right
            heroImg.style.transition = "opacity 0.7s cubic-bezier(.4,0,.2,1), transform 0.7s cubic-bezier(.4,0,.2,1)";
            heroImg.style.opacity = 1;
            heroImg.style.transform = "translateX(0) scale(1)";
        }, 700);
    }, 4000);
});

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
    body.style.overflow='hidden';
});

cancel.addEventListener('click', () => {
    content.classList.remove('active');
    sidy.classList.remove('active');
    content.style.pointerEvents="all";
    content.classList.remove('opaci');
    body.style.overflowX='hidden';
    body.style.overflowY='scroll';
});

  document.getElementById('searchForm').addEventListener('submit', function (e) {
    e.preventDefault();

    const input = document.getElementById('homepageSearch').value.trim().toLowerCase();

    if (!input) return;

    // Define keyword groups
    const carKeywords = ['car', 'cars', 'vehicle', 'automobile', 'sale', 'buy'];
    const brandKeywords = ['brand', 'brands', 'toyota', 'lexus', 'benz', 'ford', 'kia', 'honda', 'nissan', 'hyundai'];
    const serviceKeywords = ['service', 'services', 'repair', 'fix', 'mechanic', 'inspection', 'transform'];

    // Redirect logic
    if (
      carKeywords.some(word => input.includes(word)) ||
      brandKeywords.some(word => input.includes(word))
    ) {
      window.location.href = 'carsales.html?search=' + encodeURIComponent(input);
    } else if (serviceKeywords.some(word => input.includes(word))) {
      window.location.href = 'service.html?search=' + encodeURIComponent(input);
    } else {
      // Optional fallback
      window.location.href = 'search.html?query=' + encodeURIComponent(input);
    }
  });