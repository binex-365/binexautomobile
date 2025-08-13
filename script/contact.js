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

const form = document.getElementById("contact-form");
const status = document.getElementById("form-status");

form.addEventListener("submit", async (e) => {
  e.preventDefault();

  const formData = new FormData(form);
  const endpoint = "https://formspree.io/f/YOUR_FORM_ID_HERE"; // ← Replace with your Formspree endpoint

  // Show loading spinner
  status.classList.remove('show');
  status.innerHTML = `<div class="spinner"></div>`;
  status.classList.add('show');

  try {
    const res = await fetch(endpoint, {
      method: "POST",
      body: formData,
      headers: { 'Accept': 'application/json' }
    });

    if (res.ok) {
      status.innerHTML = `<span style="color: limegreen;">✅ Message sent successfully!</span>`;
      status.classList.add('show');
      form.reset();
    } else {
      throw new Error("Failed");
    }

  } catch (err) {
    status.innerHTML = `<span style="color: red;">❌ Failed to send message. Please try again.</span>`;
    status.classList.add('show');
  }
}); 