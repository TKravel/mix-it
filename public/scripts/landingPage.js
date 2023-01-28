const handleSelection = (e) => {
  console.log(e.currentTarget.dataset.drinkid);
  let drinkId = e.currentTarget.dataset.drinkid;
  if (drinkId == null || drinkId == "") return;

  window.location = `drinks/details/${drinkId}`;
};
