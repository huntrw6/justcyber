function sendMessage() {

    let filePath = "my-folder/Genesis/chapter1.txt";

    if (filePath.startsWith("my-folder/Genesis")) {
      console.log("This file is in the book of Genesis.");
    } else if (filePath.startsWith("my-folder/Exodus")) {
      console.log("This file is in the book of Exodus.");
    } else if (filePath.startsWith("my-folder/Leviticus")) {
      console.log("This file is in the book of Leviticus.");
    } else if (filePath.startsWith("my-folder/Numbers")) {
      console.log("This file is in the book of Numbers.");
    } else {
      console.log("This file is not in the Pentateuch.");
    }};