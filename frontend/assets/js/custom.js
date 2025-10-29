$(document).ready(function () {
  $("main#spapp > section").height($(document).height() - 60);

  var app = $.spapp({ pageNotFound: "error_404" }); // initialize

  // define routes
  app.route({
    view: "view_1",
    onCreate: function () {
      $("#view_1").append($.now() + ": Written on create<br/>");
    },
    onReady: function () {
      $("#view_1").append($.now() + ": Written when ready<br/>");
    },
  });
  app.route({ view: "view_2", load: "view_2.html" });
  app.route({
    view: "view_3",
    onCreate: function () {
      $("#view_3").append("I'm the third view");
    },
  });
  app.route({
    view: "home",
    load: "home.html",
  });
  app.route({
    view: "squad",
    load: "squad.html",
  });
  app.route({
    view: "matches",
    load: "matches.html",
  });
  app.route({
    view: "shop",
    load: "shop.html",
  });
  app.route({
    view: "cart",
    load: "cart.html",
  });
  app.route({
    view: "login",
    load: "login.html",
  });
  app.route({
    view: "contact",
    load: "contact.html",
  });

  // run app
  app.run();
});
