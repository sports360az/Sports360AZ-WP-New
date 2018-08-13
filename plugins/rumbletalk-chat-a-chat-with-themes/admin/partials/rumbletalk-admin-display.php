<div id="fb-root"></div>
<div class="rumbletalk-main">
    <header>
        <img src="<?= plugins_url('../images/rumbletalk-logo.png', __FILE__) ?>" alt="RumbleTalk Logo">
        <h1 id="view-title"></h1>
    </header>

    <div id="create-account" class="rt-page">
        <form id="create-account-form">
            <div class="form-data">
                <p>Type your email and choose a password</p>

                <div>
                    <input type="text" name="email" placeholder="Email">
                </div>
                <div>
                    <input type="password" name="password" placeholder="Password">
                </div>
                <div>
                    <input type="password" name="password_confirmation" placeholder="Confirm password">
                </div>
            </div>

            <div class="form-note">
                <p>Free account entitles you to one chat room</p>
                <div class="error-note"></div>
            </div>

            <div class="form-buttons">
                <button type="submit" class="button-main">
                    Create your account
                </button>
                <button type="button" class="anchor update-token-button">
                    Already have an account?
                </button>
            </div>
        </form>
    </div>

    <div id="manage-chats" class="rt-page">
        <div class="header">
            <div class="plan-info">
                <a href="https://www.rumbletalk.com/admin/groups.php" title="Account email"
                   class="account-email" target="_blank"></a>
                <div>Your current plan:</div>
                <div>
                    <b class="users">5</b> Users
                    <br>
                    <b class="rooms">1</b> Rooms
                    <br>
                    <b class="keywords">1</b> Keywords
                </div>
                <button type="button" class="button-attention"
                        title="Upgrade your account, create more rooms and get more chat seats">
                    Upgrade
                </button>
            </div>

            <div class="managing-buttons">
                <button type="button" class="button-main update-token-button">
                    Account
                </button>
                <button type="button" id="manage-keywords" class="button-main">Manage Keywords</button>
            </div>
        </div>

        <div id="chats">
            You don't have any chats,
            <button type="button" class="anchor">create one!</button>
        </div>

        <button type="button" id="create-new-chat" class="button-main" title="create a new chat">
            Add new chat
            +
        </button>
        <button type="button" id="refresh-chats" class="button-main" title="Reload chats data">
            Reload chats
            <span class="refresh-icon">&#x21bb;</span>
        </button>

        <?php include 'rumbletalk-admin-info-display.php'; ?>
    </div>

    <script id="update-token" type="text/template">
        <p>
            If you already have an account, login to your
            <a href="https://www.rumbletalk.com/admin/groups.php" target="_blank">RumbleTalk administration panel</a>
            and go to "My Account" to get your token.
            <br>
            A visual display can be found
            <a href="<?= plugins_url('../images/token-location.png', __FILE__) ?>" target="_blank">here.</a>
        </p>

        <form id="update-token-form">
            <div>
                <label for="token-manage-key">Key</label>
                <input type="text" name="tokenKey" id="token-manage-key" value="">
            </div>
            <div>
                <label for="token-manage-secret">Secret</label>
                <input type="text" name="tokenSecret" id="token-manage-secret"
                       value="">
            </div>
        </form>
    </script>

    <script id="edit-chat" type="text/template">
        <div class="chat-name">
            <label>Chat name</label>
            <a class="chat-open" target="_blank" title="Open the chat">
                <img src="<?= plugins_url('../images/open-in-new.svg', __FILE__) ?>" alt="Open the chat">
            </a>
            <div class="hash-display" title="Hash"></div>
            <input type="text" name="name">
        </div>
        <div class="chat-dimensions">
            <label class="label-width">Width</label>
            <input type="text" name="width" placeholder="auto">
            <label class="label-height">Height</label>
            <input type="text" name="height" placeholder="500px">
        </div>
        <div class="chat-properties">
            <label>
                <input type="checkbox" name="membersOnly">
                Members
                <select name="loginName" class="login-name-options" title="Login name">
                    <option value="display_name">Display name</option>
                    <option value="user_login">Username</option>
                    <option value="nickname">Nickname</option>
                    <option value="first_name">First name</option>
                    <option value="last_name">Last name</option>
                    <option value="first_name last_name">First name + Last name</option>
                    <option value="last_name first_name">Last name + First name</option>
                </select>
            </label>
            <label>
                <input type="checkbox" name="floating">
                Floating
            </label>
        </div>
        <div class="chat-buttons">
            <button type="button" class="button-sub chat-delete">Delete</button>
            <button type="button" class="button-sub chat-settings">Settings</button>
            <button type="submit" class="button-main">Save</button>
        </div>
        <div class="shortcode-bar">
            shortcode:
            <strong class="shortcode-handle">[rumbletalk-chat hash="<span class="shortcode-hash"></span>"]</strong>
        </div>
    </script>
</div>
<?php include 'rumbletalk-admin-sidebar-display.php'; ?>
