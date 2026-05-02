$(document).ready(function () {
  var searchTimer = null;
  var brandTicker = $("#brandTicker");
  var brandNames = [
    "Mini Ecommerce",
    "Mini Ecommerce Store",
    "Mini Ecommerce Shop",
  ];
  var brandIndex = 0;

  if (brandTicker.length) {
    setInterval(function () {
      brandIndex = brandIndex + 1;
      if (brandIndex >= brandNames.length) {
        brandIndex = 0;
      }
      brandTicker.fadeOut(180, function () {
        brandTicker.text(brandNames[brandIndex]).fadeIn(180);
      });
    }, 2200);
  }

  $("#productSearchInput").on("keyup", function () {
    var q = $(this).val();

    if (searchTimer) {
      clearTimeout(searchTimer);
    }

    if (q.length < 1) {
      $("#searchSuggestionBox").addClass("d-none").html("");
      return;
    }

    searchTimer = setTimeout(function () {
      $.ajax({
        url: "/mini_ecommerce_project/backend/products/search.php",
        type: "GET",
        data: { q: q },
        dataType: "json",
        success: function (res) {
          var html = "";
          var i = 0;

          if (res.status === "ok" && res.items.length > 0) {
            for (i = 0; i < res.items.length; i++) {
              html +=
                '<a class="search-item" href="/mini_ecommerce_project/product/' +
                res.items[i].slug +
                '">' +
                res.items[i].name +
                "</a>";
            }
            $("#searchSuggestionBox").html(html).removeClass("d-none");
          } else {
            $("#searchSuggestionBox")
              .html('<div class="search-item">No product found</div>')
              .removeClass("d-none");
          }
        },
      });
    }, 220);
  });

  $(document).on("click", function (e) {
    if (
      !$(e.target).closest("#productSearchInput, #searchSuggestionBox").length
    ) {
      $("#searchSuggestionBox").addClass("d-none");
    }
  });

  $(window).on("scroll", function () {
    if ($(this).scrollTop() > 240) {
      $("#scrollTopBtn").fadeIn(200);
    } else {
      $("#scrollTopBtn").fadeOut(200);
    }
  });

  $("#scrollTopBtn").on("click", function () {
    $("html, body").animate({ scrollTop: 0 }, 450);
  });

  $(document).on("click", ".add-to-cart-btn", function (e) {
    e.preventDefault();

    var btn = $(this);
    var productId = btn.attr("data-product-id");
    var imageEl = btn.closest(".product-card").find("img")[0];
    var cartBadgeEl = $("#cartBadge")[0];

    if (imageEl && cartBadgeEl) {
      var start = imageEl.getBoundingClientRect();
      var end = cartBadgeEl.getBoundingClientRect();

      var fly = $("<img>");
      fly.attr("src", imageEl.src);
      fly.addClass("fly-image");
      fly.css({
        top: start.top + window.scrollY + "px",
        left: start.left + window.scrollX + "px",
      });

      $("body").append(fly);

      fly.animate(
        {
          top: end.top + window.scrollY + "px",
          left: end.left + window.scrollX + "px",
          width: "22px",
          height: "22px",
          opacity: 0.5,
        },
        600,
        function () {
          fly.remove();
        },
      );
    }

    $.ajax({
      url: "/mini_ecommerce_project/backend/cart/add-to-cart.php",
      type: "POST",
      data: { product_id: productId, qty: 1 },
      dataType: "json",
      success: function (res) {
        if (res.status === "ok") {
          $("#cartBadge").text(res.cart_count);
        }
      },
    });
  });
});
