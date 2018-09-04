var loading;

$(document).ready(function () {
    function dropdown_transport()
      {
        smtp = document.getElementsByClassName("smtp");
        smtpauth = document.getElementsByClassName("smtpauth");
        dropdown = document.getElementById("transport");
        if(dropdown.options[dropdown.selectedIndex].text == "smtp")
        {
          for(i=0; i<smtp.length; i++)
          {
            smtp[i].style.display = "";
          }

          checkbox = document.getElementById("smtpauth");
          if(checkbox.checked)
          {
            for(i=0; i<smtpauth.length; i++)
            {
              smtpauth[i].style.display = "";
            }
          }
        }
        else
        {
          for(i=0; i<smtp.length; i++)
          {
            smtp[i].style.display = "none";
          }

          for(i=0; i<smtpauth.length; i++)
          {
            smtpauth[i].style.display = "none";
          }
        }
      }

      function checkbox_smtpauth()
      {
        elements = document.getElementsByClassName("smtpauth");
        checkbox = document.getElementById("smtpauth");
        if(checkbox.checked)
        {
          for(i=0; i<elements.length; i++)
          {
            elements[i].style.display = "";
          }
        }
        else
        {
          for(i=0; i<elements.length; i++)
          {
            elements[i].style.display = "none";
          }
        }
      }

    // Set current mouse position
    var mouseX, mouseY;
    $(document).mousemove(function (e) {
        mouseX = e.pageX;
        mouseY = e.pageY;
    }).mouseover();

    /* GUI operation */
    $(".search-popup").hide();
    $("#show-search-pop, .search-popup").click(function (event) {
        event.stopPropagation();
        event.preventDefault();
        $(".search-popup").show();
        $(".search-popup .search--wrapper input[type=text]").focus();
    });
    setTimeout(function () {
        $("#alert").hide();
    }, 5000);

    /* Loading */
    loading = {
        ajax: function (st) {
            this.show('load');
        },
        show: function (el) {
            this.getID(el).style.display = '';
        },
        getID: function (el) {
            return document.getElementById(el);
        }
    };

    var tabContentId = document.location.hash ? document.location.hash : "#documents-tab";
    tabContentId += "-content";
    $(".tab-show").removeClass("selected");

    $(".tab-show[data-content='" + tabContentId + "']").addClass("selected");
    $(".tab-data").addClass("hide");
    $(tabContentId).removeClass("hide");

    $(".datepicker").datepicker();

    $("#document-program--add .document_type").change(function () {
        $parent = $(this).parents(".modal");
        $.ajax({
            url: BASE_URL + '/api/governance/parent_documents_dropdown?type=' + encodeURI($(this).val()),
            type: 'GET',
            success: function (res) {
                $(".parent_documents_container", $parent).html(res.data.html)
            }
        });
    })

    $("#document-update-modal .document_type").change(function () {
        $parent = $(this).parents(".modal");
        var document_id = $("[name=document_id]", $parent).val();
        $.ajax({
            url: BASE_URL + '/api/governance/selected_parent_documents_dropdown?type=' + encodeURI($(this).val()) + "&child_id=" + document_id,
            type: 'GET',
            success: function (res) {
                $(".parent_documents_container", $parent).html(res.data.html)
            }
        });
    })

    $("body").on("click", ".document--edit", function () {
        var document_id = $(this).data("id");
        $.ajax({
            url: BASE_URL + '/api/governance/document?id=' + document_id,
            type: 'GET',
            success: function (res) {
                var data = res.data;
                $.ajax({
                    url: BASE_URL + '/api/governance/selected_parent_documents_dropdown?type=' + encodeURI(data.document_type) + '&child_id=' + document_id,
                    type: 'GET',
                    success: function (res) {
                        $("#document-update-modal .parent_documents_container").html(res.data.html)
                    }
                });
                $("#document-update-modal [name=document_id]").val(data.id);
                $("#document-update-modal [name=document_type]").val(data.document_type);
                $("#document-update-modal [name=document_name]").val(data.document_name);
                $("#document-update-modal [name=creation_date]").val(data.creation_date);
                $("#document-update-modal [name=status]").val(data.status);
                $("#document-update-modal").modal();
            }
        });

    })
    $("body").on("click", ".framework-name", function (e) {
        e.preventDefault();
        var framework_id = $(this).data("id")
        $.ajax({
            url: BASE_URL + '/api/governance/framework?framework_id=' + framework_id,
            type: 'GET',
            dataType: 'json',
            success: function (res) {
                var data = res.data;

                // Add parent framework dropdown
                $.ajax({
                    url: BASE_URL + '/api/governance/selected_parent_frameworks_dropdown?child_id=' + framework_id,
                    type: 'GET',
                    success: function (res) {
                        $("#framework--update .parent_frameworks_container").html(res.data.html)
                    }
                });

                $("#framework--update [name=framework_id]").val(framework_id);
                $("#framework--update [name=framework_name]").val(data.framework.name);
                $("#framework--update [name=framework_description]").val(data.framework.description);
                $("#framework--update").modal();
            }
        });
    });

    $("body").on("click", ".control-name", function (e) {
        e.preventDefault();
        var control_id = $(this).data("id")
        $.ajax({
            url: BASE_URL + '/api/governance/control?control_id=' + control_id,
            type: 'GET',
            dataType: 'json',
            success: function (res) {
                var data = res.data;
                var control = data.control;

                var modal = $('#control--update');
                $('.control_id', modal).val(control_id);
                $('[name=short_name]', modal).val(control.short_name);
                $('[name=long_name]', modal).val(control.long_name);
                $('[name=description]', modal).val(control.description);
                $('[name=supplemental_guidance]', modal).val(control.supplemental_guidance);

                $.each(control.framework_ids.split(","), function (i, e) {
                    $("#frameworks option[value='" + e + "']").prop("selected", true);
                });

                $('[name=control_class]', modal).val(Number(control.control_class) ? control.control_class : "");
                $('[name=control_phase]', modal).val(Number(control.control_phase) ? control.control_phase : "");
                $('[name=control_owner]', modal).val(Number(control.control_owner) ? control.control_owner : "");
                $('[name=control_number]', modal).val(control.control_number);
                $('[name=control_priority]', modal).val(Number(control.control_priority) ? control.control_priority : "");
                $('[name=family]', modal).val(Number(control.family) ? control.family : "");

                modal.modal();
            }
        });
    });

    $("body").on("click", ".test-name", function (e) {
        e.preventDefault();

        var test_id = $(this).data('id');
        $.ajax({
            type: "GET",
            url: BASE_URL + "/api/compliance/test?id=" + test_id,
            success: function (result) {
                var data = result['data'];
                var modal = $('#test--edit');

                $('[name=test_id]', modal).val(data['id']);
                $('[name=tester]', modal).val(data['tester']);
                $('[name=test_frequency]', modal).val(data['test_frequency']);
                $('[name=last_date]', modal).val(data['last_date']);
                $('[name=next_date]', modal).val(data['next_date']);
                $('[name=name]', modal).val(data['name']);
                $('[name=objective]', modal).val(data['objective']);
                $('[name=test_steps]', modal).val(data['test_steps']);
                $('[name=approximate_time]', modal).val(data['approximate_time']);
                $('[name=expected_results]', modal).val(data['expected_results']);
                $(".datepicker", modal).datepicker();

                modal.modal();
            }
        })
    });

    // Event when clicks Initiate Framework Audit button
    $('body').on("click", ".initiate-framework-audit-btn", function () {
        document.location.href = BASE_URL + "/compliance/audit_initiation.php?initiate&type=framework&id=" + $(this).data('id');
    })

    // Event when clicks Initiate Control Audit button
    $('body').on("click", ".initiate-control-audit-btn", function () {
        document.location.href = BASE_URL + "/compliance/audit_initiation.php?initiate&type=control&id=" + $(this).data('id');
    })

    // Event when clicks Initiate Test button
    $('body').on("click", ".initiate-test-btn", function () {
        document.location.href = BASE_URL + "/compliance/audit_initiation.php?initiate&type=test&id=" + $(this).data('id');
    });

    $("#framework-add-btn").click(function () {
        $.ajax({
            url: BASE_URL + '/api/governance/parent_frameworks_dropdown?status=1',
            type: 'GET',
            success: function (res) {
                $("#framework--add .parent_frameworks_container").html(res.data.html)
            }
        });
    })

    $("body").on("click", ".framework-block--edit", function () {
        var framework_id = $(this).data("id");
        $.ajax({
            url: BASE_URL + '/api/governance/framework?framework_id=' + framework_id,
            type: 'GET',
            success: function (res) {
                var data = res.data;
                $.ajax({
                    url: BASE_URL + '/api/governance/selected_parent_frameworks_dropdown?child_id=' + framework_id,
                    type: 'GET',
                    success: function (res) {
                        $("#framework--update .parent_frameworks_container").html(res.data.html)
                    }
                });
                $("#framework--update [name=framework_id]").val(framework_id);
                $("#framework--update [name=framework_name]").val(data.framework.name);
                $("#framework--update [name=framework_description]").val(data.framework.description);
                $("#framework--update").modal();
            }
        });

    })

    var tabContentId = document.location.hash ? document.location.hash : "#frameworks-tab";
    tabContentId += "-content";
    $(".tab-show").removeClass("selected");

    $(".tab-show[data-content='" + tabContentId + "']").addClass("selected");
    $(".tab-data").addClass("hide");
    $(tabContentId).removeClass("hide");

    var length = $('.tab-close').length;
    if (length == 1) {
        $('.tab-show button').hide();
    }


    $("div#add-tab").click(function () {

        $('.tab-show button').show();
        var num_tabs = $("div.container-fluid div.new").length + 1;
        var form = $('#tab-append-div').html();

        $('.tab-show').removeClass('selected');
        $("div.tab-append").prepend(
            "<div class='tab new tab-show form-tab selected' id='tab" + num_tabs + "'><div><span>New Risk (" + num_tabs + ")</span></div>" +
            "<button class='close tab-close' aria-label='Close' data-id='" + num_tabs + "'>" +
            "<i class='fa fa-close'></i>" +
            "</button>" +
            "</div>"
        );
        $('.tab-data').css({
            'display': 'none'
        });
        $("#tab-content-container").append(
            "<div class='tab-data' id='tab-container" + num_tabs + "'>" + form + "</div>"
        );

        focus_add_css_class("#AffectedAssetsTitle", "#assets", $("#tab-container" + num_tabs));
        focus_add_css_class("#RiskAssessmentTitle", "#assessment", $("#tab-container" + num_tabs));
        focus_add_css_class("#NotesTitle", "#notes", $("#tab-container" + num_tabs));


        $("#tab-container" + num_tabs)
            .find('.file-uploader label').attr('for', 'file_upload' + num_tabs);

        $("#tab-container" + num_tabs)
            .find('.hidden-file-upload')
            .attr('id', 'file_upload' + num_tabs)
            .prev('label').attr('for', 'file_upload' + num_tabs);


        $("#tab-container" + num_tabs + " .assets")
            .bind("keydown", function (event) {
                if (event.keyCode === $.ui.keyCode.TAB && $(this).autocomplete("instance").menu.active) {
                    event.preventDefault();
                }
            })
            .autocomplete({
                minLength: 0,
                source: function (request, response) {
                    // delegate back to autocomplete, but extract the last term
                    response($.ui.autocomplete.filter(
                        availableAssets, extractLast(request.term)));
                },
                focus: function () {
                    // prevent value inserted on focus
                    return false;
                },
                select: function (event, ui) {
                    var terms = split(this.value);
                    // remove the current input
                    terms.pop();
                    // add the selected item
                    terms.push(ui.item.value);
                    // add placeholder to get the comma-and-space at the end
                    terms.push("");
                    terms = terms.reverse().filter(function (e, i, arr) {
                        return arr.indexOf(e, i + 1) === -1;
                    }).reverse();

                    this.value = terms.join(", ");
                    return false;
                }
            })
            .focus(function () {
                var self = $(this);
                window.setTimeout(function () {
                    self.autocomplete("search", "");
                }, 1000)
            });

    });


    function sleep(ms) {
        return new Promise(resolve => setTimeout(resolve, ms));
    }

    focus_add_css_class("#AffectedAssetsTitle", "#assets", $("#tab-container"));
    focus_add_css_class("#RiskAssessmentTitle", "#assessment", $("#tab-container"));
    focus_add_css_class("#NotesTitle", "#notes", $("#tab-container"));

    function showScoreDetails() {
        document.getElementById("scoredetails").style.display = "";
        document.getElementById("hide").style.display = "block";
        document.getElementById("show").style.display = "none";
    }
    
    function hideScoreDetails() {
        document.getElementById("scoredetails").style.display = "none";
        document.getElementById("updatescore").style.display = "none";
        document.getElementById("hide").style.display = "none";
        document.getElementById("show").style.display = "";
    }
    
    function updateScore() {
        document.getElementById("scoredetails").style.display = "none";
        document.getElementById("updatescore").style.display = "";
        document.getElementById("show").style.display = "none";
    }

    /*
    * Function to add the css class for textarea title and make it popup.
    * Example usage:
    * focus_add_css_class("#foo", "#bar");
    */
    function focus_add_css_class(id_of_text_head, text_area_id) {
        // If enable_popup setting is false, disable popup
        if ($("#enable_popup").val() != 1) {
            $("textarea").removeClass("enable-popup");
            return;
        } else {
            $("textarea").addClass("enable-popup");
        }

        look_for = "textarea" + text_area_id;
        if (!$(look_for).length) {
            text_area_id = text_area_id.replace('#', '');
            look_for = "textarea[name=" + text_area_id;
        }
        $(look_for).focusin(function () {
            $(id_of_text_head).addClass("affected-assets-title");
            $('.ui-autocomplete').addClass("popup-ui-complete")
        });
        $(look_for).focusout(function () {
            $(id_of_text_head).removeClass("affected-assets-title");
            $('.ui-autocomplete').removeClass("popup-ui-complete")
        });
    }

    focus_add_css_class("#AffectedAssetsTitle", "#assets");
    focus_add_css_class("#RiskAssessmentTitle", "#assessment");
    focus_add_css_class("#NotesTitle", "#notes");
    focus_add_css_class("#SecurityRequirementsTitle", "#security_requirements");
    focus_add_css_class("#CurrentSolutionTitle", "#current_solution");
    focus_add_css_class("#SecurityRecommendationsTitle", "#security_recommendations");


    /**
     * Change Event of Risk Scoring Method
     * 
     */
    $('body').on('change', '[name=scoring_method]', function (e) {
        e.preventDefault();
        var formContainer = $(this).parents('form');
        handleSelection($(this).val(), formContainer);
    })

    /**
     * events in clicking soring button of edit details page, muti tabs case
     */
    $('body').on('click', '[name=cvssSubmit]', function (e) {
        e.preventDefault();
        var form = $(this).parents('form');
        popupcvss(form);
    });

    $('body').on('click', '[name=view_all_reviews], .view-all-reviews', function (e) {
        e.preventDefault();
        var tabContainer = $(this).parents('.tab-data');
        if ($('.current_review', tabContainer).is(":visible")) {
            $('.all_reviews', tabContainer).show();
            $('.current_review', tabContainer).hide();
            $('.all_reviews_btn', tabContainer).html("<?php echo $escaper->escapeHtml($lang['LastReview']); ?>");
        } else {
            $('.all_reviews', tabContainer).hide();
            $('.current_review', tabContainer).show();
            $('.all_reviews_btn', tabContainer).html("<?php echo $escaper->escapeHtml($lang['ViewAllReviews']); ?>");
        }
    });

    $("#comment-submit").attr('disabled', 'disabled');
    $("#cancel_disable").attr('disabled', 'disabled');
    $("#rest-btn").attr('disabled', 'disabled');
    $("#comment-text").click(function () {
        $("#comment-submit").removeAttr('disabled');
        $("#rest-btn").removeAttr('disabled');
    });

    $("#comment-submit").click(function () {
        var submitbutton = document.getElementById("comment-text").value;
        if (submitbutton == '') {
            $("#comment-submit").attr('disabled', 'disabled');
            $("#rest-btn").attr('disabled', 'disabled');
        }
    });
    $("#rest-btn").click(function () {
        $("#comment-submit").attr('disabled', 'disabled');
    });

    $(".active-textfield").click(function () {
        $("#cancel_disable").removeAttr('disabled');
    });

    $("select").change(function changeOption() {
        $("#cancel_disable").removeAttr('disabled');
    });

    $('.collapsible').hide();

    $('.collapsible--toggle span').click(function (event) {
        event.preventDefault();
        $(this).parents('.collapsible--toggle').next('.collapsible').slideToggle('400');
        $(this).find('i').toggleClass('fa-caret-right fa-caret-down');
    });

    $('.add-comments').click(function (event) {
        event.preventDefault();
        $(this).parents('.collapsible--toggle').next('.collapsible').slideDown('400');
        $(this).toggleClass('rotate');
        $('#comment').fadeToggle('100');
        $(this).parent().find('span i').removeClass('fa-caret-right');
        $(this).parent().find('span i').addClass('fa-caret-down');
    });


    $('#edit-subject').click(function () {
        $('.edit-subject').show();
        $('#static-subject').hide();
    });

    $(".add-comment-menu").click(function (event) {
        event.preventDefault();
        $commentsContainer = $("#comment").parents('.well');
        $commentsContainer.find(".collapsible--toggle").next('.collapsible').slideDown('400');
        $commentsContainer.find(".add-comments").addClass('rotate');
        $('#comment').show();
        $commentsContainer.find(".add-comments").parent().find('span i').removeClass('fa-caret-right');
        $commentsContainer.find(".add-comments").parent().find('span i').addClass('fa-caret-down');
        $("#comment-text").focus();
    })
    $(".datepicker").datepicker();
    function checkAll(bx) {
        var cbs = document.getElementsByTagName('input');
        for(var i=0; i < cbs.length; i++) {
          if (cbs[i].type == 'checkbox') {
            cbs[i].checked = bx.checked;
          }
        }
    }

    function colourNameToHex(colour)
        {
            var colours = {"aliceblue":"#f0f8ff","antiquewhite":"#faebd7","aqua":"#00ffff","aquamarine":"#7fffd4","azure":"#f0ffff",
            "beige":"#f5f5dc","bisque":"#ffe4c4","black":"#000000","blanchedalmond":"#ffebcd","blue":"#0000ff","blueviolet":"#8a2be2","brown":"#a52a2a","burlywood":"#deb887",
            "cadetblue":"#5f9ea0","chartreuse":"#7fff00","chocolate":"#d2691e","coral":"#ff7f50","cornflowerblue":"#6495ed","cornsilk":"#fff8dc","crimson":"#dc143c","cyan":"#00ffff",
            "darkblue":"#00008b","darkcyan":"#008b8b","darkgoldenrod":"#b8860b","darkgray":"#a9a9a9","darkgreen":"#006400","darkkhaki":"#bdb76b","darkmagenta":"#8b008b","darkolivegreen":"#556b2f",
            "darkorange":"#ff8c00","darkorchid":"#9932cc","darkred":"#8b0000","darksalmon":"#e9967a","darkseagreen":"#8fbc8f","darkslateblue":"#483d8b","darkslategray":"#2f4f4f","darkturquoise":"#00ced1",
            "darkviolet":"#9400d3","deeppink":"#ff1493","deepskyblue":"#00bfff","dimgray":"#696969","dodgerblue":"#1e90ff",
            "firebrick":"#b22222","floralwhite":"#fffaf0","forestgreen":"#228b22","fuchsia":"#ff00ff",
            "gainsboro":"#dcdcdc","ghostwhite":"#f8f8ff","gold":"#ffd700","goldenrod":"#daa520","gray":"#808080","green":"#008000","greenyellow":"#adff2f",
            "honeydew":"#f0fff0","hotpink":"#ff69b4",
            "indianred ":"#cd5c5c","indigo":"#4b0082","ivory":"#fffff0","khaki":"#f0e68c",
            "lavender":"#e6e6fa","lavenderblush":"#fff0f5","lawngreen":"#7cfc00","lemonchiffon":"#fffacd","lightblue":"#add8e6","lightcoral":"#f08080","lightcyan":"#e0ffff","lightgoldenrodyellow":"#fafad2",
            "lightgrey":"#d3d3d3","lightgreen":"#90ee90","lightpink":"#ffb6c1","lightsalmon":"#ffa07a","lightseagreen":"#20b2aa","lightskyblue":"#87cefa","lightslategray":"#778899","lightsteelblue":"#b0c4de",
            "lightyellow":"#ffffe0","lime":"#00ff00","limegreen":"#32cd32","linen":"#faf0e6",
            "magenta":"#ff00ff","maroon":"#800000","mediumaquamarine":"#66cdaa","mediumblue":"#0000cd","mediumorchid":"#ba55d3","mediumpurple":"#9370d8","mediumseagreen":"#3cb371","mediumslateblue":"#7b68ee",
            "mediumspringgreen":"#00fa9a","mediumturquoise":"#48d1cc","mediumvioletred":"#c71585","midnightblue":"#191970","mintcream":"#f5fffa","mistyrose":"#ffe4e1","moccasin":"#ffe4b5",
            "navajowhite":"#ffdead","navy":"#000080",
            "oldlace":"#fdf5e6","olive":"#808000","olivedrab":"#6b8e23","orange":"#ffa500","orangered":"#ff4500","orchid":"#da70d6",
            "palegoldenrod":"#eee8aa","palegreen":"#98fb98","paleturquoise":"#afeeee","palevioletred":"#d87093","papayawhip":"#ffefd5","peachpuff":"#ffdab9","peru":"#cd853f","pink":"#ffc0cb","plum":"#dda0dd","powderblue":"#b0e0e6","purple":"#800080",
            "rebeccapurple":"#663399","red":"#ff0000","rosybrown":"#bc8f8f","royalblue":"#4169e1",
            "saddlebrown":"#8b4513","salmon":"#fa8072","sandybrown":"#f4a460","seagreen":"#2e8b57","seashell":"#fff5ee","sienna":"#a0522d","silver":"#c0c0c0","skyblue":"#87ceeb","slateblue":"#6a5acd","slategray":"#708090","snow":"#fffafa","springgreen":"#00ff7f","steelblue":"#4682b4",
            "tan":"#d2b48c","teal":"#008080","thistle":"#d8bfd8","tomato":"#ff6347","turquoise":"#40e0d0",
            "violet":"#ee82ee",
            "wheat":"#f5deb3","white":"#ffffff","whitesmoke":"#f5f5f5",
            "yellow":"#ffff00","yellowgreen":"#9acd32"};

            if (typeof colours[colour.toLowerCase()] != 'undefined')
                return colours[colour.toLowerCase()];

            return colour;
        }


        $('.colorSelector').each(function(){
            var color = $(this).parent().find('.level-colorpicker').val()
            color = colourNameToHex(color)
            $(this).ColorPicker({
                color: color,
                onShow: function (colpkr) {
                    $(colpkr).fadeIn(500);
                    return false;
                },
                onHide: function (colpkr) {
                    $(colpkr).fadeOut(500);
                    return false;
                },
                onSubmit: function (hsb, hex, rgb, el) {
                    console.log(el)
                },
                onChange: function (hsb, hex, rgb, el) {
                    $('div', el).css('backgroundColor', '#' + hex);
                    $(el).parent().find('.level-color').val('#' + hex);
                }
            });
        })
});