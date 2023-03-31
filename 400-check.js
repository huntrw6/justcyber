function sendMessage() {

    let filePath = "my-folder";

    if (filePath.startsWith("my-folder/Genesis/Exodus/Leviticus/Numbers")) {
      console.log("This file is in the book of test.");
    } else if (filePath.startsWith("my-folder/Exodus")) {
      console.log("This file is in the book of Exodus.");
    } else if (filePath.startsWith("my-folder/Leviticus")) {
      console.log("This file is in the book of Leviticus.");
    } else if (filePath.startsWith("my-folder/Numbers")) {
      console.log("This file is in the book of Numbers.");
    } else {
      console.log("This file is not in the Pentateuch.");
    }};