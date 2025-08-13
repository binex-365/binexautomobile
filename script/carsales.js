(() => {
  // ====== PROTECT FROM DOUBLE-INCLUSION ======
  if (window.__GENKADA_CARSALES_LOADED__) return;
  window.__GENKADA_CARSALES_LOADED__ = true;

  // ====== CAR DATA FIRST ======
 let cars = [];

const savedCars = localStorage.getItem('genkadaCars');
if (savedCars) {
  try {
    cars = JSON.parse(savedCars);
  } catch (err) {
    console.warn("Failed to parse saved cars:", err);
    cars = []; // fallback
  }
}

/* localStorage.removeItem('genkadaCars');
localStorage.removeItem('selectedCar');
location.reload(); */

/*
if (!cars || cars.length === 0) {
  cars = [{
    name: "Toyota Venza XLE 2021 Gold",
    price: "₦53,500,000",
    image: "New cars/venza-main.webp",
    model: "XLE Hybrid",
    description: "Extremely Clean Foreign Use 2021 Toyota Venza with valid customer docs, going cheap for quick sale. This car is in excellent condition with no issues.",
    images: {
      front: "New cars/venza-main.webp",
      back: "New cars/venza-back.webp",
      interior: "New cars/venza-int.webp",
      interior2: "New cars/venza-int2.webp",
    }
  }, {
    name: "Mercedes-Benz GL-Class GL 450 2013 Brown",
    price: "₦23,700,000",
    image: "New cars/benz-main.webp",
    model: "XLE Hybrid",
    description: "2013 GL450 4matic with full option, panuromic roof with 360 cameras, keyless, glass roof, bluetooth, navigation, 3rd row seats, 4matic, and more. This car is in excellent condition with no issues.",
    images: {
      front: "New cars/benz-main.webp",
      back: "New cars/benz-back.webp",
      interior: "New cars/benz-int.webp",
      interior2: "New cars/benz-int2.webp",
      exterior: "New cars/benz-side.webp",
      engine: "New cars/benz-engine.webp",
      engine2: "New cars/benz-eng.webp",
      keyStat: "New cars/benz-key_start.webp",
    }
  }, {
    name: "Ford Explorer XLT 4x4 2020 Black",
    price: "₦52,100,000",
    image: "New cars/ford-main.webp",
    model: "XLE Hybrid",
    description: "Just Cleared New entry, Low mileage, Black on Black, Accident free, Panoramic roof,This car is in excellent condition with no issues.",
    images: {
      front: "New cars/ford-main.webp",
      back: "New cars/ford-back.webp",
      interior: "New cars/ford-int1.webp",
      interior2: "New cars/ford-int2.webp",
      exterior: "New cars/ford-side.webp",
      engine: "New cars/ford-engine.webp"
    }
  }, {
    name: "Honda Pilot EX-L 4x4 (3.5L 6cyl 5A) 2008 Green",
    price: "₦12,500,000",
    image: "New cars/pilot-main.webp",
    model: "XLE Hybrid",
    description: "2008 Honda Pilot Special Edition 4dr SUV *AWD/4ED*Sunroof/Moonroof*Third-row Seating*Power. This car is in excellent condition with no issues.",
    images: {
      front: "New cars/pilot-main.webp",
      back: "New cars/pilot-back.webp",
      interior: "New cars/pilot-int.webp",
      exterior: "New cars/pilot-side.webp"
    }
  }, {
    name: "Toyota Camry LE 4dr Sedan (2.4L 4cyl 5A) 2006 Red",
    price: "₦6,200,000",
    image: "New cars/camry-main.webp",
    model: "XLE Hybrid",
    description: "This is a perfectly working Toyota Camry LE 4dr Sedan (2.4L 4cyl 5A) with a clean title and no accidents. It has been well-maintained and is in excellent condition.",
    images: {
      front: "New cars/camry-main.webp",
      back: "New cars/camry-back.webp",
      interior: "New cars/camry-int1.webp",
      interior2: "New cars/camry-int2.webp",
      exterior: "New cars/camry-side1.webp",
      exterior2: "New cars/camry-side2.webp",
      engine: "New cars/camry-engine.webp",
    }
  }, {
    name: "Toyota Corolla 2015 Black",
    price: "₦15,000,000",
    image: "New cars/corol-front.webp",
    model: "XLE Hybrid",
    description: "Toyota Corolla 2015 in excellent condition, accident-free, with a clean title. This car has been well-maintained and is ready for a new owner.",
    images: {
      front: "New cars/corol-front.webp",
      back: "New cars/corol-back.webp",
      interior: "New cars/corol-int.webp",
      interior2: "New cars/corol-int2.webp",
      exterior: "New cars/corol-side.webp",
    }
  }];
}

  window.cars = cars;

  if (!localStorage.getItem('genkadaCars')) {
    localStorage.setItem('genkadaCars', JSON.stringify(cars));
  }
*/

document.addEventListener("DOMContentLoaded", () => {
  fetch("get_all_cars.php")
    .then(res => res.json())
    .then(fetchedCars => {
      console.log("✅ Cars received from server:", fetchedCars);

      // Assign to global cars array
      cars = fetchedCars;

      const container = document.getElementById("carContainer");
      container.innerHTML = ""; // Clear previous content

      fetchedCars.forEach((car) => {
        const carCard = document.createElement("div");
        carCard.innerHTML = `
          <div class="car-card">
            <img src="${car.image}" alt="${car.name}">
            <div class="details">
              <h3 class="name">${car.name}</h3>
              <p class="car-description infoss">${car.description}</p>
              <div class="price">${car.price}</div>

              <div class="actions">
                <div class="top-buttons">
                  <button>
                    <a href="inspection.html?name=${encodeURIComponent(car.name)}&price=${encodeURIComponent(car.price)}&model=${encodeURIComponent(car.model)}&img=${encodeURIComponent(car.image)}">Book Inspection</a>
                  </button>
                  <button class="details-btn" data-id="${car.id}">Car Details</button>
                </div>

                <p class="share-label" style="margin-top: 10px; margin-bottom: 5px; font-size: 14px; font-weight: 500; color: #00ccff;">
                  📢 Share this car with friends:
                </p>

                <div class="bottom-buttons">
                  <button class="share-btn" style="background-color: green; text-shadow: 0 1px 2px black;" onclick="shareCarWhatsApp('${encodeURIComponent(car.name)}', '${encodeURIComponent(car.price)}')">
                    <img src="assets/wt.png" style="width: 25px; height: 25px; margin-bottom: 0.3rem;"> WhatsApp
                  </button>
                  <button class="share-btn" style="background-color: rgba(20, 116, 164, 0.91); text-shadow: 0 1px 2px black;" onclick="shareCarFacebook('${encodeURIComponent(car.name)}', '${encodeURIComponent(car.price)}')">
                    <img src="assets/face.jpeg" style="width: 25px; height: 25px; margin-bottom: 0.3rem;"> Facebook
                  </button>
                </div>
              </div>
            </div>
          </div>
        `;

        container.appendChild(carCard);
      });

      // Attach click event for details buttons (only once)
      document.addEventListener('click', function (e) {
        if (e.target.classList.contains('details-btn')) {
          const carId = e.target.getAttribute('data-id');
          window.location.href = `car-details.html?id=${carId}`;
        }
      });

    })
    .catch(error => {
      console.error("❌ Failed to load cars:", error);
    });
});

// Optional helper functions for sharing
function shareCarWhatsApp(name, price) {
  const message = `🚗 Check out this car: ${decodeURIComponent(name)} - Price: ${decodeURIComponent(price)}\nVisit: ${window.location.href}`;
  const url = `https://wa.me/?text=${encodeURIComponent(message)}`;
  window.open(url, "_blank");
}

function shareCarFacebook(name, price) {
  const message = `🚗 Check out this car: ${decodeURIComponent(name)} - Price: ${decodeURIComponent(price)}\nVisit: ${window.location.href}`;
  const url = `https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(window.location.href)}&quote=${encodeURIComponent(message)}`;
  window.open(url, "_blank");
}


  /*
try {
  const urlParams = new URLSearchParams(window.location.search);
  const replaceCarData = urlParams.get('replaceCar');

  if (replaceCarData) {
  const updatedCar = JSON.parse(decodeURIComponent(replaceCarData));

  const indexToReplace = cars.findIndex(car =>
    car.name === updatedCar.oldName || car.image === updatedCar.oldImage
  );

  if (indexToReplace !== -1) {
    cars[indexToReplace] = {
      ...cars[indexToReplace],
      name: updatedCar.name,
      price: updatedCar.price,
      description: updatedCar.description,
      image: updatedCar.image,

      // ✅ Update the additional views too:
      front: updatedCar.front,
      back: updatedCar.back,
      interior: updatedCar.interior,
      interior2: updatedCar.interior2,
      exterior: updatedCar.exterior,
      engine: updatedCar.engine
    };

    // ✅ Save to localStorage
    localStorage.setItem('genkadaCars', JSON.stringify(cars));

    console.log("✅ Replaced and saved car:", updatedCar);
  } else {
    console.warn("⚠️ Could not find matching car to replace.");
  }

  // ✅ Clean up the URL
  history.replaceState(null, '', window.location.pathname);
}

} catch (err) {
  console.error("❌ Error replacing car from URL param:", err);
} */


  
  // ====== SIDEBAR MENU ======
  function exported() {
    const sidy = document.querySelector('.sidy');
    const content = document.querySelector('.all');
    const ham = document.querySelector('.ham-div');
    const cancel = document.querySelector('.cancel');
    const body = document.querySelector('body');

    if (ham && content && sidy && body) {
      ham.addEventListener('click', () => {
        content.classList.add('active');
        sidy.classList.add('active');
        content.style.pointerEvents = "none";
        content.classList.add('opaci');
        body.style.overflow = 'hidden';
      });
    }

    if (cancel && content && sidy && body) {
      cancel.addEventListener('click', () => {
        content.classList.remove('active');
        sidy.classList.remove('active');
        content.style.pointerEvents = "all";
        content.classList.remove('opaci');
        body.style.overflowY = 'scroll';
      });
    }
  }
  exported();

  // ====== RENDER CARS ======
 function renderCars(carList) {
  const container = document.getElementById("carContainer");
  if (!container) return;

  container.innerHTML = "";

  carList.forEach((car, index) => {
    container.innerHTML += `
      <div class="car-card">
        <img src="${car.image}" alt="${car.name}">
        <div class="details">
          <h3 class="name">${car.name}</h3>
          <p class="car-description infoss">${car.description}</p>
          <div class="price">${car.price}</div>
          <div class="actions">
            <div class="top-buttons">
              <button>
                <a href="inspection.html?name=${encodeURIComponent(car.name)}&price=${encodeURIComponent(car.price)}&model=${encodeURIComponent(car.model)}&img=${encodeURIComponent(car.image)}">Book Inspection</a>
              </button>
              <button class="details-btn" data-index="${index}">Car Details</button>
            </div>

            <p class="share-label" style="margin-top: 10px; margin-bottom: 5px; font-size: 14px; font-weight: 500; color: #00ccff;">
              📢 Share this car with friends:
            </p>

            <div class="bottom-buttons">
              <button class="share-btn" style="background-color: green; text-shadow: 0 1px 2px black;" onclick="shareCarWhatsApp('${encodeURIComponent(car.name)}', '${encodeURIComponent(car.price)}')">
                <img src="assets/wt.png" style="width: 25px; height: 25px; margin-bottom: 0.3rem;"> WhatsApp
              </button>
              <button class="share-btn" style="background-color: rgba(20, 116, 164, 0.91); text-shadow: 0 1px 2px black;" onclick="shareCarFacebook('${encodeURIComponent(car.name)}', '${encodeURIComponent(car.price)}')">
                <img src="assets/face.jpeg" style="width: 25px; height: 25px; margin-bottom: 0.3rem;"> Facebook
              </button>
            </div>
          </div>
        </div>
      </div>
    `;
  });

  // Attach event listeners AFTER the cards are rendered
  document.querySelectorAll(".details-btn").forEach((btn) => {
    btn.addEventListener("click", (e) => {
      const index = e.currentTarget.getAttribute("data-index");
      const selectedCar = carList[index];

      // Save car to localStorage
      localStorage.setItem("selectedCar", JSON.stringify(selectedCar));

      // Redirect to details page
      window.location.href = "car-details.html";
    });
  });
}

  // ====== EDIT MODE FEATURE ======
  const urlParams = new URLSearchParams(window.location.search);
  const isEditMode = urlParams.get('editMode') === 'true';

  if (isEditMode) {
    document.body.style.cursor = 'crosshair';

    const style = document.createElement("style");
    style.innerHTML = `
      .dimmed-body .car-card {
        opacity: 0.25;
        transition: opacity 0.3s ease;
      }
      .car-card.highlighted {
        opacity: 1 !important;
        border: 2px dashed #0ff;
        box-shadow: 0 0 15px #0ff;
      }
      #edit-overlay-msg {
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        font-size: 28px;
        font-weight: bold;
        color: white;
        padding: 30px 50px;
        border: 3px dotted white;
        border-radius: 10px;
        background: rgba(0,0,0,0.6);
        z-index: 9999;
        opacity: 0;
        animation: fadeInCenter 2s forwards;
      }
      @keyframes fadeInCenter {
        from { opacity: 0; }
        to { opacity: 1; }
      }
    `;
    document.head.appendChild(style);

    document.addEventListener("DOMContentLoaded", () => {
      const overlay = document.createElement("div");
      overlay.id = "edit-overlay-msg";
      overlay.innerText = "Click a car to edit";
      document.body.appendChild(overlay);
      document.body.classList.add('dimmed-body');

      const container = document.getElementById("carContainer");

      if (container) {
        container.addEventListener("click", (e) => {
          const card = e.target.closest(".car-card");
          if (!card || card.classList.contains("highlighted")) return;

          card.classList.add("highlighted");

          const name = card.querySelector(".name")?.textContent || "Unknown";
          const price = card.querySelector(".price")?.textContent || "₦0";
          const description = card.querySelector(".car-description")?.textContent || "";
          const image = card.querySelector("img")?.getAttribute("src") || "";

          const car = {
            name,
            price,
            description,
            image,
            model: card.querySelector(".model")?.textContent || "Unknown",
            oldName: name,
            oldImage: image
          };


          setTimeout(() => {
            const encodedCar = encodeURIComponent(JSON.stringify(car));
            window.location.href = `admin_dashboard.php?carData=${encodedCar}`;
          }, 700);
        });
      }
    });
  }

  // ====== SEARCH, LOAD, DETAILS ======
  let lastSearchResults = [];
  let currentLoadCount = 0;

  function searchCars() {
  const query = document.getElementById("searchInput")?.value.trim();
  if (!query) return alert("Please enter a search term.");

  // Show loader if you have one
  const spinner = document.getElementById("searchLoader");
  if (spinner) spinner.style.display = "inline-block";

  // Fetch search results from backend
  fetch(`search_cars.php?q=${encodeURIComponent(query)}`)
   .then(res => res.json())
.then(results => {
  // Hide loader
  if (spinner) spinner.style.display = "none";

  const container = document.getElementById("carContainer");
  if (!container) return;

  container.innerHTML = ""; // Clear previous results

  // Check if results is an array
  if (!Array.isArray(results)) {
    if (results.error) {
      alert("Search error: " + results.error);
    } else {
      alert("Unexpected response from server.");
      console.error("Unexpected search response:", results);
    }
    document.getElementById("seeMoreBtn").style.display = "none";
    document.getElementById("goBackBtn").style.display = "flex";
    return;
  }

  // No results found
  if (results.length === 0) {
    container.innerHTML = `<p style="color:#ccc; font-size: 18px; text-align:center;">No cars found matching "${query}".</p>`;
    document.getElementById("seeMoreBtn").style.display = "none";
    document.getElementById("goBackBtn").style.display = "flex";
    return;
  }

  // Save last search results globally if needed
  lastSearchResults = results;

  // Render each car card
  results.forEach(car => {
    const carCard = document.createElement("div");
    carCard.classList.add("car-card");
    carCard.innerHTML = `
      <img src="${car.image}" alt="${car.name}">
      <div class="details">
        <h3 class="name">${car.name}</h3>
        <p class="car-description infoss">${car.description}</p>
        <div class="price">${car.price}</div>

        <div class="actions">
          <div class="top-buttons">
            <button>
              <a href="inspection.html?name=${encodeURIComponent(car.name)}&price=${encodeURIComponent(car.price)}&model=${encodeURIComponent(car.model)}&img=${encodeURIComponent(car.image)}">Book Inspection</a>
            </button>
            <button class="details-btn" data-id="${car.id}">Car Details</button>
          </div>

          <p class="share-label" style="margin-top: 10px; margin-bottom: 5px; font-size: 14px; font-weight: 500; color: #00ccff;">
            📢 Share this car with friends:
          </p>

          <div class="bottom-buttons">
            <button class="share-btn" style="background-color: green; text-shadow: 0 1px 2px black;" onclick="shareCarWhatsApp('${encodeURIComponent(car.name)}', '${encodeURIComponent(car.price)}')">
              <img src="assets/wt.png" style="width: 25px; height: 25px; margin-bottom: 0.3rem;"> WhatsApp
            </button>
            <button class="share-btn" style="background-color: rgba(20, 116, 164, 0.91); text-shadow: 0 1px 2px black;" onclick="shareCarFacebook('${encodeURIComponent(car.name)}', '${encodeURIComponent(car.price)}')">
              <img src="assets/face.jpeg" style="width: 25px; height: 25px; margin-bottom: 0.3rem;"> Facebook
            </button>
          </div>
        </div>
      </div>
    `;
    container.appendChild(carCard);
  });

  // Show buttons after search
  const seeMoreBtn = document.getElementById("seeMoreBtn");
  const goBackBtn = document.getElementById("goBackBtn");
  if (seeMoreBtn) seeMoreBtn.style.display = "flex";
  if (goBackBtn) goBackBtn.style.display = "flex";

  // Smooth scroll to results
  container.scrollIntoView({ behavior: "smooth" });
})
.catch(err => {
  if (spinner) spinner.style.display = "none";
  console.error("Search failed:", err);
  alert("Sorry, something went wrong with your search. Please try again.");
});

}

  function goBackToAllCars() {
  const input = document.getElementById("searchInput");
  const spinner = document.getElementById("searchLoader");

  if (input) input.value = "";
  if (spinner) spinner.style.display = "inline-block";

  setTimeout(() => {
    // Use the already fetched global cars array
    console.log("Global cars array at goBackToAllCars:", cars);
    renderCars(cars);

    // Hide the buttons after showing full list
    document.getElementById("seeMoreBtn").style.display = "none";
    document.getElementById("goBackBtn").style.display = "none";

    if (spinner) spinner.style.display = "none";
  }, 1000); // simulate loading for UX
}

  function handleCarDetails(button, car) {
    const spinner = button.querySelector(".spinner");
    if (spinner) spinner.style.display = "inline-block";
    button.disabled = true;

    setTimeout(() => {
      localStorage.setItem("selectedCar", JSON.stringify(car));
      window.location.href = "car-details.html";
    }, 100);
  }

  document.addEventListener("DOMContentLoaded", () => {
    const container = document.getElementById("carContainer");
    if (container && container.children.length === 0) {
      renderCars(cars); // ✅ Only render if not already rendered
    }

    const hero = document.querySelector(".hero");
    if (hero) {
      hero.innerHTML = `
        <div class="hero-container">
          <div class="hero-text">
            <h2 class="animate-title">Find Your Perfect Car</h2>
            <p class="animate-sub">Search and buy cars with ease. Verified listings. Trusted dealers.</p>
          </div>
          <div class="search-bar animate-search">
            <input type="text" id="searchInput" placeholder="Search for cars by name, brand or year...">
            <button onclick="triggerSearch()">Search</button>
            <div id="searchLoader" class="spinner" style="display: none;"></div>
          </div>

          <div id="goBackBtn" onclick="goBackToAllCars()" style="display: none; margin-top: 15px; padding: 10px 25px; background: linear-gradient(145deg, #002f4b, #005f73); color: white; border-radius: 8px; text-align: center; width: fit-content; margin-left: auto; margin-right: auto; cursor: pointer; box-shadow: 0 0 10px #00ccff; font-weight: bold; transition: all 0.3s ease;">
            ⬅ Go Back
          </div>
        </div>
      `;
    }


    function shareCarWhatsApp(name, price) {
      const message = `🚗 Check out this car on Genkada Automobile!

    🔹 Name: ${decodeURIComponent(name)}
    💰 Price: ${decodeURIComponent(price)}

    🔗 Visit: https://genkadaautomobile.com/car-details.html`;

      const whatsappURL = `https://wa.me/?text=${encodeURIComponent(message)}`;
      window.open(whatsappURL, '_blank');
    }

    function shareCarFacebook(name, price) {
      const message = `Check out this car: ${decodeURIComponent(name)} – ${decodeURIComponent(price)}`;
      const pageURL = 'https://genkadaautomobile.com/car-details.html';

      const facebookURL = `https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(pageURL)}&quote=${encodeURIComponent(message)}`;
      window.open(facebookURL, '_blank');
    }
//ngrok http http://localhost:8080

   const moreBtn = document.createElement("div");
    moreBtn.id = "seeMoreBtn";
    moreBtn.style.backgroundColor = "rgba(255, 255, 255, 0.86)";
    moreBtn.innerHTML = `
      <div id="backToTopBtn" style="margin: 30px auto; padding: 10px 25px; border-radius: 30px; background: linear-gradient(to right, #004080, #002f6c); color: white; font-size: 16px; display: flex; justify-content: center; align-items: center; gap: 10px; cursor: pointer;">
        ⬆ Back to top
      </div>
    `;
    document.body.insertBefore(moreBtn, document.querySelector("footer"));
    moreBtn.style.display = "none";

    // Smooth scroll to top when backToTopBtn is clicked
    document.addEventListener("click", function (e) {
      if (e.target && e.target.id === "backToTopBtn") {
        window.scrollTo({
          top: 0,
          behavior: "smooth"
        });
      }
    });
  });

  // ====== EXPORT TO WINDOW ======
  window.searchCars = searchCars;
  window.goBackToAllCars = goBackToAllCars;
  window.triggerSearch = () => {
    const input = document.getElementById("searchInput");
    const query = input?.value.trim();
    if (!query) return alert("Please enter a search term.");
    document.getElementById("searchLoader").style.display = "inline-block";
    document.getElementById("carContainer").innerHTML = "";
    setTimeout(() => {
      document.getElementById("searchLoader").style.display = "none";
      searchCars();
    }, 4000);
  };
  window.handleCarDetails = handleCarDetails;


  window.shareCarWhatsApp = function(name, price) {
  const message = `Check out this car on Genkada Automobile:\n${decodeURIComponent(name)} – ${decodeURIComponent(price)}\nhttps://genkadaautomobile.com/carsales.html`;
  const whatsappURL = `https://wa.me/?text=${encodeURIComponent(message)}`;
  window.open(whatsappURL, '_blank');
};

window.shareCarFacebook = function(name, price) {
  const quote = `Check out this car on Genkada Automobile: ${decodeURIComponent(name)} – ${decodeURIComponent(price)}`;
  const facebookURL = `https://www.facebook.com/sharer/sharer.php?u=https://genkadaautomobile.com/carsales.html&quote=${encodeURIComponent(quote)}`;
  window.open(facebookURL, '_blank');
};

// Load car info from localStorage when inspection page loads
  window.addEventListener('DOMContentLoaded', function () {
    const car = JSON.parse(localStorage.getItem('selectedCar') || '{}');

    if (car.name) {
      document.querySelector('.car-name').textContent = car.name;
      document.querySelector('.car-model span').textContent = car.description;
      document.querySelector('.car-price').textContent = car.price;
      document.querySelector('.car-image').src = car.image;
    }
  });

})();