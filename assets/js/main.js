(function () {
  var appBase = "";
  if (typeof APP_BASE !== "undefined") {
    appBase = APP_BASE;
  }

  function bindScrollTop() {
    var btn = document.getElementById("scrollTopBtn");
    if (!btn) {
      return;
    }

    window.addEventListener("scroll", function () {
      if (window.scrollY > 250) {
        btn.style.display = "block";
      } else {
        btn.style.display = "none";
      }
    });

    btn.addEventListener("click", function () {
      window.scrollTo({ top: 0, behavior: "smooth" });
    });
  }

  function bindGlobalSearch() {
    var input = $("#globalSearch");
    var box = $("#searchSuggestionBox");

    if (!input.length) {
      return;
    }

    input.on("keyup", function () {
      var q = $.trim($(this).val());

      if (q.length < 1) {
        box.addClass("d-none").html("");
        return;
      }

      $.ajax({
        url: appBase + "/backend/search_suggestions.php",
        method: "GET",
        data: { q: q },
        dataType: "json",
        success: function (resp) {
          var html = "";
          var i;

          if (resp && resp.length > 0) {
            for (i = 0; i < resp.length; i++) {
              html +=
                '<div class="suggestion-item" data-slug="' +
                resp[i].slug +
                '">' +
                resp[i].name +
                " - $" +
                resp[i].price +
                "</div>";
            }
            box.removeClass("d-none").html(html);
          } else {
            box
              .removeClass("d-none")
              .html('<div class="suggestion-item">No matches found</div>');
          }
        },
        error: function () {
          box.addClass("d-none").html("");
        },
      });
    });

    $(document).on("click", ".suggestion-item", function () {
      var slug = $(this).attr("data-slug");
      if (slug) {
        window.location.href = appBase + "/product/" + slug;
      }
    });

    $(document).on("click", function (e) {
      if (!$(e.target).closest(".search-wrap").length) {
        box.addClass("d-none");
      }
    });
  }

  function animateFlyToCart(imgEl) {
    var cartBadge = document.getElementById("cartCountBadge");
    if (!imgEl || !cartBadge) {
      return;
    }

    var imgRect = imgEl.getBoundingClientRect();
    var cartRect = cartBadge.getBoundingClientRect();
    var clone = imgEl.cloneNode(true);

    clone.classList.add("fly-clone");
    clone.style.left = imgRect.left + "px";
    clone.style.top = imgRect.top + "px";
    document.body.appendChild(clone);

    var deltaX = cartRect.left - imgRect.left;
    var deltaY = cartRect.top - imgRect.top;

    clone.animate(
      [
        { transform: "translate(0px, 0px) scale(1)", opacity: 1 },
        {
          transform:
            "translate(" + deltaX + "px, " + deltaY + "px) scale(0.25)",
          opacity: 0.35,
        },
      ],
      {
        duration: 700,
        easing: "ease-in-out",
        fill: "forwards",
      },
    );

    setTimeout(function () {
      clone.remove();
    }, 720);
  }

  function bindAddToCart() {
    $(document).on("click", ".add-to-cart-btn", function (e) {
      e.preventDefault();

      var btn = $(this);
      var productId = btn.attr("data-id");
      var productCard = btn.closest(".product-card");
      var imgEl = productCard.find("img").get(0);

      $.ajax({
        url: appBase + "/backend/cart_api.php",
        method: "POST",
        data: { product_id: productId, qty: 1 },
        dataType: "json",
        success: function (resp) {
          if (resp && resp.status === "ok") {
            animateFlyToCart(imgEl);
            $("#cartCountBadge").text(resp.cart_count);
          } else {
            alert("Could not add product to cart.");
          }
        },
        error: function () {
          alert("Could not add product to cart.");
        },
      });
    });
  }

  function bindBuyNow() {
    $(document).on("click", ".buy-now-btn", function (e) {
      e.preventDefault();
      var id = $(this).attr("data-id");

      $.ajax({
        url: appBase + "/backend/cart_api.php",
        method: "POST",
        data: { product_id: id, qty: 1 },
        dataType: "json",
        success: function () {
          window.location.href = appBase + "/cart";
        },
        error: function () {
          window.location.href = appBase + "/cart";
        },
      });
    });
  }

  bindScrollTop();
  bindGlobalSearch();
  bindAddToCart();
  bindBuyNow();
})();
