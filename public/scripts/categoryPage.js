let PageController;
let cardContainer = document.getElementById("card-container");
let drinkCount = document.getElementById("drinkCount");
let pageButtons = document.getElementsByClassName("page-btn");
let pageControls = document.getElementsByClassName("page-control");

/**
 * Calls controller to fetch data. Category value is grabbed from hidden input in template.
 * On response creates new PageController object passing in response data to contstructor. Gets first page worth of data and injects into document
 */
const fetchData = () => {
  let category = document.getElementById("catValue").value;

  fetch(`/mix-it/drinks/category/search/${category}`, {
    method: "GET",
  })
    .then((res) => res.json())
    .then((res) => {
      PageController = new categoryData(res);
      let firstPage = PageController.getPageData();
      cardContainer.innerHTML = PageController.createHtml(firstPage);
    })
    .catch((err) => {
      console.log(err);
    });
};

/**
 * Handles page changes
 *
 * @param {object} e event object
 * @returns void
 */
const changePage = (e) => {
  // check if page control or page number button was selected
  let direction = e.target.hasAttribute("id")
    ? e.target.id
    : parseInt(e.target.innerText);
  // exit function if pages can not change correctly
  if (parseInt(direction) !== NaN && direction > PageController.totalPages)
    return;
  if (direction == "prev" && PageController.page == 1) return;
  if (direction == "next" && PageController.page == PageController.totalPages)
    return;

  // get / inject new page data
  let newData = PageController.changePage(direction);
  cardContainer.innerHTML = PageController.createHtml(newData);
  window.scrollTo({ top: 0, behavior: "smooth" });
};

/**
 * Onclick callback function to handle drink selection. Redirect to drink details passing drink ID as a query param
 * @param {object} e event object
 * @returns void
 */
const handleSelection = (e) => {
  let drinkId = e.currentTarget.dataset.drinkid;
  if (drinkId == null || drinkId == "") return; // safety check
  window.location = `/mix-it/drinks/details/${drinkId}`;
};

/**
 * PageHandler class
 *
 * Stores / handles data related to paginating and creating HTML to show drink data
 */
class PageHandler {
  drinkData = [];
  page = 1;
  pageSize = 20;
  dataLength;
  totalPages;

  /**
   * Sets drinkData, data length, and total pages upon instantiation
   * @param {array} data array of drink data based off selected category
   */
  constructor(data) {
    this.drinkData = data;
    this.dataLength = data.length;
    this.totalPages = Math.ceil(data.length / this.pageSize);
  }

  /**
   * Calculates start / end index in slicing drinkData array and returning data for current page
   * Calls to updatePageButtons to update pagination buttons before returning data
   * @returns {array} pageData array with length of 20 containing drink data
   */
  getPageData() {
    let start = this.page == 1 ? 0 : this.page * this.pageSize - this.pageSize;
    let end = this.page == 1 ? 20 : this.page * this.pageSize;
    let pageData = this.drinkData.slice(start, end);
    this.updatePageButtons();
    return pageData;
  }

  /**
   * Updates current page. Calls getPageData to get new page data. Returns new data
   * @param {string | int} direction string if button was next or prev, int if page number slection button
   * @returns {array} pageData array of new page data
   */
  changePage(direction) {
    let pageData;
    if (direction === "next") {
      this.page = this.page + 1;
      pageData = this.getPageData();
    } else if (direction === "prev") {
      this.page = this.page - 1;
      pageData = this.getPageData();
    } else {
      this.page = direction;
      pageData = this.getPageData();
    }
    return pageData;
  }

  /**
   * Iterates over drink array creating HTML of drink cards to be injected into the dom
   * @param {array} drinks array of 20 drink items to show on page
   * @returns {string} html string representing HTML
   */
  createHtml(drinks) {
    let html = "";
    drinks.forEach((drink) => {
      html += `
        <div class="thumb-card text-center rounded-circle p-3 m-3 m-md-5" data-drinkId="${drink.id}" onClick="handleSelection(event)">
            <img class="rounded-circle img-fluid mb-2" src="${drink.img}" alt="${drink.name}" />
            <p>${drink.name}</p>
        </div>
        `;
    });
    return html;
  }

  /**
   * Handles pagination buttons. First clears active / disabled class styles. Depending on page sets proper classes and page numbers.
   * Pagination buttons consist of a 'Prev', three numbered page selection buttons, and a 'Next'. On page change the active page is indicated and remains in the center of numbered pages. Page controls disable if page can not move in that direction anymore
   * @returns {void}
   */
  updatePageButtons() {
    //clear active-page class styles
    for (const el of pageButtons) {
      if (el.classList.contains("active-page")) {
        el.classList.remove("active-page");
      }
    }
    //clear disabled-page styles
    for (const el of pageControls) {
      if (el.classList.contains("disabled-page")) {
        el.classList.remove("disabled-page");
        el.classList.add("active-page");
      }
    }
    // handle button if page equals 1
    if (this.page == 1) {
      pageButtons[0].classList.add("active-page");
      for (let i = 0; i < 3; i++) {
        pageButtons[i].innerText = i + 1;
        pageControls[0].classList.add("disabled-page");

        // if totals pages is less than 3 disable correct numbers
        if (this.totalPages < i + 1) {
          pageButtons[i].classList.add("disabled-page");
        }
        // disable next buttom
        if (this.totalPages == 1) {
          pageControls[1].classList.add("disabled-page");
        }
      }
      // handle button if current page is last page
    } else if (this.page == this.totalPages) {
      // check if page total is less than 3
      if (this.totalPages <= 3) {
        if (this.page == 1) {
          pageControls[0].classList.add("disabled-page");
        }
        pageButtons[parseInt(this.totalPages) - 1].classList.add("active-page");
        pageControls[1].classList.add("disabled-page");
        return;
      }
      // update buttons as if you reached the last page
      pageButtons[2].classList.add("active-page");
      pageButtons[0].innerText = this.totalPages - 2;
      pageButtons[1].innerText = this.totalPages - 1;
      pageButtons[2].innerText = this.totalPages;
      pageControls[1].classList.add("disabled-page");
    } else {
      // update buttons as if total pages is greater than 3 AND its not the first or last page
      pageButtons[0].innerText = this.page - 1;
      pageButtons[1].innerText = this.page;
      pageButtons[1].classList.add("active-page");
      pageButtons[2].innerText = this.page + 1;
    }
  }
}

// on load fetch first pages data
window.addEventListener("load", fetchData);
