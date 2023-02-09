/**
 * On click callback function for drink selection. Redirects to drink details page passing drink id as query param
 * @param {object} e event object
 * @returns {void}
 */
const handleSelection = (e) => {
  let drinkId = e.currentTarget.dataset.drinkid;
  if (drinkId == null || drinkId == "") return; // safety check
  window.location = `drinks/details/${drinkId}`;
};
