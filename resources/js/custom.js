(function() {
    var Message;
    Message = function(arg) {
        (this.text = arg.text), (this.message_side = arg.message_side);
        this.draw = (function(_this) {
            return function() {
                var $message;
                $message = $(
                    $(".message_template")
                        .clone()
                        .html()
                );
                $message
                    .addClass(_this.message_side)
                    .find(".text")
                    .html(_this.text);
                $(".messages").append($message);
                return setTimeout(function() {
                    return $message.addClass("appeared");
                }, 0);
            };
        })(this);
        return this;
    };
    $(function() {
        let next_is_password = false;
        let jwt_token = "";
        var getMessageText, message_side, sendMessage;
        message_side = "right";
        getMessageText = function() {
            var $message_input;
            $message_input = $(".message_input");
            return $message_input.val();
        };
        sendMessage = function(text, user_type) {
            if (!next_is_password) {
                sendMessageHtml(text, user_type);
            }

            if (user_type != "bot") {
                $.ajax({
                    method: "POST",
                    url: "/botman",
                    beforeSend: function(xhr) {
                        xhr.setRequestHeader(
                            "Authorization",
                            "Bearer " + jwt_token
                        );
                    },
                    data: {
                        driver: "web",
                        userId: "",
                        message: text,
                        attachment: null,
                        interactive: false,
                        attachment_data: undefined
                    }
                }).done(function(response) {
                    let messages = eval(response.messages);
                    for (var i = 0; i < messages.length; i++) {
                        if (messages[i].text.includes("password")) {
                            next_is_password = true;
                        } else {
                            next_is_password = false;
                        }

                        if (messages[i].text.includes("|{")) {
                            let json_data = JSON.parse(
                                messages[i].text.split("|")[1]
                            );

                            jwt_token = json_data.token;

                            messages[i].text = messages[i].text.split("|")[0];
                        }
                        sendMessageHtml(messages[i].text, "bot");
                    }
                });
            }
        };

        sendMessageHtml = function(text, user_type) {
            var $messages, message;
            if (text.trim() === "") {
                return;
            }
            $(".message_input").val("");
            $messages = $(".messages");
            message_side = user_type === "bot" ? "left" : "right";
            message = new Message({
                text: text,
                message_side: message_side
            });
            message.draw();
            return $messages.animate(
                { scrollTop: $messages.prop("scrollHeight") },
                300
            );
        };
        $(".send_message").click(function(e) {
            return sendMessage(getMessageText(), "person");
        });
        $(".message_input").keyup(function(e) {
            if (e.which === 13) {
                return sendMessage(getMessageText(), "person");
            }
        });
        sendMessage("Hello I'm the Banker! :)", "bot");
        return setTimeout(function() {
            return sendMessage(
                "Type <b>help</b> to show all commands or <b>login</b> to enter on ChatBank",
                "bot"
            );
        }, 100);
    });

    // setInterval(function() {
    //     $.ajax({
    //         url: "/refresh-token",
    //         type: "get",
    //         dataType: "json",
    //         success: function(result) {
    //             $('meta[name="csrf-token"]').attr("content", result.token);
    //             $.ajaxSetup({
    //                 headers: {
    //                     "X-CSRF-TOKEN": result.token
    //                 }
    //             });
    //         },
    //         error: function(xhr, status, error) {
    //             console.log(xhr);
    //         }
    //     });
    // }, 1000);
}.call(this));
