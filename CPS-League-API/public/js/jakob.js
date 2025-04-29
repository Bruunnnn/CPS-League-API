let championIdMap = {};

function addComma(number) {
    let array = number.toString().split("");
    let newString = "";
    for (let i = 0; i < array.length; i++) {
        newString += array[(array.length - i-1)];
        if ((i+1) % 3 === 0 && array.length !== (i+1)) {
            newString += ",";
        }
    }
    toBeReturned = newString.split("").reverse().join("")
    return toBeReturned;
}
loadChampionData().then(() => {
    createMasteryCardElement()
    createMasteryCardElement()
});

async function loadChampionData() {
    const response = await fetch('https://ddragon.leagueoflegends.com/cdn/14.8.1/data/en_US/champion.json');
    const data = await response.json();

    // Convert the data into a championId-to-name map
    for (const champKey in data.data) {
        const champ = data.data[champKey];
        championIdMap[parseInt(champ.key)] = champ.name;
    }
}

function getChampionNameById(id) {
    if (championIdMap[id]) {
        return championIdMap[id];
    } else {
        return "Unknown Champion";
    }
}
function getChampionIconUrlById(id) {
    const version = "14.8.1"; // You can update this as needed
    const name = getChampionNameById(id).replace(/\s/g, '');

    return `https://ddragon.leagueoflegends.com/cdn/${version}/img/champion/${name}.png`;
}



function createMasteryCardElement() {

    champion = {
        "championId": 901,
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
    championIcon.setAttribute("src", getChampionIconUrlById(champion.championId));
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

    championElement.textContent = getChampionNameById(champion.championId) //Should be name
    championLevel.textContent = "Level " + champion.championLevel
    championPoints.textContent = addComma(champion.championPoints) + " Points"
    sinceLevelUp.textContent = addComma(champion.championPointsSinceLastLevel) + " since last level"
    toLevelUp.textContent = addComma(champion.championPointsUntilNextLevel) + " until next level"

    championIcon.alt = "Champion Icon"

    const date = new Date(champion.lastPlayTime);
    const formattedDate = date.toLocaleString();
    timeSincePlay.textContent = " Last Played: " + formattedDate

    masteryMainElement.append(championIcon)
    children = [championLevel, championPoints, sinceLevelUp, toLevelUp, timeSincePlay];

    masteryInfo.append(...children);
    for (i = 0; i++; 0 < children.length) {
        masteryInfo.append(children[i])
    }

    masteryCardElement.append(championElement)
    masteryCardElement.append(masteryMainElement)
    masteryMainElement.append(masteryInfo)



    parent = document.getElementsByClassName('personal')[0];
    console.log(parent)
    parent.append(masteryCardElement);
}

