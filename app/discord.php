<?php
/* Discord Oauth v.4.1
 * This file contains the core functions of the oauth2 script.
 * @author : MarkisDev
 * @copyright : https://markis.dev
 * 
 * Modified by Tealstone for The Red Legion.
 * 
 * Follow Discord App Bot setup process here:
 * https://github.com/MarkisDev/discordoauth
 * 
 */

// Setting the base url for API requests
$GLOBALS['base_url'] = "https://discord.com";

// Setting bot token for related requests
$GLOBALS['bot_token'] = null;

// A function to generate a random string to be used as state | (protection against CSRF)
function gen_state()
{
    $_SESSION['state'] = bin2hex(openssl_random_pseudo_bytes(12));
    return $_SESSION['state'];
}

// A function to generate oAuth2 URL for logging in
function url($clientid, $redirect, $scope)
{
    $state = gen_state();
    return 'https://discordapp.com/oauth2/authorize?response_type=code&client_id=' . $clientid . '&redirect_uri=' . $redirect . '&scope=' . $scope . "&state=" . $_SESSION['state'];
}

// A function to initialize and store access token in SESSION to be used for other requests
function init($redirect_url, $client_id, $client_secret, $bot_token = null)
{
    if ($bot_token != null)
    {
        $GLOBALS['bot_token'] = $bot_token;
    }

    // Fetching code and state from URL
    $code  = $_GET['code']  ?? null;
    $state = $_GET['state'] ?? null;
    
    // Check if $state == $_SESSION['state'] to verify if the login is legit | CHECK THE FUNCTION get_state($state) FOR MORE INFORMATION.
    $url = $GLOBALS['base_url'] . "/api/oauth2/token";
    $data = array(
        "client_id" => $client_id,
        "client_secret" => $client_secret,
        "grant_type" => "authorization_code",
        "code" => $code,
        "redirect_uri" => $redirect_url
    );

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($curl);
    curl_close($curl);
    $results = json_decode($response, true);
    $accessToken = $results['access_token'] ?? null;
}

// A function to get user information | (identify scope)
function get_user($email = null)
{
    $url = $GLOBALS['base_url'] . "/api/users/@me";
    $headers = array('Content-Type: application/x-www-form-urlencoded', 'Authorization: Bearer ' . $_SESSION['access_token']);
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    $response = curl_exec($curl);
    curl_close($curl);
    $results = json_decode($response, true);
    $_SESSION['user'] = $results;
    $_SESSION['username'] = $results['username'];
    $_SESSION['discrim'] = $results['discriminator'];
    $_SESSION['user_id'] = $results['id'];
    $_SESSION['user_avatar'] = $results['avatar'];
    
    //Fetching email 
    if ($email == True) 
    {
        $_SESSION['email'] = $results['email'];
    }
}

// A function to give roles to the user
// Note : The bot has to be a member of the server with MANAGE_ROLES permission.
//        The bot DOES NOT have to be online, just has to be a bot application and has to be a member of the server.
//        This is the basic function which requires few parameters. [ 1: Guild ID,  2: Role ID ]
function give_role($guildid, $roleid)
{
    $data = json_encode(array("roles" => array("$roleid")));
    $url = $GLOBALS['base_url'] . "/api/guilds/$guildid/members/" . $_SESSION['user_id'] . "/roles/$roleid";
    $headers = array('Content-Type: application/json', 'Authorization: Bot ' . $GLOBALS['bot_token']);
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
    $response = curl_exec($curl);
    curl_close($curl);
    $results = json_decode($response, true);
    return $results;
}

// A function to get user guilds | (guilds scope)
function get_guilds()
{
    $url = $GLOBALS['base_url'] . "/api/users/@me/guilds";
    $headers = array('Content-Type: application/x-www-form-urlencoded', 'Authorization: Bearer ' . $_SESSION['access_token']);
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    $response = curl_exec($curl);
    curl_close($curl);
    $results = json_decode($response, true);
    return $results;
}

// A function to fetch information on a single guild | (requires bot token)
function get_guild($id)
{
    $url = $GLOBALS['base_url'] . "/api/guilds/$id";
    $headers = array('Content-Type: application/x-www-form-urlencoded', 'Authorization: Bot ' . $env['DISCORD_BOT_TOKEN']);
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    $response = curl_exec($curl);
    curl_close($curl);
    $results = json_decode($response, true);
    return $results;
}

// A function to get user connections | (connections scope)
function get_connections()
{
    $url = $GLOBALS['base_url'] . "/api/users/@me/connections";
    $headers = array('Content-Type: application/x-www-form-urlencoded', 'Authorization: Bearer ' . $_SESSION['access_token']);
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    $response = curl_exec($curl);
    curl_close($curl);
    $results = json_decode($response, true);
    return $results;
}

// Function to make user join a guild | (guilds.join scope)
// Note : The bot has to be a member of the server with CREATE_INSTANT_INVITE permission.
//        The bot DOES NOT have to be online, just has to be a bot application and has to be a member of the server.
//        This is the basic function with no parameters, you can build on this to give the user a nickname, mute, deafen or assign a role.
//
// Tealstone: This function is currently hardcoded to add users to The Red Legion's Discord server.
// TODO: Examine the user_id SESSION variable to see if it exists or is being set.
function join_guild($guildid)
{
    $data = json_encode(array("access_token" => $_SESSION['access_token']));
    $url = $GLOBALS['base_url'] . "/api/guilds/814699481912049704/members/" . $_SESSION['user_id'];
    $headers = array('Content-Type: application/json', 'Authorization: Bot ' . $GLOBALS['bot_token']);
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
    $response = curl_exec($curl);
    curl_close($curl);
    $results = json_decode($response, true);
    return $results;
}

// A function to verify if login is legit
function check_state($state)
{
    if($state == $_SESSION['state']) 
    {
        return true;
    } 
    else 
    {
        # The login is not valid, so you should probably redirect them back to home page
        return false;
    }
}


function discordPermissionMap(): array {
    return [
        // Legacy + Core
        0  => 'CREATE_INSTANT_INVITE',
        1  => 'KICK_MEMBERS',
        2  => 'BAN_MEMBERS',
        3  => 'ADMINISTRATOR',
        4  => 'MANAGE_CHANNELS',
        5  => 'MANAGE_GUILD',
        6  => 'ADD_REACTIONS',
        7  => 'VIEW_AUDIT_LOG',
        8  => 'PRIORITY_SPEAKER',
        9  => 'STREAM',
        10 => 'VIEW_CHANNEL',
        11 => 'SEND_MESSAGES',
        12 => 'SEND_TTS_MESSAGES',
        13 => 'MANAGE_MESSAGES',
        14 => 'EMBED_LINKS',
        15 => 'ATTACH_FILES',
        16 => 'READ_MESSAGE_HISTORY',
        17 => 'MENTION_EVERYONE',
        18 => 'USE_EXTERNAL_EMOJIS',
        19 => 'VIEW_GUILD_INSIGHTS',
        20 => 'CONNECT',
        21 => 'SPEAK',
        22 => 'MUTE_MEMBERS',
        23 => 'DEAFEN_MEMBERS',
        24 => 'MOVE_MEMBERS',
        25 => 'USE_VAD',
        26 => 'CHANGE_NICKNAME',
        27 => 'MANAGE_NICKNAMES',
        28 => 'MANAGE_ROLES',
        29 => 'MANAGE_WEBHOOKS',
        30 => 'MANAGE_EMOJIS_AND_STICKERS',

        // New / Extended
        31 => 'USE_APPLICATION_COMMANDS',
        32 => 'REQUEST_TO_SPEAK',
        33 => 'MANAGE_EVENTS',
        34 => 'MANAGE_THREADS',
        35 => 'CREATE_PUBLIC_THREADS',
        36 => 'CREATE_PRIVATE_THREADS',
        37 => 'USE_EXTERNAL_STICKERS',
        38 => 'SEND_MESSAGES_IN_THREADS',
        39 => 'START_EMBEDDED_ACTIVITIES',
        40 => 'MODERATE_MEMBERS',
        41 => 'VIEW_CREATOR_MONETIZATION_ANALYTICS',
        42 => 'USE_SOUNDBOARD',
        45 => 'USE_EXTERNAL_SOUNDS',
        46 => 'SEND_VOICE_MESSAGES'
    ];
}

function syncDiscordGuilds(): int
{
    global $pdo; 
    $session = $_SESSION;

    if (empty($session['user']['guilds']) || !is_array($session['user']['guilds'])
    )
    {
        return 0;
    }

    $sql = "
        INSERT INTO Guilds (
            GuildID,
            GuildName,
            GuildIcon,
            GuildBanner,
            CreateDate,
            ModifyDate
        )
        VALUES (
            :guild_id,
            :guild_name,
            :guild_icon,
            :guild_banner,
            NOW(),
            NULL
        )
        ON DUPLICATE KEY UPDATE
            GuildName   = VALUES(GuildName),
            GuildIcon   = VALUES(GuildIcon),
            GuildBanner = VALUES(GuildBanner),
            ModifyDate  = NOW()
    ";

    $stmt = $pdo->prepare($sql);
    $count = 0;

    foreach ($session['user']['guilds'] as $guild)
    {
        $guildId     = (int)($guild['id'] ?? 0);
        $guildName   = trim($guild['name'] ?? '');
        $guildIcon   = $guild['icon']   ?: null;
        $guildBanner = $guild['banner'] ?: null;

        if ($guildId === 0 || $guildName === '')
        {
            continue;
        }

        $stmt->execute([
            ':guild_id'     => $guildId,
            ':guild_name'   => $guildName,
            ':guild_icon'   => $guildIcon,
            ':guild_banner' => $guildBanner
        ]);

        $count++;
    }

    return $count;
}