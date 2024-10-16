
@push('head')
<link rel="stylesheet" href="{{asset('css/chat-app.css')}}">
<script src="https://unpkg.com/timeago.js/dist/timeago.min.js"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endpush
<div class="chat-app-container flex flex-column">
    <section class="chat-heading flex">
        <div class="chat-title-section flex flex-column">
            <h4 class="convo-title text-bold">RND Menu Masterfile</h4>
            <div class="convo-info">
                {{$menu_item_description}}
            </div>
        </div>
        <div class="message-counter">
            
        </div>
    </section>
    <div class="scroll-body">
        <section class="chat-body flex flex-column">
            <div class="no-message-note"><h4 class="text-center">💬 No comments to show...</h4><div>
        </section>
    </div>
    @if ($to_comment)
    <section class="type-message-section">
        <form action="" id="message-comment-form" class="message-form flex">
            <input form="message-comment-form" type="text" class="type-message form-control" placeholder="✏️ Type your comment here..." autofocus="false">
            <button form="message-comment-form" class="btn btn-success send-btn" disabled>➡️Send</button>
        </form>
    </section>
    @endif
</div> 

@push('bottom')
<script>
    const savedComments =  {!! json_encode($comments) !!}

    function reloadInfo() {
        const messageCount = $('.message').length;
        $('.message-counter').text(`${messageCount} Comment(s)`);
        if (messageCount) {
            $('.no-message-note').hide();
        } else {
            $('.no-message-note').show();
            return;
        }
        timeago.cancel();
        const nodes = $('.timeago').get();
        timeago.render(nodes);
    }

    function appendComment(comments) {
        const myId = "{{ CRUDBooster::myId() }}";
        const scrollBody = $('.scroll-body');
        comments.forEach(comment => {
            const sender = comment.name?.trim();
            const splittedBySpace = sender.split(' ');
            const initials = splittedBySpace[0][0] + splittedBySpace.at(-1)[0];
            const messageContainer = $(document.createElement('div')).addClass('flex');
            const profilePhotoSection = $(document.createElement('div')).addClass('profile-photo-section flex');
            const profilePhoto = $(document.createElement('div')).addClass('profile-photo').text(initials);
            const messageWrapper = $(document.createElement('div')).addClass('message-wrapper flex flex-column');
            const senderDetails = $(document.createElement('div')).addClass('sender-details flex');
            const senderName = $(document.createElement('div')).addClass('sender-name text-bold');
            const senderDate = $(document.createElement('div')).attr('datetime', comment.comment_added_at).addClass('sender-date timeago');
            const message = $(document.createElement('p')).addClass('message').text(comment.comment_content);
            const deleteComment = $(document.createElement('div')).addClass('delete-comment').text('×').hide();

            const isMyMessage = comment.cms_users_id == myId;

            if (isMyMessage) {
                messageWrapper.addClass('my-message-wrapper');
                messageContainer.addClass('my-message-container');
                senderDetails.addClass('my-sender-details');
                message.addClass('my-message').append(deleteComment);
                senderName.text('Me');

            } else {
                messageWrapper.addClass('their-message-wrapper');
                messageContainer.addClass('their-message-container');
                message.addClass('their-message');
                profilePhotoSection.append(profilePhoto);
                messageContainer.append(profilePhotoSection);
                senderName.text(sender);
            }
            message.attr('comment_id', comment.comment_id);
            senderDetails.append(senderName, senderDate);
            messageWrapper.append(senderDetails, message);
            messageContainer.append(messageWrapper);

            $('.chat-body').append(messageContainer);
        });
        scrollBody.animate({scrollTop: scrollBody.prop('scrollHeight')}, 1000)
        reloadInfo();
    }

    function deleteInDB(commentId) {
        $.ajax({
                type: 'POST',
                url: "{{ route('delete_rnd_comment') }}",
                data: {
                    comment_id: commentId,
                },
                success: function(response) {
                    response = JSON.parse(response);
                    $(`.my-message[comment_id="${commentId}"]`)
                        .parents('.message-wrapper')
                        .hide(300, function() {
                            $(this).remove();
                            reloadInfo();
                        });
                },
                error: function(response) { 
                    console.log(response);
                    Swal.fire({
                        title: 'Oops',
                        html: 'Something went wrong.',
                        icon: 'error'
                    });
                }  
            });
    }

    function formatMyMessage() {
        $('.my-message').each(function() {
            if (!$(this).attr('_clicked')) {
                $(this).find('.delete-comment').hide();
            }
        })
    }

    $('.type-message').on('keyup', function() {
        const value = $(this).val().trim()
        if (value) $('.send-btn').attr('disabled', false);
        else $('.send-btn').attr('disabled', true);
    });

    $('.message-form').on('submit', function(event) {
        event.preventDefault();
        const message = $('.type-message').val();
        const rndMenuItemsId = "{{ $rnd_menu_items_id }}";
        if (!message) return;
        $.ajax({
                type: 'POST',
                url: "{{ route('add_rnd_comment') }}",
                data: {
                    comment_content: message,
                    rnd_menu_items_id : rndMenuItemsId,
                },
                success: function(response) {
                    response = JSON.parse(response);
                    appendComment(response);
                    console.log(response);
                },
                error: function(response) { 
                    console.log(response);
                    Swal.fire({
                        title: 'Oops',
                        html: 'Something went wrong.',
                        icon: 'error'
                    });
                }  
            });
        $('.type-message').val('');
    });

    $(document).on('click', '.my-message', function() {
        if ($(this).attr('_clicked')) {
            $('.my-message').removeAttr('_clicked');
        } else {
            $('.my-message').removeAttr('_clicked');
            $(this).attr('_clicked', 'true');
        }
        formatMyMessage();
    });

    @if ($to_comment)
    $(document).on('mouseenter', '.my-message', function() {
        const deleteButton = $(this).find('.delete-comment');
        deleteButton.show();
    });

    $(document).on('mouseleave', '.my-message', function() {
        const deleteButton = $(this).find('.delete-comment');
        if (!$(this).attr('_clicked')) {
            deleteButton.hide();
        }
    });

    $(document).on('click', '.delete-comment', function() {
        const messageId = $(this).parents('.message').attr('comment_id');
        Swal.fire({
            title: 'Are you sure you want to delete this comment?',
            html: '📄 You won\'t be able to revert this.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes'
        }).then((result) => {
            if (result.isConfirmed) {
                deleteInDB(messageId);
            }
        });
    });
    @endif

    appendComment(savedComments);
</script>
@endpush