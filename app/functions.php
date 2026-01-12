<?php
/* Functions
 * This file contains various functions used throughout the application.
 * @author : Tealstone
 */

// d()
//
// Show debug information only to people in the $_DEBUG_USERS array.
// Accepts any type of variable, including arrays and objects.
// Recursively converts objects to arrays for easier viewing.
function d($display)
{

        if(true)
        {
                echo '<br><font color="red">**START**</font> <pre>';

                if(is_array($display))
                {
                        print_r($display);
                }
                elseif(is_object($display))
                {
                        $d = @get_object_vars($display);
                        if(is_array($d))
                        {
                                /*
                                 * Return array converted to object Using __FUNCTION__ (Magic constant)
                                 * for recursive call.
                                 */
                                print_r(array_map(__FUNCTION__, $d));
                        }
                        else
                        {
                                print_r($d);
                        }
                }
                elseif (is_bool($display))
                {
                     if ($display)
                     {
                         echo 'Boolean: true';
                     }
                     else
                     {
                         echo 'Boolean: false';
                     }
                }
                elseif (is_null($display))
                {
                    echo 'NULL';
                }
                else
                {
                        echo $display;
                }

                echo '</pre><font color="red">**END**</font>';
        }
}


/**
 * Inserts a new applicant using Discord OAuth data stored in $_SESSION.
 *
 * Session requirements:
 * - $_SESSION['user'] must exist
 * - $_SESSION['access_token'] must exist
 *
 * Business rules:
 * - Blocks re-application unless previous status is Approved or Denied
 * - ApplicantID is always a new row
 * - DiscordAccessTokenExpiration = NOW() + 7 days
 * - RSIConfirmationToken generated once
 *
 * @return int|null  ApplicantID on success, NULL if blocked
 * @throws RuntimeException if session data is missing
 */
function insertApplicantFromDiscord(): ?int
{
    global $pdo;
    $session = $_SESSION;

    /**
     * STEP 0: Validate session structure
     */
    if (empty($session['user']) || empty($session['access_token'])) {
        throw new RuntimeException(
            'Discord OAuth session is missing. User must authenticate first.'
        );
    }

    $discordUser = $session['user'];

    if (empty($discordUser['id']) || empty($discordUser['username'])) {
        throw new RuntimeException(
            'Incomplete Discord user data in session.'
        );
    }

    /**
     * STEP 1: Extract Discord identity
     */
    $discordId  = (int)$discordUser['id'];
    $username   = $discordUser['username'];
    $globalName = $discordUser['global_name'] ?? null;
    $avatar     = $discordUser['avatar'] ?? null;
    $banner     = $discordUser['banner'] ?? null;
    $locale     = $discordUser['locale'] ?? null;

    $primaryGuildId = $discordUser['primary_guild']['identity_guild_id'] ?? null;

    $accessToken = $session['access_token'];
    $tokenExpiration = (new DateTime('+7 days'))->format('Y-m-d H:i:s');

    /**
     * STEP 2: Block duplicate pending applications
     */
    $checkSql = "
        SELECT 1
        FROM Applicants
        WHERE DiscordID = :discord_id
          AND Status NOT IN ('Approved', 'Denied')
        LIMIT 1
    ";

    $stmt = $pdo->prepare($checkSql);
    $stmt->execute([':discord_id' => $discordId]);

    if ($stmt->fetchColumn()) {
        return null;
    }

    /**
     * STEP 3: Generate RSI confirmation token (one-time)
     */
    $rsiToken = bin2hex(random_bytes(32));

    /**
     * STEP 4: Insert applicant
     */
    $insertSql = "
        INSERT INTO Applicants (
            DiscordID,
            DiscordAvatar,
            DiscordBanner,
            DiscordUsername,
            DiscordGlobalName,
            DiscordPrimaryGuildID,
            DiscordLocale,
            DiscordAccessToken,
            DiscordAccessTokenExpiration,
            RSIConfirmationToken,
            RSIConfirmed,
            CreateDate,
            ModifyDate,
            Status
        ) VALUES (
            :discord_id,
            :avatar,
            :banner,
            :username,
            :global_name,
            :primary_guild_id,
            :locale,
            :access_token,
            :token_expiration,
            :rsi_token,
            'N',
            NOW(),
            NULL,
            'Unsubmitted'
        )
    ";

    $stmt = $pdo->prepare($insertSql);
    $stmt->execute([
        ':discord_id'       => $discordId,
        ':avatar'           => $avatar,
        ':banner'           => $banner,
        ':username'         => $username,
        ':global_name'      => $globalName,
        ':primary_guild_id' => $primaryGuildId,
        ':locale'           => $locale,
        ':access_token'     => $accessToken,
        ':token_expiration' => $tokenExpiration,
        ':rsi_token'        => $rsiToken,
    ]);

    return (int)$pdo->lastInsertId();
}

/**
 * Inserts or updates guild memberships for an applicant.
 *
 * Uses:
 * - $_SESSION['user']['guilds']
 *
 * Rules:
 * - Composite key (ApplicantID, GuildID)
 * - GuildOwner = 'Y' if owner flag is set
 * - Uses permissions_new
 */
function insertGuildMembershipsForApplicant(int $applicantId): void
{
    global $pdo;
    $session = $_SESSION;

    $guilds = $session['user']['guilds'] ?? [];

    if (empty($guilds)) {
        return;
    }

    $sql = "
        INSERT INTO Guild_Memberships (
            ApplicantID,
            GuildID,
            GuildPermissions,
            GuildOwner,
            CreateDate
        ) VALUES (
            :applicant_id,
            :guild_id,
            :permissions,
            :owner,
            NOW()
        )
        ON DUPLICATE KEY UPDATE
            GuildPermissions = VALUES(GuildPermissions),
            GuildOwner = VALUES(GuildOwner)
    ";

    $stmt = $pdo->prepare($sql);

    foreach ($guilds as $guild) {
        $stmt->execute([
            ':applicant_id' => $applicantId,
            ':guild_id'     => (int)$guild['id'],
            ':permissions'  => $guild['permissions_new'] ?? null,
            ':owner'        => !empty($guild['owner']) ? 'Y' : 'N',
        ]);
    }
}

/**
 * Retrieves the RSI authentication / confirmation token for an applicant.
 *
 * @param int $applicantId  Internal ApplicantID
 * @return string|null      RSI token if found, null otherwise
 */
function getRSIAuthTokenByApplicantId(int $applicantId): ?string
{
    global $pdo;

    $sql = "
        SELECT RSIConfirmationToken
        FROM Applicants
        WHERE ApplicantID = :applicant_id
        LIMIT 1
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':applicant_id' => $applicantId
    ]);

    $token = $stmt->fetchColumn();

    return $token !== false ? $token : null;
}

/**
 * Validates that an RSI profile contains the expected confirmation token.
 *
 * @param string $rsiUsername
 * @param string $expectedToken
 * @return bool
 */
function validateRSIProfile(string $rsiUsername, string $expectedToken): bool
{
    $url = 'https://robertsspaceindustries.com/en/citizens/' . rawurlencode($rsiUsername);

    $html = fetchRSIProfileHtml($url);

    if (!$html) {
        return false;
    }

    return rsiProfileContainsToken($html, $expectedToken);
}


function fetchRSIProfileHtml(string $url): ?string
{
    $ch = curl_init($url);

    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_TIMEOUT => 10,
        CURLOPT_CONNECTTIMEOUT => 5,
        CURLOPT_USERAGENT => 'Mozilla/5.0 (compatible; RSI-Validator/1.0)',
        CURLOPT_HTTPHEADER => [
            'Accept: text/html',
            'Accept-Language: en-US,en;q=0.9',
        ],
    ]);

    $html = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    curl_close($ch);

    if ($httpCode !== 200 || !$html) {
        return null;
    }

    return $html;
}

function rsiProfileContainsToken(string $html, string $token): bool
{
    return stripos($html, $token) !== false;
}

/**
 * Marks an applicant's RSI account as confirmed.
 *
 * This function:
 * - Stores the confirmed RSI username
 * - Sets RSIConfirmed = 'Y'
 * - Sets RSIConfirmedDate only once
 *
 * @param int    $applicantId   Internal ApplicantID
 * @param string $rsiUsername   Confirmed RSI username
 * @return bool                True if updated, false if already confirmed or failed
 */
function markRSIConfirmed(int $applicantId, string $rsiUsername): bool
{
    global $pdo;

    /**
     * Only update records that are not already confirmed
     */
    $sql = "
        UPDATE Applicants
        SET
            RSIUsername = :rsi_username,
            RSIConfirmed = 'Y',
            ModifyDate = NOW()
        WHERE ApplicantID = :applicant_id
          AND RSIConfirmed = 'N'
        LIMIT 1
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':rsi_username' => $rsiUsername,
        ':applicant_id' => $applicantId
    ]);

    return $stmt->rowCount() === 1;
}

/**
 * Updates an applicant's status to Applied and stores their reason for joining.
 *
 * @param int    $applicantId  Applicant primary key
 * @param string $reason       Applicant's reason for joining
 * @return void
 * @throws PDOException
 */
function updateApplicantReasonAndStatus(int $applicantId, string $reason): void
{
    global $pdo;

    $sql = "
        UPDATE Applicants
        SET
            Status = 'Applied',
            Reason = :reason,
            ModifyDate = NOW()
        WHERE ApplicantID = :applicant_id
        LIMIT 1
    ";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        ':reason'        => $reason,
        ':applicant_id' => $applicantId,
    ]);

    if ($stmt->rowCount() !== 1) {
        throw new RuntimeException('Applicant update failed.');
    }
}


function getApplicantsForReview()
{
    global $pdo;

    $sql = "
        SELECT
            a.*,

            /* Red flag: applicant is a member of at least one blacklisted guild */
            CASE
                WHEN EXISTS (
                    SELECT 1
                    FROM Guild_Memberships gm
                    JOIN Guilds g
                      ON g.GuildID = gm.GuildID
                    WHERE gm.ApplicantID = a.ApplicantID
                      AND g.Blacklisted = 'Y'
                )
                THEN 'Y'
                ELSE 'N'
            END AS HasBlacklistedGuild

        FROM Applicants a
        WHERE a.Status <> 'Unsubmitted'
        ORDER BY a.CreateDate DESC
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}



function renderRedFlags(array $a): string
{
    $flags = [];

    if (
        isset($a['HasBlacklistedGuild']) &&
        $a['HasBlacklistedGuild'] === 'Y'
    ) {
        $flags[] = '<span class="badge bg-danger">Blacklisted Guild</span>';
    }

    if (
        isset($a['Blacklist']) &&
        $a['Blacklist'] === 'Y'
    ) {
        $flags[] = '<span class="badge bg-warning">Applicant Blacklisted</span>';
    }

    return $flags
        ? implode(' ', $flags)
        : '<span class="text-muted">None</span>';
}

/**
 * Maps applicant status to Bootstrap badge color.
 * MUST remain in sync with allowed Status values.
 */
function statusBadge(?string $status): string
{
    return match ($status ?? '') {
        'Applied'     => 'info',
        'Accepted'    => 'success',
        'Denied'      => 'danger',
        'Hold'        => 'warning',
        'Blacklisted' => 'dark',
        default       => 'secondary',
    };
}

/**
 * Builds a Discord avatar URL, handling animated avatars correctly.
 */
function discordAvatarUrl(?string $discordId, ?string $avatarHash, int $size = 128): ?string
{
    if (!$discordId || !$avatarHash) {
        return null;
    }

    $isAnimated = str_starts_with($avatarHash, 'a_');
    $extension  = $isAnimated ? 'gif' : 'png';

    return sprintf(
        'https://cdn.discordapp.com/avatars/%s/%s.%s?size=%d',
        $discordId,
        $avatarHash,
        $extension,
        $size
    );
}

function getApplicantById(int $applicantId): ?array
{
    global $pdo;

    $stmt = $pdo->prepare("
        SELECT *
        FROM Applicants
        WHERE ApplicantID = :id
        LIMIT 1
    ");
    $stmt->execute([':id' => $applicantId]);

    return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
}

function getApplicantGuilds(int $applicantId): array
{
    global $pdo;

    $stmt = $pdo->prepare("
        SELECT
            g.GuildID,
            g.GuildName,
            g.StarCitizenRelated,
            g.Blacklisted,

            gm.GuildOwner,
            gm.GuildPermissions

        FROM Guild_Memberships gm
        JOIN Guilds g
          ON g.GuildID = gm.GuildID
        WHERE gm.ApplicantID = :id
        ORDER BY g.GuildName
    ");

    $stmt->execute([':id' => $applicantId]);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


function getApplicantHistory(?int $discordId): array
{
    if (!$discordId) {
        return [];
    }

    global $pdo;

    $stmt = $pdo->prepare("
        SELECT ApplicantID, Status, CreateDate
        FROM Applicants
        WHERE DiscordID = :discordId
        ORDER BY CreateDate DESC
    ");
    $stmt->execute([':discordId' => $discordId]);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
