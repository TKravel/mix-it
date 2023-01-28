const arrowIcon = document.getElementsByClassName("arrow-icon")[0];
const searchBar = document.getElementById("search-bar");
const autoDataList = document.getElementById("autocompleteOptions");

const toggleIcon = () => {
  console.log("toggle");
  arrowIcon.classList.toggle("drawer-open");
};

const populateDataList = (data) => {
  listHtml = "";
  data.forEach((drinkName) => {
    listHtml += `<option value="${drinkName}">`;
  });
  autoDataList.innerHTML = listHtml;
};

const submitSearch = (e) => {
  e.preventDefault();
  let drinkName = searchBar.value;
  drinkName = drinkName.replace(" ", "_");
  window.location = `/mix-it/drink/${drinkName}`;
};

const fetchList = (e) => {
  let inputStr = e.target.value;
  fetch("/mix-it/drinks/autocomplete", {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify({ userInput: inputStr }),
  })
    .then((res) => res.json())
    .then((data) => {
      if (data.length > 0) {
        populateDataList(data);
      }
    })
    .catch((err) => {
      console.log(err);
    });
};

searchBar.addEventListener("input", fetchList);

// fetch("/mix-it/drinks/autocomplete", {
//   method: "POST",
//   headers: {
//     "Content-Type": "application/json",
//   },
//   body: JSON.stringify({ userInput: "1-900" }),
// })
//   .then((res) => res.json())
//   .then((data) => {
//     console.log(data);
//   })
//   .catch((err) => {
//     console.log(err);
//   });
