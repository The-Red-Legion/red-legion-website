<?php
$bootstrap = require __DIR__ . '/../../../app/bootstrap.php';
include __DIR__ . '/../../../app/functions.php';

function e(?string $v): string {
    return htmlspecialchars($v ?? '', ENT_QUOTES, 'UTF-8');
}

$applicantId = (int)($_GET['id'] ?? 0);
$applicant   = getApplicantById($applicantId);
$guilds      = getApplicantGuilds($applicantId);
$history     = $applicant ? getApplicantHistory($applicant['DiscordID']) : [];
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="../../assets/css/theme.min.css" rel="stylesheet">
<link rel="stylesheet" href="../../assets/fonts/bootstrap-icons/bootstrap-icons.min.css">
<title>The Red Legion – Applicant Review</title>

<style>
html, body { height:100%; }
body { display:flex; flex-direction:column; }
main { flex:1 0 auto; }
footer { flex-shrink:0; }

.content-offset { padding-top:120px; }
@media (max-width:991.98px){ .content-offset{padding-top:90px;} }

.form-select, .form-control {
    background:#1f2933;
    color:#e5e7eb;
    border-color:#374151;
}
.form-select option { background:#1f2933; color:#e5e7eb; }
.form-control::placeholder { color:#9ca3af; }
</style>
</head>

<body>
<?php include __DIR__ . '/../../../app/partials/preloader.html'; ?>

<header class="z-fixed header-transparent header-absolute-top sticky-reverse">
<nav class="navbar navbar-expand-lg navbar-light navbar-link-white">
<div class="container position-relative">
<a class="navbar-brand" href="/">
<img src="../../assets/img/logo/logo.png" class="img-fluid navbar-brand-sticky" alt="">
<img src="../../assets/img/logo/logo.png" class="img-fluid navbar-brand-transparent" alt="">
</a>
</div>
</nav>
</header>

<main class="content-offset">
<div class="container-xl">

<?php if (!$applicant): ?>
<div class="alert alert-danger">Applicant not found.</div>
<?php else: ?>

<!-- ================= Applicant Header ================= -->
<div class="card bg-dark mb-4">
<div class="card-body">
<div class="row align-items-center">

<div class="col-md-3 text-center mb-3 mb-md-0">
<?php
$avatar = discordAvatarUrl(
    $applicant['DiscordID'],
    $applicant['DiscordAvatar'],
    128
);
if ($avatar):
?>
<img src="<?= e($avatar) ?>" class="rounded-circle mb-2" width="96" height="96">
<?php endif; ?>
<h6 class="mb-0"><?= e($applicant['DiscordUsername']) ?></h6>
<?php if ($applicant['DiscordGlobalName']): ?>
<small class="text-muted"><?= e($applicant['DiscordGlobalName']) ?></small>
<?php endif; ?>
</div>

<div class="col-md-9">
<div class="d-flex justify-content-between align-items-center mb-2">
<h3 class="mb-0">Applicant Review</h3>
<span class="badge bg-<?= statusBadge($applicant['Status']) ?>">
<?= e($applicant['Status']) ?>
</span>
</div>

<p class="mb-2">
<strong>RSI:</strong>
<?php if ($applicant['RSIUsername']): ?>
<a href="https://robertsspaceindustries.com/citizens/<?= e($applicant['RSIUsername']) ?>"
   target="_blank" class="text-info">
<?= e($applicant['RSIUsername']) ?>
</a>
<?php if ($applicant['RSIConfirmed'] !== 'Y'): ?>
<span class="badge bg-warning ms-2">Unconfirmed</span>
<?php endif; ?>
<?php else: ?>
<span class="text-muted">Not provided</span>
<?php endif; ?>
</p>

<!-- Compact Decision Controls -->
<form method="post" action="RenderDecision.php" class="mt-2">
<input type="hidden" name="ApplicantID" value="<?= (int)$applicant['ApplicantID'] ?>">

<div class="d-flex gap-2 align-items-center flex-wrap">
<select name="Decision" class="form-select form-select-sm w-auto" required>
<option value="">Decision…</option>
<option value="Approved">Approve</option>
<option value="Denied">Deny</option>
<option value="Blacklisted">Blacklist</option>
</select>

<button type="submit" class="btn btn-sm btn-success">
Submit
</button>
</div>

<textarea name="DecisionNotes"
          class="form-control form-control-sm mt-2"
          rows="2"
          placeholder="Optional decision notes…"></textarea>
</form>

</div>
</div>
</div>
</div>

<!-- ================= Reason ================= -->
<div class="card bg-dark mb-4">
<div class="card-header"><strong>Reason for Applying</strong></div>
<div class="card-body">
<?= nl2br(e($applicant['Reason'] ?? 'No reason provided.')) ?>
</div>
</div>

<!-- ================= Guild Memberships ================= -->
<div class="card bg-dark mb-4">
<div class="card-header"><strong>Discord Guild Memberships</strong></div>
<div class="card-body p-0">
<div class="table-responsive">
<table class="table table-dark table-hover mb-0">
<thead>
<tr>
<th>Guild</th>
<th style="width:110px;">Role</th>
<th style="width:220px;">Flags</th>
<th style="width:90px;"></th>
</tr>
</thead>
<tbody>
<?php foreach ($guilds as $g): ?>
<tr id="guild-row-<?= (int)$g['GuildID'] ?>"
    class="<?= $g['Blacklisted']==='Y'?'table-danger':'' ?>">
<td><?= e($g['GuildName']) ?></td>
<td><?= $g['GuildOwner']==='Y'?'Owner':'Member' ?></td>
<td class="guild-flags">
<?php if ($g['StarCitizenRelated']==='Y'): ?>
<span class="badge bg-info me-1">Star Citizen</span>
<?php endif; ?>
<?php if ($g['Blacklisted']==='Y'): ?>
<span class="badge bg-danger me-1">Blacklisted</span>
<?php endif; ?>
<?php if ($g['Blacklisted']===null && $g['StarCitizenRelated']===null): ?>
<span class="badge bg-secondary">Unreviewed</span>
<?php endif; ?>
</td>
<td class="text-end">
<button class="btn btn-sm btn-outline-warning"
        data-bs-toggle="modal"
        data-bs-target="#guildReviewModal"
        data-guild-id="<?= (int)$g['GuildID'] ?>"
        data-guild-name="<?= e($g['GuildName']) ?>"
        data-sc="<?= e($g['StarCitizenRelated']) ?>"
        data-blacklisted="<?= e($g['Blacklisted']) ?>">
Review
</button>
</td>
</tr>
<?php endforeach; ?>
</tbody>
</table>
</div>
</div>
</div>

<!-- ================= Previous Applications ================= -->
<?php
$previous = array_filter(
    $history,
    fn($h) => (int)$h['ApplicantID'] !== (int)$applicant['ApplicantID']
);
if ($previous):
?>
<div class="card bg-dark mb-5">
<div class="card-header"><strong>Previous Applications</strong></div>
<div class="card-body">
<ul class="list-group list-group-flush">
<?php foreach ($previous as $h): ?>
<li class="list-group-item bg-dark text-light d-flex justify-content-between">
<span><?= date('M d, Y', strtotime($h['CreateDate'])) ?></span>
<span class="badge bg-<?= statusBadge($h['Status']) ?>">
<?= e($h['Status']) ?>
</span>
</li>
<?php endforeach; ?>
</ul>
</div>
</div>
<?php endif; ?>

<?php endif; ?>
</div>
</main>

<!-- ================= Guild Review Modal ================= -->
<div class="modal fade" id="guildReviewModal" tabindex="-1">
<div class="modal-dialog">
<div class="modal-content bg-dark text-light">
<form id="guildReviewForm">
<div class="modal-header">
<h5 class="modal-title">Review Guild</h5>
<button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
</div>
<div class="modal-body">
<input type="hidden" name="GuildID" id="modalGuildID">

<p><strong id="modalGuildName"></strong></p>

<label class="form-label">Star Citizen Related</label>
<select name="StarCitizenRelated" id="modalSC" class="form-select mb-3">
<option value="">Unreviewed</option>
<option value="Y">Yes</option>
<option value="N">No</option>
</select>

<label class="form-label">Blacklisted</label>
<select name="Blacklisted" id="modalBlacklisted" class="form-select">
<option value="">Unreviewed</option>
<option value="Y">Yes</option>
<option value="N">No</option>
</select>
</div>
<div class="modal-footer">
<button type="submit" class="btn btn-warning">Save Review</button>
<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
</div>
</form>
</div>
</div>
</div>

<script src="../../assets/js/theme.bundle.min.js"></script>

<script>
const modal = document.getElementById('guildReviewModal');
const form  = document.getElementById('guildReviewForm');

modal.addEventListener('show.bs.modal', e => {
    const b = e.relatedTarget;
    modalGuildID.value        = b.dataset.guildId;
    modalGuildName.textContent = b.dataset.guildName;
    modalSC.value             = b.dataset.sc || '';
    modalBlacklisted.value    = b.dataset.blacklisted || '';
});

form.addEventListener('submit', async e => {
    e.preventDefault();

    const data = new FormData(form);
    const res  = await fetch('AjaxSaveGuildReview.php', {
        method: 'POST',
        body: data
    });

    if (!res.ok) {
        alert('Failed to save guild review.');
        return;
    }

    const json = await res.json();
    if (!json.success) {
        alert('Save failed.');
        return;
    }

    const row = document.getElementById('guild-row-' + data.get('GuildID'));
    const flags = row.querySelector('.guild-flags');

    row.classList.remove('table-danger');
    let html = '';

    // Update the Review button's data attributes so modal reopens correctly
    const reviewButton = row.querySelector('[data-bs-target="#guildReviewModal"]');
    reviewButton.dataset.sc = json.StarCitizenRelated ?? '';
    reviewButton.dataset.blacklisted = json.Blacklisted ?? '';

    // Explicit flags
    if (json.StarCitizenRelated === 'Y') {
        html += '<span class="badge bg-info me-1">Star Citizen</span>';
    }

    if (json.Blacklisted === 'Y') {
        html += '<span class="badge bg-danger me-1">Blacklisted</span>';
        row.classList.add('table-danger');
    }

    // ONLY show Unreviewed if BOTH are null
    if (
        json.StarCitizenRelated === null &&
        json.Blacklisted === null
    ) {
        html = '<span class="badge bg-secondary">Unreviewed</span>';
    }

    // Otherwise: reviewed & clean → empty cell
    flags.innerHTML = html;


    flags.innerHTML = html;
    bootstrap.Modal.getInstance(modal).hide();
});
</script>

<?php include __DIR__ . '/../../../app/partials/footers/footer-9.php'; ?>
</body>
</html>
