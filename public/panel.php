<?php
// Made by copilot for a temporary admin panel, will be removed later when a more complete and secure solution is implemented
require_once __DIR__ . '/../src/php/init.php';
require_once BASE_PATH . '/src/php/mailer.php';

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$errors = [];
$successes = [];
$revealedApiKey = null;

function h($value)
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function add_error(&$errors, $message)
{
    $errors[] = $message;
}

function add_success(&$successes, $message)
{
    $successes[] = $message;
}

function current_account($pdo)
{
    if (empty($_SESSION['account_id'])) {
        return null;
    }

    $stmt = $pdo->prepare('SELECT id, username, email, role, created_at FROM accounts WHERE id = ? LIMIT 1');
    $stmt->execute([(int) $_SESSION['account_id']]);
    $account = $stmt->fetch();

    if (!$account) {
        unset($_SESSION['account_id'], $_SESSION['account_role']);
        return null;
    }

    return $account;
}

function format_fr_date($dateString)
{
    if (!$dateString) {
        return 'Jamais';
    }

    $ts = strtotime($dateString);
    if ($ts === false) {
        return h($dateString);
    }

    return date('d/m/Y H:i', $ts);
}

function is_admin($account)
{
    return $account && ($account['role'] ?? '') === 'admin';
}

function poetic_category_label($categorie)
{
    switch ($categorie) {
        case 'rejoindre':
            return 'Voeu de rejoindre le saint culte';
        case 'beurre':
            return 'Quete de l\'auguste beurre sacré';
        case 'question':
            return 'Interrogation adressée au Dieu du Beurre';
        case 'suggestion':
            return 'Proclamation d\'un conseil pour l\'ordre sacré du Jambon-Beurre';
        case 'autre':
        default:
            return 'Missive d\'un humble mortel';
    }
}

function build_panel_mail_html($mailCss, $senderName, $categoryLabel, $message, $subject)
{
    $safeSender = h($senderName);
    $safeCategory = h($categoryLabel);
    $safeSubject = h($subject);
    $safeMessage = nl2br(h($message));

    $formatter = new IntlDateFormatter(
        'fr_FR',
        IntlDateFormatter::LONG,
        IntlDateFormatter::NONE,
        'Europe/Paris',
        IntlDateFormatter::GREGORIAN,
        "d MMMM y 'à' HH:mm"
    );

    $date = $formatter->format(new DateTime());

    return "<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>{$safeSubject}</title>
    <style>{$mailCss}</style>
</head>
<body>
    <div class='mail-bg'>
        <table role='presentation' class='mail-wrapper' cellpadding='0' cellspacing='0'>
            <tr>
                <td>
                    <div class='mail-card'>
                        <p class='mail-kicker'>✦ Chronique des Messagers de Beurreland ✦</p>
                        <h1>Par la plume et l'encre sacrée, missive de {$safeSender}</h1>
                        <p class='mail-description'>Un message vous a été envoyé par l'administration de Beurreland depuis le panel interne.</p>
                        <p class='mail-meta'><strong>Categorie scellée :</strong> {$safeCategory}</p>
                        <div class='mail-message'>{$safeMessage}</div>
                        <p class='mail-date'>Redigé en ce jour du {$date}, consigné pour memoire éternelle.</p>
                    </div>
                </td>
            </tr>
        </table>
    </div>
</body>
</html>";
}

$currentAccount = current_account($pdo);

$adminMailForm = [
    'recipient_mode' => 'single',
    'recipient_email' => '',
    'subject' => '',
    'categorie' => 'autre',
    'message' => ''
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $postedCsrf = $_POST['csrf_token'] ?? '';
    if (!hash_equals($_SESSION['csrf_token'], $postedCsrf)) {
        add_error($errors, 'CSRF token invalide. Recharge la page et recommence.');
    } else {
        $action = $_POST['action'] ?? '';

        if ($action === 'login') {
            $identifier = trim($_POST['identifier'] ?? '');
            $password = $_POST['password'] ?? '';

            if ($identifier === '' || $password === '') {
                add_error($errors, 'Username/email et mot de passe sont requis.');
            } else {
                $stmt = $pdo->prepare('SELECT id, username, email, password_hash, role FROM accounts WHERE username = ? OR email = ? LIMIT 1');
                $stmt->execute([$identifier, $identifier]);
                $account = $stmt->fetch();

                if (!$account || !password_verify($password, $account['password_hash'])) {
                    add_error($errors, 'Identifiants invalides.');
                } else {
                    session_regenerate_id(true);
                    $_SESSION['account_id'] = (int) $account['id'];
                    $_SESSION['account_role'] = $account['role'];
                    $currentAccount = current_account($pdo);
                    add_success($successes, 'Connexion réussie. Welcome back, ' . $account['username'] . '.');
                }
            }
        } elseif ($action === 'logout') {
            unset($_SESSION['account_id'], $_SESSION['account_role']);
            $currentAccount = null;
            add_success($successes, 'Tu es maintenant déconnecté.');
        } elseif ($action === 'update_account') {
            if (!$currentAccount) {
                add_error($errors, 'Tu dois être connecté pour modifier ton compte.');
            } else {
                $email = trim($_POST['email'] ?? '');
                $currentPassword = $_POST['current_password'] ?? '';
                $newPassword = $_POST['new_password'] ?? '';
                $newPasswordConfirm = $_POST['new_password_confirm'] ?? '';

                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    add_error($errors, 'Adresse email invalide.');
                }

                if ($currentPassword === '') {
                    add_error($errors, 'Mot de passe actuel requis pour confirmer les changements.');
                }

                $stmt = $pdo->prepare('SELECT password_hash FROM accounts WHERE id = ? LIMIT 1');
                $stmt->execute([(int) $currentAccount['id']]);
                $passwordRow = $stmt->fetch();

                if (!$passwordRow || !password_verify($currentPassword, $passwordRow['password_hash'])) {
                    add_error($errors, 'Mot de passe actuel incorrect.');
                }

                if ($newPassword !== '' || $newPasswordConfirm !== '') {
                    if (strlen($newPassword) < 8) {
                        add_error($errors, 'Le nouveau mot de passe doit faire au moins 8 caracteres.');
                    }
                    if (!hash_equals($newPassword, $newPasswordConfirm)) {
                        add_error($errors, 'La confirmation du nouveau mot de passe ne correspond pas.');
                    }
                }

                if (empty($errors)) {
                    $emailCheck = $pdo->prepare('SELECT id FROM accounts WHERE email = ? AND id != ? LIMIT 1');
                    $emailCheck->execute([$email, (int) $currentAccount['id']]);
                    if ($emailCheck->fetch()) {
                        add_error($errors, 'Cet email est deja utilise par un autre compte.');
                    }
                }

                if (empty($errors)) {
                    if ($newPassword !== '') {
                        $newHash = password_hash($newPassword, PASSWORD_DEFAULT);
                        $update = $pdo->prepare('UPDATE accounts SET email = ?, password_hash = ? WHERE id = ?');
                        $update->execute([$email, $newHash, (int) $currentAccount['id']]);
                        add_success($successes, 'Email et mot de passe mis a jour.');
                    } else {
                        $update = $pdo->prepare('UPDATE accounts SET email = ? WHERE id = ?');
                        $update->execute([$email, (int) $currentAccount['id']]);
                        add_success($successes, 'Email mis a jour.');
                    }
                    $currentAccount = current_account($pdo);
                }
            }
        } elseif ($action === 'send_admin_mail') {
            if (!is_admin($currentAccount)) {
                add_error($errors, 'Seuls les admins peuvent envoyer des mails depuis le panel.');
            } else {
                $adminMailForm['recipient_mode'] = $_POST['recipient_mode'] ?? 'single';
                $adminMailForm['recipient_email'] = trim($_POST['recipient_email'] ?? '');
                $adminMailForm['subject'] = trim($_POST['mail_subject'] ?? '');
                $adminMailForm['categorie'] = trim($_POST['categorie'] ?? 'autre');
                $adminMailForm['message'] = trim($_POST['mail_message'] ?? '');

                $recipientMode = $adminMailForm['recipient_mode'];
                $recipientEmail = $adminMailForm['recipient_email'];
                $subject = preg_replace('/[\r\n]+/', ' ', $adminMailForm['subject']);
                $categorie = $adminMailForm['categorie'];
                $message = $adminMailForm['message'];

                $allowedCategories = ['rejoindre', 'beurre', 'question', 'suggestion', 'autre'];
                if (!in_array($categorie, $allowedCategories, true)) {
                    $categorie = 'autre';
                }

                if (!in_array($recipientMode, ['single', 'all_accounts'], true)) {
                    add_error($errors, 'Mode de destinataire invalide.');
                }

                if (strlen($subject) < 3 || strlen($subject) > 180) {
                    add_error($errors, 'Le sujet doit faire entre 3 et 180 caracteres.');
                }

                if (strlen($message) < 5 || strlen($message) > 5000) {
                    add_error($errors, 'Le message doit faire entre 5 et 5000 caracteres.');
                }

                $recipients = [];
                if (empty($errors)) {
                    if ($recipientMode === 'single') {
                        if (!filter_var($recipientEmail, FILTER_VALIDATE_EMAIL)) {
                            add_error($errors, 'Adresse email destinataire invalide.');
                        } else {
                            $recipients[] = $recipientEmail;
                        }
                    } else {
                        $mailTargets = $pdo->query('SELECT email FROM accounts WHERE email IS NOT NULL AND email != "" ORDER BY id ASC');
                        foreach ($mailTargets->fetchAll() as $row) {
                            if (filter_var($row['email'], FILTER_VALIDATE_EMAIL)) {
                                $recipients[] = $row['email'];
                            }
                        }
                        $recipients = array_values(array_unique($recipients));
                        if (empty($recipients)) {
                            add_error($errors, 'Aucun destinataire valide trouve dans les comptes.');
                        }
                    }
                }

                if (empty($errors)) {
                    $mailCss = '';
                    $mailCssPath = __DIR__ . '/assets/css/mail.css';
                    if (is_readable($mailCssPath)) {
                        $mailCss = file_get_contents($mailCssPath) ?: '';
                    }

                    $categoryLabel = poetic_category_label($categorie);
                    $mailBody = build_panel_mail_html(
                        $mailCss,
                        $currentAccount['username'],
                        $categoryLabel,
                        $message,
                        $subject
                    );

                    $sentCount = 0;
                    $failedRecipients = [];
                    foreach ($recipients as $to) {
                        if (send_mail($to, $subject, $mailBody, true)) {
                            $sentCount++;
                        } else {
                            $failedRecipients[] = $to;
                        }
                    }

                    if ($sentCount > 0) {
                        add_success($successes, 'Mail envoye a ' . $sentCount . ' destinataire(s).');
                    }

                    if (!empty($failedRecipients)) {
                        add_error($errors, 'Echec d envoi pour: ' . implode(', ', $failedRecipients));
                    }
                }
            }
        } elseif ($action === 'create_api_key') {
            if (!$currentAccount) {
                add_error($errors, 'Tu dois etre connecte pour creer une API key.');
            } else {
                $name = trim($_POST['api_key_name'] ?? '');
                $expiresDate = trim($_POST['expires_at'] ?? '');
                $expiresAt = null;

                if ($name === '') {
                    $name = null;
                }

                if ($name !== null && strlen($name) > 100) {
                    add_error($errors, 'Le nom de la cle API doit faire 100 caracteres max.');
                }

                if ($expiresDate !== '') {
                    $dt = DateTime::createFromFormat('Y-m-d', $expiresDate);
                    $validDate = $dt && $dt->format('Y-m-d') === $expiresDate;
                    if (!$validDate) {
                        add_error($errors, 'Date d expiration invalide.');
                    } else {
                        $dt->setTime(23, 59, 59);
                        $expiresAt = $dt->format('Y-m-d H:i:s');
                    }
                }

                if (empty($errors)) {
                    $apiKey = bin2hex(random_bytes(32));
                    $insert = $pdo->prepare('INSERT INTO api_keys (account_id, api_key, name, expires_at) VALUES (?, ?, ?, ?)');
                    $insert->execute([(int) $currentAccount['id'], $apiKey, $name, $expiresAt]);
                    $revealedApiKey = $apiKey;
                    add_success($successes, 'Nouvelle API key creee. Copie-la maintenant, elle ne sera plus affichee en clair ensuite.');
                }
            }
        } elseif ($action === 'revoke_api_key') {
            if (!$currentAccount) {
                add_error($errors, 'Tu dois etre connecte pour revoquer une API key.');
            } else {
                $keyId = (int) ($_POST['api_key_id'] ?? 0);
                if ($keyId <= 0) {
                    add_error($errors, 'ID de cle API invalide.');
                } else {
                    $update = $pdo->prepare('UPDATE api_keys SET is_revoked = 1 WHERE id = ? AND account_id = ?');
                    $update->execute([$keyId, (int) $currentAccount['id']]);
                    if ($update->rowCount() === 0) {
                        add_error($errors, 'Impossible de revoquer cette cle API.');
                    } else {
                        add_success($successes, 'API key revoquee.');
                    }
                }
            }
        } else {
            add_error($errors, 'Action inconnue.');
        }
    }
}

$currentAccount = current_account($pdo);
$apiKeys = [];
$accountMailCount = 0;

if ($currentAccount) {
    $keyStmt = $pdo->prepare(
        'SELECT
            k.id,
            k.api_key,
            k.name,
            k.is_revoked,
            k.expires_at,
            k.created_at,
            MAX(u.used_at) AS last_used_at,
            MAX(u.ip_address) AS last_used_ip,
            MAX(u.used_type) AS last_used_type,
            COUNT(u.id) AS usage_count
        FROM api_keys k
        LEFT JOIN api_key_usage u ON u.api_key_id = k.id
        WHERE k.account_id = ?
        GROUP BY k.id, k.api_key, k.name, k.is_revoked, k.expires_at, k.created_at
        ORDER BY k.id DESC'
    );
    $keyStmt->execute([(int) $currentAccount['id']]);
    $apiKeys = $keyStmt->fetchAll();

    if (is_admin($currentAccount)) {
        $countStmt = $pdo->query('SELECT COUNT(*) AS c FROM accounts WHERE email IS NOT NULL AND email != ""');
        $countRow = $countStmt ? $countStmt->fetch() : null;
        $accountMailCount = (int) ($countRow['c'] ?? 0);
    }
}

$current_file = __FILE__;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel - Accounts & API Keys</title>

    <link rel="shortcut icon" href="/assets/img/Butter_Pixel.png" type="image/x-icon">
    <link rel="stylesheet" href="/assets/css/style.css">
    <link rel="stylesheet" href="/assets/css/panel.css">

    <script src="/assets/js/snow.js"></script>
    <link rel="stylesheet" href="/assets/css/snow.css">

    <script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
</head>

<body>
    <div id="google_translate_element"></div>

    <?php include __DIR__ . '/../inc/sidebar.php'; ?>

    <div class="page">
        <div class="topbar">
            <marquee behavior="scroll" direction="left">★ Account + API Key Management Panel ★</marquee>
        </div>

        <div class="banner">
            <h1>Panel</h1>
            <div class="subtitle">Gestion de compte, connexion, et API keys</div>
        </div>

        <div class="panel-wrap">
            <?php foreach ($errors as $error): ?>
                <div class="panel-alert panel-alert-error"><?= h($error) ?></div>
            <?php endforeach; ?>

            <?php foreach ($successes as $success): ?>
                <div class="panel-alert panel-alert-success"><?= h($success) ?></div>
            <?php endforeach; ?>

            <?php if (!$currentAccount): ?>
                <section class="panel-section">
                    <h2>Login</h2>
                    <p>Connecte-toi avec ton username ou email pour gerer ton compte et tes cles API.</p>

                    <form method="post" class="panel-form">
                        <input type="hidden" name="action" value="login">
                        <input type="hidden" name="csrf_token" value="<?= h($_SESSION['csrf_token']) ?>">

                        <label for="identifier">Username or Email</label>
                        <input type="text" id="identifier" name="identifier" maxlength="255" required>

                        <label for="password">Mot de passe</label>
                        <input type="password" id="password" name="password" required>

                        <button type="submit">Se connecter</button>
                    </form>

                    <p class="panel-note">Registration is disabled on this page (login only).</p>
                </section>
            <?php else: ?>
                <section class="panel-section">
                    <div class="panel-section-header">
                        <h2>Mon compte</h2>
                        <form method="post">
                            <input type="hidden" name="action" value="logout">
                            <input type="hidden" name="csrf_token" value="<?= h($_SESSION['csrf_token']) ?>">
                            <button type="submit" class="btn-small">Logout</button>
                        </form>
                    </div>

                    <div class="panel-meta">
                        <span><strong>Username:</strong> <?= h($currentAccount['username']) ?></span>
                        <span><strong>Role:</strong> <?= h($currentAccount['role']) ?></span>
                        <span><strong>Created:</strong> <?= h(format_fr_date($currentAccount['created_at'])) ?></span>
                    </div>

                    <form method="post" class="panel-form panel-form-compact">
                        <input type="hidden" name="action" value="update_account">
                        <input type="hidden" name="csrf_token" value="<?= h($_SESSION['csrf_token']) ?>">

                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" value="<?= h($currentAccount['email']) ?>" required>

                        <label for="current_password">Mot de passe actuel (required)</label>
                        <input type="password" id="current_password" name="current_password" required>

                        <label for="new_password">Nouveau mot de passe (optional)</label>
                        <input type="password" id="new_password" name="new_password" minlength="8">

                        <label for="new_password_confirm">Confirmer nouveau mot de passe</label>
                        <input type="password" id="new_password_confirm" name="new_password_confirm" minlength="8">

                        <button type="submit">Sauvegarder le compte</button>
                    </form>
                </section>

                <?php if (is_admin($currentAccount)): ?>
                    <section class="panel-section">
                        <h2>Mail Admin</h2>
                        <p>Envoi de mail aux utilisateurs avec le meme template HTML/CSS que la page contact.</p>

                        <form method="post" class="panel-form js-send-mail-form">
                            <input type="hidden" name="action" value="send_admin_mail">
                            <input type="hidden" name="csrf_token" value="<?= h($_SESSION['csrf_token']) ?>">

                            <label for="recipient_mode">Destinataires</label>
                            <select id="recipient_mode" name="recipient_mode" class="panel-select js-recipient-mode">
                                <option value="single" <?= $adminMailForm['recipient_mode'] === 'single' ? 'selected' : '' ?>>Un email</option>
                                <option value="all_accounts" <?= $adminMailForm['recipient_mode'] === 'all_accounts' ? 'selected' : '' ?>>Tous les comptes (<?= (int) $accountMailCount ?>)</option>
                            </select>

                            <div class="js-recipient-single">
                                <label for="recipient_email">Email destinataire</label>
                                <input type="email" id="recipient_email" name="recipient_email" value="<?= h($adminMailForm['recipient_email']) ?>" maxlength="255" placeholder="personne@example.com">
                            </div>

                            <label for="mail_subject">Sujet</label>
                            <input type="text" id="mail_subject" name="mail_subject" value="<?= h($adminMailForm['subject']) ?>" minlength="3" maxlength="180" required>

                            <label for="categorie">Catégorie</label>
                            <select id="categorie" name="categorie" class="panel-select">
                                <option value="rejoindre" <?= $adminMailForm['categorie'] === 'rejoindre' ? 'selected' : '' ?>>Rejoindre</option>
                                <option value="beurre" <?= $adminMailForm['categorie'] === 'beurre' ? 'selected' : '' ?>>Beurre</option>
                                <option value="question" <?= $adminMailForm['categorie'] === 'question' ? 'selected' : '' ?>>Question</option>
                                <option value="suggestion" <?= $adminMailForm['categorie'] === 'suggestion' ? 'selected' : '' ?>>Suggestion</option>
                                <option value="autre" <?= $adminMailForm['categorie'] === 'autre' ? 'selected' : '' ?>>Autre</option>
                            </select>

                            <label for="mail_message">Message</label>
                            <textarea id="mail_message" name="mail_message" minlength="5" maxlength="5000" rows="8" required><?= h($adminMailForm['message']) ?></textarea>

                            <button type="submit" class="btn-small">Envoyer le mail</button>
                        </form>

                        <p class="panel-note">En mode "Tous les comptes", l'email est envoyé à chaque compte avec une adresse valide.</p>
                    </section>
                <?php endif; ?>

                <section class="panel-section">
                    <h2>API Keys</h2>
                    <p>Cree une cle avec expiration optionnelle. Les cles revoquees restent visibles pour historique.</p>

                    <form method="post" class="panel-form panel-form-inline">
                        <input type="hidden" name="action" value="create_api_key">
                        <input type="hidden" name="csrf_token" value="<?= h($_SESSION['csrf_token']) ?>">

                        <div>
                            <label for="api_key_name">Nom (optional)</label>
                            <input type="text" id="api_key_name" name="api_key_name" maxlength="100" placeholder="CI, script local, etc.">
                        </div>

                        <div>
                            <label for="expires_at">Expiration date (optional)</label>
                            <input type="date" id="expires_at" name="expires_at">
                        </div>

                        <div class="panel-form-actions">
                            <button type="submit">Create API key</button>
                        </div>
                    </form>

                    <?php if ($revealedApiKey): ?>
                        <div class="panel-reveal" id="new-key-block">
                            <strong>Nouvelle cle (copy now):</strong>
                            <div class="panel-reveal-row">
                                <input type="text" id="new-api-key" readonly value="<?= h($revealedApiKey) ?>">
                                <button type="button" data-copy-target="new-api-key">Copy</button>
                            </div>
                        </div>
                    <?php endif; ?>

                    <div class="panel-table-wrap">
                        <table class="panel-table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Key</th>
                                    <th>Status</th>
                                    <th>Expires</th>
                                    <th>Usage</th>
                                    <th>Last used</th>
                                    <th>Last used type</th>
                                    <th>Last used IP</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($apiKeys)): ?>
                                    <tr>
                                        <td colspan="10">Aucune API key pour le moment.</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($apiKeys as $key): ?>
                                        <?php
                                        $masked = substr($key['api_key'], 0, 8) . '/...' . substr($key['api_key'], -4);
                                        $isExpired = $key['expires_at'] !== null && strtotime($key['expires_at']) < time();
                                        $status = $key['is_revoked'] ? 'revoked' : ($isExpired ? 'expired' : 'active');
                                        ?>
                                        <tr>
                                            <td><?= (int) $key['id'] ?></td>
                                            <td><?= h($key['name'] ?: '-') ?></td>
                                            <td class="panel-key-cell" title="<?= h($key['api_key']) ?>"><?= h($masked) ?></td>
                                            <td><span class="status status-<?= h($status) ?>"><?= h($status) ?></span></td>
                                            <td><?= h($key['expires_at'] ? format_fr_date($key['expires_at']) : 'never') ?></td>
                                            <td><?= (int) $key['usage_count'] ?></td>
                                            <td><?= h(format_fr_date($key['last_used_at'])) ?></td>
                                            <td><?= h($key['last_used_type'] ?: '-') ?></td>
                                            <td><?= h($key['last_used_ip'] ?: '-') ?></td>
                                            <td>
                                                <?php if (!(int) $key['is_revoked']): ?>
                                                    <form method="post" class="inline-form js-revoke-form">
                                                        <input type="hidden" name="action" value="revoke_api_key">
                                                        <input type="hidden" name="csrf_token" value="<?= h($_SESSION['csrf_token']) ?>">
                                                        <input type="hidden" name="api_key_id" value="<?= (int) $key['id'] ?>">
                                                        <button type="submit" class="btn-danger">Revoke</button>
                                                    </form>
                                                <?php else: ?>
                                                    <span class="panel-muted">-</span>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </section>
            <?php endif; ?>
        </div>

        <?php include __DIR__ . '/../inc/footer.php'; ?>
    </div>

    <?php include __DIR__ . '/../inc/rsidebar.php'; ?>

    <script src="/assets/js/script.js"></script>
    <script src="/assets/js/panel.js"></script>
</body>

</html>
