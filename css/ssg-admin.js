var ssgSDzero = false;
var ssgSubzero = false;
var ssgUpzero = false;

function ssgZeroReset(el) {
  if (el.id == "watermarkOffsetX" || el.id == "watermarkOffsetY") {
    if (el.value < 0 && el.value >= 0 - el.step && !ssgSubzero) {
      el.step = 0.5;
      el.value = -0.5;
      ssgSubzero = true;
      ssgUpzero = false;
    } else if (el.value > 0 && el.value <= el.step && !ssgUpzero) {
      el.step = 0.1;
      el.value = 0.1;
      ssgSubzero = false;
      ssgUpzero = true;
    }

    if (el.value < 0) {
      el.step = 0.5;
      el.min = -1111;
    } else if (el.value > 0) {
      el.step = 0.1;
      el.max = 1111;
    }
  } else {
    if (el.value < el.step) {
      if (el.id == "preferedCaptionLocation") {
        el.min = -1111;
      } else if (el.id == "scrollDuration") {
        el.min = 0;
        if (!ssgSDzero) el.value = 0;
        ssgSDzero = true;
      } else {
        el.min = 0;
        el.value = 0;
      }
    }
  }
}

function SSGsetColor(id, butt) {
  if (butt.innerText == "use preset") {
    document.getElementById(id).type = "text";
    document.getElementById(id).disabled = true;
    document.getElementById(id).value = "";
    document.getElementById(id).placeholder = "Preset";
    butt.innerText = "set color";
  } else {
    document.getElementById(id).type = "color";
    document.getElementById(id).disabled = false;
    document.getElementById(id).value = "#BBFF3D";
    butt.innerText = "use preset";
  }
}
