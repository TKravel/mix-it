const ingredents = document.getElementsByClassName("ingredent");
const carouselInner = document.getElementsByClassName("carousel-inner")[0];
const carouselIndicators = document.getElementsByClassName(
  "carousel-indicators"
)[0];
const drinkName = document.querySelector("[data-name]").dataset.name;

/**
 * Dynamically create carousel items from array of drink data
 *
 * @param {array} drinkArr
 */
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
        <p class="carousel-drink-name">${drink.strDrink}</p>
        </div>
        
        </div>
      `;
  });
  carouselIndicators.innerHTML = indicators;
  carouselInner.innerHTML = items;
};

/**
 * Fetch similar drinks for carousel. Grabs top 2 ingredients to search by and passes them in the body. On respose calls createCarouselItems
 */
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
      if (data.length > 0) {
        createCarouselItems(data);
      } else {
        console.log("error");
      }
    })
    .then((err) => {
      console.log(err);
    });
};

// on load call fetchDrinks callback
window.addEventListener("load", fetchDrinks);
