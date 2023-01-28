const ingredents = document.getElementsByClassName("ingredent");
const carouselInner = document.getElementsByClassName("carousel-inner")[0];
const carouselIndicators = document.getElementsByClassName(
  "carousel-indicators"
)[0];
const drinkName = document.querySelector("[data-name]").dataset.name;

const createCarouselItems = (drinkArr) => {
  let indicators = "";
  let items = "";
  drinkArr.forEach((drink, idx) => {
    indicators += `
        <button type="button" data-bs-target="#similar-carousel" data-bs-slide-to="${idx}" ${
      idx === 0 ? 'class="active" aria-current="true"' : null
    } aria-label="Slide ${idx + 1}"></button>
        `;
    items += `
        <div class="carousel-item ${idx === 0 ? "active" : null}">
        <div class="w-100 mx-auto p-5">
        <a href="/mix-it/drinks/details/${
          drink.idDrink
        }" class="d-block w-50 mx-auto rounded-circle">
        <img src="${
          drink.strDrinkThumb
        }" class="d-block w-100 rounded-circle" style="cursor: pointer" alt="${
      drink.strDrink
    }""></a>
        <h3 class="text-center">${drink.strDrink}</h3>
        </div>
        
        </div>
      `;
  });
  carouselIndicators.innerHTML = indicators;
  carouselInner.innerHTML = items;
};

const handleSelection = (id) => {
  console.log(id);
};

const fetchDrinks = () => {
  let data = {
    ingredents: [ingredents[0].innerText, ingredents[1].innerText],
    name: drinkName,
  };
  console.log(data);
  fetch("/mix-it/drinks/similar", {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify(data),
  })
    .then((res) => res.json())
    .then((data) => {
      if (data.length > 1) {
        createCarouselItems(data);
      } else {
        console.log("error");
      }
    })
    .then((err) => {
      console.log(err);
    });
};

window.addEventListener("load", fetchDrinks);
