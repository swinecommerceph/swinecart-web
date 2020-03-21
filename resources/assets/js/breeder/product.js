"use strict";

var product = {
  before_select_value: "",
  current_display_photo: 0,
  modal_history: [],
  modal_history_tos: function() {
    return product.modal_history[product.modal_history.length - 1];
  },

  add: function(parent_form) {
    // Attach overlay preloader

    $(
      '<div id="overlay-preloader-circular" class="valign-wrapper" style="padding:7rem;">' +
        '<div class="center-align preloader-overlay">' +
        '<div class="preloader-wrapper big active">' +
        '<div class="spinner-layer spinner-blue-only">' +
        '<div class="circle-clipper left">' +
        '<div class="circle"></div>' +
        '</div><div class="gap-patch">' +
        '<div class="circle"></div>' +
        '</div><div class="circle-clipper right">' +
        '<div class="circle"></div>' +
        "</div>" +
        "</div>" +
        "</div>" +
        "</div>" +
        "</div>"
    )
      .css({
        position: "absolute",
        width: "100%",
        height: "100%",
        top: $("#add-product-modal .modal-content").scrollTop(),
        left: 0,
        background: "rgba(255,255,255,0.8)",
        display: "block"
      })
      .appendTo(
        $("#add-product-modal .modal-content").css("position", "relative")
      );

    // Let the overlay preloader change its top position every scroll
    $("#add-product-modal .modal-content").scroll(function() {
      $("#overlay-preloader-circular").css({ top: $(this).scrollTop() });
    });

    var data_values = {
      name: parent_form.find("input[name=name]").val(),
      type: parent_form.find("#select-type").val(),
      //"price": parent_form.find('input[name=price]').val(),

      min_price: parent_form.find("input[name=min_price]").val(),
      max_price: parent_form.find("input[name=max_price]").val(),

      farm_from_id: parent_form.find("#select-farm").val(),
      birthdate: parent_form.find("input[name=birthdate]").val(),
      birthweight: parent_form.find("input[name=birthweight]").val(),
      house_type: parent_form.find("#select-housetype").val(),
      adg: parent_form.find("input[name=adg]").val(),
      fcr: parent_form.find("input[name=fcr]").val(),
      backfat_thickness: parent_form
        .find("input[name=backfat_thickness]")
        .val(),
      lsba: parent_form.find("input[name=lsba]").val(),
      left_teats: parent_form.find("input[name=left_teats]").val(),
      right_teats: parent_form.find("input[name=right_teats]").val(),
      other_details: $("#other_details").val(),
      quantity: $(".product-quantity").val(),
      _token: parent_form.find("input[name=_token]").val()
    };

    /* Check if the checkbox for product uniqueness is checked or not */
    if ($(".product-unique-checker").is(":checked"))
      data_values["is_unique"] = 1;
    else data_values["is_unique"] = 0;

    /* Set proper values for semen type */
    var select_type_value = $("#select-type option:selected").text();
    if (select_type_value === "Semen") {
      data_values["is_unique"] = 0;
      data_values.quantity = -1;
    }

    data_values.min_price = data_values.min_price.replace(",", ""); // remove comma in price before storing
    data_values.max_price = data_values.max_price.replace(",", ""); // remove comma in price before storing

    // Transform breed syntax if crossbreed
    if ($("#create-product input:checked").val() === "crossbreed") {
      var fbreed = parent_form.find("input[name=fbreed]").val();
      var mbreed = parent_form.find("input[name=mbreed]").val();

      data_values["breed"] =
        fbreed.toLowerCase().trim() + "+" + mbreed.toLowerCase().trim();
      console.log(
        fbreed.toLowerCase().trim() + "+" + mbreed.toLowerCase().trim()
      );
    } else
      data_values["breed"] = parent_form
        .find("input[name=breed]")
        .val()
        .toLowerCase()
        .trim();

    // Do AJAX
    $.ajax({
      url: parent_form.attr("action"),
      type: "POST",
      cache: false,
      data: data_values,
      success: function(data) {
        var data = JSON.parse(data);
        var hidden_inputs =
          '<input name="productId" type="hidden" value="' +
          data.product_id +
          '">' +
          '<input name="name" type="hidden" value="' +
          data.name +
          '">' +
          '<input name="type" type="hidden" value="' +
          data.type +
          '">' +
          '<input name="breed" type="hidden" value="' +
          data.breed +
          '">';

        Materialize.toast("Product added!", 2500, "green lighten-1");
        //location.href = location.origin + '/breeder/products'; // redirect to Show Products page

        $("#media-dropzone").append(hidden_inputs);
        $("#add-media-modal h4").append(" to " + "'" + data.name + "'");
        $(".add-product-button").attr("href", "#add-media-modal");
        $("#overlay-preloader-circular").remove();
        $("#add-product-modal").modal("close");
        parent_form.find("#submit-button").removeClass("disabled");

        $("#submit-button").removeClass("disabled");
        $("#submit-button").html("Add Product");

        // Open Add Media Modal
        $("#add-media-modal").modal("open");
        product.modal_history.push("#add-media-modal");
      },
      error: function(message) {
        console.log(message["responseText"]);
        $("#overlay-preloader-circular").remove();
      }
    });
  },

  edit: function(parent_form, update_button) {
    var data_values = {
      id: parent_form.find("input[name=productId]").val(),
      name: parent_form.find("input[name='edit-name']").val(),
      type: parent_form.find("#edit-select-type").val(),
      farm_from_id: parent_form.find("#edit-select-farm").val(),
      birthdate: parent_form.find("input[name='edit-birthdate']").val(),
      price: parent_form.find("input[name='edit-price']").val(),
      adg: parent_form.find("input[name='edit-adg']").val(),
      fcr: parent_form.find("input[name='edit-fcr']").val(),
      backfat_thickness: parent_form
        .find("input[name='edit-backfat_thickness']")
        .val(),
      _token: parent_form.find("input[name=_token]").val()
    };

    data_values.price = data_values.price.replace(",", "");

    // Transform breed syntax if crossbreed
    if ($("#edit-product input:checked").val() === "crossbreed") {
      var fbreed = parent_form.find("input[name='edit-fbreed']").val();
      var mbreed = parent_form.find("input[name='edit-mbreed']").val();

      data_values["breed"] =
        fbreed.toLowerCase().trim() + "+" + mbreed.toLowerCase().trim();
    } else
      data_values["breed"] = parent_form
        .find("input[name='edit-breed']")
        .val()
        .toLowerCase()
        .trim();

    $.when(
      // Wait for the update on the database
      // Do AJAX
      $.ajax({
        url: parent_form.attr("action"),
        type: "PUT",
        cache: false,
        data: data_values,
        success: function(data) {
          Materialize.toast("Product updated!", 1500, "green lighten-1");
          // $('#edit-product-modal').modal('close');
        },
        error: function(message) {
          console.log(message["responseText"]);
        }
      })
    ).done(function() {
      // Enable update-button
      update_button.removeClass("disabled");
      update_button.html("Update Product");

      // Then get the product summary
      //product.modal_history.push('#edit-product-modal');
      product.get_summary(
        $("#edit-product")
          .find('input[name="productId"]')
          .val()
      );
    });
  },

  delete_selected: function(parent_form, products, products_container) {
    // Check if there are checked products
    if (products.length > 0) {
      // Acknowledge first confirmation to remove
      $("#confirmation-modal").modal("open");
      $("#confirm-remove").click(function(e) {
        e.preventDefault();

        config.preloader_progress.fadeIn();
        // Do AJAX
        $.ajax({
          url: config.manageSelected_url,
          type: "DELETE",
          cache: false,
          data: {
            _token: parent_form.find("input[name=_token]").val(),
            product_ids: products
          },
          success: function(data) {
            products.forEach(function(element) {
              $("#product-" + element).remove();
            });
            config.preloader_progress.fadeOut();
            Materialize.toast(
              "Selected Products deleted!",
              2000,
              "green lighten-1"
            );

            if (products_container.children().length == 0) {
              products_container.append(`
                                <table>
                                    <tr>
                                        <td>
                                            <h5 class="center">There are no products</h5>
                                        </td>
                                    </tr>
                                </table>
                            `);
            }
          },
          error: function(message) {
            console.log(message["responseText"]);
          }
        });
      });
    } else Materialize.toast("No products chosen!", 1500, "orange accent-2");
  },

  get_product: function(product_id) {
    // Do AJAX
    $.ajax({
      url: config.productSummary_url,
      type: "GET",
      cache: false,
      data: {
        product_id: product_id
      },
      success: function(data) {
        var data = JSON.parse(data);
        var parent_form = $("#edit-product");
        var images = data.imageCollection;
        var videos = data.videoCollection;
        var image_list = "";
        var video_list = "";

        var hidden_inputs =
          '<input name="productId" type="hidden" value="' +
          data.id +
          '">' +
          '<input name="name" type="hidden" value="' +
          data.name +
          '">' +
          '<input name="type" type="hidden" value="' +
          data.type +
          '">' +
          '<input name="breed" type="hidden" value="' +
          data.breed +
          '">';

        $(parent_form).append(
          '<input name="productId" type="hidden" value="' + data.id + '">'
        );
        $("#edit-media-dropzone").append(hidden_inputs);
        $("#edit-media-modal h4").html(
          "Edit Media of " + "'" + data.name + "'"
        );

        // General input initialization
        parent_form.find("input[name='edit-name']").val(data.name);
        parent_form.find("label[for='edit-name']").addClass("active");
        parent_form.find("input[name='edit-price']").val(data.price);
        parent_form.find("label[for='edit-price']").addClass("active");
        parent_form.find("input[name='edit-birthdate']").val(data.birthdate);
        parent_form.find("label[for='edit-birthdate']").addClass("active");
        parent_form.find("input[name='edit-adg']").val(data.adg);
        parent_form.find("label[for='edit-adg']").addClass("active");
        parent_form.find("input[name='edit-fcr']").val(data.fcr);
        parent_form.find("label[for='edit-fcr']").addClass("active");
        parent_form
          .find("input[name='edit-backfat_thickness']")
          .val(data.backfat_thickness);
        parent_form
          .find("label[for='edit-backfat_thickness']")
          .addClass("active");

        // For select initializations
        $("#edit-select-type").val(data.type.toLowerCase());
        $("#edit-select-farm").val(data.farm_from_id);
        $("select").material_select();

        // For the breed initialization
        if (data.breed.includes("x")) {
          var crossbreed = data.breed.split("x");

          // Check the crossbreed radio
          $("#edit-crossbreed").prop("checked", true);

          parent_form
            .find("input[name='edit-fbreed']")
            .val(crossbreed[0].toString().trim());
          parent_form.find("label[for='edit-fbreed']").addClass("active");
          parent_form
            .find("input[name='edit-mbreed']")
            .val(crossbreed[1].toString().trim());
          parent_form.find("label[for='edit-mbreed']").addClass("active");
          parent_form.find(".input-purebreed-container").hide();
          parent_form.find(".input-crossbreed-container").fadeIn(300);
        } else {
          // Check the crossbreed radio
          $("#edit-purebreed").prop("checked", true);

          parent_form.find("input[name='edit-breed']").val(data.breed);
          parent_form.find("label[for='edit-breed']").addClass("active");
          parent_form.find(".input-crossbreed-container").hide();
          parent_form.find(".input-purebreed-container").fadeIn(300);
        }

        // Other Details
        if (data.other_details) {
          // Set-up value of current_modal_id
          product.modal_history.push("#edit-product-modal");

          // Set-up Images in Edit Media Modal
          var images_length = images.length;
          if (images_length === 0) {
            console.log("here video");
            $(".edit-image-contents").html(
              '<p class="grey-text">(No uploaded images)</p>'
            );
          } else {
            images.forEach(function(element) {
              var anchor_tag_html = "Set";
              var delete_anchor_tag_html = "Delete";
              var cursor_none_prop = '"';

              // Change html value of set-display-photo anchor tag if image is the display photo
              if (element.id == data.primary_img_id) {
                product.current_display_photo = element.id;
                anchor_tag_html = "Displayed";
                cursor_none_prop = 'cursor: none;"';
              }

              image_list +=
                '<div class="col s12 m6">' +
                '<div class="card hoverable">' +
                '<div class="card-image white">' +
                '<img src="' +
                config.productImages_path +
                "/" +
                element.name +
                '">' +
                "</div>" +
                '<div class="card-action grey lighten-5" style="border-top: none !important;">' +
                "<div class=row>" +
                '<div class="col s4 m6 l3">' +
                '<a href="#!" id="display-photo" style="font-weight: 700; width: 11vw !important; ' +
                cursor_none_prop +
                'class="set-display-photo btn blue lighten-1" data-product-id="' +
                data.id +
                '" data-img-id="' +
                element.id +
                '">' +
                anchor_tag_html +
                "</a>" +
                "</div>" +
                '<div class="col s3"></div>' +
                '<div class="col s4 m6 l3">' +
                '<a href="#!" style="font-weight: 700; width: 10vw !important;" class="delete-image btn-flat grey-text text-darken-2 grey lighten-5" data-media-id="' +
                element.id +
                '">' +
                delete_anchor_tag_html +
                "</a>" +
                "</div>" +
                "</div>" +
                "</div>" +
                "</div>" +
                "</div>";
            });

            $("#edit-images-summary .card-content .edit-image-contents").html(
              image_list
            );
          }

          // Set-up Videos in Edit Media Modal
          var videos_length = videos.length;
          if (videos_length === 0) {
            console.log("here video");
            $(".edit-video-contents").html(
              '<p class="grey-text">(No uploaded videos)</p>'
            );
          } else {
            videos.forEach(function(element) {
              video_list +=
                '<div class="col s12 m6">' +
                '<div class="card hoverable">' +
                '<div class="card-image">' +
                '<video class="responsive-video" controls>' +
                '<source src="' +
                config.productVideos_path +
                "/" +
                element.name +
                '" type="video/mp4">' +
                "</video>" +
                "</div>" +
                '<div class="card-action grey lighten-5" style="border-top: none !important;">' +
                "<a></a>" +
                '<a href="#!" style="font-weight: 700; float: right !important;" class="delete-video grey-text text-darken-2 grey lighten-5" data-media-id="' +
                element.id +
                '">Delete</a>' +
                "</div>" +
                "</div>" +
                "</div>";
            });

            $("#edit-videos-summary .card-content .edit-video-contents").html(
              video_list
            );
          }
        }
      },
      error: function(message) {
        console.log(message["responseText"]);
      }
    });
  },

  get_summary: function(product_id) {
    $(product.modal_history_tos()).modal("close");

    // Set-up first modal action buttons depending
    // on what modal it came from

    if (product.modal_history_tos() === "#add-product-modal") {
      $(".from-add-process").show();
      $(".from-edit-process").hide();
    } else {
      $(".from-add-process").hide();
      $(".from-edit-process").show();
    }

    $("#product-summary-modal").modal({ dismissible: false });
    $("#product-summary-modal").modal("open");
    product.modal_history.push("#product-summary-modal");

    // Attach overlay preloader
    $(
      '<div id="overlay-preloader-circular" class="valign-wrapper" style="padding:7rem;">' +
        '<div class="center-align preloader-overlay">' +
        '<div class="preloader-wrapper big active">' +
        '<div class="spinner-layer spinner-blue-only">' +
        '<div class="circle-clipper left">' +
        '<div class="circle"></div>' +
        '</div><div class="gap-patch">' +
        '<div class="circle"></div>' +
        '</div><div class="circle-clipper right">' +
        '<div class="circle"></div>' +
        "</div>" +
        "</div>" +
        "</div>" +
        "</div>" +
        "</div>"
    )
      .css({
        position: "absolute",
        width: "100%",
        height: "100%",
        top: $("#add-product-modal .modal-content").scrollTop(),
        left: 0,
        background: "rgba(255,255,255,0.8)",
        display: "block"
      })
      .appendTo(
        $("#product-summary-modal .modal-content").css("position", "relative")
      );

    // Let the overlay preloader change its top position every scroll
    $("#product-summary-modal .modal-content").scroll(function() {
      $("#overlay-preloader-circular").css({ top: $(this).scrollTop() });
    });

    // Do AJAX
    $.ajax({
      url: config.productSummary_url,
      type: "GET",
      cache: false,
      data: {
        product_id: product_id
      },
      success: function(data) {
        var data = JSON.parse(data);
        var images = data.imageCollection;
        var videos = data.videoCollection;

        // General Info
        /* Catching the unfilled input fields */

        // ADG
        if (data.adg === null) {
          var item_adg =
            '<li class="collection-item">Average Daily Gain: <i class="grey-text">Not indicated</i></li>';
        } else {
          var item_adg =
            '<li class="collection-item">Average Daily Gain: ' +
            data.adg +
            " g</li>";
        }

        // FCR
        if (data.fcr === null) {
          var item_fcr =
            '<li class="collection-item">Feed Conversion Ratio: <i class="grey-text">Not indicated</i></li>';
        } else {
          var item_fcr =
            '<li class="collection-item">Feed Conversion Ratio: ' +
            data.fcr +
            "</li>";
        }

        // Backfat Thickness
        if (data.backfat_thickness === null) {
          var item_backfat_thickness =
            '<li class="collection-item">Backfat Thickness: <i class="grey-text">Not indicated</i></li>';
        } else {
          var item_backfat_thickness =
            '<li class="collection-item">Backfat Thickness: ' +
            data.backfat_thickness +
            "mm </li>";
        }

        // Backfat Thickness
        if (data.lsba === null) {
          var item_lsba =
            '<li class="collection-item">Litter size born alive: <i class="grey-text">Not indicated</i></li>';
        } else {
          var item_lsba =
            '<li class="collection-item">Litter size born alive: ' +
            data.lsba +
            "</li>";
        }

        // Birthweight
        if (data.birthweight === null) {
          var item_birthweight =
            '<li class="collection-item">Birth weight: <i class="grey-text">Not indicated</i></li>';
        } else {
          var item_birthweight =
            '<li class="collection-item">Birth weight: ' +
            data.birthweight +
            "g </li>";
        }

        // Number of teats only for Gilt and Sow
        if (data.type === "Gilt" || data.type === "Sow") {
          // number of teats
          if (data.left_teats === null || data.right_teats === null) {
            var item_number_of_teats =
              '<li class="collection-item">Number of teats <i class="grey-text">Not indicated</i></li>';
          } else {
            var item_number_of_teats =
              '<li class="collection-item">Number of teats: ' +
              data.left_teats +
              "(left) | " +
              data.right_teats +
              " (right)" +
              "</li>";
          }
        } else var item_number_of_teats = "";

        // House Type
        if (data.house_type === null) {
          var item_house_type =
            '<li class="collection-item">House Type: <i class="grey-text">Not indicated</i></li>';
        } else {
          var house_type_string;
          if (data.house_type === "tunnelventilated")
            house_type_string = "Tunnel ventilated";
          else house_type_string = "Open sided";

          var item_house_type =
            '<li class="collection-item">House Type: ' +
            house_type_string +
            "</li>";
        }

        var items =
          item_adg +
          item_fcr +
          item_backfat_thickness +
          item_lsba +
          item_birthweight +
          item_number_of_teats +
          item_house_type;
        var image_list = "";
        var video_list = "";

        var images_length = images.length;
        if (images_length === 0) {
          $(".image-contents").html(
            '<p class="grey-text">(No uploaded images)</p>'
          );
        } else {
          // Images
          images.forEach(function(element) {
            var anchor_tag_html = "Set";
            var delete_anchor_tag_html = "Delete";
            var cursor_none_prop = '"';

            // Change html value of set-display-photo anchor tag if image is the display photo
            if (element.id == data.primary_img_id) {
              product.current_display_photo = element.id;
              anchor_tag_html = "Displayed";
              cursor_none_prop = 'cursor: none;"';
            }

            image_list +=
              '<div class="col s12 m6">' +
              '<div class="card hoverable">' +
              '<div class="card-image">' +
              '<img src="' +
              config.productImages_path +
              "/" +
              element.name +
              '">' +
              "</div>" +
              '<div class="card-action grey lighten-5" style="border-top: none !important;">' +
              "<div class=row>" +
              '<div class="col s4 m6 l3">' +
              '<a href="#!" id="display-photo" style="font-weight: 700; width: 11vw !important; ' +
              cursor_none_prop +
              'class="set-display-photo btn blue lighten-1" data-product-id="' +
              data.id +
              '" data-img-id="' +
              element.id +
              '">' +
              anchor_tag_html +
              "</a>" +
              "</div>" +
              '<div class="col s3"></div>' +
              '<div class="col s4 m6 l3">' +
              '<a href="#!" style="font-weight: 700; width: 10vw !important;" class="delete-image btn-flat grey-text text-darken-2 grey lighten-5" data-media-id="' +
              element.id +
              '">' +
              delete_anchor_tag_html +
              "</a>" +
              "</div>" +
              "</div>" +
              "</div>" +
              "</div>" +
              "</div>";
          });

          $("#images-summary .card-content .image-contents").html(image_list);
        }

        // Videos
        var videos_length = videos.length;
        if (videos_length === 0) {
          $(".video-contents").html(
            '<p class="grey-text">(No uploaded videos)</p>'
          );
        } else {
          videos.forEach(function(element) {
            video_list +=
              '<div class="col s12 m6">' +
              '<video class="responsive-video hoverable" controls>' +
              '<source src="' +
              config.productVideos_path +
              "/" +
              element.name +
              '" type="video/mp4">' +
              "</video>" +
              "</div>";
          });

          $("#videos-summary .card-content .video-contents").html(video_list);
        }

        $("#product-summary-collection h3").html(data.name);

        var item_type = data.type + " - " + data.breed;
        $("#product-summary-collection h5").html(item_type);

        $("#product-summary-province").html(
          "Farm Address: " + data.farm_province
        );

        if (data.birthdate === "November 30, -0001") {
          var item_birthdate = '<i class="grey-text">No age information</i>';
        } else {
          var item_birthdate = "Birthdate: " + data.birthdate;
        }
        $("#product-summary-birthdate").html(item_birthdate);

        $("#swine-information").html(items);
        if (data.other_details === null) {
          var other_details_data = '<i class="grey-text">Not indicated</i>';
        } else {
          var other_details_data = "<p>" + data.other_details + "</p>";
        }
        $("#other-information").html(other_details_data);

        $("#display-product-form").prepend(
          '<input name="productId" type="hidden" value="' + data.id + '">'
        );
        $("#overlay-preloader-circular").remove();
      },
      error: function(message) {
        console.log(message["responseText"]);
      }
    });
  },

  set_display_photo: function(anchor_tag, parent_form, product_id, img_id) {
    // Disable the Display photo anchor tag
    anchor_tag.addClass("disabled");
    anchor_tag.html("Setting ...");

    // Do AJAX
    $.ajax({
      url: parent_form.attr("action"),
      type: "POST",
      cache: false,
      data: {
        _token: parent_form.find("input[name=_token]").val(),
        product_id: product_id,
        img_id: img_id
      },
      success: function(data) {
        // Overwrite the old display photo's anchor description
        parent_form
          .find(
            '.set-display-photo[data-img-id="' +
              product.current_display_photo +
              '"]'
          )
          .css("cursor", "default")
          .html("Set");

        // New Display Photo id
        product.current_display_photo = img_id;
        anchor_tag.removeClass("disabled");
        anchor_tag.css("cursor", "none");
        anchor_tag.html("Displayed");
      },
      error: function(message) {
        console.log(message["responseText"]);
      }
    });
  },

  display_product: function(parent_form) {
    // Do AJAX
    $.ajax({
      url: parent_form.attr("action"),
      type: "POST",
      cache: false,
      data: {
        _token: document.querySelector('meta[name="csrf-token"]').content,
        product_id: parent_form.find("input[name=productId]").val()
      },
      success: function(data) {
        window.setTimeout(function() {
          location.reload(true);
        }, 1200);
      },
      error: function(message) {
        console.log(message["responseText"]);
      }
    });
  },

  update_selected: function(parent_form, update_button, products, status) {
    // Check if there are checked products
    if (products.length > 0) {
      config.preloader_progress.fadeIn();
      // Do AJAX
      $.ajax({
        url: parent_form.attr("action"),
        type: "POST",
        cache: false,
        data: {
          _token: parent_form.find("input[name=_token]").val(),
          product_ids: products,
          updateTo_status: status
        },
        success: function(data) {
          var filter_status = $("#status-select option:selected").val();

          // Do not remove product card if the filter enables
          // all product statuses (hidden & displayed)
          if (filter_status == "all-status") {
            var product_name = update_button.attr("data-product-name");

            if (status == "display") {
              update_button.removeClass("display-product-button");
              update_button.addClass("hide-product-button");
              update_button.attr(
                "data-tooltip",
                "Hide " + "'" + product_name + "'"
              );
              update_button.tooltip({ delay: 50 });
              update_button.find(".material-icons").html("visibility_off");
              update_button
                .parents(".card")
                .find(".card-image img")
                .removeClass("hidden");
            } else {
              update_button.removeClass("hide-product-button");
              update_button.addClass("display-product-button");
              update_button.attr(
                "data-tooltip",
                "Display " + "'" + product_name + "'"
              );
              update_button.tooltip({ delay: 50 });
              update_button.find(".material-icons").html("visibility");
              update_button
                .parents(".card")
                .find(".card-image img")
                .addClass("hidden");
            }
          } else {
            products.forEach(function(element) {
              $("#product-" + element).remove();
            });
          }
          config.preloader_progress.fadeOut();
          Materialize.toast(
            "Selected Products updated!",
            2000,
            "green lighten-1"
          );
        },
        error: function(message) {
          console.log(message["responseText"]);
        }
      });
    } else Materialize.toast("No products chosen!", 1500, "orange accent-2");
  }
};

/* function addComma(string) {
  // clear every instance of keyup input with comma
  string.value = string.value.replace(",", "");
  // add comma to the string number
  string.value = string.value.replace(/\d{1,3}(?=(\d{3})+(?!\d))/g, "$&,");
}

$('.price-field').keyup(function (event) {
  // got this from: https://stackoverflow.com/questions/2632359/can-jquery-add-commas-while-user-typing-numbers

  $(this).val(function (index, value) {
    return value
      .replace(",", "") // replace
      .replace(/\d{1,3}(?=(\d{3})+(?!\d))/g, "$&,");
  });
}); */

$(".product-quantity , .edit-product-quantity").keyup(function() {
  var pattern = /^[1-9]\d*$/;
  $(this).val(function(index, value) {
    return value.match(pattern);
  });
});
