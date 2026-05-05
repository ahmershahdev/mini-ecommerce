(function () {
  function setStrength(input, bar, label) {
    if (!input || !bar || !label) {
      return;
    }

    input.addEventListener("input", function () {
      var val = input.value;
      var score = 0;

      if (val.length >= 8) {
        score += 1;
      }
      if (val.length >= 12) {
        score += 1;
      }
      if (/[A-Z]/.test(val)) {
        score += 1;
      }
      if (/[0-9]/.test(val)) {
        score += 1;
      }
      if (/[^A-Za-z0-9]/.test(val)) {
        score += 1;
      }

      var percent = score * 20;
      if (percent > 100) {
        percent = 100;
      }

      bar.style.width = percent + "%";
      if (percent <= 40) {
        bar.className = "strength-bar weak";
        label.textContent = "Strength: Weak";
      } else if (percent <= 70) {
        bar.className = "strength-bar mid";
        label.textContent = "Strength: Medium";
      } else {
        bar.className = "strength-bar strong";
        label.textContent = "Strength: Strong";
      }
    });
  }

  function bindToggleButtons() {
    var buttons = document.getElementsByClassName("toggle-pass");
    var i;

    for (i = 0; i < buttons.length; i++) {
      buttons[i].addEventListener("click", function () {
        var targetId = this.getAttribute("data-target");
        var input = document.getElementById(targetId);
        if (!input) {
          return;
        }

        if (input.type === "password") {
          input.type = "text";
          this.textContent = "Hide";
        } else {
          input.type = "password";
          this.textContent = "Show";
        }
      });
    }
  }

  function bindSubmitDelay() {
    var forms = document.getElementsByClassName("auth-form");
    var i;

    for (i = 0; i < forms.length; i++) {
      forms[i].addEventListener("submit", function () {
        var btn = this.querySelector(".auth-submit");
        if (!btn) {
          return;
        }

        var defaultText = btn.getAttribute("data-default-text");
        if (!defaultText) {
          defaultText = btn.textContent;
        }

        btn.disabled = true;
        btn.textContent = "Please wait...";

        setTimeout(function () {
          btn.disabled = false;
          btn.textContent = defaultText;
        }, 3000);
      });
    }
  }

  setStrength(
    document.getElementById("loginPassword"),
    document.getElementById("loginStrengthBar"),
    document.getElementById("loginStrengthLabel"),
  );

  setStrength(
    document.getElementById("signupPassword"),
    document.getElementById("signupStrengthBar"),
    document.getElementById("signupStrengthLabel"),
  );

  bindToggleButtons();
  bindSubmitDelay();
})();
