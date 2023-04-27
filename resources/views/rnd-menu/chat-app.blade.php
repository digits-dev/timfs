
@push('head')
<link rel="stylesheet" href="{{asset('css/chat-app.css')}}">
<script src="https://unpkg.com/timeago.js/dist/timeago.min.js"></script>
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
            <div class="no-message-note"><h4 class="text-center">🤷‍♂️ No comments to show...</h4><div>
        </section>
    </div>
    <section class="type-message-section">
        <form action="" class="message-form flex">
            <input type="text" class="type-message form-control" placeholder="Enter comment...">
            <button class="btn btn-primary send-btn" disabled>Send</button>
        </form>
    </section>
</div> 

@push('bottom')
<script>
    const savedComments =  {!! json_encode($comments) !!}

    function refreshTimeAgo() {
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

            const isMyMessage = comment.cms_users_id == myId;

            if (isMyMessage) {
                messageContainer.addClass('my-message-container');
                senderDetails.addClass('my-sender-details');
                message.addClass('my-message');
                senderName.text('Me')

            } else {
                messageContainer.addClass('their-message-container');
                message.addClass('their-message');
                profilePhotoSection.append(profilePhoto);
                messageContainer.append(profilePhotoSection);
                senderName.text(sender);
            }
            senderDetails.append(senderName, senderDate);
            messageWrapper.append(senderDetails, message);
            messageContainer.append(messageWrapper);

            $('.chat-body').append(messageContainer);
        });
        scrollBody.animate({scrollTop: scrollBody.prop('scrollHeight')}, 1000)
        const messageCount = $('.message').length;
        $('.message-counter').text(`${messageCount} Comment(s)`);
        if (messageCount) {
            $('.no-message-note').remove();
            refreshTimeAgo();
        } 
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
                url: "{{ route('add_comment') }}",
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

    appendComment(savedComments);
</script>
@endpush