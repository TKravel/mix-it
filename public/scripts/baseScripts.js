const arrowIcon = document.getElementsByClassName("arrow-icon")[0];
const searchBar = document.getElementById("search-bar");
const autoDataList = document.getElementById("autocompleteOptions");

/**
 * Creates a string representation of option's from a passed in array and injects them into the search bars datalist
 *
 * @param {array} data array of drink names
 */
const populateDataList = (data) => {
  listHtml = "";
  data.forEach((drinkName) => {
    listHtml += `<option value="${drinkName}">`;
  });
  autoDataList.innerHTML = listHtml;
};

/**
 * Event listener's callback function for the nav element's search field
 *
 * @param {object} e event object
 */
const submitSearch = (e) => {
  e.preventDefault();
  let drinkName = searchBar.value;
  drinkName = drinkName.replace(" ", "_");
  window.location = `/mix-it/drink/${drinkName}`;
};

/**
 * Fetch's autocomplete drop down list. Sends current input value on each request. Calls populateList func on response
 *
 * @param {object} e event object
 */
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

// Listen to search field for input changes
searchBar.addEventListener("input", fetchList);
