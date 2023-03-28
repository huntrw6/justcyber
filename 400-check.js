
function processSearchQuery(query) {
  if (query === "foo") {
    // Do something if the search query is "foo"
    console.log("Search query is foo");
  } else if (query === "bar") {
    // Do something if the search query is "bar"
    console.log("Search query is bar");
  } else {
    // Do something if the search query is neither "foo" nor "bar"
    console.log("Search query is neither foo nor bar");
  }
}
searchButton.addEventListener("click", function() {
  const searchQuery = document.getElementById("search").value;

  if (searchQuery === "") {
    searchResults.innerHTML = "<p>Please enter a search query.</p>";
  } else {
    searchResults.innerHTML = "<p>Searching for: " + searchQuery + "</p>";
    processSearchQuery(searchQuery);
  }
});















//const grade = 90;

//if (grade >= 90) {
 // console.log("You got an A");
//} else if (grade >= 80) {
//  console.log("You got a B");
//} else if (grade >= 70) {
//  console.log("You got a C");
//} else if (grade >= 60) {
//  console.log("You got a D");
//} else {
//  console.log("You failed the class");
//}