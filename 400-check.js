function sendMessage() {

    let filePath = "/Bible/Old Testament/Genesis/Chapter 1.txt";

    

    if (filePath.startsWith("/Bible/Old Testament/Genesis")) {
      console.log("The file is in the Genesis directory.");
    } else if (filePath.startsWith("/Bible/Old Testament/Exodus")) {
      console.log("The file is in the Exodus directory.");
    } else if (filePath.startsWith("/Bible/Old Testament/Leviticus")) {
      console.log("The file is in the Leviticus directory.");
    } else if (filePath.startsWith("/Bible/Old Testament/Numbers")) {
      console.log("The file is in the Numbers directory.");
    } else {
      console.log("The file is not in any of the directories.");
    }};