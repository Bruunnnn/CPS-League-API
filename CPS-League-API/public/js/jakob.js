createMasteryCardElement()
createMasteryCardElement()

function createMasteryCardElement() {

    champion = {
        "championId": 157,
        "championLevel": 13,
        "championPoints": 157411,
        "lastPlayTime": 1741642707000,
        "championPointsSinceLastLevel": 48811,
        "championPointsUntilNextLevel": 37811
    }


    masteryCardElement = document.createElement('div');
    championElement = document.createElement('div');
    masteryMainElement = document.createElement('div');
    masteryInfo = document.createElement("div")
    championIcon = document.createElement("img");
    championIcon.setAttribute("src", "https://via.placeholder.com/64");
    championLevel = document.createElement("div");
    championPoints = document.createElement("div");
    sinceLevelUp = document.createElement("div");
    toLevelUp = document.createElement("div");
    timeSincePlay = document.createElement("div");

    masteryCardElement.classList.add("mastery-card")
    masteryMainElement.classList.add("mastery-main")
    masteryInfo.classList.add("mastery-info")
    championElement.classList.add("champion-name")
    championIcon.classList.add("champion-icon")
    championLevel.classList.add("champion-level")
    championPoints.classList.add("champion-points")
    sinceLevelUp.classList.add("points-progress")
    toLevelUp.classList.add("points-progress")
    timeSincePlay.classList.add("last-played")

    championElement.textContent = champion.championId //Should be name
    championLevel.textContent = "Level " + champion.championLevel
    championPoints.textContent = champion.championPoints + " Points"
    sinceLevelUp.textContent = champion.championPointsSinceLastLevel + " since last level"
    toLevelUp.textContent = champion.championPointsUntilNextLevel + " until next level"
    timeSincePlay.textContent = " Last Played: " + champion.lastPlayTime

    masteryMainElement.append(championIcon)
    children = [championLevel, championPoints, sinceLevelUp, toLevelUp, timeSincePlay];

    masteryInfo.append(...children);
    for (i = 0; i++; 0 < children.length) {
        masteryInfo.append(children[i])
    }
    //masteryCardElement.appendChild([championElement, masteryMainElement]);
    masteryCardElement.append(championElement)
    masteryCardElement.append(masteryMainElement)
    masteryMainElement.append(masteryInfo)



    parent = document.getElementsByClassName('personal')[0];
    console.log(parent)
    parent.append(masteryCardElement);
}
