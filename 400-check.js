function sendMessage(book, chapter, verse) {
    if (book === "Genesis" && chapter === 1 && verse === 1) {
      console.log("In the beginning God created the heavens and the earth.");
    } else if (book === "Exodus" && chapter === 3 && verse === 14) {
      console.log("And God said unto Moses, I AM THAT I AM: and he said, Thus shalt thou say unto the children of Israel, I AM hath sent me unto you.");
    } else if (book === "Leviticus" && chapter === 19 && verse === 18) {
      console.log("Thou shalt not avenge, nor bear any grudge against the children of thy people, but thou shalt love thy neighbour as thyself: I am the LORD.");
    } else {
      console.log("Verse not found.");
    }
  }