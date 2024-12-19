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

  // Fetch destinations based on selected country
  function fetchDestinations(pays) {
    const restUrl = `${window.location.origin}/31w02/wp-json/wp/v2/posts?search=${pays}&per_page=30`;

    fetch(restUrl)
      .then((response) => {
        if (!response.ok) {
          throw new Error(`HTTP Error: ${response.status}`);
        }
        return response.json();
      })
      .then((data) => {
        console.log("Destinations retrieved:", data);
        displayDestinations(data); // Call function to display results
      })
      .catch((error) => {
        console.error("Error fetching destinations:", error);
      });
  }

  // Display destinations in the results div
  function displayDestinations(data) {
    const resultsDiv = document.getElementById("destinations-pays-results");
    if (!resultsDiv) {
      console.error("Results div not found.");
      return;
    }

    resultsDiv.innerHTML = ""; // Clear previous results

    if (data.length === 0) {
      resultsDiv.innerHTML = "<p>No destinations found for this country.</p>";
      return;
    }

    // Display each destination as a link
    data.forEach((destination) => {
      const destinationElement = document.createElement("p");
      destinationElement.innerHTML = `<a href="${destination.link}" target="_blank">${destination.title}</a>`;
      resultsDiv.appendChild(destinationElement);
    });
  }
})();
