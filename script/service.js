// services.js
const services = [
  { title: "Engine Repair", description: "Complete engine diagnostics and repairs", icon: "🛠️" },
  { title: "Transmission Fix", description: "Repair and replacement of transmission systems", icon: "⚙️" },
  { title: "Body Work", description: "Panel beating, painting, dent removal", icon: "🚗" },
  { title: "Brand Inspection", description: "Check car brand quality before purchase", icon: "🔍" },
  { title: "Oil Change", description: "Quick, professional oil and filter change", icon: "🛢️" },
  { title: "Tyre Replacement", description: "New tyres and wheel balancing", icon: "🛞" },
  { title: "Car Branding", description: "Brand your car with custom wrap or logo", icon: "🎨" },
];


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