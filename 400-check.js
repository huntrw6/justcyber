function search(keyword) {
    if (keyword === "Genesis") {
      window.location.href = "page1.html";
    } else if (keyword === "page2") {
      window.location.href = "page2.html";
    } else if (keyword === "page3") {
      window.location.href = "page3.html";
    } else {
      alert("Keyword not found.");
    }
  }