let inspectionData = [];
let lastDeleted = null;
let timer = null;

async function fetchInspections() {
  const res = await fetch('get_inspections.php');
  inspectionData = await res.json();
  renderCards();
}

function renderCards() {
  const container = document.getElementById('dashboardContainer');
  container.innerHTML = '';
  if (inspectionData.length === 0) {
    container.innerHTML = '<p style="grid-column: 1/-1; text-align:center;">No inspection requests yet.</p>';
    return;
  }
  inspectionData.forEach(row => {
    const card = document.createElement('div');
    card.className = 'card';
    card.setAttribute('data-id', row.id);
    card.innerHTML = `
      <h3>${row.car_name} (${row.car_model})</h3>
      <p><strong>Name:</strong> ${row.name}</p>
      <p><strong>Email:</strong> ${row.email}</p>
      <p><strong>Phone:</strong> ${row.phone}</p>
      <p><strong>Price:</strong> ${row.car_price}</p>
      <p><strong>Date:</strong> ${row.inspection_date} <strong>Time:</strong> ${row.inspection_time}</p>
      <p><strong>Note:</strong> ${row.note}</p>
      ${row.uploaded_image ? `<img src="${row.uploaded_image}" alt="Car Image">` : ''}
      <form action="edit_inspection.php" method="GET" style="display:inline;">
        <input type="hidden" name="id" value="${row.id}">
        <button class="edit-btn" type="submit">Edit</button>
      </form>
      <button class="delete-btn" onclick="showDeleteBox(${row.id})">Delete</button>
    `;
    container.appendChild(card);
  });
}

function showDeleteBox(id) {
  document.getElementById('deleteConfirmBox').style.display = 'block';
  lastDeleted = inspectionData.find(item => item.id == id);
}

function closeDeleteBox() {
  document.getElementById('deleteConfirmBox').style.display = 'none';
}

function handleYesDelete() {
  inspectionData = inspectionData.filter(item => item.id != lastDeleted.id);
  renderCards();
  document.getElementById('confirmMessage').style.display = 'none';
  document.querySelector('.confirm-buttons').style.display = 'none';
  document.getElementById('undoSaveBtns').style.display = 'block';
  if (timer) clearTimeout(timer);
  timer = setTimeout(() => {
    saveDelete();
  }, 15000);
}

function undoDelete() {
  inspectionData.push(lastDeleted);
  inspectionData.sort((a, b) => b.id - a.id);
  renderCards();
  closeDeleteBox();
}

function saveDelete() {
  fetch('delete_inspection.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
    body: 'id=' + lastDeleted.id
  }).then(() => {
    closeDeleteBox();
    fetchInspections();
  });
}

fetchInspections();
setInterval(fetchInspections, 10000);