let dispos = [];

document.getElementById("ajouter").addEventListener("click", function () {
  const jour = document.getElementById("jour").value;
  const heure = document.getElementById("heure").value;
  const minute = document.getElementById("minute").value;

  if (!jour || !heure || minute === "") {
    alert("Sélectionnez un jour, une heure et une minute");
    return;
  }

  const div = document.createElement("div");
  div.className = "dispo";

  const span = document.createElement("span");
  span.textContent = `${jour} à ${heure}h${String(minute).padStart(2, "0")}`;
  div.appendChild(span);

  const button = document.createElement("button");
  button.textContent = "x";
  button.className = "remove";
  button.addEventListener("click", function() {
    dispos = dispos.filter(d => !(d.jour === jour && d.heure === heure && d.minute === minute));
    div.remove();
    document.getElementById("dispo-hidden").value = JSON.stringify(dispos);
  });
  div.appendChild(button);

  document.getElementById("dispo-list").appendChild(div);

  // Ajoute la dispo dans le tableau
  dispos.push({ jour, heure, minute });
  document.getElementById("dispo-hidden").value = JSON.stringify(dispos);
});
