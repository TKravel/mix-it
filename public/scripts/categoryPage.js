let pageObj;
let cardContainer = document.getElementsByClassName("card-container")[0];
let drinkCount = document.getElementById("drinkCount");
let pageInfo = document.getElementById("page-info");

const fetchData = () => {
  let category = document.getElementById("catValue").value;

  fetch(`/mix-it/drinks/category/search/${category}`, {
    method: "GET",
  })
    .then((res) => res.json())
    .then((res) => {
      pageObj = new categoryData(res);
      let firstPage = pageObj.getPageData();
      drinkCount.innerHTML = `(${res.length})`;
      cardContainer.innerHTML = pageObj.createHtml(firstPage);
    })
    .catch((err) => {
      console.log(err);
    });
};

const changePage = (e) => {
  let direction = e.target.id;
  console.log(`Direction ${typeof direction}`);
  console.log(e.target.innerText);
  if (direction == "prev" && pageObj.page == 1) return;
  if (direction == "next" && pageObj.page == pageObj.totalPages) return;

  let newData = pageObj.changePage(direction);
  cardContainer.innerHTML = pageObj.createHtml(newData);
  window.scrollTo({ top: 0, behavior: "smooth" });
};

const handleSelection = (e) => {
  let drinkId = e.currentTarget.dataset.drinkid;
  if (drinkId == null || drinkId == "") return;
  window.location = `/mix-it/drinks/details/${drinkId}`;
};

window.addEventListener("load", fetchData);

class categoryData {
  drinkData = [];
  page = 1;
  pageSize = 20;
  dataLength;
  totalPages;
  constructor(data) {
    this.drinkData = data;
    this.dataLength = data.length;
    this.totalPages = Math.ceil(data.length / this.pageSize);
  }

  getPageData() {
    let start = this.page == 1 ? 0 : this.page * this.pageSize - this.pageSize;
    let end = this.page == 1 ? 20 : this.page * this.pageSize;
    console.log(`Start: ${start} - End: ${end}`);
    let pageData = this.drinkData.slice(start, end);
    pageInfo.innerText = `${this.page} of ${this.totalPages}`;
    return pageData;
  }

  changePage(direction) {
    let pageData;
    if (direction === "next") {
      this.page = this.page + 1;
      pageData = this.getPageData();
    } else {
      this.page = this.page - 1;
      pageData = this.getPageData();
    }
    return pageData;
  }

  createHtml(drinks) {
    let html = "";

    drinks.forEach((drink) => {
      html += `
        <div class="thumb-card" data-drinkId="${drink.id}" onClick="handleSelection(event)">
            <img class="img-thumb" src="${drink.img}" alt="${drink.name}" />
            <p>${drink.name}</p>
        </div>
        `;
    });

    return html;
  }
}
