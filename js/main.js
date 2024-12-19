(function () {
  console.log("in-filtrepays");

  // Select all the country buttons
  const paysButtons = document.querySelectorAll(".btn-pays");

  // Ensure buttons exist
  if (!paysButtons.length) {
    console.error("No country buttons found.");
    return;
  }

  // Add event listener to each button
  paysButtons.forEach((button) => {
    button.addEventListener("click", () => {
      const pays = button.getAttribute("data-pays"); // Get the country name
      if (!pays) {
        console.error("Country name not found for this button.");
        return;
      }

      // Call function to fetch destinations
      fetchDestinations(pays);
    });
  });

  