function showText(imageNumber) {
    const textElement = document.getElementById(`text${imageNumber}`);
    textElement.style.display = "block";
}

function hideText(imageNumber) {
    const textElement = document.getElementById(`text${imageNumber}`);
    textElement.style.display = "none";
}
