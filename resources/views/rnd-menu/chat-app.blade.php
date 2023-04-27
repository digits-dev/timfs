
@push('head')
<link rel="stylesheet" href="{{asset('css/chat-app.css')}}">
@endpush
<body>
    <div class="chat-app-container flex flex-column">
        <section class="chat-heading flex">
            <div class="chat-title-section flex flex-column">
                <h4 class="convo-title text-bold">RND Menu Masterfile</h4>
                <div class="convo-info">
                    Chicken Inasal
                </div>
            </div>
            <div class="message-counter">
                3 messages
            </div>
        </section>
        <div class="scroll-body">
            <section class="chat-body flex flex-column">
                <div class="their-message-container flex">
                    <div class="profile-photo-section">
                        <div class="profile-photo">JD</div>
                    </div>
                    <div class="message-wrapper flex flex-column">
                        <div class="sender-details flex">
                            <div class="sender-name text-bold">John Doe </div>
                            <div class="sender-date">3 hours ago</div>
                        </div>
                        <p class="message their-message">
                            Lorem ipsum dolor sit amet consectetur adipisicing elit. Temporibus omnis doloremque similique, provident modi nesciunt delectus officia sint aliquam sed numquam! Minus suscipit dolor, dicta qui animi voluptatibus culpa iste.
                            Odio omnis vel maxime natus nobis velit. Quo in sed velit cum dolor officia odit animi corrupti nihil optio minima deserunt, iste a necessitatibus! Aut architecto amet incidunt blanditiis accusamus.
                        </p>
                    </div>
                </div>
                <div class="my-message-container flex">
                    <div class="message-wrapper flex flex-column">
                        <div class="sender-details my-sender-details flex">
                            <div class="sender-name text-bold">Me </div>
                            <div class="sender-date">8 mins ago</div>
                        </div>
                        <p class="message my-message">
                            Lorem ipsum dolor sit amet consectetur adipisicing elit. Temporibus omnis doloremque similique, provident modi nesciunt delectus officia sint aliquam sed numquam! Minus suscipit dolor, dicta qui animi voluptatibus culpa iste.
                            Odio omnis vel maxime natus nobis velit. Quo in sed velit cum dolor officia odit animi corrupti nihil optio minima deserunt, iste a necessitatibus! Aut architecto amet incidunt blanditiis accusamus.
                        </p>
                    </div>
                </div>
                <div class="their-message-container flex">
                    <div class="profile-photo-section">
                        <div class="profile-photo">JD</div>
                    </div>
                    <div class="message-wrapper flex flex-column">
                        <div class="sender-details flex">
                            <div class="sender-name text-bold">Juan Dela Cruz </div>
                            <div class="sender-date">3 mins ago</div>
                        </div>
                        <p class="message their-message">
                            Lorem ipsum dolor sit amet.
                        </p>
                    </div>
                </div>
            </section>
        </div>
        <section class="type-message-section">
            <form action="" class="message-form flex">
                <input type="text" class="type-message form-control">
                <button class="btn btn-primary send-btn">
                    Send
                </button>
            </form>
        </section>
    </div>
</body>